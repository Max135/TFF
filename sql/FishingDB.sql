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

drop view if exists CatchAlone;

create table User (
    id int auto_increment primary key,
    email varchar(255) unique,
    password varchar(255),
    username varchar(255),
    picture blob
);

create table Trip (
    id int auto_increment primary key,
    userId int,
    bites int,
    hooks int,
    throws int,
    dateTimeStart datetime,
    dateTimeEnd datetime
);

alter table Trip
    add constraint fk_trip_user foreign key (userId) references User(id);

create table Catch (
    id int auto_increment primary key,
    tripId int,
    hotspotId int,
    temperature double,
    barometricPressure double,
    humidity double,
    time timestamp,
    coordinates point
);

alter table Catch
    add constraint fk_catch_trip foreign key (tripId) references Trip(id);

alter table Catch
    add constraint fk_catch_hotspot foreign key (hotspotId) references Hotspot(id);

create table Winds (
    id int auto_increment primary key,
    catchId int,
    speed int,
    direction char
);

alter table Winds
    add constraint fk_winds_catch foreign key (catchId) references Catch(id);

create table Fish (
    id int auto_increment primary key,
    catchId int,
    species varchar(255),
    weight double
);

alter table Fish
    add constraint fk_fish_catch foreign key (catchId) references Catch(id);

create table Picture (
    id int,
    path varchar(255)
);

create table FishPicture (
    fishId int,
    pictureId int
);

alter table FishPicture
    add constraint pk_FishPicture primary key (fishId, pictureId);

alter table FishPicture
    add constraint fk_fish_picture_fish foreign key (fishId) references Fish(id);

alter table FishPicture
    add constraint fk_fish_picture_picture foreign key (pictureId) references Picture(id);


create table Hotspot (
    id int auto_increment primary key,
    userId int,
    lastTimeUpdated datetime,
    isShared bool,
    coordinates point
);

alter table Trip
    add constraint fk_hotspot_user foreign key (userId) references User(id);

create table Friend (
    userOne int references User(id),
    userTwo int references User(id)
);

alter table Friend
    add constraint pk_Friend primary key (userOne, userTwo);

alter table Friend
    add constraint fk_friend_user_one foreign key (userOne) references User(id);

alter table Friend
    add constraint fk_friend_user_two foreign key (userTwo) references User(id);

create view CatchAlone as select C.id, X(C.coordinates) as lat, Y(C.coordinates) as lon from Catch C left outer join Hotspot H on H.id = C.hotspotId where C.hotspotId IS NULL;

