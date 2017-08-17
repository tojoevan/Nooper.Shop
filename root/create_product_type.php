<?php
// declare(strict_types = 1);
namespace NooperShop;

require_once '../init/loader.php';


$datas=['name'=>'Phone', 'up_id'=>0, 'position'=>0 ];

/**
$pt=new ProductType('product_type');
$pt->add($datas);
*/

$pt=new ProductType();
$pt->memory('product_type')->add($datas);


$pt=new ProductType();
$pt->memory('product_type')->where('`id`=1')->delete();

$pt=new ProductType();
$pt->memory('product_type')->where('`id`>10')->limit(12)->modify($datas);


$pt->field('*')->select();
$datas=$pt->field('name,sex')->where('id>10')->select();









