# Data Management Demo 
18th April 2019 

## Query page: 
* Tab 1: Staff Job History 
    * `Vincent Lim`, `Teo Chee Hean`
    * `Alice Tay` for No matching staff identities found 
    * `Sintia Teddy-Ang` for __Data Limitation__: some StaffIDs exist without any corresponding JobIDs in the system 
* Tab 2: Dept Info
    * `88703` for PMO
    * `88928` for Justices of the Peace (no job positions for that Dept)
* Tab 3: Org Chart 
    * Default date is 2018-01-01 (arbitrary based on dataset, but can be set to current date to be more effective
    * `National Research Foundation` on `2018-01-01` - To see structure 
    * `Corporate Affairs Department`
    * Select any particular person to highlight, and hover to see their staff id. 
    * `PMO` on `2015-07-01` - Go back and check past records, dynamic and automatic population. If you wanted to look for a particular Minister, which of his lackeys should you contact? (Follow Chain of Command)
    * For future new joiners or changes to organization chart, no need to go to Visio or powerpoint to manually change the graphics because it can be automatically populated. 
    * navigate the bureaucracy of organizations
    * __Data Limitations__ with `PMO` on `2018-01-01` : Missing Link that is restored a few months later
* PHP Code: 
    * CSS, Java script functions
    * Header to place the gov.sg logo and header title
    * in the body, establish connections to database using another php file. `include` runs the code in that file (contain connect, if cannot connect etc). 
    * Tabs for each query function, containing the forms, queries (`select distinct dept`) for dropdown lists
    * Google Visualization API, package orgchart


## Folder Contents: 
- `QueryPage.php` : code for front end
- `Q*Result.php` : code for each question's query (3 files in total)
- `sql_con.php` : code for mysqli_connect parameters and function 
- `StyleSheet.css` : CSS for styling 
How they're used in q1-3 php files
- `Readme.md` : Instructions for use, describes some data limitations

## Q1 query + php  
1. Find StaffIDs in either column of the same_staff table where the staffid matches to a name in the staff table which is like the query name
2. DISTINCT in case there are duplicates. 
3. Store all the staffids as an array `$multresults`
4. <PHP> For each staffid (`$result`) returned, run another query for the jobs related to that staff id and return a table. For each row in that table (while a new result row is being fetched), list all the details in table format. 
5. Second query will have all staff `np/p_assignments`, join to `job` information, further INNER JOIN to another copy of `job` table based on REPORTTO column to get the information. Then choose only those with matching staffid and order by the postdate of the staff. 

## Q2 query + php 

## Q3 query + php
1. SELECT StaffName, StaffJobTitle, StaffJobID ManagerName <br> FROM department, job, staffname/details/manager subquery (__TEMP3__)

2. Table of Each Staff's assignments (both P and NP): __TEMP__
    1. as of the date which was queried (`PostDate` is on or before QueryDate AND `EndDate` is after QueryDate or NULL, i.e. Staff is still in that assignment)
    2. Why `LEFT OUTER JOIN` with job to get `ReportTo`: keep ALL in department even if no Manager (i.e. the Head of Department)
3. Left join to a similar table for Manager staff assignments: __TEMP2__ , where jobid matches the `ReportTo` column of __TEMP__
4. Only get the rows of the department being queried: __TEMP3__
* using JSON to display the PHP table(array) on the webpage as a orgchart visualization. 

Data Limitations: 
- Some Job IDs are not filled at a certain point of time. For some Depts, Job IDs are not filled at all. (Missing links, maybe even empty organization charts)
- In cases of department reorganizations where the Dept ID changes but the Dept Name does not, there may be cases where 2 organization charts will be returned if both Departments with the same name have Job positions that are filled. 

#### Note
Extra SQL files for reference: 
- `data-limitation-examples.sql` : If we have any time to show  
- `PHP-for-workbench.sql`: to test and run any part of the php query on workbench (save time no need to change the ? to variables)