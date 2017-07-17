<?php
// declare(strict_types = 1);
namespace NooperShop;

require_once '../init/bootstrap.php';



/* ���ʵ����Mysql�� */


// ��һ����򵥵ķ���,�����κβ���
$mysql=new Mysql();

// �ڶ��֣���ʵ������ͬʱ��ָ��Ҫ�����ı� ���ַ���ʵ������mysql�� �ں����Ĳ����У�������ʹ��memory()���������������ı�
$mysql=new Mysql('product_type');

// �����֣���ʵ������ͬʱ��������ָ���������ݿ�ʹ�õĲ���ֵ�� Ĭ�ϵ��������ݵĲ���ֵ����nooper.config.php�ļ�����database_connect_params
// ���������ǿ�����ʵ������ʱ�򣬲�ʹ�����ò��������Լ�ָ������

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


// �����֣������ָ�����Ӳ�����ͬʱ����ϣ��ָ�������ı�

$mysql=new Mysql(null, $params);



// �ײ�����ݲ�ѯ����query(), ʵ����mysql��ʱ�򣬲���Ҫָ�����������ǿ���ָ�����Ӳ���
// query()����������ִ�в�ѯ����
// ����ֵ��һ�����飬���汣���ѯ�õ�������

$mysql=new Mysql();
$sql="select * from product_type where id>10";
$datas=$mysql->query($sql);


// �ײ�����ݲ�������cmd(), ʵ����mysql��ʱ��ͬ��������Ҫָ������������ָ������
// cmd()����������ִ��������ɾ�����޸����ֲ���
// ����ֵ��һ����������ʾ���ݲ�����Ӱ�������
$mysql=new Mysql();
$sql="delete from product_type where id=12";
$sql2="insert into product_type() values()";
$sql3="update product_type set ?=? where id=32";
$end=$mysql->cmd($sql);



//���ݲ�ѯ�ĸ߼�����select()
// �÷�����Ҫ���sql��ɵ�����������ͬʹ�ã���������������sql�������ִ�в�ѯ
// field()��������Ҫ��ѯ���е��б�
// memeory()����ָ����ѯ�ı�
// where()����ָ�����������
// ���������ӹ����sql���Ϊ��select id,name,sex from user where id=12


$mysql=new Mysql();
$mysql->field('id,name,sex')->table('user')->where('id=12')->select();

// select distinct * from product_type order by id desc limit 20
$mysql->distinct(true)->field('*')->memory('product_type')->order('id desc')->limit(20)->select();


// select * from user inner join product on user.id=product.userid limit 10
$msyql->field('*')->memory('user')->join('inner join product on user.id=product.userid ')->limit(10)->select();


/* ���ݲ����ĸ߼�����add() */

$datas=[
		'name'=>'zhangsan',
		'sex'=>'male',
		'age'=>12
];
$msyql=new Mysql();
$mysql->memory('user')->add($datas);

// ����

$mysql=new Mysql('user');
$mysql->add($datas);

/* ���ݲ����ĸ߼�����modify()*/

$datas=[
		'sex'=>'male'
		'salary'=>'salary+200',
];
$msyql=new Mysql('user');
$mysql->where('id=12')->modify($datas);

/* ���ݸ߼���������delete() */
$mysql=new Mysql('user');
$msyql->where('id=12')->delete();


/* ���ݸ߼���������increase(), ���������modify()��һ�ָ߼�������ʽ�������������������� ,
 * ���磬Ҫ��ԭ�й�Աнˮ�Ļ�����ͳһ����200Ԫ���ʣ������ʹ��modify()������ʱ���޷�������д��
 * datas���������޷���ֵ�����ʹ��increase()
 */

$datas=[
		'salary'=>200
];
$mysql=new Mysql('user');
$mysql->where('name="zhangsan"')->increase($datas);

//��������ʵ�ֵ�sql���Ϊ update user set salary=salary+200 where name='zhangsan';





// clear()����������

$mysql=new Mysql('user');

// �������д���ִ��ɾ���Ĳ���, �����sql���Ϊ delete from user where id=12;
// ��Ϊʹ����where��������sql���������֣�����ڱ�������sql_datas�����
// $mysql->sql_datas['where']='id=12'�����ݣ����Ҹ�������ִ����delete�����󲢲�����ʧ.
$mysql->where(id=12)->delete();


// �������ڼ���ִ�в�ѯ���
// �������Ͽ���û��ָ����ѯ��������������һ��ɾ������where()Ч����Ȼ���ڣ� ���
// ʵ��ִ�е��� $mysql->field('*')->where('id=12')->select()
// Ϊ�˽��������⣬������ִ����delete֮��ִ��clear���������ǰ������Ĺ���Ч����
$mysql->field('*')->select();
// ������ķ���ִ��
$mysql->clear()->field('*')->select();



// ��ʵ����ʱ��ָ���˱����������ָ���ı���������clear������Ӱ�죬һֱ����ڡ�
$mysql=new Mysql('user');
$mysql->clear();  		// ��ʱ�� $this->sql_datas['memory]='user', ������ʧ

// ���ǣ�memory������ָ���ı�������clear��Ӱ��
$mysql=new Mysql();
$mysql->memory('user')->select();
$mysql->clear();		// ��ʱ $this->sql_datas['memory]�����ڡ�






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













