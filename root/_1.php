<?php
// declare(strict_types = 1);
namespace NooperShop;

require_once '../init/bootstrap.php';



/* 如何实例化Mysql类 */


// 第一种最简单的方法,不带任何参数
$mysql=new Mysql();

// 第二种，在实例化的同时，指定要操作的表， 这种方法实例化的mysql， 在后续的操作中，不用再使用memory()方法，声明操作的表
$mysql=new Mysql('product_type');

// 第三种，在实例化的同时，还可以指定连接数据库使用的参数值， 默认的连接数据的参数值，在nooper.config.php文件中由database_connect_params
// 声明，但是可以在实例化的时候，不使用配置参数，而自己指定参数

$params=[
		'type'=>'mysql',
		'host'=>'127.0.0.1',
		'port'=>'3306',
		'dbname'=>'nooper_shop',
		'charset'=>'utf8',
		'username'=>'user1',
		'password'=>'12345678'
];

$mysql=new Mysql('product_type', $params);


// 第四种，如果在指定连接参数的同时，不希望指定操作的表

$mysql=new Mysql(null, $params);



// 底层的数据查询方法query(), 实例化mysql的时候，不需要指定表名，但是可以指定连接参数
// query()方法，可以执行查询操作
// 返回值是一个数组，里面保存查询得到的数据

$mysql=new Mysql();
$sql="select * from product_type where id>10";
$datas=$mysql->query($sql);


// 底层的数据操作方法cmd(), 实例化mysql的时候，同样，不需要指定表名，可以指定参数
// cmd()方法，可以执行新增，删除，修改三种操作
// 范湖值是一个整数，表示数据操作受影响的行数
$mysql=new Mysql();
$sql="delete from product_type where id=12";
$sql2="insert into product_type() values()";
$sql3="update product_type set ?=? where id=32";
$end=$mysql->cmd($sql);



//数据查询的高级方法select()
// 该方法需要配合sql组成的其它方法共同使用，用来构造完整的sql语句后才能执行查询
// field()方法设置要查询的列的列表
// memeory()方法指定查询的表
// where()方法指定条件子语句
// 因此这个例子构造的sql语句为：select id,name,sex from user where id=12


$mysql=new Mysql();
$mysql->field('id,name,sex')->table('user')->where('id=12')->select();

// select distinct * from product_type order by id desc limit 20
$mysql->distinct(true)->field('*')->memory('product_type')->order('id desc')->limit(20)->select();


// select * from user inner join product on user.id=product.userid limit 10
$msyql->field('*')->memory('user')->join('inner join product on user.id=product.userid ')->limit(10)->select();


/* 数据操作的高级方法add() */

$datas=[
		'name'=>'zhangsan',
		'sex'=>'male',
		'age'=>12
];
$msyql=new Mysql();
$mysql->memory('user')->add($datas);

// 或者

$mysql=new Mysql('user');
$mysql->add($datas);

/* 数据操作的高级方法modify()*/

$datas=[
		'sex'=>'male'
		'salary'=>'salary+200',
];
$msyql=new Mysql('user');
$mysql->where('id=12')->modify($datas);

/* 数据高级操作方法delete() */
$mysql=new Mysql('user');
$msyql->where('id=12')->delete();


/* 数据高级操作方法increase(), 这个方法是modify()的一种高级变形形式，适用于特殊的自增情况 ,
 * 例如，要在原有雇员薪水的基础上统一增加200元工资，这个在使用modify()方法的时候，无法进行书写，
 * datas数组数据无法给值，因此使用increase()
 */

$datas=[
		'salary'=>200
];
$mysql=new Mysql('user');
$mysql->where('name="zhangsan"')->increase($datas);

//这个例子里，实现的sql语句为 update user set salary=salary+200 where name='zhangsan';





// clear()方法的讨论

$mysql=new Mysql('user');

// 下面这行代码执行删除的操作, 构造的sql语句为 delete from user where id=12;
// 因为使用了where方法构造sql的条件部分，因此在保护属性sql_datas里，存在
// $mysql->sql_datas['where']='id=12'的内容，并且该内容在执行完delete方法后并不会消失.
$mysql->where(id=12)->delete();


// 下面我在继续执行查询语句
// 从字面上看，没有指定查询的条件，但是上一个删除语句的where()效果仍然存在， 因此
// 实际执行的是 $mysql->field('*')->where('id=12')->select()
// 为了解决这个问题，可以在执行完delete之后，执行clear方法，清除前面命令的构造效果。
$mysql->field('*')->select();
// 用下面的方法执行
$mysql->clear()->field('*')->select();



// 在实例化时，指定了表名，这个被指定的表名，不受clear方法的影响，一直会存在。
$mysql=new Mysql('user');
$mysql->clear();  		// 此时， $this->sql_datas['memory]='user', 不会消失

// 但是，memory方法，指定的表名会受clear的影响
$mysql=new Mysql();
$mysql->memory('user')->select();
$mysql->clear();		// 此时 $this->sql_datas['memory]不存在。






//
$pt=new ProductType();


//
$datas=[];
$pt->add($datas);


//
$pt->where('id=12')->delete();

//
$pt->where('id=13')->modify($datas);


// 
$pt->get_list();




//
$mysql=new Mysql();

$mysql->field('id,name')->group('sex desc,grade')->select();
$mysql->field('id,name')->group([
		'sex'=>'desc',
		'grade',
		'salary'=>'asc'
])->select();













