/**
 *
 */
 use `nooper_shop`
 
 
 /**
 * Part 1: System
 */
 
 
 /**
 * Part 2: Manager
 */
insert into `manager_permissions`(`id`, `code`, `name`) values
		(1, 'system', '系统管理权限'),
		(2, 'manager', '授权管理权限'),
		(3, 'customer', '客户管理权限'),
		(4, 'product', '商品管理权限'),
		(5, 'order', '订单管理权限 '),
		(6, 'gift-card', '礼物卡管理权限'),
		(7, 'coupon', '优惠卷权利权限'),
		(8, 'message', '消息管理权限'),
		(9, 'express', '物流和税费管理权限');
	
insert into `manager_roles`(`id`, `code`, `name`) values(1, 'system-admin', '系统管理员');

insert into `manager_role_rel_permissions`(`id`, `role_id`, `permission_id`) values(1, 1, 1);

insert into `managers`(`id`, `role_id`,`name`, `email`, `pwd`) values(1, 1, 'root', 'root@localhost', password('0123456789'));
 
 
 /**
 * Part 3: Customer
 */
 
 
 /**
 * Part 4: Product
 */
 
 
 /**
 * Part 5: Order
 */
 
 
 /**
 * Part 6: Gift_Card
 */
 
 
 /**
 * Part 7: Coupon
 */
 
 
 /**
 * Part 8: Message
 */
 insert into `message_categories`(`id`, `code`,`name`) values
		(1, 'important', '重要信息'),
		(2, 'order', '订单信息'),
		(3, 'account', '账户信息'),
		(4, 'other', '其它信息');
		
 
 /**
 * Part 9: Express
 */
 /*
 insert into `express_default_params`(`id`, `carriage_mode`, `inner_free_money`, `inner_ceil_money`) values
		(1, 'global', 0.00, 0.00);
		
insert into `express_address_regions`(`id`, `code`, `name`) values
		(1, 'China', '中国'),
		(2, 'United States', '美国'),
		(3, 'Japan', '日本');
		
insert into `express_address_provinces`(`id`, `region_id`, `name`) values
		(1, 1, '北京'),
		(2, 1, '上海'),
		(3, 1, '天津'),
		(4, 1, '重庆'),
		(5, 1, '河北'),
		(6, 1, '山西'),
		(7, 1, '河南'),
		(8, 1, '辽宁'),
		(9, 1, '吉林'),
		(10, 1, '黑龙江'),
		(11, 1, '内蒙古'),
		(12, 1, '江苏'),
		(13, 1, '山东'),
		(14, 1, '安徽'),
		(15, 1, '浙江'),
		(16, 1, '福建'),
		(17, 1, '湖北'),
		(18, 1, '湖南'),
		(19, 1, '广东'),
		(20, 1, '广西'),
		(21, 1, '江西'),
		(22, 1, '四川'),
		(23, 1, '海南'),
		(24, 1, '贵州'),
		(25, 1, '云南'),
		(26, 1, '西藏'),
		(27, 1, '陕西'),
		(28, 1, '甘肃'),
		(29, 1, '青海'),
		(30, 1, '宁夏'),
		(31, 1, '新疆'),
		(32, 1, '台湾'),
		(33, 1, '港澳');
		*/

 /**
 * Part 10: User
 */
 




/*
insert into `coupon_categories`(`code`, `name`) values('RED-PAPER', '全场红包'), ('COUPON', '全场优惠券');
insert into `message_default_params`(`auto_clear_switch`) values(1);
insert into `product_default_params`(`ceil_property_num`, `ceil_sale_num`, `stock_num`) values(2, 100, 1000);
*/

