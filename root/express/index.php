<?php
// declare(strict_types = 1);
namespace NooperShop;

require_once '../../init/loader.php';

$express=new Express();
$regions=$express->get_address_regions();
print_r($regions);