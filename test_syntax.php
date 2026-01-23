<?php
// Simple test to check if PHP and Laravel are working
echo "PHP is working\n";
require_once 'vendor/autoload.php';
echo "Composer autoload works\n";
$app = require_once 'bootstrap/app.php';
echo "Laravel app loads\n";
echo "Syntax check passed!\n";