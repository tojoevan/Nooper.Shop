<?php
// declare(strict_types = 1);
namespace Nooper;

require_once '../../init/loader.php';

$gift_card = new GiftCard();
$gift_card->delete_model(1);
