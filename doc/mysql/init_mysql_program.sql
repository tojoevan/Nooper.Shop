/**
 *
 */
set global event_scheduler=true;


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
 
 
 /**
 * Part 9: Express
 */
 
 
 /**
 * Part 10: User
 */
 
 
 /**
  *
  */
 drop event if exists modify_expired_coupon_status;
 delimiter **
 create event modify_expired_coupon_status
	on schedule every 1 day
		starts now()+ interval 1 day
		ends '2028-01-01 00:00:00'
	on completion preserve
	enable
	do
		begin
			declare now_num bigint;
			set now_num=unix_timestamp()+0;
			update `coupon_models` `cm` inner join `coupons` `c` on `cm`.`id`=`c`.`model_id`
				set `c`.`status`='expired', `cm`.`status`='expired' where `cm`.`end_time`<=now_num && `cm`.`status`='normal';
		end**
delimiter ;


/**
 *
 */
drop event if exists delete_unpaid_gift_card;
delimiter **
create event delete_unpaid_gift_card
	on schedule every 1 day
		starts now()+ interval 1 day
		ends '2028-01-01 00:00:00'
	on completion preserve
	enable
	do
		begin
			 declare now_num bigint;
			 declare max_length bigint;
			 set now_num=unix_timestamp();
			 set max_length=60*60*24*7;
			delete from `gift_cards` where `status`='unpaid' and (now_num-unix_timestamp(`add_time`)>max_length);
		end**
delimiter ;


/**
 *
 */
drop event if exists delete_expired_message;
delimiter **
create event delete_expired_message
	on schedule every 1 day
		starts now()+ interval 1 day
		ends '2028-01-01 00:00:00'
	on completion preserve
	enable
	do
		begin
			 declare now_num bigint;
			 declare max_length bigint;
			 set now_num=unix_timestamp();
			 set max_length=60*60*24*90;
			delete from `messages` where (now_num-unix_timestamp(`add_time`)>max_length);
		end**
delimiter ;


























 
 