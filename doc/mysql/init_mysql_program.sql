/**
 *
 */
set global event_scheduler=true;


/**
 *
 */
 use `nooper_shop`
 
 
 /**
  *
  */
 drop event if exists modify_expired_coupon_status;
 delimiter **
 create event if not exists modify_expired_coupon_status
	on schedule every 1 day
		starts now()+ interval 1 day
		ends '2028-01-01 00:00:00'
	on completion preserve
	enable
	do
		begin
			declare now_num bigint;
			set now_num=now()+0;
			update `coupon_models` `cm` inner join `coupons` `c` on `cm`.`id`=`c`.`model_id`
				set `c`.`status`='expired', `cm`.`status`='expired' where `cm`.`end_time`<=now_num && `cm`.`status`='normal';
		end**
delimiter ;























 
 