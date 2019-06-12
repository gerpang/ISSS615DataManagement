# ISSS615DataManagement
## Content
- Data
- DDL.sql : to create and load tables
- script.sql : for 3 specific queries 
- PHP Demo Folder 
  - `QueryPage.php` : code for front end
  - `Q*Result.php` : code for each question's query (3 files in total)
  - `sql_con.php` : code for mysqli_connect parameters and function 
  - `StyleSheet.css` : CSS for styling 
  - `Readme.md` : Instructions for use, describes some data limitations

  
## About this Project
Objective: build a Government Directory Database System 

### Phase 1 
Create an Entity-Relation diagram that captures all of the specified business rules.

### Phase 2
A. Using MySQL database, create the tables for this reporting system, load the data (inside the `./Data` file). 
B. Derive DML SQL statements to answer 3 questions
C. Develop a web reporting system (using PHP for the administrator of the Government Directory System)

## To Use
(With MYSQL Workbench editor; you can use any other editor/command line to communicate with the database server.)
1. Start WAMP/MAMP server and make sure that MySQL service is runnning
2. Launch MySQL Workbench
3. Set up a new connection 
    - Default password for Windows: empty (Port 3306)
    - Default password for Mac: root (Port 8889)
4. Connect the connection you have just created on MySQL workbench with MySQL server
5. Create a database/schema. Create and load tables using the `DDL.sql` script. 
6. Use the `script.sql` to run 3 different queries OR use `QueryPage.php` to run the web reporting system. 
    - (On Windows) PHP files have to be stored in `c:\wamp64\www\`
    - (On Mac) PHP files have to be stored in the `/Library/WebServer/Documents/localhost`. (Note: Web Sharing must be enabled.)
    - Then open your webserver and call `http://localhost/QueryPage.php` to run.


#### Reporting System Functions 
1. Staff Job History Query 

2. Department Information Query 
> Data Limitation: There are Departments without any Job positions, thus the result will return empty if that particular Department ID is selected (e.g. DeptID = 88928) 

3. Organization Chart Query 
Within a specified Department, shows who reports directly to who in a tree format.
> Data Limitation 1: For each JobID, there are instances where the JobID is not filled by a person at a certain point of time. e.g. if you query 2018-01-01, nobody is filling the job position of 92137 e so there are several missing links, causing the organization chart to be inaccurate. Search for 2018-10-01, this position is filled. 
> Data Limitation 2: Some Departments may have all of its Job positions not assigned at a particular date. thus an empty organization chart will be returned.
> Data Limitation 3: There are cases of department reorganization where the Department ID changes but the Department Name does not change. Thus there could be cases where 2 organization charts will be returned if both Departments with the same name have Job positions that are filled. 

### FAQ 
##### Error: Schema not found. How can I connect to my own database? 
Change your schema or host/password settings:
Go to `sql_con.php` and change the variable `$db`

### Credits
SMU MITB ISSS615 Data Management Project Phase 2 
By Daxaniie d/o Selvaraj, Geraldine Pang Yi Han, Tan Woo Leong Benjamin and Yew Teik Kheng from Group 03
Submitted: 16th April 2019
