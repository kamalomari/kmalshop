<?php
ob_start();
session_start();
$pageTitle="Profile";//for function getTitle (default)
$noSlider="";
include 'init.php';
if (isset($_SESSION['user'])){
    $getUser = $con->prepare("SELECT UserID,Username,First_N,Last_N,Email,VerifyEmail,Phone,VerifyPhone,Date,Avatar FROM users WHERE Username = ?");
    $getUser->execute(array($sessionUser));
    $info = $getUser->fetch();
    $userid = $info['UserID'];
?>
    <div class="container">
        <h1 class="text-center">My Profile</h1>
        <style>
            .img-thumbnail{
                margin-left: 50px
            }
            .item-box{
                display: block;
            }
        </style>
        <div class=" col-lg-offset-4 col-lg-3">
            <a href="admin/uploads/avatars/<? echo $info["Avatar"]; ?>">
                <?php
                if (empty($info["Avatar"])){?>
                    <a href="admin/uploads/avatars/johnDoe.png">
                        <img class="imgAvtr" style="display: block;margin: auto;width: 300px;max-width: 300px" class='img-responsive img-thumbnail' src='admin/uploads/avatars/johnDoe.png' alt='johnDoe' />
                    </a>
               <? }else{?>
                    <a href="admin/uploads/avatars/<? echo $info['Avatar']; ?>">
                        <img class="imgAvtr" style="display: block;margin: auto;width: 300px;max-width: 300px" class='img-responsive img-thumbnail' src='admin/uploads/avatars/<? echo $info['Avatar']; ?>' alt='' />
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
                My Information
            </div>
            <div class="panel-body">
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-unlock-alt fa-fw"></i>
                        <span>Login Name</span> : <?php echo $info['Username'] ?>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>First Name</span> : <?php if (empty($info['First_N'])){echo "There IS No First Name";}else{echo $info['First_N'];} ?>
                    </li>
                    <li>
                        <i class="fa fa-user-plus fa-fw"></i>
                        <span>Last Name</span> : <?php if (empty($info['Last_N'])){echo "There IS No Last Name";}else{echo $info['Last_N'];} ?>
                    </li>
                    <li>
                        <i class="fa fa-envelope-o fa-fw"></i>
                        <span>Email</span> : <?php echo $info['Email'] ?>
                        <? if ($info['VerifyEmail'] === "1"){ ?>
                            <strong class="verifyEmail"><i class="fa fa-check-circle"></i> verify</strong>
                        <? }else{ ?>
                            <a
                                    href="verify.php?do=verifyEmail"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="This For Verify Email">
                                <strong class="noVerifyEmail">
                                    <i class="fa fa-times-circle"></i> not verify
                                </strong>
                            </a>
                        <? } ?>
                    </li>
                    <li>
                        <i class="fa fa-phone fa-fw"></i>
                        <span>Phone</span> : <?php echo $info['Phone'] ?>
                        <? if ($info['VerifyPhone'] === "1"){ ?>
                            <strong class="verifyPhone"><i class="fa fa-check-circle"></i> verify</strong>
                        <? }else{ ?>
                            <a
                                    href="verify.php?do=verifyPhone"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="This For Verify Phone">
                                <strong class="noVerifyPhone">
                                    <i class="fa fa-times-circle"></i> not verify
                                </strong>
                            </a>
                        <? } ?>
                    </li>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Registered Date</span> : <?php echo $info['Date'] ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Fav Category</span> :
                    </li>
                </ul>
                <?
                $encryptedUserid = my_simple_crypt($info["UserID"] , 'e' );
                ?>
                <a href="edit.php?do=Edit&userid=<? echo $encryptedUserid; ?>" class="btn btn-default">Edit Information</a>
            </div>
        </div>
    </div>
</div>
<div id="my-ad" class="my-adss block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                My Advertisements
            </div>
            <div class="panel-body">
                <?php
                $myItems = getAllFrom("*", "items", "where Member_ID = $userid", "", "Item_ID");
                if (! empty($myItems)) {
                    echo '<div class="row">';
                            foreach ($myItems as $item) {
                                echo '<div class="col-sm-6 col-md-3">';
                                    echo '<div class="thumbnail item-box">';
                                        if ($item['Approve'] == 0) {
                                            echo '<span class="approve-status">Waiting Approval</span>';
                                        }
                                        echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                                if (empty($item["Image1"])){
                                    echo "<div><a href='admin/uploads/Images/new.png'><img class='img-responsive'  src='admin/uploads/Images/new.png' alt='New Item'></a></div>";
                                }else {
                                    echo "<div><a href='admin/uploads/Images/". $item['Image1']."'><img class='img-responsive'  src='admin/uploads/Images/" . $item['Image1'] . "' alt='".$item["Name"]."'></a></div>";
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
                    echo 'Sorry There\' No Ads To Show, Create <a href="newad.php">New Ad</a>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<!--    Start Purchases-->
    <div id="my-ad" class="my-adss block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    My Purchases
                </div>
                <div class="panel-body">
                    <?php
                    $sessionUser = $_SESSION['user'];

//                  // fetch all and put ot in foreach to show my purchases
                    $stmtPro = $con->prepare("SELECT  *
                                                         FROM   `items`,`payments`
                                                         WHERE   payments.User_pay = '$sessionUser'
                                                         AND     items.Item_ID = payments.Id_product ");
                    $stmtPro->execute();
                    $infoPaymentItem = $stmtPro->fetchAll();// we have write fetchAll for working good foreach
                    if (!empty($infoPaymentItem)) {
                        echo '<div class="row">';
                        foreach ($infoPaymentItem as $pays) {
                                echo '<div class="col-sm-6 col-md-3">';
                                    echo '<div class="thumbnail item-box">';
                                        echo '<span class="price-tag">$' . $pays['Price'] . '</span>';
                                        if (empty($pays["Image1"])){
                                            echo "<div><a href='admin/uploads/Images/new.png'>
                                                       <img class='img-responsive'  src='admin/uploads/Images/new.png' alt='New Item'></a></div>";
                                        }else{
                                            echo "<div><a href='admin/uploads/Images/". $pays['Image1']."'>
                                                    <img class='img-responsive'  src='admin/uploads/Images/" . $pays['Image1'] . "' alt='".$pays["Name"]."'></a></div>";
                                        }
                                        echo '<div class="caption">';
                                            echo '<h3><a href="purchases.php?idtranspyl='.$pays['Id_trans_paypal'].'">' . $pays['Name_product'] .'</a></h3>';
                                            echo '<p>' . $pays['Description'] . '</p>';
                                            echo '<div class="date">' . $pays['Date'] . '</div>';
                                            echo '<div class="date">Quantity : <mark>' . $pays['Quantity'] . '</mark></div>';
                                        echo '</div>';
                                    echo '</div>';
                                echo '</div>';
                        }
                        echo '</div>';
                    }else {
                        echo 'Sorry There\' No Purchases To Show.';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<!--    End Purchases-->
    <!--    Start Sales-->
    <div id="my-ad" class="my-adss block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    My Sales
                </div>
                <div class="panel-body">
                    <?php
                    $sessionUser = $_SESSION['user'];

                    //                  // fetch all and put ot in foreach to show my purchases
                    $stmtPro = $con->prepare("SELECT  *
                                                         FROM   `items`,`payments`
                                                         WHERE   payments.User_sell = '$sessionUser'
                                                         AND     items.Item_ID = payments.Id_product ");
                    $stmtPro->execute();
                    $infoPaymentItem = $stmtPro->fetchAll();// we have write fetchAll for working good foreach
                    if (!empty($infoPaymentItem)) {
                        echo '<div class="row">';
                        foreach ($infoPaymentItem as $pays) {
                            echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="thumbnail item-box">';
                            echo '<span class="price-tag">$' . $pays['Price'] . '</span>';
                            if (empty($pays["Image1"])){
                                echo "<div><a href='admin/uploads/Images/new.png'>
                                                       <img class='img-responsive'  src='admin/uploads/Images/new.png' alt='New Item'></a></div>";
                            }else{
                                echo "<div><a href='admin/uploads/Images/". $pays['Image1']."'>
                                                    <img class='img-responsive'  src='admin/uploads/Images/" . $pays['Image1'] . "' alt='".$pays["Name"]."'></a></div>";
                            }
                            echo '<div class="caption">';
                            echo '<h3><a href="sales.php?idtranspyl='.$pays['Id_trans_paypal'].'">' . $pays['Name_product'] .'</a></h3>';
                            echo '<p>' . $pays['Description'] . '</p>';
                            echo '<div class="date">' . $pays['Date'] . '</div>';
                            echo '<div class="date">Quantity : <mark>' . $pays['Quantity'] . '</mark></div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '</div>';
                    }else {
                        echo 'Sorry There\' No Sales To Show.';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!--    End Sales-->
<div class="my-Comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">
                My Comments
            </div>
            <div class="panel-body">
                <?php
                $myComments = getAllFrom("comment", "comments", "where user_id = $userid", "", "c_id");
                if (! empty($myComments)) {
                    foreach ($myComments as $comment) {
                        echo '<p><i class="fa fa-commenting"></i> : ' . $comment['comment'] . '</p>';
                    }
                } else {
                    echo 'There\'s No Comments to Show';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
}else{
    header("Location: login.php");

    exit();
}
include $tpl.'footer.php';
ob_end_flush(); // Release The Output

?>