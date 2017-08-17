<?php
// declare(strict_types = 1);
namespace NooperShop;

//require_once '../init/bootstrap.php';


$arr=['a'=>1,'b'=>'zhangsan','c'=>200];
foreach($arr as $key=>&$data){
	if(is_int($data)) unset($data);
}
print_r($arr);

echo getcwd();