use FishingDB;

# Password = Omega123
insert into User values (default, "qwe@qwe.qwe", "$2y$10$.PWFhre43nG7h88stW/.PelsIe09hwLROWIkkij4o8NgDYZtP6/c.", "qwe", null);

insert into Hotspot values (default, 1, date('2008-10-29 14:56:59'), false, point(46.057236, -72.824689));


insert into Trip values (default, 1, 3, 3, 3, date('2008-10-29 14:56:59'), date('2008-11-11 11:12:01'));

insert into Catch values (default, 1, 1, 1, 1, 1, date('2008-11-11 11:12:01'), point(46.057216, -72.824669));
insert into Winds values (default, 1, 1, 'N');
insert into Fish values (default, 1, 'Salmon', 15);

insert into Catch values (default, 1, 1, 1, 1, 1, date('2008-11-11 11:11:11'), point(46.057226, -72.824679));
insert into Winds values (default, 2, 5, 'N');
insert into Fish values (default, 2, 'Perch', 2);

insert into Catch values (default, 1, 1, 1, 1, 1, date('2008-11-11 11:11:11'), point(46.057236, -72.824689));
insert into Winds values (default, 3, 2, 'W');
insert into Fish values (default, 3, 'Trout', 20);


insert into Trip values (default, 1, 1, 1, 1, date('2008-10-29 14:56:59'), date('2008-11-11 11:12:01'));

insert into Catch values (default, 2, null, 1, 1, 1, date('2008-11-11 11:12:01'), point(45.000000, -75.000000));
insert into Winds values (default, 4, 5, 'S');
insert into Fish values (default, 4, 'Muskellunge', 20);