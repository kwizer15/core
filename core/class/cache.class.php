<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */
require_once __DIR__ . '/../../core/php/core.inc.php';

/**
 * Gestionnaire de cache générique pour Jeedom
 *
 * Cette classe fournit une abstraction unifiée pour différentes solutions de cache
 * (fichier, Redis, MariaDB) avec un système de fallback automatique vers le FileCache
 * en cas de problème.
 *
 * Caractéristiques principales :
 * - Support de multiples moteurs de cache (FileCache, RedisCache, MariadbCache)
 * - Gestion de la durée de vie des entrées
 * - Persistence des données possible
 * - Interface fluide pour la manipulation des entrées
 *
 * Exemple d'utilisation basique :
 * ```php
 * // Stockage d'une valeur
 * cache::set('ma_cle', 'ma_valeur', 3600); // expire dans 1h
 *
 * // Récupération
 * $valeur = cache::byKey('ma_cle')->getValue();
 *
 * // Vérification d'existence
 * if (cache::exist('ma_cle')) {
 *     // ...
 * }
 * ```
 *
 * @todo Considérer l'ajout d'évènements pour le monitoring (hit/miss/delete)
 * @todo Envisager un système de préfixes automatiques par domaine fonctionnel
 *       Par exemple : 'cmd::' pour les commandes, 'eqLogic::' pour les équipements,
 *       permettant une meilleure organisation et un nettoyage sélectif du cache
 * @todo Ajouter possibilité de cache hiérarchique (cascade entre moteurs)
 */
class cache {
	/*     * *************************Attributs****************************** */

    /**
     * Clé unique identifiant l'entrée dans le cache
     *
     * @var string
     */
    private $key;

    /**
     * Valeur stockée dans le cache
     *
     * La valeur peut être de tout type sérialisable.
     * La valeur null indique une entrée non initialisée.
     *
     * @var mixed|null
     * @see getValue() Pour récupérer la valeur avec gestion des valeurs par défaut
     */
    private $value = null;

    /**
     * Durée de vie de l'entrée en cache en secondes
     *
     * Une valeur de 0 indique une entrée sans expiration.
     * Les valeurs négatives sont normalisées à 0.
     *
     * @var int
     * @see setLifetime() Pour définir la durée de vie avec normalisation
     */
    private $lifetime = 0;

    /**
     * Timestamp Unix de dernière modification
     *
     * Utilisé pour :
     * - Calculer l'expiration des entrées
     * - Suivre les modifications du cache
     * - Gérer la cohérence des données
     *
     * @var int|null
     * @see getDatetime() Pour obtenir une date formatée
     * @see getTimestamp() Pour obtenir le timestamp brut
     * @todo Considérer l'utilisation de DateTimeImmutable en interne
     */
    private $timestamp;

    /**
     * Instance du moteur de cache actif
     *
     * Stocke le nom de la classe du moteur de cache utilisé
     * (FileCache, RedisCache, ou MariadbCache).
     * L'instance est initialisée à la demande via getEngine().
     *
     * @var string|null
     * @see getEngine() Pour obtenir et initialiser le moteur
     * @todo Envisager l'utilisation d'une interface CacheEngine
     *       pour standardiser les implémentations des moteurs
     */
    private static $_engine = null;

	/*     * ***********************Methode static*************************** */

    /**
     * Récupère le moteur de cache configuré pour l'application
     * Cette méthode implémente un pattern singleton pour le moteur de cache
     * et gère la fallback vers FileCache si nécessaire.
     *
     * @template T of FileCache|RedisCache|MariadbCache
     * @return class-string<T> Nom de la classe du moteur de cache (FileCache, RedisCache, ou MariadbCache)
     * @todo Considérer l'ajout d'une interface CacheEngine pour standardiser les implémentations futures
     * @todo Envisager un système de fallback plus flexible avec priorisation des moteurs
     */
	public static function getEngine(){
		if(self::$_engine != null){
			return self::$_engine;
		}
		self::$_engine = config::byKey('cache::engine');
		if(!class_exists(self::$_engine)){
			config::save('cache::engine','FileCache');
			self::$_engine = 'FileCache';
		}
		if(method_exists(self::$_engine,'isOk') && !self::$_engine::isOk()){
			config::save('cache::engine','FileCache');
			self::$_engine = 'FileCache';
		}
		return self::$_engine;
	}

