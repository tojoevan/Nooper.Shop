<?php
// declare(strict_types = 1);
namespace Nooper;


require_once '../../init/loader.php';

$express=new Express();
$datas=$express->get_carriage_template_detail_by_id(1);
print_r($datas);