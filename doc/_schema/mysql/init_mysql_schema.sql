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

