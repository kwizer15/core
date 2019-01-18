<?php

namespace Jeedom\Core\Infrastructure\Service;

use Jeedom\Core\Domain\Repository\ScenarioRepository;
use Jeedom\Core\Presenter\Service\HumanScenarioMap;

class RepositoryHumanScenarioMap implements HumanScenarioMap
{
    /**
     * @var ScenarioRepository
     */
    private $repository;

    public function __construct(ScenarioRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param object $_input
     *
     * @return string
     * @throws \ReflectionException
     */
    public function toHumanReadable($_input) {
        if (is_object($_input)) {
            $reflections = array();
            $uuid = spl_object_hash($_input);
            if (!isset($reflections[$uuid])) {
                $reflections[$uuid] = new \ReflectionClass($_input);
            }
            $reflection = $reflections[$uuid];
            $properties = $reflection->getProperties();
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $value = $property->getValue($_input);
                $property->setValue($_input, $this->toHumanReadable($value));
                $property->setAccessible(false);
            }
            return $_input;
        }
        if (is_array($_input)) {
            foreach ($_input as $key => $value) {
                $_input[$key] = $this->toHumanReadable($value);
            }
            return $_input;
        }
        $text = $_input;
        preg_match_all('/#scenario(\d*)#/', $text, $matches);
        foreach ($matches[1] as $scenario_id) {
            if (is_numeric($scenario_id)) {
                $scenario = $this->repository->get($scenario_id);
                if (is_object($scenario)) {
                    $text = str_replace('#scenario' . $scenario_id . '#', '#' . $scenario->getHumanName(true) . '#', $text);
                }
            }
        }
        return $text;
    }

    /**
     *
     * @param type $_input
     *
     * @return type
     * @throws \ReflectionException
     */
    public function fromHumanReadable($_input) {
        $isJson = false;
        if (is_json($_input)) {
            $isJson = true;
            $_input = json_decode($_input, true);
        }
        if (is_object($_input)) {
            $reflections = array();
            $uuid = spl_object_hash($_input);
            if (!isset($reflections[$uuid])) {
                $reflections[$uuid] = new \ReflectionClass($_input);
            }
            $reflection = $reflections[$uuid];
            $properties = $reflection->getProperties();
            foreach ($properties as $property) {
                $property->setAccessible(true);
                $value = $property->getValue($_input);
                $property->setValue($_input, $this->fromHumanReadable($value));
                $property->setAccessible(false);
            }
            return $_input;
        }
        if (is_array($_input)) {
            foreach ($_input as $key => $value) {
                $_input[$key] = $this->fromHumanReadable($value);
            }
            if ($isJson) {
                return json_encode($_input, JSON_UNESCAPED_UNICODE);
            }
            return $_input;
        }
        $text = $_input;

        preg_match_all("/#\[(.*?)\]\[(.*?)\]\[(.*?)\]#/", $text, $matches);
        if (count($matches) === 4) {
            $countMatches = count($matches[0]);
            for ($i = 0; $i < $countMatches; $i++) {
                if (isset($matches[1][$i], $matches[2][$i], $matches[3][$i])) {
                    $scenario = $this->repository->findOneByObjectNameGroupNameScenarioName($matches[1][$i], $matches[2][$i], $matches[3][$i]);
                    if (is_object($scenario)) {
                        $text = str_replace($matches[0][$i], '#scenario' . $scenario->getId() . '#', $text);
                    }
                }
            }
        }

        return $text;
    }
}
