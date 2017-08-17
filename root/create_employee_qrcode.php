<?php
// declare(strict_types = 1);
namespace NooperShop;

require_once '../init/loader.php';

$emp_id = $_GET['emp_id'] ?? null;
$qr_key = $_GET['qr_key'] ?? null;

$carrier = new Carrier();
$ends = $carrier->create_limit_str_qrcode($qr_key);

$employee = new SpreadEmployee();
$employee->sql('where', "where `id`=" . $emp_id . "");


$datas = ['qr_scene_key'=>$qr_key, 'qr_ticket'=>$ends['ticket'], 'qr_url'=>$ends['url']];
$employee->update($datas);