    /**
     * Définit une valeur dans le cache avec une durée de vie optionnelle
     * Utilise une approche fluide pour la configuration
     *
     * @param string $_key Clé unique pour identifier la donnée en cache
     * @param mixed $_value Valeur à mettre en cache (doit être sérialisable)
     * @param int $_lifetime Durée de vie en secondes (0 = infini)
     * @return cache Instance de l'objet cache pour chaînage
     * @todo Considérer l'ajout de validation de la sérialisabilité des données
     * @todo Envisager un système de tags pour grouper les entrées du cache
     */
	public static function set($_key, $_value, $_lifetime = 0) {
		return (new self())
			->setKey($_key)
			->setValue($_value)
			->setLifetime($_lifetime)
		    ->save();
	}

    /**
     * Supprime une entrée du cache
     *
     * @param string $_key Clé de l'entrée à supprimer
     * @return void
     * @todo Ajouter un retour booléen pour confirmer la suppression
     * @todo Considérer l'ajout d'une suppression par motif (pattern)
     */
	public static function delete($_key) {
		$cache = cache::byKey($_key);
		if (is_object($cache)) {
			$cache->remove();
		}
	}

    /**
     * Récupère une entrée du cache par sa clé
     * Crée une nouvelle instance si l'entrée n'existe pas
     *
     * @param string $_key Clé de l'entrée à récupérer
     * @return cache|null Instance de l'objet cache (nouvelle ou existante)
     * @todo Considérer l'ajout d'un système de préfixage des clés
     * @todo Envisager une gestion des erreurs plus précise pour distinguer les cas :
     *       - Entrée inexistante (actuellement : retourne nouvelle instance)
     *       - Entrée expirée (actuellement : même comportement qu'inexistante)
     *       - Erreur de désérialisation (actuellement : retourne null)
     *       - Erreur d'accès au moteur de cache (actuellement : silencieux)
     *       Cette distinction permettrait par exemple :
     *       - De monitorer la santé du cache plus efficacement
     *       - De mettre en place des stratégies de récupération différentes selon le cas
     *       - D'aider au debugging en environnement de développement
     */
	public static function byKey($_key) {
		$cache = self::getEngine()::fetch($_key);
		if (!is_object($cache)) {
			return (new self())
				->setKey($_key)
				->setTimestamp(strtotime('now'));
		}
		return $cache;
	}

    /**
     * Vérifie l'existence d'une entrée dans le cache
     *
     * @param string $_key Clé à vérifier
     * @return bool True si l'entrée existe et n'est pas nulle
     * @todo Considérer l'ajout d'une vérification de validité (non expirée)
     * @todo Envisager l'ajout d'un mode strict (existence pure sans tenir compte de la valeur)
     */
	public static function exist($_key){
		return (self::byKey($_key)->getValue(null) !== null);
	}

    /**
     * Vide entièrement le cache
     *
     * Utilise la méthode deleteAll() du moteur de cache actif pour supprimer
     * toutes les entrées en cache.
     *
     * @return mixed Résultat de l'opération, dépend du moteur de cache utilisé
     * @todo Considérer l'ajout d'un retour standardisé (bool) pour tous les moteurs
     * @todo Envisager un système de vidage partiel (par préfixe ou pattern)
     */
	public static function flush() {
		return self::getEngine()::deleteAll();
	}

    /**
     * Persiste le contenu du cache
     *
     * Cette méthode est particulièrement importante pour la continuité du service
     * lors des redémarrages ou des bascules entre instances. Elle n'est pas
     * supportée par tous les moteurs de cache.
     *
     * @return void
     * @see isPersistOk() Pour vérifier l'état de la persistence
     * @see restore() Pour restaurer un cache persisté
     * @todo Ajouter un mécanisme de notification en cas d'échec de persistence
     * @todo Envisager un système de persistence sélective pour les données critiques
     */
	public static function persist() {
		if(method_exists(self::getEngine(),'persist')){
			self::getEngine()::persist();
		}
	}

