<html>
<head>
<!--Metadata to parse the HTML document-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--Link to css style sheet-->
<link href="StyleSheet.css" rel="stylesheet" type="text/css"> 

<script>
function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}
</script>

</head>
<div class = 'header'>
  <div class = "image"><img src="http://www.gov.sg/~/media/gov/home/icons/gov-wide.png" 
    alt="gov.sg"
    style="width:206px;height:135px;"></div>
    <div class = "text"><h2>Database Reporting System</h2></div>
</div>

<body>

<?php
  // estabalish SQL Connections to database
  include "sql_con.php";
?>
<p><i>Click on the button tabs for different functions:</i></p>

<div class="tab">
  <button class="tablinks" onclick="openTab(event, 'Staff Job History')">Staff Job History</button>
  <button class="tablinks" onclick="openTab(event, 'Department Information')">Department Information</button>
  <button class="tablinks" onclick="openTab(event, 'Organization Chart')">Organization Chart</button>
</div>

<div id="Staff Job History" class="tabcontent">
  <h3>Staff Job History</h3>
  <form method="post" action="Q1Result.php">
        Enter staff name:
        <br>
        <input type="text" name="searchname"/><br><br>
        <input type="submit" value=" Search "/>
    </form>
</div>

<div id="Department Information" class="tabcontent">
  <h3>Department Information</h3>
  <form method="post" action="Q2Result.php">
        Select a Department: <br>
        <select name="type">
        <option value="" selected>-- Select One --</option>
        <?php
            $query = "select distinct deptid from department order by deptid asc";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $type_r);
            while (mysqli_stmt_fetch($stmt)) {
                print "<option value=".$type_r.">".$type_r."</option>";
            }
        ?>
        </select><p></p>
        <input type="submit" value=" Search " />
    </form>
</div>

<div id="Organization Chart" class="tabcontent">
  <h3>Organization Chart</h3>
  <form method="post" action="Q3Result.php">

        Select a Department: 
        <select name="department">
        <option value="" selected>-- Select One --</option>
        
        <?php
            $query = 'select distinct name from department order by name';
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $name_r);
            while (mysqli_stmt_fetch($stmt)){
            print "<option> $name_r </option>";
            }
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        ?>
        </select><p></p>
         
        Input Date in 'YYYY-MM-DD' Format: <input type="text" name="date" value="2018-01-01" />
        
        <br/><br/> 
        <input type="submit" value=" Generate " />
        
    </form>
</div>  
</body>
</html> 
