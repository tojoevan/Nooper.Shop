<?php
// declare(strict_types = 1);
namespace Nooper;

require_once '../../init/loader.php';

$customer = new Customer();
$num=$customer->get_order_num_by_customer_id(1);
echo $num;

