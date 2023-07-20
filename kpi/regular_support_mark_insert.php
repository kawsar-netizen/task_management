<?php error_reporting(0);
session_start();
include_once '../inc/connection.php';
include_once '../inc/head.php';
 $userid =$_SESSION['user_id'];

  $month_and_year = $_GET['month_and_year'];

   $montOfTheYear = date('m', strtotime($month_and_year));
   $year = date('Y', strtotime($month_and_year));
  
      $select_if_exist ="SELECT * FROM `regular_support_marks` where year_and_month = '$month_and_year' ";

                        $select_q = mysqli_query($connection, $select_if_exist);

                        if (mysqli_num_rows($select_q) > 0) {

                             $_SESSION['result'] ='
                                <div class="alert alert-danger fade in alert-dismissible" >
                                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close"></a>
                                <strong>Data Already Exist !</strong> 
                                </div>
                                ';

                                echo "<script>window.location.href='regular_support_marks.php';</script>";
                                exit();
                        }


    
                        $user_sql = "SELECT id, name FROM users where user_status='1' and id not in (1,28,30)";
                                        
        
                $user_sql_q = mysqli_query($connection, $user_sql);

                $sl =0;
                while ($user_sql_f = mysqli_fetch_array($user_sql_q)) {
                        $sl++;


                $user_id = $user_sql_f['id'];
               $name = $user_sql_f['name'];
               
               $select_sql = "
                                 SELECT 
count(m.status) as total,
(select count(p.status) from developer_tasks p where p.status = 1 and find_in_set($user_id, p.team_member) and  month(p.delivery_date)='$montOfTheYear' and year(p.delivery_date)='$year' ) as progress,

(select count(c.status) from developer_tasks c where c.status = 2 and find_in_set($user_id, c.team_member) and  month(c.delivery_date)='$montOfTheYear' and year(c.delivery_date)='$year' ) as complete
FROM developer_tasks m  where   find_in_set($user_id, m.team_member) and month(m.delivery_date)='$montOfTheYear' and year(m.delivery_date)='$year'";

$select_q = mysqli_query($connection, $select_sql);
$select_f = mysqli_fetch_array($select_q);



   $complete_task = $select_f['complete'];

   // $late_completed_status = $select_f['late_completed_status'];

    //$late_completed_status_marks = $late_completed_status * 5;

    $total_task = $select_f['total'];

    file_put_contents('tf.txt',$total_task.'\n',FILE_APPEND);

    $marks = ((20 * $complete_task) / $total_task);

   
    if ($total_task == '0') {
       $final_marks =0;

    }else{
          $final_marks = round($marks);
    }



             $inser_sql = "INSERT INTO `regular_support_marks` SET `emp_id` = '$user_id',`year_and_month`='$month_and_year', `total_task`='$total_task', `completed_task` = '$complete_task',
             `marks` = '$final_marks', 
              `entry_usr` = '$userid' ";

            $insert_q = mysqli_query($connection, $inser_sql);
             

     }

       if ($insert_q == 1) {

                  $_SESSION['result'] ='
                <div class="alert alert-success fade in alert-dismissible" >
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close"></a>
                <strong>Regular Support Marks Inserted Successfully.</strong> 
                </div>
                ';

                echo "<script>window.location.href='regular_support_marks.php';</script>";
                exit();

            }

?>

