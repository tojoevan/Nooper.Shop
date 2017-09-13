
insert into customers values(default, 'uni-id-001', 'open-id-001', 'nobody', '000000', 0, 0, default, 'normal');

insert into customers values(default, 'uni-id-002', 'open-id-002', 'luna', '123456', 0, 0, default, 'normal');

insert into customer_deliver_addresses values(default, 1, 'profoundblue', '18630856246', 'tianjin', 'jinnan', true, default);

insert into customer_deliver_addresses values(default, 1, 'lanboshu', '13702147059', 'tianjin', 'jinnan', false, default);

insert into customer_message_categories values
		(default, 'IMPORTANT', '重要信息', 100, default),
		(default, 'NOTICE', '通知信息', 10, default);
