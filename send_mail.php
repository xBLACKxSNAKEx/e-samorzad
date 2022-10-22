<?php
  //Import PHPMailer classes into the global namespace
  //These must be at the top of your script, not inside a function
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\SMTP;
  use PHPMailer\PHPMailer\Exception;
  
  require "PHPMailer-master/src/Exception.php";
  require "PHPMailer-master/src/PHPMailer.php";
  require "PHPMailer-master/src/SMTP.php";
  
  if((isset($_POST['text-editor']) || isset($_FILES["file-input-is-word"])) && isset($_POST['receiver-email']) && isset($_POST['sender-email']))
  {
    session_start();
    
    require "credentials.php";
    
    if (isset($_FILES["file-input-is-word"])) {
      $tmp_dir = dirname($_FILES['file-input-is-word']["tmp_name"], 1);
      $target_dir = "uploades/";
      $original_file = $target_dir . basename($_FILES['file-input-is-word']["name"]);
      $imageFileType = strtolower(pathinfo($original_file, PATHINFO_EXTENSION));
      $target_file = basename($_FILES['file-input-is-word']["tmp_name"], ".tmp") . "." . $imageFileType;
      $uploadOk = 1;

      if(!file_exists($target_dir) && !is_dir($target_dir))
        mkdir($target_dir);
      
      // Allow certain file formats
      if ($imageFileType != "doc" && $imageFileType != "docx" && $imageFileType != "pdf") {
        // not good file type
        $uploadOk = 0;
      }
      
      // Check if $uploadOk is set to 0 by an error
      if ($uploadOk == 0) {
        // TODO: error uploading file
      } else {
        if (!move_uploaded_file($_FILES['file-input-is-word']["tmp_name"], $target_dir.$target_file)) {
          $uploadOk = 0;
        }
      }
      
      $target_file;
    }
    
    try
    {
      $sql = new mysqli($db_ip, $db_user, $db_password, $db_name);
      
      if ($sql->errno > 0)
        goto send_mail;
      
      $receiver_email = $_POST['receiver-email'];
      
      $result = $sql->query("SELECT name FROM authorities WHERE `mail`=$receiver_email");
      if ($result->num_rows == 0)
        goto send_mail;
      
      $receiver_name = ($result->fetch_assoc())['name'];
      
      
      send_mail:
        $sql->close();
    }
    catch(Exception $e)
    {
      
    }
    
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    
    try {
      //Server settings
      $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
      $mail->isSMTP();                                            //Send using SMTP
      $mail->Host       = $smtp_server;                            //Set the SMTP server to send through
      $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
      $mail->Username   = $smtp_user;          //SMTP username
      $mail->Password   = $smtp_password;                         //SMTP password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
      $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
      
      $mail->Sender = 'informacja@esamorzad.ct8.pl';
      $mail->setFrom($_POST['sender-email'], '', false);
      
      //Recipients
      if(isset($receiver_name))
        $mail->addAddress($_POST['receiver-email'], $receiver_name);
      else
        $mail->addAddress($_POST['receiver-email']);
      
      //Attachments
      if(isset($uploadOk) && $uploadOk == 1)
      {
        $mail->addAttachment($target_dir.$target_file, $_FILES['panel-file-input']["name"]);         //Add attachments
      }
      
      //Content
      $mail->Subject = "Petycja";
      $mail->isHTML(true);
      if(isset($uploadOk) && $uploadOk == 1)
      {
        $mail->Body = "Petycja.";
        $mail->AltBody = "Petycja.";
      }
      else
      {
        $mail->Body = $_POST['text-editor'];
        $mail->AltBody = $_POST['text-editor'];
      }
      
      $mail->send();
      $ok = true;
    }
    catch (Exception $e)
    {
      $ok = false;
    }
    
    if ($ok)
      $_SESSION['ok'] = true;
    else
      $_SESSION['ok'] = false;
  }
  
  header("Location: ./");