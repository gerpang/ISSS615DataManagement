-- SQL Statements to DML questions 

-- Q1: 
SET @searchDeptID = '88703'; 
SET @searchDeptName ='National Research Foundation';

select j2.jobid, j2.title, j2.jobtype, j2.deptid, d.name, count(p.staffid) + count(np.staffid) as NoAssignments
from 
(select j.jobid, j.title, j.jobtype, d.name, d.deptid,  d.parentdept  # Remove d.name and d.parentdept later (for checking)
from job j left join department d 
on j.deptid = d.deptid 
right join 
	(select deptid
	from department 
	where name = @searchDeptName
    ) temp				# Using Dept Name 
on d.deptid = temp.deptid or d.parentdept = temp.deptid 	 						
) as j2
left join p_assignment p on j2.jobid = p.jobid 
left join np_assignment np on j2.jobid = np.jobid
left join department d on d.deptid = j2.deptid
group by p.jobid, np.jobid
order by NoAssignments desc, jobid asc
;

-- Q2:
SET @queryDate = '2015-07-01';

select npa.staffid, s.staffname, npa.jobid, npa.postdate, npa.enddate, d.name, ContactCount
from np_staff nps 						# ALL non-political staff 
left join np_assignment npa on npa.staffid = nps.staffid			
left join ( select staffid, count(sp.ordernum) as ContactCount
			from np_assignment npa left join speech sp on npa.staffid = sp.contact 
			group by npa.staffid) temp 
			on npa.staffid = temp.staffid
# think these 3 left joins can be combined into 1 subquery to get the staffname, department name 
left join staff s on s.staffid = npa.staffid 
left join job j on j.jobid = npa.jobid
left join department d on d.deptid = j.deptid
where npa.postdate <= @queryDate 		# as of a given date (postdate <= @queryDate)
order by npa.postdate desc, nps.staffid asc, contactcount desc
;

-- Q3:
select 	main.*, t.type as "Type" from
(
	select dt.newdeptid, # as "New Dept ID", 
			d2.name as "New Dept Name", 
			dt.origdeptid as "Orig. Dept ID", 
			d1.name as "Orig. Dept Name", 
			d2.StartDate as "Reorganized Date"
	from dept_transit dt, department d1, department d2 
	where dt.OrigDeptID = d1.deptid 
	and dt.NewDeptID = d2.deptid
) main
left join 
(
	select distinct dt1.newdeptid, 
					dt1.origdeptid, #newCount, prevCount,
			IF(newCount >1,"split",		# if newCount > 1					-> Split  		
				IF(prevCount >1 ,"merger",	# if newCount = 1 and prevCount >1 	-> Merger
				"renamed")) as type			# if newCount = 1 and prevCount = 1 -> Renamed 
	from 
	(	# Count of original departments for each new department -> identify Merger & Renamed
		select newdeptid, origdeptid, count(dt.origdeptid) as prevCount
		from dept_transit dt
		group by dt.newdeptid
	) dt1 
	inner join 
	(	# Count of new departments with same original dept -> to identify SPLITS
		select newdeptid, origdeptid, count(distinct dt.newdeptid) as newCount 
		from dept_transit dt
		group by dt.origdeptid
	) dt2 
    on dt1.origdeptid = dt2.origdeptid
) t 
on main.newdeptid =  t.newdeptid
order by t.type asc, main.newdeptid asc
;
