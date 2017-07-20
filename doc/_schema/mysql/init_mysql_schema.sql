/**
 *
 */
drop schema if exists `nooper_shop`;
create schema if not exists `nooper_shop`
	default character set utf8
	default collate utf8_bin;

/**
 *
 */
use `nooper_shop`

/**
 *
 */
drop table if exists `access_token`;
create table if not exists`access_token`(
	`id` bigint unsigned auto_increment not null,
	`string` varchar(150) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp on update current_timestamp ,
	primary key(`id`)	
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;

/**
 *
 */
drop table if exists `spread_employee`;
create table if not exists`spread_employee`(
	`id` bigint unsigned auto_increment not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`phone` varchar(20) character set utf8 collate utf8_bin  not null,
	`note` varchar(1000) character set utf8 collate utf8_bin null,
	`qr_scene_key` varchar(100) character set utf8 collate utf8_bin null,
	`qr_ticket` varchar(100) character set utf8 collate utf8_bin null,
	`qr_url` varchar(100) character set utf8 collate utf8_bin null,
	`add_time` timestamp default current_timestamp,
	unique(`phone`),
	unique(`name`),
	primary key(`id`)	
	)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;
	
/**
 *
 */
drop table if exists `spread_event`;
create table if not exists `spread_event`
(
	`id` bigint unsigned auto_increment not null,
	`name` varchar(100) character set utf8 collate utf8_bin not null,
	`qr_scene_key` varchar(200) character set utf8 collate utf8_bin null,
	`qr_ticket` varchar(600) character set utf8 collate utf8_bin null,
	`qr_url` varchar(300) character set utf8 collate utf8_bin null,
	`note` varchar(1000) character set utf8 collate utf8_bin null,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;
	
/**
 *
 */
drop table if exists `user`;
create table if not exists`user`(
	`id` bigint unsigned auto_increment not null,
	`open_id` varchar(50) character set utf8 collate utf8_bin not null,
	`nickname` varchar(50) character set utf8 collate utf8_bin not null,
	`remark` varchar(100) null,
	`sex` enum('unknown','male','female') not null,
	`language` varchar(50) not null,
	`country` varchar(50) not null,
	`province` varchar(50) not null,
	`city` varchar(50) not null,
	`head_img_url` varchar(200) null,
	`subscribe_time` timestamp not null,
	`add_time` timestamp default current_timestamp,
	unique(`open_id`),
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;

/**
 *
 */
drop table if exists `user_subscribe_record`;
create table if not exists`user_subscribe_record`(
	`id` bigint unsigned auto_increment not null,
	`user_open_id` varchar(100) character set utf8 collate utf8_bin not null,
	`create_time`timestamp not null,
	`qr_scene_key` varchar(200) character set utf8 collate utf8_bin null,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;

/**
 *
 */
drop table if exists `user_unsubscribe_record`;
create table if not exists`user_unsubscribe_record`(
	`id` bigint unsigned auto_increment not null,
	`user_open_id` varchar(100) character set utf8 collate utf8_bin not null,
	`create_time` timestamp not null,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;
	
/**
 *
 */
drop table if exists `user_text_message`;
create table if not exists`user_text_message`(
	`id` bigint unsigned auto_increment not null,
	`message_id` bigint unsigned not null,
	`user_open_id` varchar(100) character set utf8 collate utf8_bin not null,
	`content` varchar(2000) character set utf8 collate utf8_bin not null,
	`create_time` timestamp not null,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;
	
/**
 *
 */
drop table if exists `product_type`;
create table if not exists `product_type`(

	`id` bigint unsigned auto_increment not null,
	`name` varchar(100) character set utf8 collate utf8_bin not null,
	`up_id` bigint unsigned default 0,
	`position` int unsigned default 0,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;
	
/**
 *
 */
 drop table if exists `product_type_property`;
 create table if not exists `product_type_property`
(
	`id` bigint unsigned auto_increment not null,
	`type_id` bigint unsigned not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`add_time` timestamp default current_timestamp,
	unique(`type_id`, `name`),
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;
 
/**
 *
*/
drop table if exists `product`;
create table if not exists `product`(
	`id` bigint unsigned auto_increment not null,
	`unique_id` char(22) character set utf8 collate utf8_bin not null,
	`type_id` bigint unsigned not null,
	`code` varchar(50) character set utf8 collate utf8_bin not null,
	`name` varchar(200) character set utf8 collate utf8_bin not null,
	`tag_price` decimal(10, 4) not null,
	`discount_price` decimal(10, 4) not null,
	`add_time` timestamp default current_timestamp,
	`status` enum('prepare', 'online', 'offline', 'deleted') not null,
	unique(`unique_id`),
	unique(`code`),
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin; 
 
/**
 *
 */ 
 drop table if exists `product_summary`;
 create table if not exists `product_summary`(
	`id` bigint unsigned auto_increment not null,
	`product_id` bigint unsigned not null,
	`product_unique_id` char(22) character set utf8 collate utf8_bin not null,
	`summary` varchar(10000) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`product_unique_id`),
	unique(`product_id`),
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;

/**
 *
 */
drop table if exists `product_detail`;
create table if not exists `product_detail`(
	`id` bigint unsigned auto_increment not null,
	`product_id` bigint unsigned not null,
	`product_unique_id` char(22) character set utf8 collate utf8_bin not null,
	`description` varchar(20000) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`product_unique_id`),
	unique(`product_id`),
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;
	
/**
 *
 */
drop table if exists `product_picture`;
create table if not exists `product_picture`(
	`id` bigint unsigned auto_increment not null,
	`product_id` bigint unsigned not null,
	`product_unique_id` char(22) character set utf8 collate utf8_bin not null,
	`name` varchar(100) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`is_primary` boolean default false,
	`add_time` timestamp default current_timestamp,
	unique(`product_id`, `name`),
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;

/**
 *
 */
drop table if exists `product_picture_thumbnail`;
create table if not exists `product_picture_thumbnail`(

	`id` bigint unsigned auto_increment not null,
	`product_id` bigint unsigned not null,
	`product_unique_id` char(22) character set utf8 collate utf8_bin not null,
	`name` varchar(100) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`is_primary` boolean default false,
	`add_time` timestamp default current_timestamp,
	unique(`product_id`, `name`),
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;
	
/**
 *
 */
 drop table if exists `product_property_value`;
 create table if not exists `product_property_value`
(
	`id` bigint unsigned auto_increment not null,
	`product_id` bigint unsigned not null,
	`property_id` bigint unsigned not null,
	`value` varchar(50) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`add_time` timestamp default current_timestamp,
	unique(`product_id`, `property_id`, `value`),
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;
 
 /**
  *
  */
 drop table if exists `product_set`;
 create table if not exists `product_set`(
	`id` bigint unsigned auto_increment not null,
	`name` varchar(100) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;
	
 /**
  *
  */
 drop table if exists `product_set_detail`;
 create table if not exists `product_set_detail`(
	`id` bigint unsigned auto_increment not null,
	`set_id` bigint unsigned not null,
	`product_id` bigint unsigned not null,
	`product_unique_id` char(22) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`set_id`, `product_id`),
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;
  
 /**
 *
 */
drop table if exists `product_group`;
create table if not exists `product_group`(
	`id` bigint unsigned auto_increment not null,
	`unique_id` char(12) character set utf8 collate utf8_bin not null,
	`name` varchar(100) character set utf8 collate utf8_bin not null,
	`total_price` decimal(10, 4) not null,
	`add_time` timestamp default current_timestamp,
	`status` enum('prepare', 'online', 'offline', 'deleted') not null,
	unique(`name`),
	unique(`unique_id`),
	primary key(`id`)
)	
	engine InnoDB
	default character set utf8
	default collate utf8_bin;

/**
 *
 */
drop table if exists `product_group_detail`;
create table if not exists `product_group_detail`(
	`id` bigint unsigned auto_increment not null,
	`group_id` bigint unsigned not null,
	`group_unique_id` char(12) character set utf8 collate utf8_bin not null,
	`product_id` bigint unsigned not null,
	`product_unique_id` char(22) character set utf8 collate utf8_bin not null,
	`discount_price` decimal(10, 4) not null,
	`add_time` timestamp default current_timestamp,
	unique(`group_id`, `product_id`),
	primary key(`id`)
)
	engine InnoDB
	default character set utf8
	default collate utf8_bin;
/**
 *
 */
 drop table if exists `customer`;
 create table if not exists `customer`
(
	`id` bigint unsigned auto_increment not null,
	`unique_id` char(22) character set utf8 collate utf8_bin not null,
	`phone` varchar(50) character set utf8 collate utf8_bin not null,
	`wx_open_id` varchar(50) character set utf8 collate utf8_bin not null,
	`wx_nickname` varchar(50) character set utf8 collate utf8_bin not null,
	`wx_remark` varchar(100) character set utf8 collate utf8_bin not null,
	`growth` bigint unsigned default 0,
	`point` bigint unsigned default 0,
	`grade_id` int unsigned not null,
	`balance` decimal(10,4) default 0.0000,
	`add_time` timestamp default current_timestamp,
	`status` enum('normal', 'frozen') not null,
	unique(`unique_id`),
	unique(`nickname`),
	unique(`phone`),
	primary key(`id`)
)
default character set utf8
default collate utf8_bin;
/**
 *
 */
 drop table if exists `customer_browse_record`;
 create table if not exists `customer_browse_record`
(
	`id` bigint unsigned auto_increment not null,
	`customer_id` bigint unsigned not null,
	`customer_unique_id` char(22) character set utf8 collate utf8_bin not null,
	`product_id` bigint unsigned not null,
	`product_unique_id` char(22) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
default character set utf8
default collate utf8_bin;

 /**
  *
  */
 drop table if exists `customer_favourite`;
 create table if not exists `customer_favourite`
(
	`id` bigint unsigned auto_increment not null,
	`customer_id` bigint unsigned not null,
	`customer_unique_id` char(18) character set utf8 collate utf8_bin,
	`product_id` bigint unsigned not null,
	`product_unique_id` varchar(22) character set utf8 collate utf8_bin,	
	`add_time` timestamp default current_timestamp,
	unique(`customer_id`,`product_id`),
	primary key(`id`)
)
default character set utf8
default collate utf8_bin;

 /**
 *
 */
 drop table if exists `express_address_city`;
 create table if not exists `express_address_city`
(
	`id` bigint unsigned auto_increment not null,
	`province_id` bigint unsigned not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`is_top` boolean default false,
	`add_time` timestamp default current_timestamp,
	unique(`province_id`,`name`),
	primary key(`id`)
)
default character set utf8
default collate utf8_bin;
/**
 *
 */
 drop table if exists `express_address_county`;
 create table if not exists `express_address_county`
(
	`id` bigint unsigned auto_increment not null,
	`city_id` bigint unsigned not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`is_top` boolean default false,
	`add_time` timestamp default current_timestamp,
	unique(`city_id`,`name`),
	primary key(`id`)
)
default character set utf8
default collate utf8_bin;
 /**
 *
 */
 drop table if exists `express_address_province`;
 create table if not exists `express_address_province`
(
 	`id` bigint unsigned auto_increment not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`position` bigint unsigned default 0,
	`is_top` boolean default false,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	primary key(`id`)
)
default character set utf8
default collate utf8_bin;

/**
 *
 */ 
 drop table if exists `express_address_town`;
 create table if not exists `express_address_town`
(
	`id` bigint unsigned auto_increment not null,
	`county_id` bigint unsigned not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`is_top` boolean default false,
	`add_time` timestamp default current_timestamp,
	unique(`county_id`,`name`),
	primary key(`id`)
)
default character set utf8
default collate utf8_bin;

 /**
 *
 */
 drop table if exists `express_corporation`;
 create table if not exists `express_corporation`
(
	`id` bigint unsigned auto_increment not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`homepage` varchar(100) character set utf8 collate utf8_bin null,
	`query_api` varchar(200) character set utf8 collate utf8_bin null,
	`position` bigint unsigned default 0,
	`is_top` boolean default false,
	`add_time` timestamp default current_timestamp,
	`note` varchar(500) character set utf8 collate utf8_bin null,
	unique(`name`),
	primary key(`id`)
)
default character set utf8
default collate utf8_bin;

 /**
 *
 */
 drop table if exists `express_cost_default_param`;
create table if not exists `express_cost_default_param`
(
	`id` bigint unsigned auto_increment not null,
	`basic_cost` decimal(10,4) not null,
	`plus_each_cost` decimal(10,4) not null,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
default character set utf8
default collate utf8_bin;

 /**
 *
 */
 drop table if exists `express_cost_template`;
 create table if not exists `express_cost_template`
(
	`id` bigint unsigned auto_increment not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	primary key(`id`)
)
default character set utf8
default collate utf8_bin;
 /**
 *
 */
 drop table if exists `express_cost_template_detail`;
 create table if not exists `express_cost_template_detail`
(
	`id` bigint unsigned auto_increment not null,
	`template_id` bigint unsigned not null,
	`province_id` bigint unsigned not null,
	`basic_cost` decimal(10,4) not null,
	`plus_each_cost` decimal(10,4) not null,
	`add_time` timestamp default current_timestamp,
	unique(`template_id`,`province_id`),
	primary key(`id`)
)
default character set utf8
default collate utf8_bin;

 
 
/**
 *
 */
 insert into `spread_employee`(`name`,`phone`,`qr_scene_key`,`qr_ticket`,`qr_url`) values
	('穆怀永','13802022171','13802022171','gQHW8DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyMmYtcE1hMGlmUTAxMDAwMHcwN1UAAgR4rE9ZAwQAAAAA','http://weixin.qq.com/q/022f-pMa0ifQ010000w07U'),
	('管树阳','13802089171','13802089171','gQFK8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyUmZfaU1CMGlmUTAxMDAwMGcwN1QAAgTprE9ZAwQAAAAA','http://weixin.qq.com/q/02Rf_iMB0ifQ010000g07T'),
	('方建罡','18722489910','18722489910','gQFw8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyYjdOZk1LMGlmUTAxMDAwME0wN0cAAgQTrU9ZAwQAAAAA','http://weixin.qq.com/q/02b7NfMK0ifQ010000M07G'),
	('刘永梅','13802022790','13802022790','gQH-8DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyaS13cE5OMGlmUTAxMDAwME0wN3EAAgQmrU9ZAwQAAAAA','http://weixin.qq.com/q/02i-wpNN0ifQ010000M07q'),
	('穆勇','13512214145','13512214145','gQG_8DwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyb01veU1QMGlmUTAxMDAwME0wN2wAAgRErU9ZAwQAAAAA','http://weixin.qq.com/q/02oMoyMP0ifQ010000M07l'),
	('田洋','13820627055','13820627055','gQHJ8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyVnloYU4wMGlmUTAxMDAwMDAwN1gAAgSUrk9ZAwQAAAAA','http://weixin.qq.com/q/02VyhaN00ifQ010000007X'),
	('鲁凯','18722488680','18722488680','gQFe8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyTnVXT05aMGlmUTAxMDAwMHcwN3MAAgSirk9ZAwQAAAAA','http://weixin.qq.com/q/02NuWONZ0ifQ010000w07s'),
	('王洪','18781650364','18781650364','gQGj8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyY05JTU1JMGlmUTAxMDAwMGcwN2wAAgSork9ZAwQAAAAA','http://weixin.qq.com/q/02cNIMMI0ifQ010000g07l'),
	('于金宝','13752669676','13752669676','gQF58TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyeThmUk5IMGlmUTAxMDAwMHcwN2EAAgSrrk9ZAwQAAAAA','http://weixin.qq.com/q/02y8fRNH0ifQ010000w07a'),
	('林善隆','18222353657','18222353657','gQEO8jwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyQXotMk1xMGlmUTAxMDAwMDAwNzcAAgSurk9ZAwQAAAAA','http://weixin.qq.com/q/02Az-2Mq0ifQ0100000077'),
	('崔艳','18512277021','18512277021','gQGQ8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAycnBYOE05MGlmUTAxMDAwME0wN3EAAgS1rk9ZAwQAAAAA','http://weixin.qq.com/q/02rpX8M90ifQ010000M07q'),
	('郝洁','15620982386','15620982386','gQGd8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyV0ExaU16MGlmUTAxMDAwMHcwNzYAAgS4rk9ZAwQAAAAA','http://weixin.qq.com/q/02WA1iMz0ifQ010000w076'),
	('李金','13752608989','13752608989','gQFv8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyd19aLU5xMGlmUTAxMDAwMHcwNzYAAgTDrk9ZAwQAAAAA','http://weixin.qq.com/q/02w_Z-Nq0ifQ010000w076'),
	('张艳娇','13821121818','13821121818','gQE78TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyRk10WU5xMGlmUTAxMDAwMGcwN3UAAgTFrk9ZAwQAAAAA','http://weixin.qq.com/q/02FMtYNq0ifQ010000g07u'),
	('张倩','13920235790','13920235790','gQFi8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyalJUYU1vMGlmUTAxMDAwME0wN1cAAgTIrk9ZAwQAAAAA','http://weixin.qq.com/q/02jRTaMo0ifQ010000M07W'),
	('刘永惠','13820222400','13820222400','gQEZ8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAybWxhak5iMGlmUTAxMDAwME0wN3cAAgTKrk9ZAwQAAAAA','http://weixin.qq.com/q/02mlajNb0ifQ010000M07w'),
	('刘永媛','13652086850','13652086850','gQGP8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyRkQ3dk1XMGlmUTAxMDAwME0wN3gAAgTMrk9ZAwQAAAAA','http://weixin.qq.com/q/02FD7vMW0ifQ010000M07x'),
	('王志忠','15332002039','15332002039','gQFe8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyX2RUYk1WMGlmUTAxMDAwMGcwN0sAAgTPrk9ZAwQAAAAA','http://weixin.qq.com/q/02_dTbMV0ifQ010000g07K'),
	('周瑞锋','15822955842','15822955842','gQGU8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyTWZqR01WMGlmUTAxMDAwMDAwN18AAgTRrk9ZAwQAAAAA','http://weixin.qq.com/q/02MfjGMV0ifQ010000007_'),
	('戴广坤','15122816034','15122816034','gQFa8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyR2tzbE1QMGlmUTAxMDAwMGcwNzgAAgTUrk9ZAwQAAAAA','http://weixin.qq.com/q/02GkslMP0ifQ010000g078'),
	('张朋虎','13612135381','13612135381','gQFT8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyaHBwa004MGlmUTAxMDAwME0wN0wAAgTXrk9ZAwQAAAAA','http://weixin.qq.com/q/02hppkM80ifQ010000M07L'),
	('王德忠','13072234248','13072234248','gQF_8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyR1VOUU1fMGlmUTAxMDAwME0wN28AAgTark9ZAwQAAAAA','http://weixin.qq.com/q/02GUNQM_0ifQ010000M07o'),
	('霍然华','15522002375','15522002375','gQGN8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyTlRTbE5NMGlmUTAxMDAwME0wN0cAAgTerk9ZAwQAAAAA','http://weixin.qq.com/q/02NTSlNM0ifQ010000M07G'),
	('马胜芳','15620658180','15620658180','gQEo8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyd3dqMk1yMGlmUTAxMDAwMDAwN3kAAgThrk9ZAwQAAAAA','http://weixin.qq.com/q/02wwj2Mr0ifQ010000007y'),
	('宫际敏','13752351692','13752351692','gQFb8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAycWxNVk43MGlmUTAxMDAwMGcwN1EAAgTjrk9ZAwQAAAAA','http://weixin.qq.com/q/02qlMVN70ifQ010000g07Q'),
	('孔庆莉','15822908584','15822908584','gQEU8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyNXFrc01wMGlmUTAxMDAwME0wN2MAAgT2rk9ZAwQAAAAA','http://weixin.qq.com/q/025qksMp0ifQ010000M07c'),
	('王瑶','13820813738','13820813738','gQEd8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAydHBBRE5EMGlmUTAxMDAwMHcwN3MAAgT5rk9ZAwQAAAAA','http://weixin.qq.com/q/02tpADND0ifQ010000w07s'),
	('张雪梅','18622294192','18622294192','gQET8jwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyUTNMNk02MGlmUTAxMDAwMDAwNzMAAgT8rk9ZAwQAAAAA','http://weixin.qq.com/q/02Q3L6M60ifQ0100000073'),
	('王巍','13612084044','13612084044','gQFd8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAyRTJucE1fMGlmUTAxMDAwMGcwNzcAAgT-rk9ZAwQAAAAA','http://weixin.qq.com/q/02E2npM_0ifQ010000g077'),
	('王丽','13512288066','13512288066','gQFx8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAySjJwT01QMGlmUTAxMDAwMGcwN0kAAgQBr09ZAwQAAAAA','http://weixin.qq.com/q/02J2pOMP0ifQ010000g07I');

	
	
 insert into `express_address_province` (`name`) values
	('北京'),
	('上海'),
	('天津'),
	('重庆'),
	('河北'),
	('山西'),
	('河南'),
	('辽宁'),
	('吉林'),
	('黑龙江'),
	('内蒙古'),
	('江苏'),
	('山东'),
	('安徽'),
	('浙江'),
	('福建'),
	('湖北'),
	('湖南'),
	('广东'),
	('广西'),
	('江西'),
	('四川'),
	('海南'),
	('贵州'),
	('云南'),
	('西藏'),
	('陕西'),
	('甘肃'),
	('青海'),
	('宁夏'),
	('新疆'),
	('台湾'),
	('港澳'),
	('海外');

	
insert into `express_address_city`(`province_id`,`name`) values
('1','密云区'),
('1','朝阳区'),
('1','昌平区'),
('1','平谷区'),
('1','海淀区'),
('1','西城区'),
('1','东城区'),
('1','崇文区'),
('1','宣武区'),
('1','丰台区'),
('1','石景山区'),
('1','门头沟'),
('1','房山区'),
('1','通州区'),
('1','延庆县'),
('1','大兴区'),
('1','顺义区'),
('1','怀柔区'),
('2','静安区'),
('2','闸北区'),
('2','虹口区'),
('2','杨浦区'),
('2','宝山区'),
('2','闵行区'),
('2','嘉定区'),
('2','浦东新区'),
('2','青浦区'),
('2','松江区'),
('2','金山区'),
('2','奉贤区'),
('2','普陀区'),
('2','黄埔区'),
('2','崇明县'),
('2','徐汇区'),
('2','长宁区'),
('3','东丽区'),
('3','和平区'),
('3','河北区'),
('3','河东区'),
('3','河西区'),
('3','红桥区'),
('3','蓟县'),
('3','静海县'),
('3','南开区'),
('3','塘沽区'),
('3','西青区'),
('3','武清区'),
('3','津南区'),
('3','汉沽区'),
('3','大港区'),
('3','北辰区'),
('3','宝坻区'),
('3','宁河县'),
('4','璧山县'),
('4','荣昌县'),
('4','铜梁县'),
('4','江北区'),
('4','南岸区'),
('4','九龙坡区'),
('4','沙坪坝区'),
('4','大渡口区'),
('4','綦江区'),
('4','合川区'),
('4','巴南区'),
('4','北碚区'),
('4','江津区'),
('4','渝北区'),
('4','长寿区'),
('4','永川区'),
('4','渝中区'),
('4','高新区'),
('4','北部新区'),
('4','大足区'),
('4','万川区'),
('4','涪陵区'),
('4','滦平县'),
('4','南川区'),
('4','潼南县'),
('4','黔江区'),
('4','开县'),
('4','云阳县'),
('4','忠县'),
('4','垫江县'),
('4','城口县'),
('4','武隆县'),
('4','丰都县'),
('4','奉节县'),
('4','巫溪县'),
('4','巫山县'),
('4','石柱县'),
('4','彭水县'),
('4','酉阳县'),
('4','秀山县'),
('5','唐山市'),
('5','沧州市'),
('5','廊坊市'),
('5','衡水市'),
('5','石家庄市'),
('5','邯郸市'),
('5','邢台市'),
('5','保定市'),
('5','张家口市'),
('5','承德市'),
('5','秦皇岛市'),
('6','长治市'),
('6','太原市'),
('6','大同市'),
('6','阳泉市'),
('6','晋城市'),
('6','朔州市'),
('6','晋中市'),
('6','忻州市'),
('6','吕梁市'),
('6','临汾市'),
('6','运城市'),
('7','商丘市'),
('7','周口市'),
('7','驻马店市'),
('7','信阳市'),
('7','郑州市'),
('7','开封市'),
('7','洛阳市'),
('7','平顶山市'),
('7','焦作市'),
('7','鹤壁市'),
('7','新乡市'),
('7','安阳市'),
('7','濮阳市'),
('7','济源市'),
('7','许昌市'),
('7','漯河市'),
('7','三门峡市'),
('7','南阳市'),
('8','沈阳市'),
('8','大连市'),
('8','鞍山市'),
('8','抚顺市'),
('8','本溪市'),
('8','丹东市'),
('8','锦州市'),
('8','葫芦岛市'),
('8','营口市'),
('8','盘锦市'),
('8','阜新市'),
('8','辽阳市'),
('8','朝阳市'),
('8','铁岭市'),
('9','长春市'),
('9','吉林市'),
('9','四平市'),
('9','通化市'),
('9','白山市'),
('9','松原市'),
('9','白城市'),
('9','延边市'),
('9','辽源市'),
('10','七台河市'),
('10','黑河市'),
('10','绥化市'),
('10','大兴安岭地区'),
('10','哈尔滨市'),
('10','齐齐哈尔市'),
('10','鹤岗市'),
('10','双鸭山市'),
('10','鸡西市'),
('10','大庆市'),
('10','伊春市'),
('10','牡丹江市'),
('10','佳木斯市'),
('11','呼和浩特市'),
('11','包头市'),
('11','乌海市'),
('11','赤峰市'),
('11','乌兰察布市'),
('11','锡林郭勒盟'),
('11','呼伦贝尔市'),
('11','鄂尔多斯市'),
('11','巴彦淖尔市'),
('11','阿拉善盟'),
('11','兴安盟'),
('11','通辽市'),
('12','南京市'),
('12','徐州市'),
('12','连云港市'),
('12','淮安市'),
('12','宿迁市'),
('12','盐城市'),
('12','扬州市'),
('12','泰州市'),
('12','南通市'),
('12','镇江市'),
('12','常州市'),
('12','无锡市'),
('12','苏州市'),
('13','东营市'),
('13','潍坊市'),
('13','烟台市'),
('13','威海市'),
('13','莱芜市'),
('13','德州市'),
('13','临沂市'),
('13','聊城市'),
('13','滨州市'),
('13','菏泽市'),
('13','日照市'),
('13','济宁市'),
('13','泰安市'),
('13','济南市'),
('13','青岛市'),
('13','淄博市'),
('13','枣庄市'),
('14','铜陵市'),
('14','合肥市'),
('14','淮南市'),
('14','淮北市'),
('14','芜湖市'),
('14','蚌埠市'),
('14','马鞍山市'),
('14','安庆市'),
('14','黄山市'),
('14','滁州市'),
('14','阜阳市'),
('14','亳州市'),
('14','宣城市'),
('14','宿州市'),
('14','池州市'),
('14','六安市'),
('15','丽水市'),
('15','台州市'),
('15','舟山市'),
('15','宁波市'),
('15','杭州市'),
('15','温州市'),
('15','嘉兴市'),
('15','湖州市'),
('15','绍兴市'),
('15','金华市'),
('15','衢州市'),
('16','福州市'),
('16','厦门市'),
('16','三明市'),
('16','莆田市'),
('16','泉州市'),
('16','漳州市'),
('16','南平市'),
('16','龙岩市'),
('16','宁德市'),
('17','神农架林区'),
('17','武汉市'),
('17','潜江市'),
('17','黄石市'),
('17','襄阳市'),
('17','十堰市'),
('17','荆州市'),
('17','宜昌市'),
('17','孝感市'),
('17','黄冈市'),
('17','天门市'),
('17','仙桃市'),
('17','咸宁市'),
('17','恩施市'),
('17','鄂州市'),
('17','荆门市'),
('17','随州市'),
('18','张家界市'),
('18','郴州市'),
('18','益阳市'),
('18','永州市'),
('18','怀化市'),
('18','娄底市'),
('18','湘西州'),
('18','长沙市'),
('18','株洲市'),
('18','湘潭市'),
('18','衡阳市'),
('18','邵阳市'),
('18','岳阳市'),
('18','常德市'),
('19','广州市'),
('19','深圳市'),
('19','珠海市'),
('19','汕头市'),
('19','韶关市'),
('19','河源市'),
('19','梅州市'),
('19','惠州市'),
('19','汕尾市'),
('19','东莞市'),
('19','中山市'),
('19','江门市'),
('19','佛山市'),
('19','阳江市'),
('19','湛江市'),
('19','茂名市'),
('19','肇庆市'),
('19','云浮市'),
('19','清远市'),
('19','潮州市'),
('19','揭阳市'),
('20','贺州市'),
('20','百色市'),
('20','河池市'),
('20','崇左市'),
('20','南宁市'),
('20','柳州市'),
('20','桂林市'),
('20','梧州市'),
('20','北海市'),
('20','防城港市'),
('20','钦州市'),
('20','贵港市'),
('20','玉林市'),
('20','来宾市');
