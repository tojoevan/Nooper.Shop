<?php
// declare(strict_types = 1);
namespace NooperShop;

require_once '../../init/loader.php';

$coupon = new Coupon();
$page=$coupon->get_model_page();
print_r($page);