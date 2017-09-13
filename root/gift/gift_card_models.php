<?php
// declare(strict_types = 1);
namespace Nooper;

require_once '../../init/loader.php';

$gift_card=new GiftCard();
$num=$gift_card->get_models_num();
echo $num;
