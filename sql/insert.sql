use FishingDB;

# Password = Omega123
insert into User values (default, "qwe@qwe.qwe", "$2y$10$.PWFhre43nG7h88stW/.PelsIe09hwLROWIkkij4o8NgDYZtP6/c.", "qwe", null);

insert into Trip values (default, 2, 2, 2, 2, date('2008-10-29 14:56:59'), date('2008-11-11 11:12:01'));

insert into Catch values (default, 1, null, 1, 1, 1, date('2008-11-11 11:12:01'), point(46.057216, -72.824669));
insert into Catch values (default, 1, null, 1, 1, 1, date('2008-11-11 11:11:11'), point(46.057236, -72.824699));