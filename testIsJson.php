<?php

require_once __DIR__ . '/core/php/utils.inc.php';


/**
 * @param array{mixed, mixed, mixed} $case
 * @return void
 */
function testIsJson(array $case): void
{
    list($input, $default, $expected) = $case;
    $actual = is_json($input, $default);

    if ($actual !== $expected) {
        echo 'is_json(', var_export($input, true), ', ', var_export($default, true), ') => ', var_export($actual, true), PHP_EOL;
    }
}


foreach ([
             ['', null, false],
             [1, null, false],
             [1, 'foo', 'foo'],
             [1, true, true],
             ['1', null, false], // ceci est un json valide, retourne false
             ['1', 'foo', 'foo'],
             ['1', false, false],
             ['null', null, false], // ceci est un json valide, retourne false
             ['null', 'foo', 'foo'],
             ['null', false, false],
             ['"null"', null, false], // ceci est un json valide, retourne false
             ['"null"', 'foo', 'foo'],
             ['"null"', true, true],
             ['true', null, false], // ceci est un json valide, retourne false
             ['true', 'foo', 'foo'],
             ['true', true, true],
             ['false', null, false], // ceci est un json valide, retourne false
             ['false', 'foo', 'foo'],
             ['false', true, true],
             ['{}', null, true],
             ['{}', false, []],
             ['[]', null, true],
             ['[]', array(), []],
             ['[]', false, []],
             ['["a"]', null, true],
             ['["a"]', array(), ['a']],
             ['["a"]', false, ['a']],
             [['a' => 1], null, false],
             ['["a":1]', null, false],
             ['{"a":1,"b":2}', true, ['a' => 1, 'b' => 2]],
         ] as $case) {
    testIsJson($case);
}