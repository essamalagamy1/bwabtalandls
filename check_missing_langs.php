<?php
$arLang = require 'lang/ar/lang.php';
$keys = shell_exec("grep -roh \"__('lang\.[^']\+')\" resources/views/ | sort | uniq");
preg_match_all("/__\('lang\.([^']+)'\)/", $keys, $matches);
$bladeKeys = $matches[1];

$missing = [];
foreach ($bladeKeys as $key) {
    if (!isset($arLang[$key])) {
        $missing[] = $key;
    }
}
echo implode("\n", $missing);
