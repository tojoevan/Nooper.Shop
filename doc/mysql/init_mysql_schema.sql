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
	`code` varchar(20) character set utf8 collate utf8_bin not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	unique(`code`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;
	
	
/**
 * Table: Coupon_Default_Params
 */
drop table if exists `coupon_default_params`;
create table if not exists `coupon_default_params`(
	`id` bigint unsigned auto_increment not null,
	`add_time` timestamp default current_timestamp,
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
	`code` varchar(20) character set utf8 collate utf8_bin not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`down_money` decimal(10, 2) not null,
	`min_charge_money` decimal(10, 2)  default 0,
	`begin_time` bigint unsigned not null,
	`end_time` bigint unsigned not null,
	`add_time`timestamp default current_timestamp,
	`status` enum('normal', 'expired', 'deleted') character set utf8 collate utf8_bin not null,
	unique(`name`),
	unique(`code`),
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
	`unique_id` char(18) character set utf8 collate utf8_bin not null,
	`model_id` bigint unsigned not null,
	`customer_id` bigint unsigned not null,
	`order_id` bigint unsigned null,
	`use_time` bigint unsigned null,
	`add_time` timestamp default current_timestamp,
	`status` enum('unused', 'used', 'expired') character set utf8 collate utf8_bin not null,
	unique(`order_id`),
	unique(`unique_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	

/**
 * Table: Customer_Balance_Change_Records
 */
drop table if exists `customer_balance_change_records`;
create table if not exists `customer_balance_change_records`(
	`id` bigint unsigned auto_increment not null,
	`customer_id` bigint unsigned not null,
	`gift_card_id` bigint unsigned null,
	`order_id` bigint unsigned null,
	`money_num` decimal(10, 2) not null,
	`add_time` timestamp default current_timestamp,
	unique(`order_id`),
	unique(`gift_card_id`),
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
	`product_id` bigint unsigned not null,
	`quantity` int unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`customer_id`,`product_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	


/**
 * Table: Customer_Default_Params
 */
drop table if exists `customer_default_params`;
create table if not exists `customer_default_params`(
	`id` bigint unsigned auto_increment not null,
	`add_time` timestamp default current_timestamp,
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
	`region_id` bigint unsigned not null,
	`province_id` bigint unsigned null,
	`receiver` varchar(50) not null,
	`phone` char(11) not null,
	`primary_address` varchar(200) character set utf8 collate utf8_bin not null,
	`detail_address` varchar(200) character set utf8 collate utf8_bin not null,
	`is_default` boolean default true,
	`add_time` timestamp default current_timestamp,
	`status` enum('normal', 'disabled') character set utf8 collate utf8_bin not null,
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
 * Table: Customer_Profiles
 */
drop table if exists `customer_profiles`;
create table if not exists `customer_profiles`(
	`id` bigint unsigned auto_increment not null,
	`customer_id` bigint unsigned not null,
	`id_card` char(18) character set utf8 collate utf8_bin null,
	`real_name` varchar(50) character set utf8 collate utf8_bin null,
	`sex` enum('secrecy', 'male', 'female') character set utf8 collate utf8_bin null,
	`phone` char(11) character set utf8 collate utf8_bin not null,
	`email` varchar(200) character set utf8 collate utf8_bin null,
	`picture_circular_ url` varchar(100) character set utf8 collate utf8_bin null,
	`picture_square_url` varchar(100) character set utf8 collate utf8_bin null,
	`add_time` timestamp default current_timestamp,
	unique(`email`),
	unique(`phone`),
	unique(`id_card`),
	unique(`customer_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		


/**
 * Table: Customer_Reviews
 */
drop table if exists `customer_reviews`;
create table if not exists `customer_reviews`(
	`id` bigint unsigned auto_increment not null,
	`customer_id` bigint unsigned not null,
	`order_id` bigint unsigned not null,
	`product_id` bigint unsigned not null,
	`grade` enum('5', '4', '3', '2', '1') character set utf8 collate utf8_bin not null,
	`description` varchar(500) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`order_id`,`product_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		


/**
 * Table: Customers
 */
drop table if exists `customers`;
create table if not exists `customers`(
	`id` bigint unsigned auto_increment not null,
	`unique_id` char(26) character set utf8 collate utf8_bin not null,
	`wx_open_id` varchar(128) character set utf8 collate utf8_bin not null,
	`nickname` varchar(50) character set utf8 collate utf8_bin null,
	`pwd` char(41) character set utf8 collate utf8_bin not null,
	`balance` decimal(10, 2) unsigned default 0.00,
	`point` bigint unsigned default 0,
	`add_time` timestamp default current_timestamp,
	`status` enum('normal', 'locked') character set utf8 collate utf8_bin not null,
	unique(`wx_open_id`),
	unique(`unique_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	


/**
 * Table: Express_Address_Cities
 */
drop table if exists `express_address_cities`;
create table if not exists `express_address_cities`(
	`id` bigint unsigned auto_increment not null,
	`province_id` bigint unsigned not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`province_id`,`name`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	


/**
 * Table: Express_Address_Counties
 */
drop table if exists `express_address_counties`;
create table if not exists `express_address_counties`(
	`id` bigint unsigned auto_increment not null,
	`city_id` bigint unsigned not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`city_id`,`name`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	

/**
 * Table: Express_Address_Provinces
 */
drop table if exists `express_address_provinces`;
create table if not exists `express_address_provinces`(
	`id` bigint unsigned auto_increment not null,
	`region_id` bigint unsigned not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`region_id`,`name`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	
	
/**
 * Table: Express_Address_Regions
 */
drop table if exists `express_address_regions`;
create table if not exists `express_address_regions`(
	`id` bigint unsigned auto_increment not null,
	`code` varchar(20) character set utf8 collate utf8_bin not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	unique(`code`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	
	
/**
 * Table: Express_Address_Towns
 */
drop table if exists `express_address_towns`;
create table if not exists `express_address_towns`(
	`id` bigint unsigned auto_increment not null,
	`county_id` bigint unsigned not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`county_id`,`name`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	
	
/**
 * Table: Express_Carriage_Global_Params
 */
drop table if exists `express_carriage_global_params`;
create table if not exists `express_carriage_global_params`(
	`id` bigint unsigned auto_increment not null,
	`region_id` bigint unsigned not null,
	`basic_carriage` decimal(10, 2) unsigned not null,
	`each_plus_carriage` decimal(10, 2) unsigned not null,
	`tax_rate` decimal(10, 2) unsigned default 0.00,
	`add_time` timestamp default current_timestamp,
	unique(`region_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		

	
/**
 * Table: Express_Carriage_Inner_Template_Details
 */
drop table if exists `express_carriage_inner_template_details`;
create table if not exists `express_carriage_inner_template_details`(
	`id` bigint unsigned auto_increment not null,
	`template_id` bigint unsigned not null,
	`region_id` bigint unsigned not null,
	`province_id` bigint unsigned not null,
	`basic_carriage` decimal(10, 2) unsigned not null,
	`each_plus_carriage` decimal(10, 2) unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`template_id`, `region_id`, `province_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;			
	
	
/**
 * Table: Express_Carriage_Inner_Templates
 */
drop table if exists `express_carriage_inner_templates`;
create table if not exists `express_carriage_inner_templates`(
	`id` bigint unsigned auto_increment not null,
	`code` varchar(20) character set utf8 collate utf8_bin not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	unique(`code`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	
	
/**
 * Table: Express_Carriage_Outer_Template_Details
 */
drop table if exists `express_carriage_outer_template_details`;
create table if not exists `express_carriage_outer_template_details`(
	`id` bigint unsigned auto_increment not null,
	`template_id` bigint unsigned not null,
	`region_id` bigint unsigned not null,
	`basic_carriage` decimal(10, 2) unsigned not null,
	`each_plus_carriage` decimal(10, 2) unsigned not null,
	`tax_rate` decimal(10, 2) unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`template_id`, `region_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	
	
/**
 * Table: Express_Carriage_Outer_Templates
 */
drop table if exists `express_carriage_outer_templates`;
create table if not exists `express_carriage_outer_templates`(
	`id` bigint unsigned auto_increment not null,
	`code` varchar(20) character set utf8 collate utf8_bin not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	unique(`code`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
		

/**
 * Table: Express_Corporations
 */
drop table if exists `express_corporations`;
create table if not exists `express_corporations`(
	`id` bigint unsigned auto_increment not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`query_api` varchar(100) character set utf8 collate utf8_bin null,
	`place` int unsigned default 0,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;
	

/**
 * Table: Express_Default_Params
 */
drop table if exists `express_default_params`;
create table if not exists `express_default_params`(
	`id` bigint unsigned auto_increment not null,
	`carriage_mode` enum('global', 'template') character set utf8 collate utf8_bin not null,
	`inner_free_money` decimal(10, 2) unsigned default 0.00,
	`inner_ceil_money` decimal(10, 2) unsigned default 0.00,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	

/**
 * Table: Expresses
 */
drop table if exists `expresses`;
create table if not exists `expresses`(
	`id` bigint unsigned auto_increment not null,
	`order_id` bigint unsigned not null,
	`corporation_id` bigint unsigned not null, 
	`code` varchar(20) character set utf8 collate utf8_bin not null,
	`carriage` decimal(10, 2) unsigned not null,
	`address` varchar(200) character set utf8 collate utf8_bin not null,
	`receiver` varchar(50) not null,
	`phone` varchar(20) not null,
	`add_time` timestamp default current_timestamp,
	unique(`corporation_id`, `code`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	

/**
 * Table: Gift_Card_Default_Params
 */
drop table if exists `gift_card_default_params`;
create table if not exists `gift_card_default_params`(
	`id` bigint unsigned auto_increment not null,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	
	
/**
 * Table: Gift_Card_Models
 */
drop table if exists `gift_card_models`;
create table if not exists `gift_card_models`(
	`id` bigint unsigned auto_increment not null, 
	`code` varchar(20) character set utf8 collate utf8_bin not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`recharge_money` decimal(10, 2) unsigned not null,
	`sale_price`decimal(10, 2) unsigned not null,
	`add_time` timestamp default current_timestamp,
	`status` enum('normal', 'deleted') character set utf8 collate utf8_bin not null,
	unique(`name`),
	unique(`code`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	
	
/**
 * Table: Gift_Cards
 */
drop table if exists `gift_cards`;
create table if not exists `gift_cards`(
	`id` bigint unsigned auto_increment not null,
	`unique_id` char(22) character set utf8 collate utf8_bin not null,
	`customer_id` bigint unsigned not null,
	`model_id` bigint unsigned not null,
	`code` char(22) character set utf8 collate utf8_bin not null,
	`transaction_id` varchar(32) character set utf8 collate utf8_bin null,
	`pay_time` bigint unsigned null,
	`recharge_time` bigint unsigned null,
	`add_time` timestamp default current_timestamp,
	`status` enum('unpaid', 'paid', 'recharged') character set utf8 collate utf8_bin not null,
	unique(`transaction_id`),
	unique(`code`),
	unique(`unique_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	
	
/**
 * Table: Manager_Default_Params
 */
drop table if exists `manager_default_params`;
create table if not exists `manager_default_params`(
	`id` bigint unsigned auto_increment not null,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	

/**
 * Table: Manager_Permissions
 */
drop table if exists `manager_permissions`;
create table if not exists `manager_permissions`(
	`id` bigint unsigned auto_increment not null,
	`code` varchar(20) character set utf8 collate utf8_bin not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	unique(`code`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;
	
	
/**
 * Table: Manager_Role_rel_Permissions
 */
drop table if exists `manager_role_rel_permissions`;
create table if not exists `manager_role_rel_permissions`(
	`id` bigint unsigned auto_increment not null,
	`role_id` bigint unsigned not null,
	`permission_id` bigint unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`role_id`,`permission_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;

	
/**
 * Table: Manager_Roles
 */
drop table if exists `manager_roles`;
create table if not exists `manager_roles`(
	`id` bigint unsigned auto_increment not null,
	`code` varchar(20) character set utf8 collate utf8_bin not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	unique(`code`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;
	
	
/**
 * Table: Managers
 */
drop table if exists `managers`;
create table if not exists `managers`(
	`id` bigint unsigned auto_increment not null,
	`role_id` bigint unsigned not null,
	`name` varchar(20) character set utf8 collate utf8_bin not null,
	`email` varchar(50) character set utf8 collate utf8_bin not null,
	`pwd` char(41) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`email`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;
	
	
/**
 * Table: Message_Categories
 */
drop table if exists `message_categories`;
create table if not exists `message_categories`(
	`id` bigint unsigned auto_increment not null,
	`code` varchar(20) character set utf8 collate utf8_bin not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	unique(`code`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	

/**
 * Table: Message_Default_Params
 */
drop table if exists `message_default_params`;
create table if not exists `message_default_params`(
	`id` bigint unsigned auto_increment not null,
	`auto_clear_trigger` boolean default 1,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	


/**
 * Table: Messages
 */
drop table if exists `messages`;
create table if not exists `messages`(
	`id` bigint unsigned auto_increment not null,
	`unique_id` char(16) character set utf8 collate utf8_bin not null,
	`category_id` bigint unsigned not null,
	`customer_id` bigint unsigned not null,
	`title` varchar(100) character set utf8 collate utf8_bin not null,
	`description` varchar(1000) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	`status` enum('unread', 'read') character set utf8 collate utf8_bin not null,
	unique(`unique_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	

/**
 * Table: Order_Complete_Records
 */
drop table if exists `order_complete_records`;
create table if not exists `order_complete_records`(
	`id` bigint unsigned auto_increment not null,
	`customer_id` bigint unsigned not null,
	`order_id` bigint unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`order_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	

/**
 * Table: Order_Default_Params
 */
drop table if exists `order_default_params`;
create table if not exists `order_default_params`(
	`id` bigint unsigned auto_increment not null,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	
	
/**
 * Table: Order_Details
 */
drop table if exists `order_details`;
create table if not exists `order_details`(
	`id` bigint unsigned auto_increment not null,
	`order_id` bigint unsigned not null,
	`product_group_id` bigint unsigned null,
	`product_id` bigint unsigned null,
	`tag_price` decimal(10, 2) unsigned not null,
	`discount_price` decimal(10, 2) unsigned not null,
	`quantity` int unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`order_id`, `product_group_id`, `product_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;			
	

/**
 * Table: Order_Pay_Records
 */
drop table if exists `order_pay_records`;
create table if not exists `order_pay_records`(
	`id` bigint unsigned auto_increment not null,
	`customer_id` bigint unsigned not null,
	`order_id` bigint unsigned not null,
	`pay_money` decimal(10, 2) unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`order_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	
	
/**
 * Table: Orders
 */
drop table if exists `orders`;
create table if not exists `orders`(
	`id` bigint unsigned auto_increment not null,
	`unique_id` char(19) character set utf8 collate utf8_bin not null,
	`customer_id` bigint unsigned not null,
	`deliver_address_id` bigint unsigned not null,
	`total_tag_money` decimal(10, 2) unsigned not null,
	`total_discount_money` decimal(10, 2) unsigned not null,
	`total_express_carriage_money` decimal(10, 2) unsigned not null,
	`total_money` decimal(10, 2) unsigned not null,
	`add_time` timestamp default current_timestamp,
	`pay_method` enum('wechat', 'balance') character set utf8 collate utf8_bin null,
	`status` enum('unpaid', 'paid', 'shipped', 'completed', 'closed') character set utf8 collate utf8_bin not null,
	unique(`unique_id`),
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
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
		
			
/**
 * Table: Product_Category_Properties
 */
drop table if exists `product_category_properties`;
create table if not exists `product_category_properties`(
	`id` bigint unsigned auto_increment not null,
	`category_id` bigint unsigned not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`category_id`, `name`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	
	
/**
 * Table: Product_Children_Categories
 */
drop table if exists `product_children_categories`;
create table if not exists `product_children_categories`(
	`id` bigint unsigned auto_increment not null,
	`parent_id` bigint unsigned not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`add_time` timestamp default current_timestamp,
	unique(`parent_id`, `name`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	
	
/**
 * Table: Product_Default_Params
 */
drop table if exists `product_default_params`;
create table if not exists `product_default_params`(
	`id` bigint unsigned auto_increment not null,
	`ceil_property_num` int unsigned not null,
	`ceil_sale_num` int unsigned not null,
	`stock_num` int unsigned not null,
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	
	
/**
 * Table: Product_Details
 */
drop table if exists `product_details`;
create table if not exists `product_details`(
	`id` bigint unsigned auto_increment not null,
	`product_id` bigint unsigned not null,
	`description` varchar(20000) character set utf8 collate utf8_bin not null,
	`add_time` timestamp default current_timestamp,
	unique(`product_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	
	
	/**
 * Table: Product_Group_Details
 */
drop table if exists `product_group_details`;
create table if not exists `product_group_details`(
	`id` bigint unsigned auto_increment not null,
	`group_id` bigint unsigned not null,
	`product_id` bigint unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`group_id`, `product_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
		
	
/**
 * Table: Product_Groups
 */
drop table if exists `product_groups`;
create table if not exists `product_groups`(
	`id` bigint unsigned auto_increment not null,
	`code` varchar(20) character set utf8 collate utf8_bin not null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`add_time` timestamp default current_timestamp,
	unique(`name`),
	unique(`code`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;	
	

/**
 * Table: Product_Picture_Thumbnails
 */
drop table if exists `product_picture_thumbnails`;
create table if not exists `product_picture_thumbnails`(
	`id` bigint unsigned auto_increment not null,
	`product_id` bigint unsigned not null,
	`url` varchar(100) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`is_primary` boolean default false,
	`add_time` timestamp default current_timestamp,
	unique(`url`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	
	
/**
 * Table: Product_Pictures
 */
drop table if exists `product_pictures`;
create table if not exists `product_pictures`(
	`id` bigint unsigned auto_increment not null,
	`product_id` bigint unsigned not null,
	`url` varchar(100) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`is_primary` boolean default false,
	`add_time` timestamp default current_timestamp,
	unique(`url`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	

/**
 * Table: Product_Property_Datas
 */
drop table if exists `product_property_datas`;
create table if not exists `product_property_datas`(
	`id` bigint unsigned auto_increment not null,
	`product_id` bigint unsigned not null,
	`property_id` bigint unsigned not null,
	`value` varchar(50) character set utf8 collate utf8_bin not null,
	`position` int unsigned default 0,
	`add_time` timestamp default current_timestamp,
	unique(`product_id`, `property_id`, `value`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	
	
/**
 * Table: Product_rel_Express_Carriage_Templates
 */
drop table if exists `product_rel_express_carriage_templates`;
create table if not exists `product_rel_express_carriage_templates`(
	`id` bigint unsigned auto_increment not null,
	`product_id` bigint unsigned not null,
	`carriage_template_id` bigint unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`product_id`, `carriage_template_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	

/**
 * Table: Product_Stocks
 */
drop table if exists `product_stocks`;
create table if not exists `product_stocks`(
	`id` bigint unsigned auto_increment not null,
	`product_id` bigint unsigned not null,
	`property_datas_str` varchar(200) character set utf8 collate utf8_bin not null,
	`stock` int unsigned not null,
	`add_time` timestamp default current_timestamp,
	unique(`product_id`, `property_datas_str`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	
	
/**
 * Table: Products
 */
drop table if exists `products`;
create table if not exists `products`(
	`id` bigint unsigned auto_increment not null,
	`unique_id` char(16) character set utf8 collate utf8_bin not null,
	`category_id` bigint unsigned not null,
	`children_category_id` bigint unsigned not null,
	`code` varchar(20) character set utf8 collate utf8_bin null,
	`name` varchar(50) character set utf8 collate utf8_bin not null,
	`tag_price` decimal(10, 2) unsigned not null,
	`discount_price` decimal(10, 2) unsigned not null,
	`position` int unsigned not null,
	`add_time` timestamp default current_timestamp,
	`status` enum('prepared', 'online', 'offline', 'deleted') character set utf8 collate utf8_bin not null,
	unique(`name`),
	unique(`code`),
	unique(`unique_id`),
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	

/**
 * Table: System_Default_Params
 */
drop table if exists `system_default_params`;
create table if not exists `system_default_params`(
	`id` bigint unsigned auto_increment not null,
	`add_time` timestamp default current_timestamp,
	primary key(`id`)
)
	engine innodb
	default character set utf8
	default collate utf8_bin;		
	
	
	


	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	





	