    /**
     * Vérifie si la persistence du cache est valide
     *
     * @return bool true si le cache est correctement persisté, false sinon
     * @see persist() Pour déclencher une persistence
     * @todo Considérer l'ajout de vérifications d'intégrité des données persistées
     * @todo Envisager un système de monitoring de la santé de la persistence
     */
	public static function isPersistOk(): bool {
		if(method_exists(self::getEngine(),'isPersistOk')){
			return self::getEngine()::isPersistOk();
		}
		return true;
	}

    /**
     * Restaure le cache depuis sa version persistée
     *
     * Cette méthode est utilisée typiquement au démarrage du système ou
     * lors d'une bascule pour recharger l'état précédemment sauvegardé.
     *
     * @return void
     * @see persist() Pour créer une persistence
     * @todo Ajouter un mécanisme de fallback en cas d'échec de restauration
     * @todo Considérer l'ajout d'une validation des données restaurées
     */
	public static function restore() {
		if(method_exists(self::getEngine(),'restore')){
			self::getEngine()::restore();
		}
	}

    /**
     * Nettoie le cache des entrées invalides ou expirées
     *
     * Cette méthode effectue plusieurs opérations de nettoyage :
     * - Supprime les entrées expirées (via le moteur de cache)
     * - Nettoie les caches des caméras pour les équipements supprimés
     * - Supprime les caches de commandes obsolètes
     * - Nettoie les caches de dépendances des plugins désinstallés
     *
     * @return void
     * @todo Découper la méthode en sous-fonctions pour améliorer la maintenabilité
     * @todo Ajouter des statistiques sur le nombre d'entrées nettoyées par catégorie
     * @todo Envisager un système de nettoyage asynchrone pour les gros volumes
     */
	public static function clean() {
		if(method_exists(self::getEngine(),'clean')){
			self::getEngine()::clean();
		}
		$caches = self::getEngine()::all();
		foreach ($caches as $cache) {
			if(!is_object($cache)){
				continue;
			}
			$matches = null;
			preg_match_all('/camera(\d*)(.*?)/',  $cache->getKey(), $matches);
			if (isset($matches[1][0])) {
				if (!is_numeric($matches[1][0])) {
					continue;
				}
				$object = eqLogic::byId($matches[1][0]);
				if (!is_object($object)) {
					cache::delete($cache->getKey());
				}
			}
			if (strpos($cache->getKey(), 'cmd') !== false) {
				$id = str_replace('cmd', '', $cache->getKey());
				if (is_numeric($id)) {
					cache::delete($cache->getKey());
				}
				continue;
			}
			preg_match_all('/dependancy(.*)/', $cache->getKey(), $matches);
			if (isset($matches[1][0])) {
				try {
					$plugin = plugin::byId($matches[1][0]);
					if (!is_object($plugin)) {
						cache::delete($cache->getKey());
					}
				} catch (Exception $e) {
					cache::delete($cache->getKey());
				}
			}
		}
	}

	/*     * *********************Methode d'instance************************* */

    /**
     * Sauvegarde l'instance courante dans le cache
     *
     * Met à jour le timestamp avec l'heure actuelle avant la sauvegarde
     * pour assurer une bonne gestion de la durée de vie de l'entrée.
     *
     * @return mixed Résultat de la sauvegarde, dépend du moteur de cache utilisé
     * @throws Exception Potentiellement lancée par le moteur de cache
     * @todo Ajouter un système de validation avant sauvegarde
     * @todo Considérer l'ajout d'événements pre/post sauvegarde
     */
	public function save() {
		$this->setTimestamp(strtotime('now'));
		return self::getEngine()::save($this);
	}

    /**
     * Supprime l'entrée courante du cache
     *
     * @return mixed Résultat de la suppression, dépend du moteur de cache utilisé
     * @todo Standardiser le retour (bool) entre les différents moteurs
     * @todo Ajouter une option pour supprimer en cascade les entrées liées
     */
	public function remove() {
		return self::getEngine()::delete($this->getKey());
	}

