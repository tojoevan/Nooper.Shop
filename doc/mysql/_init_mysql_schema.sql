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

























	
/**
 * Table: Product_Categories
 */
drop table if exists `product_categories`;
create table if not exists `product_categories`(
	`id` bigint unsigned auto_increment not null,
	`upper_id` bigint unsigned default 0,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`add_time` timestamp default current_timestamp,
	unique(`upper_id`, `name`),
	primary key(`id`)
)
	engine innodb
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
	unique(`code`),
	unique(`unique_id`),
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
	`summary` varchar(10000) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
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
	`description` varchar(20000) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
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
	unique(`wx_nickname`),
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

/**
 *
 */
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
('3','蓟州区'),
('3','静海区'),
('3','南开区'),
('3','塘沽区'),
('3','西青区'),
('3','武清区'),
('3','津南区'),
('3','汉沽区'),
('3','大港区'),
('3','北辰区'),
('3','宝坻区'),
('3','宁河区'),
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
('20','来宾市'),
('21','南昌市'),
('21','景德镇市'),
('21','萍乡市'),
('21','新余市'),
('21','九江市'),
('21','鹰潭市'),
('21','上饶市'),
('21','宜春市'),
('21','抚州市'),
('21','吉安市'),
('21','赣州市'),
('22','眉山市'),
('22','资阳市'),
('22','阿坝州'),
('22','甘孜州'),
('22','凉山州'),
('22','成都市'),
('22','自贡市'),
('22','攀枝花市'),
('22','泸州市'),
('22','绵阳市'),
('22','德阳市'),
('22','广元市'),
('22','遂宁市'),
('22','内江市'),
('22','乐山市'),
('22','宜宾市'),
('22','广安市'),
('22','南充市'),
('22','达州市'),
('22','巴中市'),
('22','雅安市'),
('23','琼海市'),
('23','万宁市'),
('23','海口市'),
('23','东方市'),
('23','三亚市'),
('23','文昌市'),
('23','五指山市'),
('23','临高县'),
('23','澄迈县'),
('23','定安县'),
('23','屯昌县'),
('23','昌江县'),
('23','白沙县'),
('23','琼中县'),
('23','陵水县'),
('23','保亭县'),
('23','乐东县'),
('23','三沙市'),
('23','儋州市'),
('24','贵阳市'),
('24','六盘水市'),
('24','遵义市'),
('24','铜仁市'),
('24','毕节市'),
('24','安顺市'),
('24','黔西南州'),
('24','黔东南州'),
('24','黔南州'),
('25','丽江市'),
('25','文山州'),
('25','迪庆州'),
('25','红河州'),
('25','西双版纳州'),
('25','楚雄州'),
('25','大理州'),
('25','德宏州'),
('25','怒江州'),
('25','昆明市'),
('25','曲靖市'),
('25','玉溪市'),
('25','昭通市'),
('25','普洱市'),
('25','临沧市'),
('25','保山市'),
('26','那曲地区'),
('26','山南地区'),
('26','昌都地区'),
('26','日喀则地区'),
('26','阿里地区'),
('26','林芝地区'),
('26','拉萨市'),
('27','西安市'),
('27','铜川市'),
('27','宝鸡市'),
('27','咸阳市'),
('27','渭南市'),
('27','延安市'),
('27','汉中市'),
('27','榆林市'),
('27','商洛市'),
('27','安康市'),
('28','甘南州'),
('28','定西市'),
('28','临夏州'),
('28','兰州市'),
('28','金昌市'),
('28','白银市'),
('28','天水市'),
('28','嘉峪关市'),
('28','平凉市'),
('28','庆阳市'),
('28','陇南市'),
('28','武威市'),
('28','张掖市'),
('28','酒泉市'),
('29','西宁市'),
('29','海东地区'),
('29','海北州'),
('29','黄南州'),
('29','海南州'),
('29','果洛州'),
('29','玉树州'),
('29','海西州'),
('30','银川市'),
('30','石嘴山市'),
('30','吴忠市'),
('30','固原市'),
('30','中卫市'),
('31','五家渠市'),
('31','阿拉尔市'),
('31','图木舒克市'),
('31','乌鲁木齐市'),
('31','克拉玛依市'),
('31','石河子市'),
('31','吐鲁番地区'),
('31','铁门关市'),
('31','哈密地区'),
('31','和田地区'),
('31','阿克苏地区'),
('31','喀什地区'),
('31','克孜勒苏州'),
('31','巴音郭楞州'),
('31','昌吉州'),
('31','博尔塔拉州'),
('31','伊犁州'),
('31','塔城地区'),
('31','阿勒泰地区'),
('32','台湾'),
('33','香港特别行政区'),
('33','澳门特别行政区');
