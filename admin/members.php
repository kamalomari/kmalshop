<?php

/*
================================================
== Manage Members Page
== You Can Add | Edit | Delete Members From Here
================================================
*/
ob_start();//for resolve problem (header&refresh&url) AND id output buffering start And I can use ob_gzhandler=>parameter //this is for remove BOM(byte order mark==>utf_8)
session_start();
$pageTitle="Members";
if (isset($_SESSION["Username"])) {
    include "init.php";
    if (isset($_GET['do'])) {// OR  $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
        $do = $_GET['do'];
    } else {
        $do = "Manage";
    }
    if ($do == 'Manage') { // Manage Members Page
        $query='';
        if (isset($_GET["page"]) && $_GET["page"] == "Pending"){
            $query=" AND RegStatus = 0 ";
        }
        // Select All Users Except Admin by function
        $rows = getAllFrom("*", "users", "WHERE GroupID != 1", $query, "UserID","DESC");

        if (! empty($rows)){
            ?>
            <h1 class="text-center">Manage Members</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table manage-members text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Avatar</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Phone</td>
                            <td>First Name</td>
                            <td>Last Name</td>
                            <td>Registered Date</td>
                            <td>Control</td>
                        </tr>
                        <?php
                        foreach ($rows as $row) {
                            echo "<tr>";
                            echo "<td>" . $row['UserID'] . "</td>";
                            echo "<td>";
                            if (empty($row["Avatar"])){
                                echo "<img src='uploads/avatars/johnDoe.png' alt='johnDoe'>";
                            }else {
                                echo "<img src='uploads/avatars/" . $row['Avatar'] . "' alt=''>";
                            }
                            echo "</td>";
                            echo "<td>" . $row['Username'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . $row['Phone'] . "</td>";
                            if (!empty($row['First_N'])){echo "<td>" . $row['First_N'] . "</td>";}else{echo "<td>No!</td>";}
                            if (!empty($row['Last_N'])){echo "<td>" . $row['Last_N'] . "</td>";}else{echo "<td>No!";}
                            echo "<td>" . $row['Date'] . "</td>";
                            echo "<td>
                                        <a href='members.php?do=Edit&userid=" . $row['UserID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                        <a href='members.php?do=Delete&userid=" . $row['UserID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";

                            if ($row['RegStatus'] == 0) {
                                echo " 
									<a href='members.php?do=Activate&userid=" . $row['UserID'] . "' 
									class='btn btn-info activate'>
									<i class='fa fa-check'></i> Activate</a>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
                </div>
                <a href="members.php?do=Add" class="btn btn-primary">
                    <i class="fa fa-plus"></i> New Member
                </a>
            </div>
        <?php } else {
            echo '
                <div class="bs-calltoaction bs-calltoaction-info">
                    <div class="row">
                        <div class="col-md-9 cta-contents">
                            <h1 class="cta-title">There\'s No Members To Show</h1>
                        </div>
                        <div class="col-md-3 cta-button">
                            <a href="members.php?do=Add" class="btn btn-lg btn-block btn-info">New Member</a>
                        </div>
                     </div>
                </div>
            ';
        } ?>

        <?
    } elseif ($do == "Edit") {
        if (isset($_GET["userid"]) && is_numeric($_GET["userid"])) {// OR => $userid=isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0;
            $userid = intval($_GET["userid"]);
        } else {
            $userid = 0;
        }
        $stmt = $con->prepare("SELECT * FROM users  WHERE UserID = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) {

            //Edit page
//    echo "welcome to Edit page and your ID is : ".$_GET["userid"];// or $_SESSION["ID"]
            ?>
            <!-- if there's such id show this form -->
            <h1 class="text-center">Edit Members</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="userid" value="<? echo $userid;?>">
                    <!--  Start username field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Username</label>
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
                        <label class="col-sm-2 control-label">Password</label>
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
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                    type="email"
                                    name="email"
                                    value="<? echo $row["Email"]?>"
                                    class="form-control"
                                    pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"
                                    maxlength="50"
                                    autocomplete="off"
                                    required="required"><!-- validations of the html  -->
                        </div>
                    </div>
                    <!--  End email field-->
                    <!--  Start phone field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Phone</label>
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
                        <label class="col-sm-2 control-label">First Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                    type="text"
                                    name="first_n"
                                    value="<? if (empty($row["First_N"])){echo 'No!';}else{ echo $row["First_N"];}?>"
                                    class="form-control"
                                    pattern="(?=.*[a-z]).{3,}"
                                    title="you must contain Only letters and more than 10 letters"
                                    maxlength="30" autocomplete="off">
                            <!-- validations of the html  -->
                        </div>
                    </div>
                    <!--  End First Name field-->
                    <!--  Start First Name field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Last Name</label>
                        <div class="col-sm-10 col-md-4">
                            <input
                                    type="text"
                                    name="last_n"
                                    value="<? if (empty($row["Last_N"])){echo 'No!';}else{ echo $row["Last_N"];}?>"
                                    class="form-control"
                                    pattern="(?=.*[a-z]).{3,}"
                                    title="you must contain Only letters and more than 10 letters"
                                    maxlength="30" autocomplete="off" >
                            <!-- validations of the html  -->
                        </div>
                    </div>
                    <!--  End First Name field-->
                    <!--  Start Avatar field-->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Avatar</label>
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
            // if there's no such id show this message error
        } else {
            $theMsg= '<div class="container"><div class="alert alert-danger">this wrong your are not member here</div></div>';
            redirectHome($theMsg);
        }
    } elseif ($do == "Update") {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "<h1 class=\"text-center\">Update Members</h1>";
            echo "<div class='container''>";
            // Upload Variables
            $avatarName = $_FILES["avatar"]["name"];
            $avatarSize = $_FILES["avatar"]["size"];
            $avatarTmpName = $_FILES["avatar"]["tmp_name"];
            $avatarType = $_FILES["avatar"]["type"];

            // List of allowed file typed to upload
            $avatarAllowedExtension = array("jpeg","jpg","png","gif");

            // Get avatar extension
            $avatarExtension = strtolower(end(explode(".",$avatarName)));
            //Get variables from the FORM
            $id = $_POST["userid"];
            // Filter Name
            $user = trim($_POST['username']);
            $user = strip_tags($user);
            $user = filter_var($user, FILTER_SANITIZE_STRING);
            // Filter Email
            $email = trim($_POST['email']);
            $email = strip_tags($email);
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            // Filter First Name
            $first_n = trim($_POST['first_n']);
            $first_n = strip_tags($first_n);
            $first_n = filter_var($first_n, FILTER_SANITIZE_STRING);
            // Filter Last Name
            $last_n = trim($_POST['last_n']);
            $last_n = strip_tags($last_n);
            $last_n = filter_var($last_n, FILTER_SANITIZE_STRING);
            // Filter Phone
            $phone = trim($_POST['phone']);
            $phone = strip_tags($phone);
            $phone = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
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

            if (empty($first_n)) {
                $formErrors[] = 'First Name Cant Be <strong>Empty</strong>';
            }
            if (empty($last_n)) {
                $formErrors[] = 'Last Name Cant Be <strong>Empty</strong>';
            }

            if (empty($email)) {
                $formErrors[] = 'Email Cant Be <strong>Empty</strong>';
            }
            // avatar formError condition
            if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
                $formErrors[] = 'This Extension IS Not  <strong>Allowed</strong>';
            }
            if ( $avatarSize > 4194304 ) {
                $formErrors[] = 'Image Can\'t Be Larger Than <strong>4MB</strong>';
            }
            if (strpos($avatarName, '.php') !== false)  {
                $formErrors[] = 'Must Name Of Avatar Not Contains <strong>.php</strong>';
            }
            // Loop Into Errors Array And Echo It
            foreach ($formErrors as $error) {
                echo '<div class="container"><div class="alert alert-danger">' . $error . '</div></div>';
            }
            //check do you fiels is empty !!!
            if (empty($error)) {
                // Naming Avatar For Database
                $avatar = rand(0,10000000000)."_".$avatarName;

                move_uploaded_file($avatarTmpName,'uploads/avatars/'.$avatar);

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
                    if (!empty($avatarName)) {
                        // Update The Database With This Info

                  $stmt = $con->prepare("UPDATE users SET 
                                                                    Username = ?, 
                                                                    Password = ?, 
                                                                    Email = ?, 
                                                                    Phone = ?, 
                                                                    First_N = ?, 
                                                                    Last_N = ?, 
                                                                    Avatar = ?,
                                                                    Country = ?,
                                                                    City = ?,
                                                                    Address1 = ?,
                                                                    Address2 = ?,
                                                                    Zip = ?
                                                                    WHERE UserID= ?");

                        $stmt->execute(array($user,$pass, $email, $phone, $first_n, $last_n, $avatar, $country, $city, $address1, $address2, $zip, $id));
                    }else{
                    // Update The Database With This Info

                        $stmt = $con->prepare("UPDATE users SET 
                                                                          Username = ?, 
                                                                          Password = ?, 
                                                                          Email = ?, 
                                                                          Phone = ?, 
                                                                          First_N = ?, 
                                                                          Last_N = ?,
                                                                          Country = ? ,
                                                                          City = ?,
                                                                          Address1 = ?,
                                                                          Address2 = ?,
                                                                          Zip = ? 
                                                                          WHERE UserID = ?");

                        $stmt->execute(array($user, $pass, $email, $phone, $first_n, $last_n, $country, $city, $address1, $address2, $zip, $id));
                    }
                        // Echo Success Message

                        $theMsg = "<div class='container'><div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div></div> ';

                        redirectHome($theMsg, 'back');


                }
            } else {
                echo "Check the above rules";
            }

        } else {
            $theMsg= "<div class='container'><div class='alert alert-danger'>So sory you can't browse this page directly</div></div> ";
            redirectHome($theMsg);
        }
        echo "</div>";
    } elseif ($do == 'Add') { // Add Page ?>

        <h1 class="text-center">Add New Member</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                <!-- Start Username Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10 col-md-6">
                        <input
                                type="text"
                                name="username"
                                class="form-control"
                                autocomplete="off"
                                required="required"
                                placeholder="Username To Login Into Shop"/>
                    </div>
                </div>
                <!-- End Username Field -->
                <!-- Start Password Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10 col-md-6">
                        <input
                                type="password"
                                name="password"
                                class="password form-control"
                                required="required"
                                autocomplete="new-password"
                                placeholder="Password Must Be Hard & Complex"/>
                        <i class="show-pass fa fa-eye fa-2x"></i>
                    </div>
                </div>
                <!-- End Password Field -->
                <!-- Start Email Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Email</label>
                    <div class="col-sm-10 col-md-6">
                        <input
                                type="email"
                                name="email"
                                class="form-control"
                                required="required"
                                placeholder="Email Must Be Valid"/>
                    </div>
                </div>
                <!-- End Email Field -->
                <!-- Start Phone Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Phone</label>
                    <div class="col-sm-10 col-md-6">
                        <input
                                type="number"
                                name="phone"
                                class="form-control"
                                required="required"
                                placeholder="Your Number Phone"/>
                    </div>
                </div>
                <!-- End Phone Field -->
                <!-- Start First Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">First Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input
                                type="text"
                                name="first_n"
                                class="form-control"
                                required="required"
                                placeholder="First Name Appear In Your Profile Page"/>
                    </div>
                </div>
                <!-- End First Name Field -->
                <!-- Start First Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Last Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input
                                type="text"
                                name="last_n"
                                class="form-control"
                                required="required"
                                placeholder="Last Name Appear In Your Profile Page"/>
                    </div>
                </div>
                <!-- End First Name Field -->
                <!-- Start Avatar Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Upload Image</label>
                    <div class="col-sm-10 col-md-6">
                        <input
                                type="file"
                                name="avatar"
                                class="form-control"
                                required="required"/>
                    </div>
                </div>
                <!-- End Avatar Field -->
                <!-- Start Submit Field -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input
                                type="submit"
                                value="Add Member"
                                class="btn btn-primary btn-lg"/>
                    </div>
                </div>
                <!-- End Submit Field -->
            </form>
        </div>

        <?

    }elseif ($do == 'Insert') {
        // Insert Member Page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Insert Member</h1>";
            echo "<div class='container'>";
            // Upload Variables
            $avatarName = $_FILES["avatar"]["name"];
            $avatarSize = $_FILES["avatar"]["size"];
            $avatarTmpName = $_FILES["avatar"]["tmp_name"];
            $avatarType = $_FILES["avatar"]["type"];

            // List of allowed file typed to upload
            $avatarAllowedExtension = array("jpeg","jpg","png","gif");

            // Get avatar extension
            $avatarExtension = strtolower(end(explode(".",$avatarName)));


            // Get Variables From The Form
            // Filter Username
            $user = trim($_POST['username']);
            $user = strip_tags($user);
            $user = filter_var($user, FILTER_SANITIZE_STRING);
            // Filter Password
            $pass = trim($_POST['password']);
            $pass = strip_tags($pass);
            $pass = filter_var($pass, FILTER_SANITIZE_STRING);
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
            $hashPass = sha1($pass);
            // Validate The Form
            $formErrors = array();
            if (strlen($user) < 4) {
                $formErrors[] = 'Username Can\'t Be Less Than <strong>4 Characters</strong>';
            }
            if (strlen($user) > 20) {
                $formErrors[] = 'Username Can\'t Be More Than <strong>20 Characters</strong>';
            }
            if (empty($user)) {
                $formErrors[] = 'Username Can\'t Be <strong>Empty</strong>';
            }
            if (empty($pass)) {
                $formErrors[] = 'Password Can\'t Be <strong>Empty</strong>';
            }
            if (empty($first_n)) {
                $formErrors[] = 'First Name Can\'t Be <strong>Empty</strong>';
            }
            if (empty($last_n)) {
                $formErrors[] = 'Last Name Can\'t Be <strong>Empty</strong>';
            }
            if (empty($email)) {
                $formErrors[] = 'Email Can\'t Be <strong>Empty</strong>';
            }
            if (empty($phone)) {
                $formErrors[] = 'Phone Can\'t Be <strong>Empty</strong>';
            }
            if (! empty($avatarName) && ! in_array($avatarExtension, $avatarAllowedExtension)) {
                $formErrors[] = 'This Extension IS Not  <strong>Allowed</strong>';
            }
            if (empty($avatarName)) {
                $formErrors[] = 'Image Is  <strong>Required</strong>';
            }
            if ( $avatarSize > 4194304 ) {
                $formErrors[] = 'Image Can\'t Be Larger Than <strong>4MB</strong>';
            }
            if (strpos($avatarName, '.php') !== false)  {
                $formErrors[] = 'Must Name Of Avatar Not Contains <strong>.php</strong>';
            }
            // Loop Into Errors Array And Echo It
            foreach($formErrors as $error) {
                echo '<div class="container"><div class="alert alert-danger">' . $error . '</div></div> ';
            }
            // Check If There's No Error Proceed The Update Operation
            if (empty($formErrors)) {
                // Naming Avatar For Database
                $avatar = rand(0,10000000000)."_".$avatarName;

                move_uploaded_file($avatarTmpName,'uploads/avatars/'.$avatar);


                // Check If User Exist in Database

                $check = checkItem("Username", "users", $user);

                if ($check == 1) {

                    $theMsg = '<div class="container"><div class="alert alert-danger">Sorry This User Is Exist</div></div>';

                    redirectHome($theMsg, 'back');

                } else {

                    // Insert Userinfo In Database

                    $stmt = $con->prepare("INSERT INTO 
													  users
													         (Username, Password, Email,  Phone, First_N, Last_N, RegStatus, Date, Avatar)
											          VALUES
												              (:zuser, :zpass, :zmail, :zphone , :zfirst_n, :zlast_n, 1, now(), :zavatar) 
										  ");
                    $stmt->execute(array(

                        'zuser'     => $user,
                        'zpass'     => $hashPass,
                        'zmail'     => $email,
                        'zphone'    => $phone,
                        'zfirst_n'  => $first_n,
                        'zlast_n'   => $last_n,
                        'zavatar'   => $avatar

                    ));

                    // Echo Success Message

                    $theMsg = "<div class='container'><div class='alert alert-success'>" . $stmt->rowCount() . ' Record Inserted</div></div>';

                    redirectHome($theMsg, 'back');

                }

            }
        } else {
            $theMsg = '<div class="container"><div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div></div>';
            redirectHome($theMsg);
        }
        echo "</div>";
    }elseif ($do == 'Delete') { // Delete Member Page

        echo "<h1 class='text-center'>Delete Member</h1>";
        echo "<div class='container'>";

        // Check If Get Request userid Is Numeric & Get The Integer Value Of It

        $userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

        // Select All Data Depend On This ID

        $check = checkItem('userid', 'users', $userid);

        // If There's Such ID Show The Form

        if ($check > 0) {

            $stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");

            $stmt->bindParam(":zuser", $userid);

            $stmt->execute();

            $theMsg = "<div class='container'><div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div></div>';

            redirectHome($theMsg, 'back');

        } else {

            $theMsg = '<div class="container"><div class="alert alert-danger">This ID is Not Exist</div></div>';

            redirectHome($theMsg);

        }

        echo '</div>';

    }elseif ($do == 'Activate') {

        echo "<h1 class='text-center'>Activate Member</h1>";
        echo "<div class='container'>";

        // Check If Get Request userid Is Numeric & Get The Integer Value Of It
        if (isset($_GET["userid"]) && is_numeric($_GET["userid"])) {// OR => $userid=isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0;
            $userid = intval($_GET["userid"]);
        } else {
            $userid = 0;
        }

        // Select All Data Depend On This ID

        $check = checkItem('userid', 'users', $userid);

        // If There's Such ID Show The Form

        if ($check > 0) {

            $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserID = ?");

            $stmt->execute(array($userid));

            $theMsg = "<div class='container'><div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div></div>';

            redirectHome($theMsg);

        } else {
            $theMsg = '<div class="container"><div class="alert alert-danger">This ID is Not Exist</div></div> ';

            redirectHome($theMsg);
        }
        echo '</div>';
    }
    include $tpl."footer.php";
} else {

    header('Location: index.php');

    exit();
}

ob_end_flush(); // Release The Output

?>
