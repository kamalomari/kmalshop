<?php
session_start();
$pageTitle="Forget Password";//for function getTitle (default)
$noSlider="";
include "init.php";
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $postMail = trim($_POST['emailForget']);
    $postMail = strip_tags($postMail);
    $mail = filter_var($postMail, FILTER_SANITIZE_EMAIL);
    $formErrors = array();

    if (empty($mail)) {
        $formErrors[] = 'Your Email Is Empty!';
    }
    // Loop Into Errors Array And Echo It
    foreach ($formErrors as $error) {
        echo '<div class="container"><div class="alert alert-danger">' . $error . '</div></div>';
    }
    if (empty($error)) {
//        $checkMailForget = getAllFrom("Email,Passowrd", "users", "where Email = '$mail'", "", "UserID");
        $stmt=$con->prepare("SELECT Email,Password FROM `users` WHERE Email = ? ");
        $stmt->execute(array($mail));
        $row=$stmt->fetch();
        $count=$stmt->rowCount();
        if ($count>0){
            $randomPass = randomPassword();
            $passwordNew = sha1($randomPass);
            // Update password new password
            $stmt = $con->prepare("UPDATE users SET Password = ? WHERE Email = ?");

            $stmt->execute(array($passwordNew,$mail));

            $to = "";
            // html forget password and function mail to send
            $to = $mail;

            $subject = 'Reset Your Password';

            $headers = "From: hamza.komidi123@gmail.com\r\n";
            $headers .= "Reply-To: hamza.komidi123@gmail.com\r\n";
            $headers .= "CC: susan@example.com\r\n";// this for share email other not bind if bcc(bind carbon copy) not view to above
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            $message = '<p style="text-align: center">Your New Password Is :<strong>'.$passwordNew.'</strong></p>';


            mail($to, $subject, $message, $headers);

            echo "<div class='container'><div class='alert alert-success'>Check Your Email To Get Password.</div></div>";
        }else{
           echo "<div class='container'><div class='alert alert-danger'>This Email Not Exist! Please Try Again.</div></div>";
        }
    }
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta charset="utf-6">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="layout/js/html5shiv.min.js"></script>
    <script src="layout/js/respond.min.js"></script>
    <link rel="stylesheet" href="layout/css/bootstrap.css">
    <link rel="stylesheet" href="layout/css/forgetPassCSS.css">
    <link rel="stylesheet" href="layout/fonts/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Cinzel+Decorative" rel="stylesheet">
</head>
<body>
<h1 class="cta-title">Forgot your password?</h1>
<div class="bs-calltoaction bs-calltoaction-info cta">
    <div class="row">
        <div class="col-md-9 cta-contents">
            <h3 class="cta-title">Click in this button to retrieve your password if password it difficult remember please change them.</h3>
        </div>
        <div class="col-md-3 cta-button">
            <a href="#" data-target="#pwdModal" data-toggle="modal" class="btn btn-lg btn-block btn-info">New Password</a>
        </div>
    </div>
</div>
<!--modal-->
<div id="pwdModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h1 class="text-center">What's My Password?</h1>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="text-center">

                                <p>If you have forgotten your password you can reset it here.</p>
                                <div class="panel-body">
                                    <fieldset>
                                        <form action="<? echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                            <div class="form-group">
                                                <input
                                                        class="form-control input-lg"
                                                        placeholder="E-mail Address"
                                                        name="emailForget"
                                                        type="email">
                                            </div>
                                            <input
                                                    class="btn btn-lg btn-primary btn-block"
                                                    value="Send"
                                                    type="submit" >
                                        </form>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-md-12">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?
?>
<? include $tpl."footer.php" ?>