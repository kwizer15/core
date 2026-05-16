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

require_once __DIR__ . '/log.class.php';

use Psr\Log\AbstractLogger;

/**
 * Adaptateur PSR-3 pour le système de log Jeedom
 *
 * Cette classe permet d'utiliser le système de log de Jeedom tout en
 * respectant l'interface PSR-3 LoggerInterface. Elle sert de pont entre
 * les deux systèmes.
 *
 * @note Architecture
 *   Cette classe est un excellent exemple d'adaptation entre le système
 *   historique de Jeedom et les standards PHP modernes (PSR-3).
 *   Elle pourrait être enrichie pour supporter plus de contextes PSR-3.
 *
 * @example Utilisation basique
 * ```php
 * $logger = new PSR3LogAdapter('plugin_name');
 * $logger->info('Message');
 * ```
 *
 * @example Utilisation avec contexte
 * ```php
 * $logger = new PSR3LogAdapter('plugin_name');
 * $logger->error('Erreur équipement', ['logicalId' => 'eq_1']);
 * ```
 *
 * @see log::class Le système de log natif de Jeedom
 * @see \Psr\Log\LoggerInterface L'interface PSR-3 implémentée
 */
class PSR3LogAdapter extends AbstractLogger
{
    /**
     * Nom du logger utilisé dans Jeedom
     *
     * Ce nom apparaît dans l'interface de Jeedom et permet d'identifier
     * la source des logs (plugin, core, etc.)
     *
     * @var string
     */
    private $logName;

    /**
     * Crée un nouvel adaptateur de log PSR-3
     *
     * @note Architecture
     *   Le constructeur ne prend que le nom du log pour garder la simplicité.
     *   Une évolution possible serait d'ajouter des options de configuration
     *   supplémentaires via un tableau d'options.
     *
     * @param string $logName Nom du logger dans Jeedom
     */
    public function __construct(string $logName) {
        $this->logName = $logName;
    }

    /**
     * Enregistre un message de log avec le niveau spécifié
     *
     * Cette méthode adapte le format PSR-3 au format Jeedom.
     * Elle extrait le logicalId du contexte s'il existe.
     *
     * @note Compatibilité
     *   Actuellement seul le logicalId est extrait du contexte.
     *   Les autres éléments du contexte PSR-3 pourraient être
     *   supportés dans une version future :
     *   - Interpolation des placeholders {placeholder}
     *   - Support d'autres métadonnées du contexte
     *
     * @param mixed  $level   Niveau de log (debug, info, notice, warning, error, alert, critical, emergency)
     * @param string $message Message à logger
     * @param array  $context Contexte du log (seul logicalId est utilisé pour le moment)
     * @return void
     */
    public function log($level, $message, array $context = []): void {
        log::add($this->logName, $level, $message, $context['logicalId'] ?? '');
    }
}
