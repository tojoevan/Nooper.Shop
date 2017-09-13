<?php
// declare(strict_types = 1);
namespace Nooper;

require_once '../../init/loader.php';

$gift_card=new GiftCard();
$datas=$gift_card->get_sale_records(1);
print_r($datas);
