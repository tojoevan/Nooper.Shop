<?php
// declare(strict_types = 1);
namespace NooperShop;

require_once '../init/bootstrap.php';

$qr_ticket = $_GET['qr_ticket'] ?? null;
$file_name = $_GET['file_name'] ?? null;

$carrier = new Carrier();
$carrier->download_qrcode_image($qr_ticket, $file_name);