	/*     * **********************Getteur Setteur*************************** */

    /**
     * Récupère la clé de l'entrée de cache
     *
     * Cette clé est l'identifiant unique de l'entrée dans le système de cache.
     * Elle est utilisée pour toutes les opérations de lecture/écriture/suppression.
     *
     * @return string Clé de l'entrée de cache
     * @see setKey() Pour définir la clé
     */
	public function getKey() {
		return $this->key;
	}

    /**
     * Définit la clé de l'entrée de cache
     *
     * @param string $_key Nouvelle clé pour l'entrée
     * @return self Instance courante pour chaînage
     * @todo Ajouter une validation du format de la clé
     * @todo Considérer une normalisation automatique des clés
     *       (ex: gestion des caractères spéciaux, longueur max)
     */
	public function setKey($_key): self {
		$this->key = $_key;
		return $this;
	}

    /**
     * Récupère la valeur stockée dans le cache
     *
     * Si la valeur est null ou une chaîne vide, retourne la valeur par défaut.
     * Cette méthode est particulièrement utile pour éviter les vérifications
     * supplémentaires dans le code client.
     *
     * @param mixed $_default Valeur à retourner si l'entrée est vide (défaut: '')
     * @return mixed Valeur stockée ou valeur par défaut
     * @todo Envisager un typage plus strict des valeurs stockées
     * @todo Considérer l'ajout d'un mode strict (sans valeur par défaut)
     */
	public function getValue($_default = '') {
		return ($this->value === null || (is_string($this->value) && trim($this->value) === '')) ? $_default : $this->value;
	}

    /**
     * Définit la valeur à stocker dans le cache
     *
     * @param mixed $_value Valeur à mettre en cache (doit être sérialisable)
     * @return self Instance courante pour chaînage
     * @see getValue() Pour récupérer la valeur
     * @todo Ajouter une vérification de la sérialisabilité de la valeur
     * @todo Considérer une limite de taille pour éviter la surcharge du cache
     */
	public function setValue($_value): self {
		$this->value = $_value;
		return $this;
	}

    /**
     * Récupère la durée de vie de l'entrée en cache
     *
     * La durée de vie est exprimée en secondes.
     * Une valeur de 0 indique une entrée sans expiration.
     *
     * @return int Durée de vie en secondes
     * @see setLifetime() Pour modifier la durée de vie
     */
	public function getLifetime() {
		return $this->lifetime;
	}

    /**
     * Définit la durée de vie de l'entrée en cache
     *
     * Normalise les valeurs négatives à 0 (pas d'expiration).
     * La valeur est automatiquement convertie en entier.
     *
     * @param int $_lifetime Durée de vie en secondes (0 = infini)
     * @return self Instance courante pour chaînage
     * @todo Considérer l'ajout d'une valeur maximale de durée de vie
     * @todo Envisager un système de renouvellement automatique de durée de vie
     */
	public function setLifetime($_lifetime): self {
		if ($_lifetime < 0) {
			$_lifetime = 0;
		}
		$this->lifetime = intval($_lifetime);
		return $this;
	}

    /**
     * Récupère la date de dernière modification au format Y-m-d H:i:s
     *
     * Convertit le timestamp interne en format de date lisible.
     * Cette méthode est particulièrement utile pour le debugging et
     * le monitoring du cache.
     *
     * @return string Date au format Y-m-d H:i:s
     * @see setDatetime() Pour définir la date via une chaîne formatée
     * @see getTimestamp() Pour obtenir le timestamp brut
     * @todo Considérer l'ajout d'un paramètre pour le format de date
     * @todo Envisager un retour de type DateTimeImmutable sur une
     *       autre méthode pour une meilleure interopérabilité et
     *       manipulation des dates
     */
	public function getDatetime() {
		return date('Y-m-d H:i:s',(int) $this->timestamp);
	}

