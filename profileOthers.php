<?php
ob_start(); // Output Buffering Start
session_start();
$pageTitle="Profile Person";//for function getTitle (default)
$noSlider="";
include 'init.php';
//if (isset($_SESSION['user'])){
//    $getUser = $con->prepare("SELECT * FROM users WHERE Username = ?");
//    $getUser->execute(array($sessionUser));
//    $info = $getUser->fetch();
//    $userid = $info['UserID'];
if (isset($_GET['userN']) && !empty($_GET['userN'])) {
    $userN = $_GET['userN'];
    $stmt=$con->prepare("SELECT UserID,Username,Email,VerifyEmail,Phone,VerifyPhone,First_N,Last_N,Date,Avatar FROM `users` WHERE Username = '$userN'");
    $stmt->execute();
    $allUser=$stmt->fetch();
    $yourUserid = $allUser["UserID"];
    ?>
    <div class="container">
        <h1 class="text-center"><? echo $allUser['Username']; ?></h1>
        <style>
            .img-thumbnail{
                margin-left: 50px
            }
        </style>
        <div class=" col-lg-offset-4 col-lg-3">
            <a href="admin/uploads/avatars/<? echo $allUser["Avatar"]; ?>">
                <?php
                if (empty($allUser["Avatar"])){?>
                    <a href="admin/uploads/avatars/johnDoe.png">
                        <img style="display: block;margin: auto;width: 300px;max-width: 300px" class='img-responsive img-thumbnail' src='admin/uploads/avatars/johnDoe.png' alt='johnDoe' />
                    </a>
                <? }else{?>
                    <a href="admin/uploads/avatars/<? echo $allUser['Avatar']; ?>">
                        <img style="display: block;margin: auto;width: 300px;max-width: 300px" class='img-responsive img-thumbnail' src='admin/uploads/avatars/<? echo $allUser['Avatar']; ?>' alt='' />
                    </a>
                <? }
                ?>
            </a>
        </div>
    </div>
    <div class="information block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Information [<? echo $allUser['Username'];?>]
                </div>
                <div class="panel-body">
                    <ul class="list-unstyled">
                        <li>
                            <i class="fa fa-unlock-alt fa-fw"></i>
                            <span>Login Name</span> : <?php echo $allUser['Username']; ?>
                        </li>
                        <li>
                            <div class="container">
                                <div class="row">
                                    <i class="fa fa-envelope-o fa-fw"></i>
                                    <span>Email</span> : <?php echo $allUser['Email'] ?>
                                    <? if ($allUser['VerifyEmail'] === "1"){ ?>
                                        <span class="verifyEmailOthers"><i class="fa fa-check-circle"></i> verify</span>
                                    <? }else{ ?>
                                        <span class="noVerifyEmailOthers">
                                            <i class="fa fa-times-circle"></i> not verify
                                        </span>
                                    <? } ?>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="block">
                            <i class="fa fa-phone fa-fw"></i>
                                <span>Phone</span> : <? if (!empty($allUser['Phone'])){echo "<a href='tel:".$allUser['Phone']."'>".$allUser['Phone']."</a>";}else{echo "Not Available!";} ?>
                                <? if ($allUser['VerifyPhone'] === "1"){ ?>
                                         <span class="verifyPhoneOthers"><i class="fa fa-check-circle"></i> verify</span>
                                <? }else{ ?>
                                <span class="noVerifyPhoneOthers">
                                    <i class="fa fa-times-circle"></i> not verify
                                </span>
                                <? } ?>
                            </div>
                        </li>
                        <li>
                            <i class="fa fa-user fa-fw"></i>
                            <span>First Name</span> : <?php echo $allUser['First_N'] ?>
                        </li>
                        <li>
                            <i class="fa fa-user-plus fa-fw"></i>
                            <span>Last Name</span> : <?php echo $allUser['Last_N'] ?>
                        </li>
                        <li>
                            <i class="fa fa-calendar fa-fw"></i>
                            <span>Registered Date</span> : <?php echo $allUser['Date'] ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="my-ad" class="my-adss block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    Advertisements [<? echo $allUser['Username']; ?>]
                </div>
                <div class="panel-body">
                    <?php
                    $stmt=$con->prepare("SELECT * FROM `items` WHERE Member_ID = $yourUserid AND Approve = 1 ORDER BY Item_ID");
                    $stmt->execute();
                    $yourItems=$stmt->fetchAll();
                    if (! empty($yourItems)) {
                        echo '<div class="row">';
                        foreach ($yourItems as $item) {
                            echo '<div class="col-sm-6 col-md-3">';
                                echo '<div class="thumbnail item-box">';
                                    echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                                    if (empty($item["Image1"])){
                                        echo "<div><a href='admin/uploads/Images/new.png'>
                                                   <img class='img-responsive'  src='admin/uploads/Images/new.png' alt='New Item'>
                                                </a></div>";
                                    }else {
                                        echo "<div><a href='admin/uploads/Images/". $item['Image1']."'>
                                                   <img class='img-responsive'  src='admin/uploads/Images/" . $item['Image1'] . "' alt='".$item["Image1"]."'>
                                               </a></div>";
                                    }
                                    echo '<div class="caption">';
                                    echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'">' . $item['Name'] .'</a></h3>';
                                    echo '<p>' . $item['Description'] . '</p>';
                                    echo '<div class="date">' . $item['Add_Date'] . '</div>';
                                    echo '</div>';
                                echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                    } else {
                        echo "<div class='container'><div class='alert alert-warning'>No sales available</div></div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?} else {
    $thMsg = "<div class='container'><div class='alert alert-warning'>You Must Add Username Of Seller</div></div>";
    redirectHome($thMsg);
    echo "<div style='margin-bottom: 400px'></div>";
}
    ?>

    <?php
//    header("Location: login.php");
//
//    exit();
include $tpl.'footer.php';
ob_end_flush(); // Release The Output
?>