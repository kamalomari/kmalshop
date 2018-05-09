<?php
    ob_start();
    session_start();
    $noSlider ="";
    $pageTitle="login";//for function getTitle (default)
    if (isset($_SESSION['user'])) {
        header('Location: index.php');
    }
include 'init.php';
if ($_SERVER["REQUEST_METHOD"] == 'POST'){
    if (isset($_POST["login"])) { // Start Login
        $user;$pass;$captcha;
//        $user ="";$pass ="";$captcha ="";
        if(isset($_POST['username'])){
            $user=trim($_POST["username"]);
            $user=strip_tags($user);
            $user=filter_var($user, FILTER_SANITIZE_STRING);
        }if(isset($_POST['password'])){
            $pass=trim($_POST["password"]);
            $pass=strip_tags($pass);
            $pass=filter_var($pass,FILTER_SANITIZE_STRING);
        }if(isset($_POST['g-recaptcha-response'])){
            $captcha=$_POST['g-recaptcha-response'];
        }
        if(!$captcha){
            $theMsg='<div class="container"><div class="alert alert-danger">Please check the the captcha form.</div></div> ';
            redirectHome($theMsg,'back');
            exit;
        }
        //==============================================================================================================
            //save this username and password as cookie if remember checked start
        $servername = "localhost";
        $username   = "root";
        $password   = "12345678";
        $dbname     = "shop";// this db not needful to successfully
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }else {
            $usernameVal=$_REQUEST["username"];
            $escapedPW = mysqli_real_escape_string($conn,$_REQUEST['password']);

            //save this user and pass as cookie if remeber checked start
            if (isset($_REQUEST['remember']))
                $escapedRemember = mysqli_real_escape_string($conn,$_REQUEST['remember']);

            $cookie_time       = 60 * 60 * 24 * 30; // 30 days
            $cookie_time_Onset = $cookie_time+ time();
            if (isset($escapedRemember)) {
                // Set Cookie from here for one hour
                setcookie("username", $usernameVal, $cookie_time_Onset);
                setcookie("password", $escapedPW, $cookie_time_Onset);

            }else {
                $cookie_time_fromOffset = time() - $cookie_time;
                setcookie("username", '',$cookie_time_fromOffset );
                setcookie("password", '', $cookie_time_fromOffset);
            }
        }// end else check connect database
            // End coding remember password and username
        //==============================================================================================================
        $secretKey = "6LfNwicUAAAAALaPI4AkLW_JQOGAuhPuIaC4bm40";
        $ip = $_SERVER['REMOTE_ADDR'];
        ob_start();
        eval(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip));
        $response = ob_get_contents();
        ob_end_clean();
        $responseKeys = json_decode($response,true);
        if(intval($responseKeys["success"]) !== 1) {
            $hashpass=sha1($pass);

            $stmt=$con->prepare("SELECT 
                                                 UserID, Username, Password 
                                           FROM   
                                                  users 
                                           WHERE 
                                                  Username= ? 
                                           AND 
                                                  Password= ? 
                                           ");
            $stmt->execute(array($user, $hashpass));

            $get=$stmt->fetch();

            $count=$stmt->rowCount();

            if ($count>0){
                $_SESSION["user"]=$user;//Register session usename
                $_SESSION["uid"]=$get["UserID"];//Register User ID in Session
                header("Location: index.php");//redirect to dashboard page
                exit();
            }elseif ($count=1){
                $pasOrUserWrong = '<div class="container"><div class="alert alert-danger">Your Username Or Password Is Wrong Please Try Again.</div></div>';
            }
        }
    }else{// Start SingUp
        // Upload Variables
        $avatarName     = $_FILES["avatar"]["name"];
        $avatarSize     = $_FILES["avatar"]["size"];
        $avatarTmpName  = $_FILES["avatar"]["tmp_name"];
        $avatarType     = $_FILES["avatar"]["type"];
        // List of allowed file typed to upload
        $avatarAllowedExtension = array("jpeg","jpg","png","gif");

        // Get avatar extension
         $exp=explode(".",$avatarName);
         $endExp=end($exp);
         $avatarExtension = strtolower($endExp);

        // Filter Username
        $username=trim($_POST["username"]);
        $username=strip_tags($username);
        $username=filter_var($username, FILTER_SANITIZE_STRING);
        // Filter Password
        $password=trim($_POST["password"]);
        $password=strip_tags($password);
        $password=filter_var($password, FILTER_SANITIZE_STRING);
        // Filter Password2
        $password2=trim($_POST["password2"]);
        $password2=strip_tags($password2);
        $password2=filter_var($password2, FILTER_SANITIZE_STRING);
        // Filter Email
        $email=trim($_POST["email"]);
        $email=strip_tags($email);
        $email=filter_var($email, FILTER_SANITIZE_STRING);
        // Filter Phone
        $phone=trim($_POST["phone"]);
        $phone=strip_tags($phone);
        $phone=filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
        // Filter First Name
        $first_n=trim($_POST["first_n"]);
        $first_n=strip_tags($first_n);
        $first_n=filter_var($first_n, FILTER_SANITIZE_STRING);
        // Filter Last Name
        $last_n=trim($_POST["last_n"]);
        $last_n=strip_tags($last_n);
        $last_n=filter_var($last_n, FILTER_SANITIZE_STRING);

        $formErrors = array();

        // Filter Username
        if (isset($username)) {

            $filterdUser = filter_var($username, FILTER_SANITIZE_STRING);

            if (strlen($filterdUser) < 4) {

                $formErrors[] = 'Username Must Be Larger Than 4 Characters';

            }

        }
        // Filter Password
        if (isset($password) && isset($password2)) {

            if (empty($password)) {

                $formErrors[] = 'Sorry Password Cant Be Empty';

            }

            if (sha1($password) !== sha1($password2)) {

                $formErrors[] = 'Sorry Password Is Not Match';

            }

        }
        // Filter Email
        if (isset($email)) {

            $filterdEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

            if (filter_var($filterdEmail, FILTER_VALIDATE_EMAIL) != true) {

                $formErrors[] = 'This Email Is Not Valid';

            }

        }
        if (empty($phone)) {
            $formErrors[] = 'Phone Can\'t Be <strong>Empty</strong>';
        }
        if (empty($first_n)) {
            $formErrors[] = 'First Name Can\'t Be <strong>Empty</strong>';
        }
        if (empty($last_n)) {
            $formErrors[] = 'Last Name Can\'t Be <strong>Empty</strong>';
        }
        // Filter Image and get all error putting users
        if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
            $formErrors[] = 'This Extension IS Not  <strong>Allowed</strong>';
        }
        if ( $avatarSize > 4194304 ) {
            $formErrors[] = 'Image Can\'t Be Larger Than <strong>4MB</strong>';
        }
        if (strpos($avatarName, '.php') !== false)  {
            $formErrors[] = 'Must Name Of File Not Contains <strong>.php</strong>';
        }
        // Check If There's No Error Proceed The User Add

        if (empty($formErrors)) {
            // Naming Avatar For Database
            $avatar = rand(0,10000000000)."_".$avatarName;
            // this function for move pictute to the path of admin
            move_uploaded_file($avatarTmpName,'admin/uploads/avatars/'.$avatar);

            // Check If User Exist in Database

            $check = checkItem("Username", "users", $username);
            $user_ip = get_client_ip();
            echo $user_ip;

            if ($check == 1) {

                $formErrors[] = 'Sorry This User Is Exists';

            } else {
                if (!empty($avatarName)) {
                    // Insert Userinfo In Database

                    $stmt = $con->prepare("INSERT INTO
											users
										        	(Username, Password, Email, Phone, First_N, Last_N, RegStatus, Date, Avatar, IP)
										    VALUES
										            (:zuser, :zpass, :zmail, :zphone, :zfirst_n, :zlast_n, 0, now(), :zavatar, :zip )");
                    $stmt->execute(array(

                        'zuser'    => $username,
                        'zpass'    => sha1($password),
                        'zmail'    => $email,
                        'zphone'   => $phone,
                        'zfirst_n' => $first_n,
                        'zlast_n'  => $last_n,
                        'zavatar'  => $avatar,
                        'zip'      => $user_ip

                    ));
                }else{
                    // Insert Userinfo In Database

                    $stmt = $con->prepare("INSERT INTO
											users
										        	(Username, Password, Email, Phone, First_N, Last_N, RegStatus, Date, IP)
										    VALUES
										            (:zuser, :zpass, :zmail, :zphone, :zfirst_n, :zlast_n, 0, now(), :zip )");
                    $stmt->execute(array(

                        'zuser' => $username,
                        'zpass' => sha1($password),
                        'zmail' => $email,
                        'zphone' => $phone,
                        'zfirst_n' => $first_n,
                        'zlast_n'  => $last_n,
                        'zip' => $user_ip

                    ));
                }
                // Echo Success Message

                $succesMsg = 'Congrats You Are Now Registerd User';

            }
        }
    }
}
?>
<div class="container login-page">
    <h1 class="text-center">
        <span data-class="login" class="selected">Login</span> | <span data-class="singup" class="optClr">Singup</span>
    </h1>
    <!-- Start Login Form -->
    <form  class="login" action="<? echo $_SERVER['PHP_SELF']?>" method="POST">
        <div class="input-container">
            <label>Username :</label>
                <input
                    class="form-control"
                    id="loginUser"
                    type="text"
                    value="<?php if(isset($_COOKIE['username'])) echo $_COOKIE['username']; ?>"
                    name="username"
                    placeholder="Your Username"
                    pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                    title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                    maxlength="30"
                    autofocus
                    required="required"
                />
        </div>
        <div class="input-container">
            <label>Password :</label>
            <input
                    class="form-control password"
                    id="loginPass"
                    type="password"
                    name="password"
                    value="<?php if(isset($_COOKIE['password'])) echo $_COOKIE['password']; ?>"
                    placeholder="Your Password"
                    autocomplete="new-password"
                    pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                    title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                    maxlength="30"
                    required="required"
            />   <i class="show-pass fa fa-eye fa-2x"></i>
            <!-- div remember password and username for checked it -->
            <div id="remember" class="checkbox">
                <label>
                    <input
                            type="checkbox"
                            value="remember-me"
                            name="remember"
                            id="rememberUser"
                        <? if(isset($_COOKIE['username'])){echo "checked='checked'"; } ?>
                    > <label for="rememberUser">Remember me</label>
                </label>
            </div>
            <!-- div Captcha coogle -->
            <div class="g-recaptcha"  data-sitekey="6LfNwicUAAAAADiAu39aQMU6HT9uGJBtWqlDG1n8" data-callback="recaptcha_callback"></div><br />
        </div>
                <input
                    class="btn btn-primary btn-block"
                    type="submit"
                    name="login"
                    value="Login"
                    id="submitBtn"
                    disabled="disabled"
                /><br />
        <div class="input-container">
            <a href="forgetPass.php" style="text-decoration: none"> <i class="fa fa-lock "></i> Lost password?</a>
        </div>
    </form>
    <!-- End Login Form -->
    <br />
    <!-- Start Singup Form -->
    <form  class="singup" action="<? echo $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data">
        <div class="input-container">
            <label>Username :</label>
                <input
                    class="form-control"
                    type="text"
                    name="username"
                    placeholder="Username complex"
                    required="required"
                    autocomplete="off"
                />
        </div>
        <div class="input-container">
            <label>Password :</label>
                <input
                    class="form-control password"
                    type="password"
                    name="password"
                    placeholder="Type complex password"
                    required="required"
                    autocomplete="new-password"
                />
        </div>
        <div class="input-container">
            <label>Confirm Password :</label>
                <input
                    class="form-control password"
                    type="password"
                    name="password2"
                    placeholder="Type a password again"
                    required="required"
                    autocomplete="new-password"
                />
            <i class="show-pass fa fa-eye fa-2x"></i>
        </div>
        <div class="input-container">
            <label>Email :</label>
                <input
                    class="form-control"
                    type="email"
                    name="email"
                    placeholder="Type email validation"
                    required="required"
                    autocomplete="new-password"
                />
        </div>
        <div class="input-container">
            <label>Phone :</label>
            <input
                    class="form-control"
                    type="number"
                    name="phone"
                    placeholder="Your Number Phone"
                    required="required"
                    autocomplete="off"
            />
        </div>
        <div class="input-container">
            <label>First Name :</label>
            <input
                    class="form-control"
                    type="text"
                    name="first_n"
                    placeholder="Your First Name"
                    required="required"
                    autocomplete="off"
            />
        </div>
        <div class="input-container">
            <label>Last Name :</label>
            <input
                    class="form-control"
                    type="text"
                    name="last_n"
                    placeholder="Your Last Name"
                    required="required"
                    autocomplete="off"
            />
        </div>
        <div class="input-container">
            <label>Your Avatar(optional) :</label>
            <input
                    type="file"
                    name="avatar"
                    class="form-control"
                    />
        </div>
        <div class="input-container">
        <input
            class="btn btn-success btn-block"
            type="submit"
            name="singup"
            value="Singup"
        />
    </form>
    <!-- End Singup Form -->


</div>
<?php
if (isset($pasOrUserWrong)):
    echo $pasOrUserWrong;
endif;
?>
<div class="the-errors text-center">
    <?php

    if (!empty($formErrors)) {

        foreach ($formErrors as $error) {

            echo '<div class="msg error">' . $error . '</div>';

        }
    }

            if (isset($succesMsg)) {

                echo '<div class="msg success">' . $succesMsg . '</div>';

            }

    ?>
</div>
<!-- because not work in login width 100% -->
<style>
    .container{
        width: 100% !important;
    }
</style>
<?php
include $tpl.'footer.php';
ob_end_flush();
?>
