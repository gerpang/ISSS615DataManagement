<html>
	<body>	
		<?php
			include "sql_con.php";
			$dept = $_POST["type"];
			$query1 = "select temp.deptid, temp.name, temp.numPositions, j2.jobid, j2.title from
					(select d.deptid, name, count(distinct j.jobid) as numPositions, max(j.joblevel) as senior
					from department d inner join job j
					on d.deptid = j.deptid
					group by d.deptid, name
					having d.deptid=?) temp
					left join job j2 on temp.senior = j2.joblevel and temp.deptid = j2.deptid;";
			$stmt1 = mysqli_prepare($conn, $query1);
			mysqli_stmt_bind_param($stmt1, 's', $dept);
			mysqli_stmt_execute($stmt1);
			mysqli_stmt_bind_result($stmt1, $deptidR, $deptName, $numPositionsR, $jobidR, $jobTitle); 
			mysqli_stmt_store_result($stmt1);

			if(mysqli_stmt_num_rows($stmt1)>0) {
				print "<table border=1 cellpadding='5'>";
				print "<tr>";
				print "<th>StaffId</th>";
				print "<th>StaffName</th>";
				print "<th>Staff Type</th>";
				print "<th>Post Date</th>";
				print "<th>End Date</th>";
				print "</tr>";

				while (mysqli_stmt_fetch($stmt1)) {
				print "QUERY:".$dept."</br>";
				print "Department ID: ".$deptidR."</br>";
				print "Department Name: ".$deptName."</br>";
				print "Number of Job Positions Available: ".$numPositionsR."</br>";

				print "</br>";

				print "<b>Most senior job position:</b></br>";
				print "Job ID ".$jobidR."</br>";
				print "Job Title: ".$jobTitle."</br>";
				}

				mysqli_stmt_close($stmt1);

				$query2 = "select s.staffid, s.staffname, 
						s.stafftype, temp2.postdate, temp2.enddate from
						(select d.deptid, name, count(distinct j.jobid) as numPositions, max(j.joblevel) as senior
						from department d inner join job j
						on d.deptid = j.deptid
						group by d.deptid, name
						having d.deptid=?) temp
						left join job j2 on temp.senior = j2.joblevel and temp.deptid = j2.deptid
						inner join
						(select * from p_assignment
						union all 
						select * from np_assignment) temp2
						on j2.jobid = temp2.jobid
						inner join staff s on temp2.staffid = s.staffid
						order by temp2.postdate desc;";
				$stmt2 = mysqli_prepare($conn, $query2);
				mysqli_stmt_bind_param($stmt2, 's', $dept);

				mysqli_stmt_execute($stmt2);
				mysqli_stmt_bind_result($stmt2, $staffidR,
				$staffnameR, $stafftypeR, $postdateR, $enddateR);

				while (mysqli_stmt_fetch($stmt2)) {
					print "<tr>";
					print "<td>";
					print $staffidR;
					print "</td>";
					print "<td>";
					print $staffnameR;
					print "</td>";
					if ($stafftypeR=="NP") {
						$stafftypeR="Non-Political";
					} elseif ($stafftypeR=="P") {
						$stafftypeR="Political";
					}
					print "<td>";
					print $stafftypeR;
					print "</td>";
					print "<td>";
					print $postdateR;
					print "</td>";
					print "<td>";
					print $enddateR;
					print "</td>";
					print "</tr>";
				}
				mysqli_stmt_close($stmt2);
				mysqli_close($conn);
				print "</table>";
				} else {
					echo "<h4>No Job Positions Found for Department ID $dept<h4>";
				}
				
			?>
	</body>
</html>