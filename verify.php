<?php

/*
================================================
== Template Page
================================================
*/

ob_start(); // Output Buffering Start
$noSlider="";
session_start();

$pageTitle = '';

if (isset($_SESSION['uid'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {
    } elseif ($do == 'Add') {


    } elseif ($do == 'verifyEmail') {
        /*====================*/
        /* Start Verify Email*/
        $randomPassVerifyEmail = randomPassword();
        $uid = $_SESSION["uid"];
        $stmt=$con->prepare("SELECT Email FROM `users` WHERE UserID = ?");
        $stmt->execute(array($uid));
        $row=$stmt->fetch();
        // Send Code Verify To Email Sender
        $to = $row["Email"];

        $subject = 'Verify Your Email';

        $headers = "From: hamza.komidi123@gmail.com\r\n";
        $headers .= "Reply-To: hamza.komidi123@gmail.com\r\n";
//        $headers .= "CC: susan@example.com\r\n";// this for share email other not bind if bcc(bind carbon copy) not view to above
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $message = '<p style="text-align: center">Code Verify Is :<strong>'.$randomPassVerifyEmail.'</strong></p>';

        mail($to, $subject, $message, $headers);
        echo "<div class='container'><div class='alert alert-success'>Check Your Email To Get Code Verify.</div></div>";
        ?>
        <style>
            @media (max-width: 414px) {
                .asterisk {
                    position: absolute;
                    font-size: 32px;
                    color: #d23030;
                    right: 52px;
                    top: 92px;
                }
            }
            @media (min-width: 415px) {
            .asterisk {
                position: absolute;
                font-size: 32px;
                color: #d23030;
                right: 50px;
                top: 70px;
            }
            }
        </style>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="text-center">Verify Email</h1>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="text-center">
                                    <p>Please Check Your Email And Put Code Here.</p>
                                    <div class="panel-body">
                                        <fieldset>
                                            <form action="?do=verifyedEmail" method="POST">
                                                <input type="hidden" name="randomPassVerifyEmail" value="<? echo $randomPassVerifyEmail;?>">
                                                <div class="form-group">
                                                    <input
                                                            class="form-control input-lg"
                                                            placeholder="Code Verify Email"
                                                            name="vEmail"
                                                            required
                                                            type="text"
                                                            autofocus
                                                    >
                                                </div>
                                                <input
                                                        class="btn btn-lg btn-primary btn-block"
                                                        value="Verify"
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
                    </div>
                </div>
            </div>
        </div>
<?php        /* End Verify Email*/
        /*====================*/
    }elseif ($do == 'verifyedEmail') {
        /*===================*/
        /* Start Result Email*/
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            $vMail = trim($_POST['vEmail']);
            $vMail = strip_tags($vMail);
            $verifyMail = filter_var($vMail, FILTER_SANITIZE_STRING);
            $uid = $_SESSION["uid"];
            $randomPassVerifyEmail = $_POST["randomPassVerifyEmail"];
            $formErrors = array();

            if (empty($verifyMail)) {
                $formErrors[] = 'Your Code Verify Is Empty!';
            }
            // Loop Into Errors Array And Echo It
            foreach ($formErrors as $error) {
                echo '<div class="container"><div class="alert alert-danger">' . $error . '</div></div>';
            }
            if (empty($error)) {
//        $checkMailForget = getAllFrom("Email,Passowrd", "users", "where Email = '$mail'", "", "UserID");
                $stmt=$con->prepare("SELECT VerifyEmail FROM `users` WHERE UserID = ? AND VerifyEmail = 0");
                $stmt->execute(array($uid));
                $row=$stmt->fetch();
                $count=$stmt->rowCount();
                if ($count>0){
                    if ($verifyMail === $randomPassVerifyEmail){
                        // Update password new password
                        $stmt = $con->prepare("UPDATE users SET VerifyEmail = 1 WHERE UserID = ?");

                        $stmt->execute(array($uid));
                    }else{
                        echo "<div class='container'><div class='alert alert-warning'>This Code Is Wrong Please check Again To Code Verify In Your Email.</div></div>";
                    }
                }else{
                    echo "<div class='container'><div class='alert alert-success'>This Email Is Verified.</div></div>";
                }
            }
        };
        /*  End Result Email */
        /*===================*/
    } elseif ($do == 'verifyPhone') {
        /*====================*/
        /* Start Verify Phone*/
      $randomPassVerifyPhone = rand(0,98765);
        $uid = $_SESSION["uid"];
        $stmt=$con->prepare("SELECT Phone FROM `users` WHERE UserID = ?");
        $stmt->execute(array($uid));
        $row=$stmt->fetch();
        // Send Code Verify To Email Sender
        $to = $row["Phone"];
        // Site  to send sms api                    
      require 'class-Clockwork.php';
try
{
    $codee = "Your Code Verify Is:".$randomPassVerifyPhone;
    // Create a Clockwork object using your API key
    $options = array( 'ssl' => false );
    $clockwork = new Clockwork("a063fd15c7ae426c2b4d162aa282eedeb13c11a9", $options );

    // Setup and send a message
    $message = array( 'to' => $to, 'message' => $codee );
    $result = $clockwork->send( $message );

    // Check if the send was successful
    if($result['success']) {
//        $result['id'];
        echo "<div class='container'><div class='alert alert-success'>Message sent successfully.</div></div>";
    } else {
//        $result['error_message']
        echo "<div class='container'><div class='alert alert-warning'>Message failed please try once again.</div></div>";
    }
}
catch (ClockworkException $e)
{
    echo 'Exception sending SMS: ' . $e->getMessage();
};

?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="text-center">Verify Phone</h1>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="text-center">
                                    <p>Please Check Your Phone And Put Code Here.</p>
                                    <div class="panel-body">
                                        <fieldset>
                                            <form action="?do=verifyedPhone" method="POST">
                                                <input type="hidden" name="randomPassVerifyPhone" value="<? echo $randomPassVerifyPhone;?>">
                                                <div class="form-group">
                                                    <input
                                                            class="form-control input-lg"
                                                            placeholder="Code Verify Phone"
                                                            name="vPhone"
                                                            required
                                                            type="text"
                                                            autofocus
                                                    >
                                                </div>
                                                <input
                                                        class="btn btn-lg btn-primary btn-block"
                                                        value="Verify"
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
                    </div>
                </div>
            </div>
        </div>
  <?      /* End Verify Phone*/
        /*====================*/
    }elseif ($do == 'verifyedPhone') {
        /*===================*/
        /* Start Result Phone*/
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            $vPhone = trim($_POST['vPhone']);
            $vPhone = strip_tags($vPhone);
            $verifyPhone = filter_var($vPhone, FILTER_SANITIZE_STRING);
            $uid = $_SESSION["uid"];
            $randomPassVerifyPhone = $_POST["randomPassVerifyPhone"];
            $formErrors = array();

            if (empty($verifyPhone)) {
                $formErrors[] = 'Your Code Verify Is Empty!';
            }
            // Loop Into Errors Array And Echo It
            foreach ($formErrors as $error) {
                echo '<div class="container"><div class="alert alert-danger">' . $error . '</div></div>';
            }
            if (empty($error)) {
//        $checkMailForget = getAllFrom("Email,Passowrd", "users", "where Email = '$mail'", "", "UserID");
                $stmt=$con->prepare("SELECT VerifyPhone FROM `users` WHERE UserID = ? AND VerifyPhone = 0");
                $stmt->execute(array($uid));
                $row=$stmt->fetch();
                $count=$stmt->rowCount();
                if ($count>0){
                    if ($verifyPhone == $randomPassVerifyPhone){
                        // Update password new password
                        $stmt = $con->prepare("UPDATE users SET VerifyPhone = 1 WHERE UserID = ?");

                        $stmt->execute(array($uid));
                        $thMsg = "<div class='container'><div class='alert alert-success'>Verify Is Successfully</div></div>";
                        redirectHome($thMsg);
                    }else{
                        echo "<div class='container'><div class='alert alert-warning'>This Code Is Wrong Please check Again To Code Verify In Your Phone.</div></div>";
                    }
                }else{
                    echo "<div class='container'><div class='alert alert-success'>This Phone Is Verified.</div></div>";
                }
            }
        };
        /*  End Result Phone */
        /*===================*/
    }

    include $tpl . 'footer.php';

} else {

    header('Location: index.php');

    exit();
}

ob_end_flush(); // Release The Output

?>