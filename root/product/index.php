<?php
// declare(strict_types = 1);
namespace Nooper;

require_once '../../init/loader.php';

$product=new Product();
$category_num=$product->get_category_num();
echo $category_num;
echo "<hr />";

$category_page=$product->get_category_page();
print_r($category_page);
echo "<hr />";