    /**
     * Définit la date de dernière modification via une chaîne formatée
     *
     * Convertit la date fournie en timestamp pour le stockage interne.
     * Accepte tout format de date compatible avec strtotime().
     *
     * @param string $_datetime Date au format compatible strtotime
     * @return self Instance courante pour chaînage
     * @see getDatetime() Pour obtenir la date formatée
     * @see setTimestamp() Pour définir directement le timestamp
     * @todo Ajouter une validation du format de date
     * @todo Considérer l'ajout d'un paramètre pour spécifier le format d'entrée
     * @todo Envisager une nouvelle méthode permettant de gérer un objet
     *       DateTimeInterface en paramètre pour une meilleure type safety
     *       et manipulation des dates
     *       Exemple : setDatetime(DateTimeInterface $datetime)
     */
	public function setDatetime($_datetime): self {
		$this->timestamp = strtotime($_datetime);
		return $this;
	}

    /**
     * Récupère le timestamp Unix de dernière modification
     *
     * Le timestamp est la représentation interne utilisée pour stocker
     * la date de dernière modification de l'entrée en cache.
     *
     * @return int|null Timestamp Unix (secondes depuis epoch)
     * @see setTimestamp() Pour définir le timestamp
     * @see getDatetime() Pour obtenir une date formatée
     */
	public function getTimestamp(){
		return $this->timestamp;
	}

    /**
     * Définit le timestamp Unix de dernière modification
     *
     * Cette méthode est utilisée en interne pour maintenir
     * la cohérence des dates dans le système de cache.
     *
     * @param int $_timestamp Timestamp Unix (secondes depuis epoch)
     * @return self Instance courante pour chaînage
     * @see getTimestamp() Pour récupérer le timestamp
     * @see setDatetime() Pour définir la date via une chaîne formatée
     * @todo Ajouter une validation de la validité du timestamp
     */
	public function setTimestamp($_timestamp){
		$this->timestamp = $_timestamp;
		return $this;
	}
}

/**
 * Implémentation du système de cache utilisant MariaDB/MySQL comme backend de stockage.
 *
 * Cette classe fournit une implémentation du cache basée sur une table 'cache' dans
 * la base de données MariaDB/MySQL. Elle gère le stockage et la récupération des
 * objets cache sérialisés.
 *
 * @todo Envisager l'utilisation de JSON_ENCODE/JSON_DECODE au lieu de serialize/unserialize
 * @todo Ajouter des index sur la table cache pour optimiser les performances
 * @todo Implémenter une méthode de compression des données pour réduire la taille en base
 * @todo Envisager de déplacer cette classe dans son propre fichier
 */
class MariadbCache {

    /**
     * Récupère tous les objets cache stockés en base de données.
     *
     * @return array<cache> Tableau d'objets cache désérialisés
     * @todo Implémenter la pagination pour éviter de charger trop de données
     */
	public static function all(){
		$sql = 'SELECT `key`,`timestamp`,`value`,`lifetime`
		FROM cache';
		$results =  DB::Prepare($sql,array(), DB::FETCH_TYPE_ALL, PDO::FETCH_CLASS,'cache');
		foreach ($results as $cache) {
			$cache->setValue(unserialize($cache->getValue()));
		}
		return $results;
	}

    /**
     * Nettoie les entrées expirées du cache.
     * Supprime toutes les entrées dont la durée de vie (lifetime) est dépassée.
     *
     * @return mixed Résultat de la requête de nettoyage
     * @todo Implémenter un nettoyage par lots pour les grandes bases
     * @todo Ajouter des statistiques de nettoyage (nombre d'entrées supprimées)
     * @todo Optimiser la table après le nettoyage
     */
	public static function clean(){
		$sql = 'DELETE 
		FROM cache
		WHERE `lifetime` > 0
			AND (`timestamp`+`lifetime`) < UNIX_TIMESTAMP()';
		return  DB::Prepare($sql,array(), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS);
	}

