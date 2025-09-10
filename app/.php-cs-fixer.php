<?php

$config = new PhpCsFixer\Config();
$config->setRules([
    'no_superfluous_phpdoc_tags' => false,
    'phpdoc_trim' => false,
]);

return $config;
