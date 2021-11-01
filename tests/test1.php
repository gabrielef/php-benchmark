<?php
include __DIR__ . '/../vendor/autoload.php';

use gabrielef\PhpBenchmark;

$a = function () {
    echo 'abcdef'.'abcdef';
};

$b = function () {
    print('abcdef'.'abcdef');
};

$config = ['iterations' => 1000000];
$benchmark = new PhpBenchmark($config);
$benchmark->addTest('echo', $a);
$benchmark->addTest('print', $b);
$benchmark->executionTimeBenchmark();
$benchmark->printStats();

//TODO stampare differenza tra i test

//test della memoria
