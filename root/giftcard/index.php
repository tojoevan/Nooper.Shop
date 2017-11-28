<?php
// declare(strict_types = 1);
namespace NooperShop;

require_once '../../init/loader.php';

$card=new GiftCard();
echo $card->unique_id();