<?php
ob_start();
session_start();
$noNavbar='';//for not showing the file navbar in this file
$pageTitle="login";//for function getTitle (default)
if (isset($_SESSION["Username"])){
    header("Location: dashboard.php");
}
include 'init.php';

if ($_SERVER["REQUEST_METHOD"] == 'POST'){
    $username;$password;$captcha;
//        $user ="";$pass ="";$captcha ="";
    if(isset($_POST['username'])){
        $username=trim($_POST["username"]);
        $username=strip_tags($username);
        $username=filter_var($username, FILTER_SANITIZE_STRING);
    }if(isset($_POST['password'])){
        $password=trim($_POST["password"]);
        $password=strip_tags($password);
        $password=filter_var($password,FILTER_SANITIZE_STRING);
    }if(isset($_POST['g-recaptcha-response'])){
        $captcha=$_POST['g-recaptcha-response'];
    }
    $formErrors = array();

    if (strlen($username) < 4) {
        $formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
    }
    // Loop Into Errors Array And Echo It
    foreach ($formErrors as $error) {
        $msgError = '<div class="container"><div class="alert alert-danger">' . $error . '</div></div> ';
    }
    if(!$captcha){
        $theMsg='<div class="container"><div class="alert alert-danger">Please check the the captcha form.</div></div> ';
        redirectHome($theMsg,'back');
        exit;
    }
    $secretKey = "6LfNwicUAAAAALaPI4AkLW_JQOGAuhPuIaC4bm40";
    $ip = $_SERVER['REMOTE_ADDR'];
    ob_start();
    eval(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip));
    $response = ob_get_contents();
    ob_end_clean();
    $responseKeys = json_decode($response,true);
    if(intval($responseKeys["success"]) !== 1) {
        $hashpassword=sha1($password);
        if (empty($error)) {
            $stmt = $con->prepare("SELECT 
                                          UserID,Username, Password 
                                   FROM   
                                          users 
                                   WHERE 
                                          Username= ? 
                                   AND 
                                          Password= ? 
                                   AND 
                                          GroupID= 1
                                   LIMIT 1");
            $stmt->execute(array($username, $hashpassword));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();
            if ($count > 0) {
                //==============================================================================================================
                //save this username and password as cookie if remember checked start
                $servername = "localhost";
                $usernamedb   = "root";
                $passworddb   = "12345678";
                $dbname     = "shop";// this db not needful to successfully
                // Create connection
                $conn = new mysqli($servername, $usernamedb, $passworddb, $dbname);
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }else {
                    $usernameVal = $_REQUEST["username"];
                    $escapedPW   = mysqli_real_escape_string($conn,$_REQUEST['password']);

                    //save this user and pass as cookie if remeber checked start
                    if (isset($_REQUEST['remember']))
                        $escapedRemember = mysqli_real_escape_string($conn,$_REQUEST['remember']);

                    $cookie_time       = 60 * 60 * 24 * 30; // 30 days
                    $cookie_time_Onset = $cookie_time + time();
                    if (isset($escapedRemember)) {
                        // Set Cookie from here for one hour
                        setcookie("adminname", $usernameVal, $cookie_time_Onset);
                        setcookie("adminpass", $escapedPW, $cookie_time_Onset);
                    }else {
                        $cookie_time_fromOffset = time() - $cookie_time;
                        setcookie("adminname", '',$cookie_time_fromOffset );
                        setcookie("adminpass", '', $cookie_time_fromOffset);
                    }
                }// end else check connect database
                // End coding remember password and username
                //==============================================================================================================
                $_SESSION["Username"] = $username;//Register session usename
                $_SESSION["ID"] = $row["UserID"];//Register session userID
                header("Location: dashboard.php");//redirect to dashboard page
                exit();
            }else {
                $theMsg = "<br /><div class='container'><div class='alert alert-danger'>Password Or Username Wrong Please Try Again</div></div>";
                redirectHome($theMsg);
            }
        }
    }
}
//session_destroy();
?>
<style>
    /* Specific styles of signin component */
    /* General styles */
    body, html {
        height: 100%;
        background-repeat: no-repeat;
        background-image: linear-gradient(rgb(174, 39, 47), rgb(3, 17, 7));
    }
</style>
<div class="container">
    <div class="card card-container">
        <img id="profile-img" class="profile-img-card" src="uploads/avatars/johnDoe.png" />
        <p id="profile-name" class="profile-name-card">Administration</p>
        <style>
            .asterisk {
                display: none;
            }
        </style>
        <form class="form-signin" action="<? echo $_SERVER['PHP_SELF']?>" method="POST">
            <div class="input-container">
                    <span id="reauth-email" class="reauth-email" style="font-weight: bold;font-size: 20px"></span>
                    <input
                            class="form-cntrol input-lg btnLogin-block"
                            type="text"
                            name="username"
                            value="<?php if(isset($_COOKIE['adminname'])) echo $_COOKIE['adminname']; ?>"
                            placeholder="Your Username"
                            autocomplete=off""
                            pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                            title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                            maxlength="30"
                            autofocus
                            required
                    />
                    <input
                            class="form-cntrol input-lg btnLogin-block password"
                            type="password"
                            name="password"
                            value="<?php if(isset($_COOKIE['adminpass'])) echo $_COOKIE['adminpass']; ?>"
                            autocomplete=new_password""
                            placeholder="Your Password"
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                            title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                            maxlength="30"
                            required
                    /> <i class="show-pass fa fa-eye fa-2x"></i><style>
                        .show-pass{
                            z-index: 10;
                            top: 72px;
                            right: 4px;
                            color: #96242a;
                        }
                        </style>
                    <!-- div remember password and username for checked it -->
                    <div id="remember" class="checkbox">
                        <label>
                            <input
                                    type="checkbox"
                                    value="remember-me"
                                    name="remember"
                                    id="rememberUser"
                                <? if(isset($_COOKIE['adminname'])){echo "checked='checked'"; } ?>
                            > <label for="rememberUser">Remember me</label>
                        </label>
                    </div>
                    <!-- div Captcha coogle -->
                    <div class="g-recaptcha"  data-sitekey="6LfNwicUAAAAADiAu39aQMU6HT9uGJBtWqlDG1n8" data-callback="recaptcha_callback"></div>
                    <br>
                    <button
                            class="btnLogin btnLogin-lg btnLogin-primary btnLogin-block btnLogin-signin"
                            style="cursor: pointer"
                            type="submit"
                            id="submitBtn"
                            disabled="disabled"
                    >Sign in</button>
            </div>
        </form><!-- /form -->
        <a href="#" class="forgot-password"><i class="fa fa-lock "></i> Forgot the password?</a>
    </div><!-- /card-container -->
</div><!-- /container -->
<?
echo $msgError;
include $tpl."footer.php";
ob_end_flush();
?>
