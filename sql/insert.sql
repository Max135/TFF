use FishingDB;

# Password = Omega123
insert into User values (default, 'qwe@qwe.qwe', '$2y$10$.PWFhre43nG7h88stW/.PelsIe09hwLROWIkkij4o8NgDYZtP6/c.', 'qwe', null);
# Password = Pierick123
INSERT INTO User VALUES (default, 'p@p.p', '$2y$10$uFYMLRz.BGCaovD/7pYTKe85j98xgz/vhPjTw82JFPvte81qnULBq', 'Pierick', null);
# Password = Omega123
INSERT INTO User VALUES (default, 'arnaudNCFF@gmail.com', '$2y$10$VEGP3bzDDZ/52obDYeo2M.TgGqyI3E.guik0z0zuV9bt47H48rXce', 'Arnaud21', null);

insert into Hotspot values (default, 1, date('2008-10-29 14:56:59'), false, point(46.057236, -72.824689));


insert into Trip values (default, 1, 3, 3, 3, date('2008-10-29 14:56:59'), date('2008-11-11 11:12:01'));

insert into Catch values (default, 1, 1, 1, 1, 1, date('2008-11-12 11:12:01'), point(46.057216, -72.824669));
insert into Winds values (default, 1, 1, 'N');
insert into Fish values (default, 1, 'Salmon', 15, null);

insert into Catch values (default, 1, 1, 1, 1, 1, date('2008-11-11 11:11:11'), point(46.057226, -72.824679));
insert into Winds values (default, 2, 5, 'N');
insert into Fish values (default, 2, 'Perch', 2, null);

insert into Catch values (default, 1, 1, 1, 1, 1, date('2008-11-10 11:13:11'), point(46.057236, -72.824689));
insert into Winds values (default, 3, 2, 'W');
insert into Fish values (default, 3, 'Trout', 20, null);

insert into Trip values (default, 1, 1, 1, 1, date('2008-10-29 14:56:59'), date('2008-11-11 11:12:01'));

insert into Catch values (default, 2, null, 1, 1, 1, date('2008-11-11 11:12:01'), point(45.000000, -75.000000));
insert into Winds values (default, 4, 5, 'S');
insert into Fish values (default, 4, 'Muskellunge', 20, null);


insert into Hotspot values (default, 1, date('2008-10-29 14:56:59'), false, point(47.057236, -73.824689));

insert into Trip values (default, 1, 5, 13, 6, date('2008-10-29 14:56:59'), date('2008-10-29 20:12:01'));

insert into Catch values (default, 3, 2, 21, 1, 1, date('2008-11-11 11:13:11'), point(47.057235, -73.824687));
insert into Winds values (default, 5, 44, 'W');
insert into Fish values (default, 5, 'Salmon', 10, null);

insert into Catch values (default, 3, 2, 22, 1, 1, date('2008-11-11 11:13:11'), point(47.057235, -73.824687));
insert into Winds values (default, 6, 20, 'W');
insert into Fish values (default, 6, 'Salmon', 20, null);

insert into Catch values (default, 3, 2, 11, 1, 1, date('2008-11-11 11:13:11'), point(47.057235, -73.824687));
insert into Winds values (default, 7, 22, 'W');
insert into Fish values (default, 7, 'Salmon', 400, null);

insert into Catch values (default, 3, 2, 14, 1, 1, date('2008-11-11 11:13:11'), point(47.057235, -73.824687));
insert into Winds values (default, 8, 10, 'W');
insert into Fish values (default, 8, 'Salmon', 220, null);

insert into Trip values (default, 1, 5, 13, 6, date('2008-11-29 14:56:59'), date('2008-11-29 20:12:01'));

insert into Catch values (default, 4, 2, 21, 3, 4, date('2008-11-11 11:13:11'), point(47.057235, -73.824687));
insert into Winds values (default, 9, 100, 'O');
insert into Fish values (default, 9, 'Magikarp', 25, null);

# WIND MAP TESTS
insert into Hotspot values (default, 2, date('2008-10-29 14:56:59'), false, point(48.057236, -72.824689));

insert into Trip values (default, 2, 32, 0, 13, date('2008-10-29 14:56:59'), date('2008-11-11 11:12:01'));

insert into Catch values (default, 5, 3, 1, 1, 1, date('2008-11-11 11:12:01'), point(48.057216, -72.824669));
insert into Winds values (default, 10, 22, 'N');
insert into Fish values (default, 10, 'Salmon', 15, null);

insert into Catch values (default, 5, 3, 1, 1, 1, date('2008-11-11 11:11:11'), point(48.057226, -72.824679));
insert into Winds values (default, 11, 17, 'N');
insert into Fish values (default, 11, 'Perch', 2, null);

