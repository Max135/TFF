create database if not exists FishingDB;
use FishingDB;

drop table if exists Friend;
drop table if exists Hotspot;
drop table if exists FishPicture;
drop table if exists Picture;
drop table if exists Fish;
drop table if exists Winds;
drop table if exists Catch;
drop table if exists Trip;
drop table if exists User;

create table User (
    id int auto_increment primary key,
    email varchar(255) unique,
    password varchar(255),
    username varchar(255),
    picture blob
);

create table Trip (
    id int auto_increment primary key,
    userId int references User(id),
    bites int,
    hooks int,
    throws int,
    dateTimeStart datetime,
    dateTimeEnd datetime
);

create table Catch (
    id int auto_increment primary key,
    tripId int references Trip(id),
    hotspotId int references Hotspot(id),
    temperature double,
    barometricPressure double,
    humidity double,
    time timestamp,
    coordinates point
);

create table Winds (
    id int auto_increment primary key,
    catchId int references Catch(id),
    speed int,
    direction char
);

create table Fish (
    id int auto_increment primary key,
    catchId int references Catch(id),
    species varchar(255),
    weight double
);

create table Picture (
    id int,
    picture blob
);

create table FishPicture (
    fishId int references Fish(id),
    pictureId int references Picture(id)
);

alter table FishPicture
    add constraint pk_FishPicture primary key (fishId, pictureId);


create table Hotspot (
    id int auto_increment primary key,
    lastTimeUpdated datetime,
    isShared bool
);

create table Friend (
    userOne int references User(id),
    userTwo int references User(id)
);

alter table Friend
    add constraint pk_Friend primary key (userOne, userTwo);


# Password = Omega123
insert into User values (default, "qwe@qwe.qwe", "$2y$10$.PWFhre43nG7h88stW/.PelsIe09hwLROWIkkij4o8NgDYZtP6/c.", "qwe", null);