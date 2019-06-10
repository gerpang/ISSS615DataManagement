<html>
    <head>
        <?php
    
            include "sql_con.php";

            $sqlQuery = "
                Select StaffName, ManagerName, j.Title as StaffJobTitle, s.StaffID
                From ((department d, job j,

                (Select * From p_assignment 
                Where PostDate <= ? and (EndDate > ? or EndDate is null) 
                union
                Select * From np_assignment
                Where PostDate <= ? and (EndDate > ? or EndDate is null) ) as temp
                ) 

                inner join

                staff s on s.StaffID = temp.StaffID

                left outer join job j2 on j.ReportTo = j2.JobID
                )
                left outer join 

                (
                    Select s.StaffName as ManagerName, temp2.JobID as ManagerJobID, temp2.StaffID as ManagerStaffID
                    From
                    (Select * From p_assignment 
                    Where PostDate <= ? and (EndDate > ? or EndDate is null) 
                    union
                    Select * From np_assignment
                    Where PostDate <= ? and (EndDate > ? or EndDate is null) ) as temp2
	
                inner join staff s on s.StaffID = temp2.StaffID
                inner join job j3 on temp2.JobID = j3.JobID
                inner join department d2 on j3.DeptID = d2.DeptID
                Where d2.Name = ?
    
                ) as temp3

                on j.ReportTo = temp3.ManagerJobID

                Where 
                    d.Name = ?  
                    and d.DeptID = j.DeptID 
                    and j.JobId = temp.JobID
                ";

            $stmt = mysqli_prepare($conn, $sqlQuery);

            mysqli_stmt_bind_param($stmt, 'ssssssssss', 
                                    $Date, 
                                    $Date, 
                                    $Date,
                                    $Date, 
                                    $Date, 
                                    $Date,  
                                    $Date, 
                                    $Date, 
                                    $Department, 
                                    $Department );

            $Date = $_POST["date"];
            
            $Department = $_POST['department'];

            mysqli_stmt_execute($stmt); 

            mysqli_stmt_bind_result($stmt, $StaffName_r, $ManagerName_r, $StaffJobTitle, $StaffID);

            echo "<center><h2>Organization Chart for $Department</h2></center>";
            echo "<center><h3>As of $Date</h3></center><br>";

            $flag = true;
            $table = array();
            $table['cols'] = array(
                array('label' => 'StaffName', 'type' => 'string'),
                array('label' => 'ManagerName', 'type' => 'string'),
                array('label' => 'StaffID', 'type' => 'string', 'role' => 'tooltip','trigger'=>'selection' ),

            );

            $table['rows'] = array();

            
            while (mysqli_stmt_fetch($stmt)){
                $temp = array();
                $temp[] = array('v' =>  $StaffName_r, 'f' => "<h3>$StaffName_r</h4><p> <h5>{$StaffJobTitle} </h4></p>");

                $temp[] = array('v' => $ManagerName_r);
                $temp[] = array('v' => $StaffID);

                $table['rows'][] = array('c' => $temp);
            }

            mysqli_stmt_close($stmt);

            mysqli_close($conn);


            $jsonTable = json_encode($table);
        ?>

        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <title>
        Org Chart
        </title>
        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <script type="text/javascript">
            function drawVisualization() {
                // Create and populate the data table.
                var data = new google.visualization.DataTable(<?php echo $jsonTable; ?>);

                // Create and draw the visualization.
                var table = new google.visualization.OrgChart(document.getElementById('visualization'));
                table.draw(data, {allowHtml:true});

            }
            
            // On Load, call function drawVisualization defined above
            google.setOnLoadCallback(drawVisualization);
            google.load('visualization', '1', {packages: ['orgchart']});

            // Listener: for when a particular staff is selected
            google.visualization.events.addListener(table,'select', function() {
                alert('selected'); 
            });
            


        </script>
    </head>
    <body style="font-family: Arial;border: ;#000000">
        <div id="visualization"></div>
    </body>
</html>