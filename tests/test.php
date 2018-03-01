<?php

namespace Tests;
use PhpOffice\PhpWord\IOFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$fileName = __DIR__ . '/1Воронеж Заключение.doc';
$phpWord = IOFactory::load($fileName, 'MsDoc');

$sections = $phpWord->getSections();
$section = $sections[0];
$arrays = $section->getElements();

var_export($arrays);