    /**
     * Récupère un objet cache par sa clé.
     *
     * @param string $_key Clé de l'objet à récupérer
     * @return cache|null L'objet cache ou null si non trouvé ou expiré
     * @todo Ajouter un système de cache secondaire en mémoire
     * @todo Implémenter un mécanisme de verrouillage pour éviter les lectures fantômes
     */
	public static function fetch($_key){
		$sql = 'SELECT `key`,`timestamp`,`value`,`lifetime`
		FROM cache
		WHERE `key`=:key';
		$cache = DB::Prepare($sql,array('key' => $_key), DB::FETCH_TYPE_ROW, PDO::FETCH_CLASS,'cache');
		if($cache === false){
			return null;
		}
		if($cache->getLifetime() > 0 && ($cache->getTimestamp() + $cache->getLifetime()) < strtotime('now')){
			return null;
		}
		$cache->setValue(unserialize($cache->getValue()));
		return $cache;
	}

    /**
     * Supprime un objet cache par sa clé.
     *
     * @param string $_key Clé de l'objet à supprimer
     * @return mixed Résultat de la requête de suppression
     */
	public static function delete($_key){
		$sql = 'DELETE 
		FROM cache
		WHERE `key`=:key';
		return  DB::Prepare($sql,array('key' => $_key), DB::FETCH_TYPE_ROW);
	}

    /**
     * Vide complètement le cache.
     *
     * @return mixed Résultat de la requête TRUNCATE
     */
	public static function deleteAll(){
		return  DB::Prepare('TRUNCATE cache',array(), DB::FETCH_TYPE_ROW);
	}

    /**
     * Sauvegarde un objet cache en base de données.
     *
     * @param cache $_cache Objet cache à sauvegarder
     * @return mixed Résultat de la requête de sauvegarde
     * @todo Ajouter une validation des données avant la sérialisation
     * @todo Implémenter un mécanisme de retry en cas d'échec
     * @todo Gérer les conflits de clés de manière plus robuste
     */
	public static function save($_cache){
		$value = array(
			'key' => $_cache->getKey(),
			'value' => serialize($_cache->getValue()),
			'lifetime' =>$_cache->getLifetime(),
			'timestamp' => $_cache->getTimestamp()
		);
		$sql = 'REPLACE INTO cache SET `key`=:key, `value`=:value,`timestamp`=:timestamp,`lifetime`=:lifetime';
		return  DB::Prepare($sql,$value, DB::FETCH_TYPE_ROW);
	}

}

/**
 * Implémentation du système de cache utilisant Redis comme backend de stockage.
 *
 * Cette classe fournit une implémentation du cache basée sur un serveur Redis.
 * Elle gère la connexion au serveur Redis et les opérations de cache via l'extension PHP Redis.
 *
 * @todo Ajouter la gestion des erreurs de connexion Redis
 * @todo Implémenter un mécanisme de reconnexion automatique
 * @todo Ajouter un système de monitoring des performances
 * @todo Envisager de déplacer cette classe dans son propre fichier
 */
class RedisCache {

    /**
     * @var Redis|null Instance de connexion Redis
     */
	private static $connection = null;

    /**
     * Vérifie si l'extension Redis est disponible.
     *
     * @return bool true si l'extension Redis est chargée
     */
	public static function isOk(){
		return class_exists('redis');
	}

    /**
     * Récupère ou établit la connexion Redis.
     *
     * @return Redis Instance de connexion Redis
     * @todo Ajouter un timeout configurable
     * @todo Gérer la reconnexion automatique en cas d'erreur
     */
	public static function getConnection(){
		if(static::$connection !== null){
			return static::$connection;
		}
		$redis = new Redis();
		$redis->connect(config::byKey('cache::redisaddr'), config::byKey('cache::redisport'));
		static::$connection = $redis;
		return static::$connection;
	}

    /**
     * Récupère tous les objets cache stockés dans Redis.
     *
     * @return array<cache> Tableau d'objets cache
     * @todo Utiliser SCAN au lieu de KEYS pour les grandes bases
     * @todo Implémenter un mécanisme de pagination
     * @todo Ajouter des filtres sur les patterns de clés
     */
	public static function all(){
		$return  = array();
		$keys = self::getConnection()->keys('*');
		foreach ($keys as $key) {
			$return[] = self::fetch($key);
		}
		return $return;
	}

