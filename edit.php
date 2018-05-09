<?php

/*
================================================
== Template Page
================================================
*/

ob_start(); // Output Buffering Start
session_start();
$noSlider ="";
$pageTitle = 'Edit';

if (isset($_SESSION['user'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

    } elseif ($do == 'Edit') {
        $decryptedUserid = my_simple_crypt( $_GET["userid"], 'd' );
        if (isset($decryptedUserid) && is_numeric($decryptedUserid)) {
            $userid = intval($decryptedUserid);
        } else {
            $userid = 0;
        }
        $stmt = $con->prepare("SELECT * FROM `users`  WHERE UserID = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) {?>
            <h1 class="text-center">Edit Enformation</h1>
             <!-- style for asterisk -->
            <style>
                span.asterisk{
                    right: 22px;
                    top: 12px;
                }
            </style>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="userid" value="<? echo $userid;?>">
                    <!--  Start username field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username :</label>
                        <div class="col-sm-10 col-md-4">
                            <input type="text"
                                   name="username"
                                   class="form-control"
                                   value="<? echo $row["Username"];?>"
                                   autocomplete=off""
                                   pattern="(?=.*\d)(?=.*[a-z]).{8,}"
                                   title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                                   maxlength="30" autofocus required="required"><!-- validations of the html  -->
                        </div>
                    </div>
                    <!--  End username field-->
                    <!--  Start password field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Password :</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                type="hidden"
                                name="oldpassword"
                                value="<? echo $row["Password"];?>">
                            <input
                                type="password"
                                name="newpassword"
                                class="form-control"
                                autocomplete="new-password"
                                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                placeholder="Leave blank if don't to change password"
                                title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"
                                maxlength="30"><!-- validations of the html  -->
                        </div>
                    </div>
                    <!--  End password field-->
                    <!--  Start email field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Email :</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                type="email"
                                name="email"
                                value="<? echo $row["Email"]?>"
                                class="form-control"
                                maxlength="50"
                                autocomplete="off"
                                required="required" ><!-- validations of the html  -->
                        </div>
                    </div>
                    <!--  End email field-->
                    <!--  Start phone field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Phone :</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                type="number"
                                name="phone"
                                value="<? echo $row["Phone"]?>"
                                class="form-control"
                                pattern="[0-9]{5,30}$"
                                maxlength="50"
                                autocomplete="off"
                                required="required"><!-- validations of the html  -->
                        </div>
                    </div>
                    <!--  End phone field-->
                    <!--  Start First Name field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">First Name :</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                type="text"
                                name="first_n"
                                value="<? if (empty($row["First_N"])){echo 'No!';}else{echo $row["First_N"];} ?>"
                                class="form-control"
                                pattern="(?=.*[a-z]).{4,}"
                                title="you must contain Only letters and more than 10 letters"
                                maxlength="40" autocomplete="off" >
                            <!-- validations of the html  -->
                        </div>
                    </div>
                    <!--  End First Name field-->
                    <!--  Start Last Name field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">First Name :</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                    type="text"
                                    name="last_n"
                                    value="<? if (empty($row["Last_N"])){echo 'No!';}else{echo $row["Last_N"];} ?>"
                                    class="form-control"
                                    pattern="(?=.*[a-z]).{4,}"
                                    title="you must contain Only letters and more than 10 letters"
                                    maxlength="40" autocomplete="off" >
                            <!-- validations of the html  -->
                        </div>
                    </div>
                    <!--  End Last Name field-->
                    <!--  Start Avatar field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Avatar :</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                    type="file"
                                    name="avatar"
                                    class="form-control">
                            <!-- validations of the html  -->
                        </div>
                    </div>
                    <!--  End Avatar field-->
                    <!--  Start Country field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country :</label>
                        <div class="col-sm-10 col-md-4">
                            <select name="country"
                                    class="selectpicker"
                                    data-style="btn-info"
                                    multiple data-max-options="1"
                                    data-live-search="true">
                                <optgroup label="Status Of Your Item">
                                    <option value="no" <?php if ($row['Country'] === "" OR $row['Country'] === "no") { echo 'selected'; } ?>>...</option>
                                    <option value="Morocco" <?php if ($row['Country'] == "Morocco"){ echo 'selected'; } ?>>Morocco</option>
                                    <option value="United-State" <?php if ($row['Country'] == "United-State"){ echo 'selected'; } ?>>United-State</option>
                                    <option value="Egypt" <?php if ($row['Country'] == "Egypt"){ echo 'selected'; } ?>>Egypt</option>
                                    <option value="France" <?php if ($row['Country'] == "France"){ echo 'selected'; } ?>>France</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <!--  End Country field-->
                    <!--  Start City field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">City :</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                    type="text"
                                    name="city"
                                    value="<? echo $row["City"];?>"
                                    class="form-control"
                                    placeholder="City"
                                    pattern="(?=.*[a-z]).{3,}"
                                    title="you must contain Only letters and more than 3 letters"
                                    maxlength="40" autocomplete="off" >
                            <!-- validations of the html  -->
                        </div>
                    </div>
                    <!--  End City field-->
                    <!--  Start Address 1 field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Address 1 :</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                    type="text"
                                    name="address1"
                                    value="<?if (!empty($row["Address1"])){echo $row["Address1"];}?>"
                                    class="form-control"
                                    placeholder="Address 1"
                                    maxlength="40" autocomplete="off" >
                            <!-- validations of the html  -->
                        </div>
                    </div>
                    <!--  End Address 1 field-->
                    <!--  Start Address 2 field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Address 2 :</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                    type="text"
                                    name="address2"
                                    value="<? if (!empty($row["Address2"])){echo $row["Address2"];} ?>"
                                    class="form-control"
                                    placeholder="Address 2"
                                    maxlength="40" autocomplete="off" >
                            <!-- validations of the html  -->
                        </div>
                    </div>
                    <!--  End Address 2 field-->
                    <!--  Start Zip field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Code Zip :</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                    type="text"
                                    name="zip"
                                    value="<? if (!empty($row["Zip"])){echo $row["Zip"];} ;?>"
                                    class="form-control"
                                    placeholder="Code Zip"
                                    pattern="^\d{5}$"
                                    title="you must contain just five number "
                                    maxlength="40" autocomplete="off" >
                            <!-- validations of the html  -->
                        </div>
                    </div>
                    <!--  End Zip field-->
                    <!--  Start Button field-->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10"><!-- Leave col-sm-2 other empty-->
                            <input
                                type="submit"
                                value="Save change"
                                class="btn btn-success btn-lg">
                        </div>
                    </div>
                    <!--  End Button field-->
                </form>
            </div>
        <?
        }else {
        $theMsg= "<div class='container'><div class='alert alert-danger'>this wrong your are not member here</div></div>";
        redirectHome($theMsg);
    }
    } elseif ($do == 'Update') {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

            echo "<h1 class='text-center'>Update Members</h1>";
            echo "<div class='container''>";
            //Get variables from the FORM
            $id = $_POST["userid"];
            // Filter Usernme
            $user = trim($_POST['username']);
            $user = strip_tags($user);
            $user = filter_var($user, FILTER_SANITIZE_STRING);
            // Filter Email
            $email = trim($_POST['email']);
            $email = strip_tags($email);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            // Filter Phone
            $phone = trim($_POST['phone']);
            $phone = strip_tags($phone);
            $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
            // Filter First Name
            $first_n = trim($_POST['first_n']);
            $first_n = strip_tags($first_n);
            $first_n = filter_var($first_n, FILTER_SANITIZE_STRING);
            // Filter Last Name
            $last_n = trim($_POST['last_n']);
            $last_n = strip_tags($last_n);
            $last_n = filter_var($last_n, FILTER_SANITIZE_STRING);
            if (!empty($_POST["country"])){
                // Filter Country
                $country = trim($_POST['country']);
                $country = strip_tags($country);
                $country = filter_var($country, FILTER_SANITIZE_STRING);
            }else{$country = "";}
            if (!empty($_POST["city"])){
                // Filter City
                $city = trim($_POST['city']);
                $city = strip_tags($city);
                $city = filter_var($city, FILTER_SANITIZE_STRING);
            }else{$city = "";}
            if (!empty($_POST["address1"])){
                // Filter Address 1
                $address1 = trim($_POST['address1']);
                $address1 = strip_tags($address1);
                $address1 = filter_var($address1, FILTER_SANITIZE_STRING);
            }else{$address1 = "";}
            if (!empty($_POST["address2"])){
                // Filter Address 2
                $address2 = trim($_POST['address2']);
                $address2 = strip_tags($address2);
                $address2= filter_var($address2, FILTER_SANITIZE_STRING);
            }else{$address2 = "";}
            if (!empty($_POST["zip"])){
                // Filter  Code Zi^p
                $zip = trim($_POST['zip']);
                $zip = strip_tags($zip);
                $zip = filter_var($zip, FILTER_SANITIZE_NUMBER_INT);
            }else{$zip = "";}
            //Password Trick
            $pass = "";
            if (empty($_POST["newpassword"])) {// Or $pass=empty($_POST["newpassword"] ? $_POST["oldpassword"] : sha1($_POST["newpassword"] ;
                $pass = $_POST["oldpassword"];
            } else {
                $pass = sha1($_POST["newpassword"]);
            }
// Validate The Form

            $formErrors = array();

            if (strlen($user) < 4) {
                $formErrors[] = 'Username Cant Be Less Than <strong>4 Characters</strong>';
            }

            if (strlen($user) > 20) {
                $formErrors[] = 'Username Cant Be More Than <strong>20 Characters</strong>';
            }

            if (empty($user)) {
                $formErrors[] = 'Username Cant Be <strong>Empty</strong>';
            }

            if (empty($email)) {
                $formErrors[] = 'Email Cant Be <strong>Empty</strong>';
            }
            if (empty($phone)) {
                $formErrors[] = 'Phone Can\'t Be <strong>Empty</strong>';
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
            // Loop Into Errors Array And Echo It

            foreach ($formErrors as $error) {
                echo '<div class="container"><div class="alert alert-danger">' . $error . '</div></div>';
            }
            //check do you fiels is empty !!!
            if (empty($error)) {
                $stmt2 = $con->prepare("SELECT 
                                                    *
                                                FROM 
                                                    users
                                                WHERE
                                                    Username = ?
                                                AND 
                                                    UserID != ?");

                $stmt2->execute(array($user, $id));

                $count = $stmt2->rowCount();

                if ($count == 1) {

                    $theMsg = '<div class="container"><div class="alert alert-danger">Sorry This User Is Exist</div></div>';

                    redirectHome($theMsg, 'back');

                } else {
                    // Naming Avatar For Database
                    $avatar = rand(0,10000000000)."_".$avatarName;
                    // this function for move pictute to the path of admin
                    move_uploaded_file($avatarTmpName,'admin/uploads/avatars/'.$avatar);
                    $user_ip = get_client_ip();
                    if (!empty($avatarName)){
                    // Update The Database With This Info

                    $stmt = $con->prepare("UPDATE users SET 
                                                                      Username = ?, 
                                                                      Email = ?, 
                                                                      Phone = ?, 
                                                                      First_N = ?, 
                                                                      Last_N = ?, 
                                                                      Password = ?, 
                                                                      Avatar = ?, 
                                                                      IP = ? ,
                                                                      Country = ? ,
                                                                      City = ? ,
                                                                      Address1 = ? ,
                                                                      Address2 = ? ,
                                                                      Zip = ?
                                                                      WHERE UserID = ?");

                    $stmt->execute(array($user, $email, $phone, $first_n, $last_n, $pass, $avatar, $user_ip, $country, $city, $address1, $address2, $zip, $id));
                    }else{
                        // Update The Database With This Info

                        $stmt = $con->prepare("UPDATE users SET 
                                                                      Username = ?, 
                                                                      Email = ?, 
                                                                      Phone = ?, 
                                                                      First_N = ?, 
                                                                      Last_N = ?, 
                                                                      Password = ?, 
                                                                      IP = ?,
                                                                      Country = ? ,
                                                                      City = ? ,
                                                                      Address1 = ? ,
                                                                      Address2 = ? ,
                                                                      Zip = ? 
                                                                      WHERE UserID = ?");

                        $stmt->execute(array($user, $email, $phone, $first_n, $last_n, $pass, $user_ip, $country, $city, $address1, $address2, $zip, $id));
                    }
                    // Echo Success Message

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated Please Singup Again</div>';
                    session_unset();//Unset The Data
                    echo $theMsg;
                    header( "refresh:3;url=login.php" );



                }
            } else {
                echo "Check the above rules";
            }

        }else {
            $theMsg= "<div class='container'><div class='alert alert-danger'>So sory you can't browse this page directly</div></div> ";
            redirectHome($theMsg);
        }


    }

    include $tpl . 'footer.php';

} else {

    header('Location: index.php');

    exit();
}

ob_end_flush(); // Release The Output

?>