<?php
// declare(strict_types = 1);
namespace NooperShop;

require_once '../init/bootstrap.php';

$mysql=new Mysql();
$sql="select name,qr_ticket from spread_employee order by id asc";
$datas=$mysql->query($sql);

foreach($datas as $data){
	echo '<a href="http://127.0.0.1/root/download_employee_qrcode_img.php?qr_ticket='.$data['qr_ticket'].'&file_name='.$data['name'].'.jpg  " target="_blank">'.$data['name'].'</a>';
	echo "<br />";
}