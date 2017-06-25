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
	`string` varchar(2000) character set utf8 collate utf8_bin not null,
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
	`phone` varchar(50) character set utf8 collate utf8_bin  not null,
	`note` varchar(600) character set utf8 collate utf8_bin null,
	`add_time` timestamp default current_timestamp,
	`qr_scene_key` varchar(200) character set utf8 collate utf8_bin null,
	`qr_ticket` varchar(600) character set utf8 collate utf8_bin null,
	`qr_url` varchar(300) character set utf8 collate utf8_bin null,
	`qr_picture` varchar(100) character set utf8 collate utf8_bin null,
	`subscribe_num` bigint unsigned default 0,
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
	`note` varchar(600) character set utf8 collate utf8_bin null,
	`add_time` timestamp default current_timestamp,
	`qr_scene_key` varchar(200) character set utf8 collate utf8_bin null,
	`qr_ticket` varchar(600) character set utf8 collate utf8_bin null,
	`qr_url` varchar(300) character set utf8 collate utf8_bin null,
	`qr_picture` varchar(100) character set utf8 collate utf8_bin null,
	`subscribe_num` bigint unsigned default 0,
	unique(`name`),
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
	`subscribe_time`timestamp not null,
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
	`unsubscribe_time` datetime not null,
	`add_time` timestamp default current_timestamp,
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
	`open_id` varchar(100) character set utf8 collate utf8_bin not null,
	`union_id` varchar(100) character set utf8 collate utf8_bin null,
	`nickname` varchar(50) character set utf8 collate utf8_bin not null,
	`remark` varchar(100) null,
	`sex` enum('unknown','male','female') not null,
	`language` varchar(50) not null,
	`country` varchar(50) not null,
	`province` varchar(50) not null,
	`city` varchar(50) not null,
	`head_img_url` varchar(500) null,
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
insert into `spread_employee`(`name`,`phone`) values('穆怀永', '13802022171'),
	('管树阳', '13802089171'),
	('方建罡', '18722489910'), 
	('刘永梅', '13802022790'), 
	('穆勇', '13512214145'), 
	('田洋', '13820627055'),
	('鲁凯', '18722488680'),
	('王洪', '18781650364'), 
	('于金宝', '13752669676'), 
	('林善隆','18222353657'),
	('崔艳','18512277021'),
	('郝洁', '15620982386'), 
	('李金', '13752608989'), 
	('张艳娇', '13821121818'),
	('张倩', '13920235790'), 
	('刘永惠', '13820222400'),
	('刘永媛', '13652086850'),
	('王志忠', '15332002039'),
	('周瑞锋', '15822955842'),
	('戴广坤', '15122816034'),
	('张朋虎', '13612135381'),
	('王德忠', '13072234248'),
	('霍然华', '15522002375'), 
	('马胜芳', '15620658180'), 
	('宫际敏', '13752351692'), 
	('孔庆莉', '15822908584'),
	('王瑶', '13820813738'), 
	('张雪梅', '18622294192'), 
	('王巍', '13612084044'), 
	('王丽', '13512288066');
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	