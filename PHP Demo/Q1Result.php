<html>
<header><title>Staff Job History Result</title></header>
<body>
<br>
<?php 
include "sql_con.php";
$staffname = $_POST["searchname"];
print("<h2>Job Details of: \"$staffname\"</h2>");

$pQuery = "SELECT distinct staffid, staffname, email FROM staff 
            WHERE staffid IN 
            (SELECT staffid1 FROM same_staff 
                WHERE staffid1 IN
                (SELECT staffid FROM staff WHERE staffname LIKE '%$staffname%')
                OR staffid2 IN 
                (SELECT staffid FROM staff WHERE staffname LIKE '%$staffname%')
            );";

$stmt = mysqli_prepare($conn, $pQuery); 

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result($stmt, $staffid_r, 
                        $staffname_r, $email_r);

$multResults = array(); //Store staffid's from the first query 
    // first query table
mysqli_stmt_store_result($stmt);


if(mysqli_stmt_num_rows($stmt)>0) {
    print("<table border = 1 >");
    print("<tr><td>Staff ID</td><td>Staff Name</td><td>Email</td></tr>");
    while (mysqli_stmt_fetch($stmt)) {
        $multResults[] = $staffid_r;
        print("<tr><td>$staffid_r</td><td>$staffname_r</td><td>$email_r</td></tr>");
    }
    print("</table>");
    print("<br>");
        // second query table
    print("<h3>Detailed Job History</h3>");
    foreach ($multResults as $result){
        $rQuery = " SELECT j.jobid, j.Title, j.JobLevel, j.JobType, 
                temp2.PostDate, temp2.EndDate, j.ReportTo, j2.Title -- Job title of reporting officer
            from job j inner join 
            (   select * from 
                (select * from np_assignment union all select * from p_assignment) temp
            ) temp2 on j.jobid = temp2.jobid
            inner join job j2 on j.reportto = j2.jobid
            where staffid = ".$result."
            order by temp2.PostDate desc; ";
        $rstmt = mysqli_prepare($conn,$rQuery);
        mysqli_stmt_execute($rstmt);
        mysqli_stmt_bind_result($rstmt, $jobid_r, $jobtitle_r, $joblevel_r, $jobtype_r,
                            $postdate_r, $enddate_r, $reportto_r,$reporttitle_r);
        print("<table border = 1 ><tr><td>Staff ID</td><td>".$result."</td></tr></table>");
        print("<table border = 1 >");
        print("<tr><td>Job ID</td><td>Job Title</td><td>Job Level</td>
                        <td>Job Type</td><td>Post Date</td><td>End Date</td>
                        <td>Report To</td><td>Job Title</td></tr>");
        while (mysqli_stmt_fetch($rstmt)) {
                print("<tr><td>$jobid_r</td><td>$jobtitle_r</td><td>$joblevel_r</td>
                            <td>$jobtype_r</td><td>$postdate_r</td><td>$enddate_r</td>
                            <td>$reportto_r</td><td>$reporttitle_r</td></tr>");
        }
        print("</table>");
        print("<br>");
    }
} else {
    echo "<h4>No Matching Staff Identities Found<h4>";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

</body>
</html>