    /**
     * Récupère un objet cache par sa clé.
     *
     * @param string $_key Clé de l'objet à récupérer
     * @return cache|null L'objet cache ou null si non trouvé
     */
	public static function fetch($_key){
		$data = self::getConnection()->get($_key);
		if($data === false){
			return null;
		}
		return @unserialize($data);
	}

    /**
     * Supprime un objet cache par sa clé.
     *
     * @param string $_key Clé de l'objet à supprimer
     * @return void
     */
	public static function delete($_key){
		self::getConnection()->del($_key);
	}


    /**
     * Vide complètement la base de données Redis.
     *
     * @return bool Résultat de l'opération de flush
     */
	public static function deleteAll(){
		return  self::getConnection()->flushDb();
	}

    /**
     * Sauvegarde un objet cache dans Redis.
     * Gère automatiquement l'expiration si une durée de vie est définie.
     *
     * @param cache $_cache Objet cache à sauvegarder
     * @return void
     * @todo Implémenter une compression des données
     * @todo Ajouter un système de versioning des données
     * @todo Gérer les erreurs de sérialisation
     */
	public static function save($_cache){
		if($_cache->getLifetime() > 0){
			self::getConnection()->set($_cache->getKey(),serialize($_cache), $_cache->getLifetime());
		}else{
			self::getConnection()->set($_cache->getKey(),serialize($_cache));
		}
	}

}

/**
 * Implémentation du système de cache utilisant le système de fichiers comme backend de stockage.
 *
 * Cette classe fournit une implémentation du cache basée sur des fichiers.
 * Elle gère le stockage et la récupération des objets cache sérialisés dans des fichiers,
 * ainsi que la persistance et la restauration du cache.
 *
 * @todo Utiliser des verrous de fichiers pour la concurrence
 * @todo Implémenter un système de nettoyage progressif
 * @todo Optimiser la gestion des permissions fichiers
 * @todo Ajouter un système de répertoires hiérarchiques pour améliorer les performances
 * @todo Envisager de déplacer cette classe dans son propre fichier
 */
class FileCache {

    /**
     * Récupère tous les objets cache stockés dans les fichiers.
     *
     * @return array<cache> Tableau d'objets cache
     */
	public static function all(){
		$return = array();
		foreach (ls(jeedom::getTmpFolder('cache'), '*',false,array('files')) as $file) {
			$return[] = self::fetch(base64_decode($file));
		}
		return $return;
	}

    /**
     * Nettoie les entrées expirées du cache fichier.
     * Supprime les fichiers corrompus et expirés.
     *
     * @return void
     * @todo Implémenter un nettoyage par lots
     * @todo Ajouter un système de journalisation des suppressions
     * @todo Optimiser la détection des fichiers corrompus
     * @todo Implémenter une limite de taille du cache
     */
	public static function clean(){
		foreach (ls(jeedom::getTmpFolder('cache'), '*',false,array('files')) as $file) {
			$cache = unserialize(file_get_contents(jeedom::getTmpFolder('cache').'/'.$file));
			if($cache->getLifetime() > 0 && ($cache->getTimestamp() + $cache->getLifetime()) < strtotime('now')){
				unlink(jeedom::getTmpFolder('cache').'/'.$file);
			}
		}
	}

    /**
     * Récupère un objet cache par sa clé.
     *
     * @param string $_key Clé de l'objet à récupérer
     * @return cache|null L'objet cache ou null si non trouvé ou expiré
     * @todo Ajouter un checksum pour vérifier l'intégrité
     * @todo Implémenter une compression différentielle
     * @todo Gérer les erreurs de compression
     * @todo Ajouter un système de rotation des archives
     */
	public static function fetch($_key){
		$data = @file_get_contents(jeedom::getTmpFolder('cache').'/'.base64_encode($_key));
        if($data === false){
        	return null;
        }
	    $cache = unserialize($data);
		if(!is_object($cache)){
			return null;
		}
		if($cache->getLifetime() > 0 && ($cache->getTimestamp() + $cache->getLifetime()) < strtotime('now')){
			self::delete($_key);
			return null;
		}
		return $cache;
	}

