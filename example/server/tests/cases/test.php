<?php
/**
 * @author Mikhail Kulakovskiy <m@klkvsk.ru>
 * @date 2015-11-25
 */

$data = array (
    'bool' => true,
    'int' => -1,
    'bigint' => 1,
    'smallint' => 0,
    'double' => 0.1,
    'float' => -0.1,
    'numeric' => 10e2,
    'string' => 'foo',
    'date' => '2015-01-01',
    'timestamp' => '2015-01-01 00:00:00',
    'timestampTz' => '2015-01-01 00:00:00+0300',
    'array' => [ 1, 'two' => 2 ],
    'binary' => 0b11001100,
    'httpUrl' => 'http://ya.ru/a/?b=1#h',
    'inet' => '1.2.3.4',
    'ipAddress' => '1.2.4.5',
    'json' => '{"a":1}',
    'uuid' => '00000000-0000-0000-0000-000000000000',
);

$data['struct'] = $data;

$test = TypesTestStruct::make($data);

$out = $test->export();

foreach ($out as $key => $value) {
    $a = $value;
    $b = $data[$key];
    $isSame = $a == $b;
    echo $key . ': ' . ($isSame ? 'ok' : "MISMATCH");
    echo "\n";
    if (!$isSame) {
        var_dump($a, $b);
        throw new Exception();
    }
}