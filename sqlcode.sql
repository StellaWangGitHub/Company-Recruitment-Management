drop table Screentest;
drop table Interview;
drop table Offer;
drop table Apply;
drop table Job_Fulltime;
drop table Job_Parttime;
drop table Job;
drop table Recruiter;
drop table Applicant;
drop table HiringManager;
drop table HumanResources;

create table HumanResources (
HRid integer primary key,
Name char(10) NOT NULL);

insert into HumanResources values (001, 'HR1');
insert into HumanResources values (002, 'HR2');
insert into HumanResources values (003, 'HR3');
insert into HumanResources values (004, 'HR4');
insert into HumanResources values (005, 'HR5');


create table HiringManager (
HMid integer primary key,
Name char(10) NOT NULL,
Department char(10) NOT NULL);

insert into HiringManager values (101, 'HM1', 'D1');
insert into HiringManager values (102, 'HM2', 'D2');
insert into HiringManager values (103, 'HM3', 'D1');
insert into HiringManager values (104, 'HM4', 'D2');
insert into HiringManager values (105, 'HM5', 'D3');


create table Applicant (
Aid integer primary key, 
Name char(10) NOT NULL, 
Phone char(10) UNIQUE, 
Address char(10) NOT NULL);

insert into Applicant values (301, 'A1', '1234567890', 'Ad1');
insert into Applicant values (302, 'A2', '1234567891', 'Ad2');
insert into Applicant values (303, 'A3', '1234567892', 'Ad1');
insert into Applicant values (304, 'A4', '1234567893', 'Ad3');
insert into Applicant values (305, 'A5', '1234567894', 'Ad4');
insert into Applicant values (306, 'AA', '1234567896', 'Ad4');


create table Recruiter (
Rid integer primary key, 
Name char(10) not null); 

insert into Recruiter values (401, 'R1');
insert into Recruiter values (402, 'R2');
insert into Recruiter values (403, 'R3');
insert into Recruiter values (404, 'R4');
insert into Recruiter values (405, 'R5');


create table Job (
Jid integer primary key,
Rid integer,
Title char(10), 
Description char(10),
Deadline date NOT NULL,
FOREIGN KEY (Rid) references Recruiter (Rid) ON DELETE CASCADE);

insert into Job values (501, 401, 'J1', 'D1', to_date('2019-06-30', 'yyyy-mm-dd'));
insert into Job values (502, 401, 'J2', 'D2', to_date('2019-05-30', 'yyyy-mm-dd'));
insert into Job values (503, 402, 'J3', 'D3', to_date('2019-06-30', 'yyyy-mm-dd'));
insert into Job values (504, 401, 'J4', 'D1', to_date('2019-04-30', 'yyyy-mm-dd'));
insert into Job values (505, 403, 'J5', 'D4', to_date('2019-05-10', 'yyyy-mm-dd'));
insert into Job values (506, 402, 'J6', 'D6', to_date('2019-05-15', 'yyyy-mm-dd'));
insert into Job values (507, 404, 'J7', 'D7', to_date('2019-04-10', 'yyyy-mm-dd'));
insert into Job values (508, 401, 'J8', 'D8', to_date('2019-03-28', 'yyyy-mm-dd'));
insert into Job values (509, 402, 'J9', 'D5', to_date('2019-04-04', 'yyyy-mm-dd'));
insert into Job values (510, 403, 'J10', 'D4', to_date('2019-06-15', 'yyyy-mm-dd'));


create table Job_Parttime ( 
Jid integer, 
Hours integer, 
PRIMARY KEY (Jid),
FOREIGN KEY (Jid) references job (Jid) ON DELETE CASCADE);

insert into Job_Parttime values (501, 20);
insert into Job_Parttime values (502, 16);
insert into Job_Parttime values (503, 25);
insert into Job_Parttime values (504, 12);
insert into Job_Parttime values (505, 12);


create table Job_Fulltime (
Jid integer primary key, 
Benefits char(10),
FOREIGN KEY (Jid) references job (Jid) ON DELETE CASCADE);

insert into Job_Fulltime values (506, 'B1');
insert into Job_Fulltime values (507, 'B2');
insert into Job_Fulltime values (508, 'B1');
insert into Job_Fulltime values (509, 'B3');
insert into Job_Fulltime values (510, 'B1');


create table Apply ( 
Apid integer primary key,
Aid integer, 
Jid integer,
FOREIGN KEY (Aid) references applicant (Aid) ON DELETE CASCADE, 
FOREIGN KEY (Jid) references job (Jid) ON DELETE CASCADE); 

insert into Apply values (801, 301, 501);
insert into Apply values (802, 301, 502);
insert into Apply values (803, 302, 501);
insert into Apply values (804, 303, 501);
insert into Apply values (805, 301, 503);


