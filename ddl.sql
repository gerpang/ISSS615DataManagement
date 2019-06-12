-- CREATE 
CREATE TABLE DEPARTMENT
(DeptID int not null,
Name varchar(90) not null,
URL varchar(170),
Address varchar(200), 
StartDate date not null,
EndDate date,
ParentDept int,
CONSTRAINT DEPARTMENT_pk PRIMARY KEY (DeptID),
CONSTRAINT DEPARTMENT_fk FOREIGN KEY (ParentDept) REFERENCES DEPARTMENT (DeptID));

CREATE TABLE DEPT_TRANSIT
(OrigDeptID int not null,
NewDeptID int not null,
CONSTRAINT DEPT_TRANSIT_pk PRIMARY KEY (OrigDeptID, NewDeptID),
CONSTRAINT DEPT_TRANSIT_fk1 FOREIGN KEY (OrigDeptID) REFERENCES DEPARTMENT (DeptID),
CONSTRAINT DEPT_TRANSIT_fk2 FOREIGN KEY (NewDeptID) REFERENCES DEPARTMENT (DeptID));

CREATE TABLE JOB
(JobID int not null,
Title varchar(180) not null,
Phone varchar(20),
JobLevel int not null,
DeptID int not null,
ReportTo int,
JobType char(2) not null,
CONSTRAINT JOB_pk PRIMARY KEY (JobID),
CONSTRAINT JOB_fk1 FOREIGN KEy (ReportTo) REFERENCES JOB (JobID),
CONSTRAINT JOB_fk2 FOREIGN KEY (DeptID) REFERENCES DEPARTMENT (DeptID));

CREATE TABLE P_JOB
(JobID int not null,
MinYears int not null,
CONSTRAINT P_JOB_pk PRIMARY KEY (JobID),
CONSTRAINT P_JOB_fk FOREIGN KEY (JobID) REFERENCES JOB (JobID));

CREATE TABLE NP_JOB
(JobID int not null,
CONSTRAINT NP_JOB_pk PRIMARY KEY (JobID),
CONSTRAINT NP_JOB_fk FOREIGN KEY (JobID) REFERENCES JOB (JobID));

CREATE TABLE NP_JOB_SKILLS 
(JobID int not null,
Skill varchar(100) not null,
CONSTRAINT NP_JOB_SKILLS_pk PRIMARY KEY (JobID,Skill),
CONSTRAINT NP_JOB_SKILLS_fk FOREIGN KEY (JobID) REFERENCES NP_JOB (JobID));

CREATE TABLE STAFF 
(StaffID int not null,
StaffName varchar(80) not null,
Email varchar(100) not null, 
StaffType char(2) not null,
CONSTRAINT STAFF_pk PRIMARY KEY (StaffID));

CREATE TABLE SAME_STAFF
(StaffID1 int not null,
StaffID2 int not null,
CONSTRAINT SAME_STAFF_pk PRIMARY KEY (StaffID1, StaffID2),
CONSTRAINT SAME_STAFF_fk1 FOREIGN KEY (StaffID1) REFERENCES STAFF(StaffID),
CONSTRAINT SAME_STAFF_fk2 FOREIGN KEY (StaffID2) REFERENCES STAFF(StaffID));

CREATE TABLE NP_STAFF
(StaffID int not null,
CONSTRAINT NP_STAFF_pk PRIMARY KEY (StaffID),
CONSTRAINT NP_STAFF_fk FOREIGN KEY (StaffID) REFERENCES STAFF (StaffID));

CREATE TABLE NP_STAFF_SKILLS 
(StaffID int not null,
Skill varchar(100) not null,
CONSTRAINT NP_STAFF_SKILLS_pk PRIMARY KEY (StaffID, Skill),
CONSTRAINT NP_STAFF_SKILLS_fk FOREIGN KEY (StaffID) REFERENCES NP_STAFF (StaffID));

CREATE TABLE P_STAFF
(StaffID int not null,
JoinDate date not null,
CONSTRAINT P_STAFF_pk PRIMARY KEY (StaffID),
CONSTRAINT P_STAFF_fk FOREIGN KEY (StaffID) REFERENCES STAFF (StaffID));

CREATE TABLE P_ASSIGNMENT
(StaffID int not null,
JobID int not null,
PostDate date not null,
EndDate date,
CONSTRAINT P_ASSIGNMENT_pk PRIMARY KEY (StaffID, JobID, PostDate),
CONSTRAINT P_ASSIGNMENT_fk1 FOREIGN KEY (StaffID) REFERENCES P_STAFF (StaffID),
CONSTRAINT P_ASSIGNMENT_fk2 FOREIGN KEY (JobID) REFERENCES P_JOB (JobID));

CREATE TABLE NP_ASSIGNMENT
(StaffID int not null,
JobID int not null,
PostDate date not null,
EndDate date,
CONSTRAINT NP_ASSIGNMENT_pk PRIMARY KEY (StaffID, JobID, PostDate),
CONSTRAINT NP_ASSIGNMENT_fk1 FOREIGN KEY (StaffID) REFERENCES STAFF (StaffID),
CONSTRAINT NP_ASSIGNMENT_fk2 FOREIGN KEY (JobID) REFERENCES NP_JOB (JobID));

CREATE TABLE T_EVENT
(EventName varchar(100) not null,
EventDate date not null,
EventTime time not null,
Location varchar(100),
CONSTRAINT T_EVENT_pk PRIMARY KEY (EventName, EventDate));

CREATE TABLE SPEECH
(EventName varchar(100) not null,
EventDate date not null,
OrderNum int not null,
ContentAbst varchar(250) not null,
Contact int not null,
Presenter int not null,
JobId int not null,
PostDate date not null,
CONSTRAINT SPEECH_pk PRIMARY KEY (EventName, EventDate, OrderNum),
CONSTRAINT SPEECH_fk1 FOREIGN KEY (EventName, EventDate) REFERENCES T_EVENT(EventName, EventDate),
CONSTRAINT SPEECH_fk2 FOREIGN KEY (Contact) REFERENCES NP_STAFF (StaffID),
CONSTRAINT SPEECH_fk3 FOREIGN KEY (Presenter, JobId, PostDate) REFERENCES P_ASSIGNMENT (StaffID, JobID, PostDate));


-- LOAD
-- Change path name accordingly. 
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/datadepartment.txt' INTO TABLE department FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/datadept_transit.txt' INTO TABLE dept_transit FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/datajob.txt' INTO TABLE job FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/datapolitical_job.txt' INTO TABLE p_job FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/datanon_political_job.txt' INTO TABLE np_job FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/datanon_political_job_skills.txt' INTO TABLE np_job_skills FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/datastaff.txt' INTO TABLE staff FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/datasame_staff.txt' INTO TABLE same_staff FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/datapolitical_staff.txt' INTO TABLE p_staff FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/datanon_political_staff.txt' INTO TABLE np_staff FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/datanon_political_staff_skills.txt' INTO TABLE np_staff_skills FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/datapolitical_assignment.txt' INTO TABLE p_assignment FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/datanon_political_assignment.txt' INTO TABLE np_assignment FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/dataevent.txt' INTO TABLE t_event FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
LOAD DATA LOCAL INFILE '/Users/geri/ghrepos/ISSS615DataManagement/dataspeech.txt' INTO TABLE speech FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;