insert into Catch values (default, 5, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 12, 21, 'W');
insert into Fish values (default, 12, 'Trout', 20, null);

insert into Catch values (default, 5, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 13, 31, 'W');
insert into Fish values (default, 13, 'Trout', 20, null);

insert into Catch values (default, 5, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 14, 4, 'W');
insert into Fish values (default, 14, 'Trout', 20, null);


insert into Trip values (default, 2, 13, 13, 0, date('2008-11-03 14:56:59'), date('2008-11-11 11:12:01'));

insert into Catch values (default, 6, 3, 1, 1, 1, date('2008-11-11 11:12:01'), point(48.057216, -72.824669));
insert into Winds values (default, 15, 32, 'N');
insert into Fish values (default, 15, 'Salmon', 15, null);

insert into Catch values (default, 6, 3, 1, 1, 1, date('2008-11-11 11:11:11'), point(48.057226, -72.824679));
insert into Winds values (default, 16, 17, 'N');
insert into Fish values (default, 16, 'Perch', 2, null);

insert into Catch values (default, 6, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 17, 48, 'W');
insert into Fish values (default, 17, 'Trout', 20, null);

insert into Catch values (default, 6, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 18, 2, 'W');
insert into Fish values (default, 18, 'Trout', 20, null);

insert into Catch values (default, 6, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 19, 0.5, 'W');
insert into Fish values (default, 19, 'Trout', 20, null);


insert into Trip values (default, 2, 13, 13, 23, date('2008-11-05 14:56:59'), date('2008-11-11 11:12:01'));

insert into Catch values (default, 7, 3, 1, 1, 1, date('2008-11-11 11:12:01'), point(48.057216, -72.824669));
insert into Winds values (default, 20, 13.2, 'N');
insert into Fish values (default, 20, 'Salmon', 15, null);

insert into Catch values (default, 7, 3, 1, 1, 1, date('2008-11-11 11:11:11'), point(48.057226, -72.824679));
insert into Winds values (default, 21, 17, 'N');
insert into Fish values (default, 21, 'Perch', 2, null);

insert into Catch values (default, 7, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 22, 8, 'W');
insert into Fish values (default, 22, 'Trout', 20, null);

insert into Catch values (default, 7, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 23, 2, 'W');
insert into Fish values (default, 23, 'Trout', 20, null);

insert into Catch values (default, 7, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 24, 4, 'W');
insert into Fish values (default, 24, 'Trout', 20, null);


insert into Trip values (default, 2, 12, 3, 5, date('2008-11-06 14:56:59'), date('2008-11-11 11:12:01'));

insert into Catch values (default, 8, 3, 1, 1, 1, date('2008-11-11 11:12:01'), point(48.057216, -72.824669));
insert into Winds values (default, 25, 13.2, 'N');
insert into Fish values (default, 25, 'Salmon', 15, null);

insert into Catch values (default, 8, 3, 1, 1, 1, date('2008-11-11 11:11:11'), point(48.057226, -72.824679));
insert into Winds values (default, 26, 12, 'N');
insert into Fish values (default, 26, 'Perch', 2, null);

insert into Catch values (default, 8, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 27, 8, 'W');
insert into Fish values (default, 27, 'Trout', 20, null);

insert into Catch values (default, 8, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 28, 2.2, 'W');
insert into Fish values (default, 28, 'Trout', 20, null);

insert into Catch values (default, 8, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 29, 14, 'W');
insert into Fish values (default, 29, 'Trout', 20, null);


insert into Trip values (default, 2, 12, 3, 5, date('2008-11-07 14:56:59'), date('2008-11-11 11:12:01'));

insert into Catch values (default, 9, 3, 1, 1, 1, date('2008-11-11 11:12:01'), point(48.057216, -72.824669));
insert into Winds values (default, 30, 4, 'N');
insert into Fish values (default, 30, 'Salmon', 15, null);

insert into Catch values (default, 9, 3, 1, 1, 1, date('2008-11-11 11:11:11'), point(48.057226, -72.824679));
insert into Winds values (default, 31, 5.5, 'N');
insert into Fish values (default, 31, 'Perch', 2, null);

insert into Catch values (default, 9, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 32, 8, 'W');
insert into Fish values (default, 32, 'Trout', 20, null);

insert into Catch values (default, 9, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 33, 2.2, 'W');
insert into Fish values (default, 33, 'Trout', 20, null);

insert into Catch values (default, 9, 3, 1, 1, 1, date('2008-11-11 11:13:11'), point(48.057236, -72.824689));
insert into Winds values (default, 34, 22, 'W');
insert into Fish values (default, 34, 'Trout', 20, null);