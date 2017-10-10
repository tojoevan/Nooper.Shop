<?php
// declare(strict_types = 1);
namespace Nooper;

require_once '../../init/loader.php';


$product=new Product();
$num=$product->add_category_properties(1, ['color','ram']);
echo $num."<br />";
echo $product->get_last_sql();