    /**
     * Supprime un objet cache par sa clé.
     *
     * @param string $_key Clé de l'objet à supprimer
     * @return void
     */
	public static function delete($_key){
		@unlink(jeedom::getTmpFolder('cache').'/'.base64_encode($_key));
	}

    /**
     * Vide complètement le répertoire de cache.
     *
     * @return false|null|string Résultat de la commande de suppression
     */
	public static function deleteAll(){
		return shell_exec(system::getCmdSudo().' rm -rf '.jeedom::getTmpFolder('cache'));
	}

    /**
     * Sauvegarde un objet cache dans un fichier.
     *
     * @param cache $_cache Objet cache à sauvegarder
     * @return void
     * @todo Implémenter un système de verrous pour la concurrence
     * @todo Ajouter une validation des données avant écriture
     * @todo Gérer les erreurs d'écriture disque
     * @todo Optimiser les performances d'écriture
     */
	public static function save($_cache){
		file_put_contents(jeedom::getTmpFolder('cache').'/'.base64_encode($_cache->getKey()),serialize($_cache));
	}

    /**
     * Persiste le contenu du cache dans une archive tar.gz.
     * Gère également les permissions des fichiers.
     *
     * @return void
     */
	public static function persist() {
		$cache_dir = jeedom::getTmpFolder('cache');
		try {
			$cmd = system::getCmdSudo() . 'rm -rf ' . __DIR__ . '/../../cache.tar.gz;cd ' . $cache_dir . ';';
			$cmd .= system::getCmdSudo() . 'tar cfz ' . __DIR__ . '/../../cache.tar.gz * 2>&1 > /dev/null;';
			$cmd .= system::getCmdSudo() . 'chmod 774 ' . __DIR__ . '/../../cache.tar.gz;';
			$cmd .= system::getCmdSudo() . 'chown ' . system::get('www-uid') . ':' . system::get('www-gid') . ' ' . __DIR__ . '/../../cache.tar.gz;';
			$cmd .= system::getCmdSudo() . 'chown -R ' . system::get('www-uid') . ':' . system::get('www-gid') . ' ' . $cache_dir . ';';
			$cmd .= system::getCmdSudo() . 'chmod 774 -R ' . $cache_dir . ' 2>&1 > /dev/null';
			com_shell::execute($cmd);
		} catch (Exception $e) {
		}
	}

    /**
     * Vérifie si la persistance du cache est valide.
     *
     * @return bool true si l'archive existe et n'est pas trop ancienne
     */
	public static function isPersistOk(): bool {
		$filename = __DIR__ . '/../../cache.tar.gz';
		if (!file_exists($filename)) {
			return false;
		}
		if (filemtime($filename) < strtotime('-65min')) {
			return false;
		}
		return true;
	}

    /**
     * Restaure le cache à partir de l'archive tar.gz.
     * Crée le répertoire de cache si nécessaire.
     *
     * @return void
     * @todo Vérifier l'intégrité de l'archive avant restauration
     * @todo Implémenter une restauration progressive
     * @todo Ajouter un mécanisme de fallback
     * @todo Gérer la fusion avec le cache existant
     */
	public static function restore() {
		$cache_dir = jeedom::getTmpFolder('cache');
		if (!file_exists(__DIR__ . '/../../cache.tar.gz')) {
			$cmd = 'mkdir ' . $cache_dir . ';';
			$cmd .= 'chmod -R 777 ' . $cache_dir . ';';
			com_shell::execute($cmd);
			return;
		}
		$cmd = 'rm -rf ' . $cache_dir . ';';
		$cmd .= 'mkdir ' . $cache_dir . ';';
		$cmd .= 'cd ' . $cache_dir . ';';
		$cmd .= 'tar xfz ' . __DIR__ . '/../../cache.tar.gz;';
		$cmd .= 'chmod -R 777 ' . $cache_dir . ' 2>&1 > /dev/null;';
		com_shell::execute($cmd);
	}

}
