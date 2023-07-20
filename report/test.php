<?php

// Include autoloader 
require_once 'dompdf/autoload.inc.php'; 
 
// Reference the Dompdf namespace 
use Dompdf\Dompdf; 
 
// Instantiate and use the dompdf class 
$dompdf = new Dompdf();

$report_header = date('jS F,Y')." Attendance Report";

date_default_timezone_set("Asia/Dhaka");

$current_date = date('Y-m-d');
include_once '../inc/connection.php';

$image_path =  getcwd().DIRECTORY_SEPARATOR."vsl.png";

 $office_status = getOfficeStatus($current_date);

 if($office_status >0){
 		
 		$office_status_result="Office Open";
	}else{
		$office_status_result="Office Off";
	}

$employee_information = getEmployeeAttendanceInformation($current_date);

 $htmlData="<!DOCTYPE html>
    <html>
    <head>
    <style>
    *{
        font-size:10px;
    }
    #customers {
      font-family: 'Trebuchet MS', Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }
    
    #customers td, #customers th {
      border: 1px solid #61616b;
      padding: 8px;
    }
    
    
    
    #customers th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
     
    }
    #top_text{
        margin-top:50px;
        margin-bottom:20px;
    }
    .halim{
        
        margin-left: 200px;
        top: 350px;
    }
    #top_text td {
        font-size:12px;
        text-align:center;   
    }
    #top_text td span b{
        font-size:12px;
    }
    .col-6{
        margin-top:50px;   
        float:left;
    }
    .col-6 td{
        padding-left:80px;
    }
    .col-6 h4{
      
        margin-left:80px;
        padding-left:10px;
        margin-bottom:50px;
       
    }
    
    .signature{
        padding-top:200px;
        
    }
    .maker{
        margin-left:50px;
        float:left;
        border-top: 1px solid black;
       
    }
    .checker{
        margin-left:350px;
        float:left;
        border-top: 1px solid black;
       
    }
    .logo{
        text-align: center;
        margin-top: 50px;
        margin-bottom: 50px;
    }
    .halim-ubs{
        margin-top:-5px;
    }
    .last{
        padding-left : 150px;
        padding-right:50px;
    
    }
    .logo_content{
    	font-size:18px;
    	text-transform:uppercase;
    	font-weight:bold;
    }
    </style>
    </head>
    <body>
    
    <div class='logo'>
            
    		<h3 class='logo_content'>Venture Solution Limited</h3>
            <p style='font-size:16px; font-weigh:bold;'>{$report_header} ({$office_status_result})</p>
        </div>
    
    <table id='customers'>
      <tr>

      	  <th>SL</th>
	      <th>Employee Name</th>
	      <th>Status</th>
	      <th>In Time</th>
	       <th>Late Status</th>
	      <th>Incoming Reason</th>
		 
      </tr>
      {$employee_information}
    </table>
    
   
    </body>
    </html>
    ";

 // file name
$currentDate = date('jS_F_Y');

$filename    = $currentDate."_DAILY_OFFICE_ATTENDANCE_REPORT.pdf";

$dompdf->loadHtml($htmlData);
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');
// Render the HTML as PDF
$dompdf->render();
// Output the generated PDF to Browser
file_put_contents("{$filename}", $dompdf->output());


function getEmployeeAttendanceInformation($date){
	global $connection;
	$sql = "SELECT
   * , if(date is null, 'Absent', 'Present') as attendance_status,
   if(status = 1, 'Correct Time', 'Late') as late_status,
   if(status = 1, 'N/A', if(date is null, 'N/A',  incoming_reason)) as late_reason
FROM
   (
      SELECT
         name,
         id 
      FROM
         `users` 
      WHERE
         id not in
         (
            1,
            7,
            10,
            11,
            12,
            14,
            15,
            16,
            17,
            18,
            24,
            25,
            27,
            29,
            30,
            31,
            34,
            36,
            42,
            43,
            47,
            48,
            63,
            66,
            69,
            71
         )
   )
   u 
   left join
      (
         SELECT
            date,
            emp_id,
            in_time,
            out_time,
            status,
            incoming_reason,
            outgoing_reason 
         FROM
            attendance 
         where
            date = '$date' 
      )
      as at 
      on u.id = at.emp_id";

      $query = mysqli_query($connection, $sql);
      $response = '';
      $sl=0;

      while($data = mysqli_fetch_array($query)){
      	 $sl++;
      	$employee_name = $data['name'];
      	$in_time =  date('h:i a', strtotime($data['in_time']));
      	$employee_name = $data['name'];
      	$employee_name = $data['name'];
      	$attendance_status = $data['attendance_status'];
      	$late_status = $data['late_status'];
      	$late_reason = $data['late_reason'];
      	if($attendance_status == "Absent"){
      		$late_status = "N/A";
      		$in_time="--";
      	}
      	$response .= "<tr>
      		  <td>{$sl}</td>
		      <td>{$employee_name}</td>
		      <td>{$attendance_status}</td>
		      <td>{$in_time}</td>	      
		      <td>{$late_status}</td>
		      <td>{$late_reason}</td>
			
	      </tr>";

      }
      return $response;

}



function getOfficeStatus($date){
	global $connection;

	$office_open_sql="SELECT * FROM offce_open_off WHERE date='$date'";
	 $office_open_query = mysqli_query($connection, $office_open_sql);
	return mysqli_num_rows($office_open_query);
	
}

?>