create table Offer (
Offerid integer primary key, 
Apid integer UNIQUE NOT NULL, 
OfferDate date NOT NULL,
Salary integer NOT NULL,
Status char(10), 
FOREIGN KEY (Apid) REFERENCES Apply (Apid) ON DELETE CASCADE); 

insert into Offer values (201, 801, to_date('2019-06-10', 'yyyy-mm-dd'), 6000, 'proposed');
insert into Offer values (202, 802, to_date('2019-06-25', 'yyyy-mm-dd'), 2500, 'accepted');
insert into Offer values (203, 803, to_date('2019-06-30', 'yyyy-mm-dd'), 5400, 'declined');
insert into Offer values (204, 804, to_date('2019-06-11', 'yyyy-mm-dd'), 3200, 'proposed');
insert into Offer values (205, 805, to_date('2019-06-04', 'yyyy-mm-dd'), 4500, 'accepted');


-- create table Presents (
-- HRid Integer,
-- HMid Integer,
-- Offerid Integer,
-- PRIMARY KEY (HRid, HMid, Offerid),
-- FOREIGN KEY (HRid) REFERENCES HumanResources (HRid) ON DELETE CASCADE,
-- FOREIGN KEY (Offerid) REFERENCES Offer (Offerid) ON DELETE CASCADE,
-- FOREIGN KEY (HMid) REFERENCES HiringManager (HMid) ON DELETE CASCADE);

-- insert into Presents values (001, 101, 201);
-- insert into Presents values (002, 104, 202);
-- insert into Presents values (003, 102, 203);
-- insert into Presents values (002, 103, 204);
-- insert into Presents values (003, 101, 205);


create table Interview (
Iid integer primary key,
Location char(10),
Interview_Time date,
HMid integer NOT NULL, 
Apid integer UNIQUE NOT NULL,
Status char(10),
FOREIGN KEY (Apid) references Apply (Apid) ON DELETE CASCADE, 
FOREIGN KEY (HMid) references HiringManager (HMid) ON DELETE CASCADE);

insert into Interview values (701, 'L1', to_date('2019-05-01','yyyy-mm-dd'), 101, 801, 'scheduled');
insert into Interview values (702, 'L2', to_date('2019-05-13','yyyy-mm-dd'), 102, 802, 'scheduled');
insert into Interview values (703, 'L2', to_date('2019-04-15','yyyy-mm-dd'), 101, 803, 'scheduled');
insert into Interview values (704, 'L1', to_date('2019-05-22','yyyy-mm-dd'), 101, 804, 'attended');
insert into Interview values (705, 'L3', to_date('2019-05-09','yyyy-mm-dd'), 103, 805, 'attended');


-- create table Attend_interview (
-- HMid integer, 
-- Aid integer, 
-- Location char(10),
-- Interview_Time date,
-- PRIMARY KEY (location, Interview_time), 
-- FOREIGN KEY (Aid) REFERENCES Interview (Aid) ON DELETE SET NULL ON UPDATE CASCADE,
-- FOREIGN KEY (HMid) REFERENCES Interview (HMid) ON DELETE SET NULL ON UPDATE CASCADE, 
-- FOREIGN KEY (location) REFERENCES Interview (location) ON DELETE SET NULL ON UPDATE CASCADE), 
-- FOREIGN KEY (Interview_Time) REFERENCES Interview (Interview_Time) ON DELETE SET NULL ON UPDATE CASCADE);

-- insert into Attend_interview values (101, 301, 'L1', to_date('2019-05-01', 'yyyy-mm-dd'));
-- insert into Attend_interview values (102, 302, 'L2', to_date('2019-02-13', 'yyyy-mm-dd'));
-- insert into Attend_interview values (101, 303, 'L2', to_date('2019-03-15', 'yyyy-mm-dd'));
-- insert into Attend_interview values (101, 304, 'L1', to_date('2019-01-22', 'yyyy-mm-dd'));
-- insert into Attend_interview values (103, 301, 'L3', to_date('2018-12-09', 'yyyy-mm-dd'));

-- create table Post (
-- Jid integer,
-- Rid integer,
-- PRIMARY KEY (Jid, Rid),
-- FOREIGN KEY (Jid) references Job (Jid) ON DELETE CASCADE,
-- FOREIGN KEY (Rid) references Recruiter (Rid) ON DELETE SET NULL);

-- insert into Post values (501, 401);
-- insert into Post values (502, 402);
-- insert into Post values (503, 402);
-- insert into Post values (504, 401);
-- insert into Post values (505, 403);


create table Screentest (
Sid integer, 
Apid integer UNIQUE NOT NULL, 
status char(10),
PRIMARY KEY (Sid),
Foreign key (Apid) REFERENCES Apply (Apid) ON DELETE CASCADE);

insert into Screentest values (601, 801, 'sent');
insert into Screentest values (602, 802, 'sent');
insert into Screentest values (603, 803, 'sent');
insert into Screentest values (604, 804, 'completed');
insert into Screentest values (605, 805, 'completed');
