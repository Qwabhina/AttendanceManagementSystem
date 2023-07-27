<?php
error_reporting(1);
include '../Includes/dbcon.php';
include '../Includes/session.php';

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="img/logo/attnlg.png" rel="icon">
    <title>Today's Report</title>
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>

<body>

    <table class=" table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Other Name</th>
                <th>Admission No</th>
                <th>Class</th>
                <th>Class Stream</th>
                <th>Session</th>
                <th>Term</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>

        <?php
        $dateTaken = date("Y-m-d");
        $filename = "Attendance List for " . $dateTaken;

        $cnt = 1;
        $ret = mysqli_query($conn, "SELECT tblattendance.Id,tblattendance.status,tblattendance.dateTimeTaken,tblclass.className,
        tblclassarms.classArmName,tblsessionterm.sessionName,tblsessionterm.termId,tblterm.termName,
        tblstudents.firstName,tblstudents.lastName,tblstudents.otherName,tblstudents.admissionNumber
        FROM tblattendance
        INNER JOIN tblclass ON tblclass.Id = tblattendance.classId
        INNER JOIN tblclassarms ON tblclassarms.Id = tblattendance.classArmId
        INNER JOIN tblsessionterm ON tblsessionterm.Id = tblattendance.sessionTermId
        INNER JOIN tblterm ON tblterm.Id = tblsessionterm.termId
        INNER JOIN tblstudents ON tblstudents.admissionNumber = tblattendance.admissionNo
        where tblattendance.dateTimeTaken = '$dateTaken' and tblattendance.classId = '$_SESSION[classId]' and tblattendance.classArmId = '$_SESSION[classArmId]'");

        if (mysqli_num_rows($ret) > 0) {
            while ($row = mysqli_fetch_array($ret)) {

                if ($row['status'] == '1') {
                    $status = "Present";
                    $colour = "#00FF00";
                } else {
                    $status = "Absent";
                    $colour = "#FF0000";
                }

                echo '  
<tr>  
<td>' . $cnt . '</td> 
<td>' . $firstName = $row['firstName'] . '</td> 
<td>' . $lastName = $row['lastName'] . '</td> 
<td>' . $otherName = $row['otherName'] . '</td> 
<td>' . $admissionNumber = $row['admissionNumber'] . '</td> 
<td>' . $className = $row['className'] . '</td> 
<td>' . $classArmName = $row['classArmName'] . '</td>	
<td>' . $sessionName = $row['sessionName'] . '</td>	 
<td>' . $termName = $row['termName'] . '</td>	
<td>' . $status = $status . '</td>	 	
<td>' . $dateTimeTaken = $row['dateTimeTaken'] . '</td>	 					
</tr>  
';
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=" . $filename . " - report.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                $cnt++;
            }
        }
        ?>
    </table>


</body>

</html>