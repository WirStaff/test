<?php

use TestTask\Calls;

require_once __DIR__.'/classLoader.php';

$calls = new Calls();

try {
    $callsDtoSpl = $calls->fillCallDtoSpl(__DIR__ . '/data.json');

    echo $calls->buildServerOverLoadTable($callsDtoSpl);
    echo $calls->buildServerLoadTable($callsDtoSpl);

} catch (Exception $e) {
    echo $e->getMessage();
}