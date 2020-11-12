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

insert into Catch values (default, 1, 1, 1, 1, 1, date('2008-11-11 11:13:11'), point(46.057236, -72.824689));
insert into Winds values (default, 3, 2, 'W');
insert into Fish values (default, 3, 'Trout', 20);


insert into Trip values (default, 1, 1, 1, 1, date('2008-10-29 14:56:59'), date('2008-11-11 11:12:01'));

insert into Catch values (default, 2, null, 1, 1, 1, date('2008-11-11 11:12:01'), point(45.000000, -75.000000));
insert into Winds values (default, 4, 5, 'S');
insert into Fish values (default, 4, 'Muskellunge', 20);

insert into Hotspot values (default, 1, date('2008-10-29 14:56:59'), false, point(47.057236, -73.824689));

insert into Trip values (default, 1, 5, 13, 6, date('2008-10-29 14:56:59'), date('2008-10-29 20:12:01'));

insert into Catch values (default, 3, 2, 21, 1, 1, date('2008-11-11 11:13:11'), point(47.057235, -73.824687));
insert into Winds values (default, 5, 44, 'W');
insert into Fish values (default, 5, 'Salmon', 10);

insert into Catch values (default, 3, 2, 22, 1, 1, date('2008-11-11 11:13:11'), point(47.057235, -73.824687));
insert into Winds values (default, 6, 20, 'W');
insert into Fish values (default, 6, 'Salmon', 20);

insert into Catch values (default, 3, 2, 11, 1, 1, date('2008-11-11 11:13:11'), point(47.057235, -73.824687));
insert into Winds values (default, 7, 22, 'W');
insert into Fish values (default, 7, 'Salmon', 400);

insert into Catch values (default, 3, 2, 14, 1, 1, date('2008-11-11 11:13:11'), point(47.057235, -73.824687));
insert into Winds values (default, 8, 10, 'W');
insert into Fish values (default, 8, 'Salmon', 220);