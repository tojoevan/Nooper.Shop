<?php
// declare(strict_types = 1);
namespace NooperShop;

require_once '../init/bootstrap.php';

$pascal='MyNameIsLanBoShu';
$str2=pascal_to_underline_named($pascal);
echo $str2;