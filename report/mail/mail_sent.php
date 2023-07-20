<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require "vendor/autoload.php";

$currentDate = date('jS_F_Y');
$filename    = $currentDate."_DAILY_OFFICE_ATTENDANCE_REPORT.pdf";

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = "smtp";

$mail->SMTPDebug  = 1;  
$mail->SMTPAuth   = TRUE;
$mail->SMTPSecure = "tls";
$mail->Port       = 587;
$mail->Host       = "smtp.gmail.com";
$mail->Username   = "halimkhanfeni7@gmail.com";
$mail->Password   = "halimkhanfeni7";

$mail->IsHTML(true);
$mail->AddAddress("faisal@venturenxt.com", "Faisal Vaia");
$mail->SetFrom("halimkhanfeni7@gmail.com", "Halim Hasan");
$mail->Subject = "Daily Attendance (Venture Solution Limited)";
$content = "<b>This is a Test Email sent via Gmail SMTP Server using PHP mailer class.</b>";
$mail->AddAttachment("../$filename");


$mail->MsgHTML($content); 
if(!$mail->Send()) {
  echo "Error while sending Email.";
  var_dump($mail);
} else {
  echo "Email sent successfully";
}