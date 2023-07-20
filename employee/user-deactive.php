<?php error_reporting(1);
session_start();
include_once '../inc/connection.php';

$employee_role=$_SESSION['employee_role'];
//get  total  bill amount  from  bill  table
 $user_id = $_POST['user_id'];
if (!empty($user_id)) {
   $update_sql="UPDATE `users` SET user_status='0' WHERE id='$user_id' ";
   $update_q = mysqli_query($connection, $update_sql);

   if ($update_q) {
       echo '1';
   }else{
    echo '0';
   }
}

?>
