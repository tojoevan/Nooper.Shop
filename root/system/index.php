<?php
// declare(strict_types = 1);
namespace Nooper;

require_once '../../init/loader.php';


$admin=new Administrator();
$datas=$admin->get_permission_item(2);
echo $admin->get_last_sql();
echo "<br />";
print_r($datas);

