/**
 * Schema: Nooper_Shop
 */
drop schema if exists `nooper_shop`;
create schema if not exists `nooper_shop`
	default character set utf8
	default collate utf8_bin;

	
/**
 * Working in Nooper_Shop
 */
use `nooper_shop`


/**
 * Table: Administrator_Permissions
 */
drop table if exists `administrator_permissions`;
create table if not exists `administrator_permissions`(
	`id` bigint unsigned auto_increment not null,
	`code` varchar(20) character set utf8 collate utf8_bin not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;
	
insert into `administrator_permissions`(`code`, `name`) values('all', '全部权限');

	
/**
 * Table: Administrator_Roles
 */
drop table if exists `administrator_roles`;
create table if not exists `administrator_roles`(
	`id` bigint unsigned auto_increment not null,
	`code` varchar(20) set utf8 collate utf8_bin not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;
	
insert into `administrator_roles`(`code`, `name`) values('super-admin', '超级管理员');
	
	
/**
 * Table: Administrator_Role_rel_Permissions
 */
drop table if exists `administrator_role_rel_permissions`;
create table if not exists `administrator_role_rel_permissions`(
	`id` bigint unsigned auto_increment not null,
	`role_id` bigint unsigned not null,
	`permission_id` bigint unsigned not null,
	`add_time` timestamp default current_timestamp,
	unqiue(`role_id`,`permission_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;
	
	
/**
 * Table: Administrators
 */
drop table if exists `administrators`;
create table if not exists `administrators`(
	`id` bigint unsigned auto_increment not null,
	`role_id` bigint unsigned not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`pwd` char(41) character set utf8 collate utf8_bin not null,
	`profile_circular_picture_url` varchar(100) character set utf8 collate utf8_bin null,
	`profile_square_picture_url` varchar(100) character set utf8 collate utf8_bin null,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;
	
insert into `administrators`(`role_id`,`name`,`pwd`) values(1, 'root', password('0123456789'));


/**
 * Table: Coupon_Categories
 */
drop table if exists `coupon_categories`;
create table if not exists `coupon_categories`(
	`id` bigint unsigned auto_increment not null,
	`code` varchar(10) character set utf8 collate utf8_bin not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;
	
insert into `coupon_categories`(`code`, `name`) values
		('apmc', '全场限最低消费额度优惠券'),
		('apnmc', '全场不限最低消费额度优惠券'),
		('spcmc', '特定商品类别限最低消费额度优惠券'),
		('spcnmc', '特定商品类别不限最低消费额度优惠券'),
		('spmc', '特定商品限最低消费额度优惠券'),
		('spnmc', '特定商品不限最低消费额度优惠券');


/**
 * Table: Coupon_Images
 */
drop table if exists `coupon_images`;
create table if not exists `coupon_images`(
	`id` bigint unsigned auto_increment not null,
	`url` varchar(100) character set utf8 collate utf8_bin not null,
	`add_time`timestamp default current_timestamp not null,
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;

	
/**
 * Table: Coupon_Models
 */
drop table if exists `coupon_models`;
create table if not exists `coupon_models`(
	`id` bigint unsigned auto_increment not null, 
	`category_id` bigint unsigned not null,
	`image_id` bigint unsigned not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`tag_money` decimal(10, 2) not null,
	`min_charge` decimal(10, 2) null,
	`quantity` int unsigned not null,
	`begin_time` timestamp not null,
	`end_time` timestamp not null,
	`add_time`timestamp default current_timestamp,
	unique(`category_id`, `name`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;
	
	
/**
 * Table: Coupons
 */
drop table if exists `coupons`;
create table if not exists `coupons`(
	`id` bigint unsigned auto_increment not null,
	`unique_id` char(28) character set utf8 collate utf8_bin not null,
	`model_id` bigint unsigned not null,
	`add_time` timestamp default current_timestamp,
	`status` enum('prepare', 'normal', 'got', 'used', 'expired') not null,
	unique(`unique_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	
	
/**
 * Table: Coupon_rel_Product_Categories
 */
drop table if exists `coupon_rel_product_categories`;
create table if not exists `coupon_rel_product_categories`(
	`id` bigint unsigned auto_increment not null,
	`coupon_model_id` bigint unsigned not null,
	`product_category_id` bigint unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`coupon_model_id`,`product_category_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	
	
/**
 * Table: Coupon_rel_Product_Sets
 */
drop table if exists `coupon_rel_product_sets`;
create table if not exists `coupon_rel_product_sets`(
	`id` bigint unsigned auto_increment not null,
	`coupon_model_id` bigint unsigned not null,
	`product_set_id` bigint unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`coupon_model_id`,`product_set_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	
	
/**
 * Table: Coupon_rel_Products
 */
drop table if exists `coupon_rel_products`;
create table if not exists `coupon_rel_products`(
	`id` bigint unsigned auto_increment not null,
	`coupon_model_id` bigint unsigned not null,
	`product_id` bigint unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`coupon_model_id`,`product_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	
	

	
/**
 * Table: Customer_Use_Coupon_Records
 */
drop table if exists `customer_use_coupon_records`;
create table if not exists `customer_use_coupon_records`(
	`id` bigint unsigned auto_increment not null,
	`customer_id` bigint unsigned not null,
	`order_id` bigint unsigned not null,
	`coupon_id` bigint unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`coupon_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	
	
/**
 * Table: Customer_Browse_Records
 */
drop table if exists `customer_browse_records`;
create table if not exists `customer_browse_records`(
	`id` bigint unsigned auto_increment not null,
	`customer_id` bigint unsigned not null,
	`product_id` bigint unsigned not null,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	


/**
 * Table: Customer_Carts
 */
drop table if exists `customer_carts`;
create table if not exists `customer_carts`(
	`id` bigint unsigned auto_increment not null,
	`customer_id` bigint unsigned not null,
	`product_group_id` bigint unsigned null,
	`product_id` bigint unsigned null,
	`quantity` int unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`customer_id`,`product_group_id`,`product_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	


/**
 * Table: Customer_Deliver_Addresses
 */
drop table if exists `customer_deliver_addresses`;
create table if not exists `customer_deliver_addresses`(
	`id` bigint unsigned auto_increment not null,
	`customer_id` bigint unsigned not null,
	`receiver` varchar(50) not null,
	`phone` char(11) not null,
	`primary_address` varchar(200) character set utf8 collate utf8_bin not null,
	`detail_address` varchar(200) character set utf8 collate utf8_bin not null,
	`is_default` boolean default true,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	

 
 /**
 * Table: Customer_Favourites
 */
drop table if exists `customer_favourites`;
create table if not exists `customer_favourites`(
	`id` bigint unsigned auto_increment not null,
	`customer_id` bigint unsigned not null,
	`product_id` bigint unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`customer_id`,`product_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	


/**
 * Table: Customer_Get_Coupon_Records
 */
drop table if exists `customer_get_coupon_records`;
create table if not exists `customer_get_coupon_records`(
	`id` bigint unsigned auto_increment not null,
	`coupon_model_id` bigint unsigned not null,
	`product_id` bigint unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`coupon_model_id`,`product_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	

/**
 * Table: Customer_Grades
 */
drop table if exists `customer_grades`;
create table if not exists `customer_grades`(
	`id` bigint unsigned auto_increment not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`image_url` varchar(100) character set utf8 collate utf8_bin not null,
	`image_num` int unsigned default 1,
	`floor_point` bigint unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	
insert into `customer_grades`(`name`,`image_url`,`image_num`,`floor_point`) 
		values('普通会员', 'basic_user.png', 1, 0), ('铜牌会员', 'bronze_user.png', 1, 1000),
				('银牌会员', 'sliver_user.png', 1, 5000), ('金牌会员', 'gold_user.png', 1, 10000), ('钻石会员', 'diamond_user.png', 1, 50000);
















	
