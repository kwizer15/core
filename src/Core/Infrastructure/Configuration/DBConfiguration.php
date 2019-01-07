<?php

namespace Jeedom\Core\Infrastructure\Configuration;

use Jeedom\Core\Application\Configuration\Configuration;
use Jeedom\Core\Infrastructure\Database\Connection;

class DBConfiguration implements Configuration
{
    /**
     * @var string
     */
    private $plugin;


    public function __construct($plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Supprime une clef de la config
     *
     * @param string $key nom de la clef à supprimer
     *
     * @return bool vrai si ok faux sinon
     */
    public function remove($key)
    {
        $sql = 'DELETE FROM config WHERE plugin=:plugin';
        $values = ['plugin' => $this->plugin];

        if ($key !== '*' || $this->plugin === 'core') {
            $sql .= ' AND `key`=:key';
            $values['key'] = $key;
        }

        try {
            Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Retourne la valeur d'une clef
     *
     * @param $key
     * @param string $default
     *
     * @return mixed valeur de la clef
     * @throws \Exception
     */
    public function get($key, $default = null)
    {
        $values = array(
            'plugin' => $this->plugin,
            'key' => $key,
        );
        $sql = 'SELECT `value`  FROM config WHERE `key`=:key AND plugin=:plugin';

        $value = Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW);

        return $this->decodeValue($value['value']);
    }

    /**
     * @param array $keys
     * @param mixed|null $default
     *
     * @return array
     * @throws \Exception
     */
    public function multiGet(array $keys, $default = null)
    {
        if (!is_array($keys) || \count($keys) === 0) {
            return [];
        }
        $values = [
            'plugin' => $this->plugin,
        ];
        $stringKeys = '\'' . implode('\',\'', $keys) . '\'';
        $sql = "SELECT `key`,`value` FROM config WHERE `key` IN ({$stringKeys}) AND plugin=:plugin";
        $values = Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL);
        $return = array_column($values, 'value', 'key');

        foreach ($keys as $key) {
            if (isset($return[$key])) {
                $return[$key] = $this->decodeValue($return[$key]);
            } elseif (is_array($default)) {
                $return[$key] = isset($default[$key]) ? $default[$key] : '';
            } else {
                $return[$key] = $default;
            }
        }

        return $return;
    }

    public function search($key)
    {
        $values = array(
            'plugin' => $this->plugin,
            'key' => '%' . $key . '%',
        );
        $sql = 'SELECT * FROM config WHERE `key` LIKE :key AND plugin=:plugin';
        $results = Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ALL);
        foreach ($results as &$result) {
            // TODO : on s'attendrait plutôt à un retour de type ['my::key' => 'my_value', 'my::other::key' => 'my_other_value']
            $result['value'] = $this->decodeValue($result['value']);
        }

        return $results;
    }

    public function set($key, $value)
    {
        if (is_object($value) || is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        $values = array(
            'plugin' => $this->plugin,
            'key' => $key,
            'value' => $value,
        );
        $sql = 'UPDATE config SET `key`=:key, `value`=:value, plugin=:plugin';
        return Connection::Prepare($sql, $values, Connection::FETCH_TYPE_ROW);
    }

    private function decodeValue($value)
    {
        $return = is_string($value) ? json_decode($value, true, 512, JSON_BIGINT_AS_STRING) : null;

        return is_array($return) ? $return : $value;
    }
}
