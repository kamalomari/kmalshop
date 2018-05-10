<?php
ob_start();
session_start();
$pageTitle="Dashboard";
if (isset($_SESSION["Username"])){
    include "init.php";
    $numUsers=4;//Number of latest users
    $latestUsers=getLatest("*","users", "UserID", $numUsers);//Latest Users Array
    $numItems=4;//Number of latest users
    $latestItems=getLatest("*","items", "Item_ID", $numItems);//Latest Items Array
    $numComments=6;
    /*=========================*/
    /* Start Dashboard Content */
    /*=========================*/
?>
    <div class="container home-stat text-center">
        <h1>Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    <i class="fa fa-users"></i>
                    <div class="info">
                    Total Members
                <span><a href="members.php"><? echo countItems("UserID","users");?></a></span>
                </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pending">
                    <i class="fa fa-user-plus"></i>
               <div class="info">
                   Pending Members
                   <span>
                        <a href="members.php?do=Manage&page=Pending">
                                                    <? echo checkItem("RegStatus","users",0);?>
                        </a>
                    </span>
               </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    <i class="fa fa-tag"></i>
                    <div class="info">
                        Total Items
                        <span><a href="items.php"><? echo countItems("Item_ID","items");?></a></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                    <i class="fa fa-comment"></i>
                   <div class="info">
                       Total Comments
                       <span><a href="comments.php"><? echo countItems("c_id","comments");?></a></span>
                   </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container latest">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-user"></i>
                        Latest <? echo $numUsers;?> Registerd Users
                    <span class="toggle-info pull-right">
                        <i class="fa fa-plus fa-lg"></i>
                    </span>
                    </div>
                    <div class="panel-body latest-users">
                        <?
                        if (! empty($latestUsers)) {
                            foreach ($latestUsers as $user) {
                                echo '<div class="panel-body">' . $user["Username"] .
                                    '<a href="members.php?do=Edit&userid=' . $user["UserID"] . '">
                                <span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit';
                                if ($user['RegStatus'] == 0) {//for fetch activation of users same a page Manage
                                    echo "
									<a href='members.php?do=Activate&userid=" . $user['UserID'] . "'
									   class='btn btn-info activate pull-right'>
									<i class='fa fa-check'></i> Activate</a>";
                                }
                                echo '</span></a></div>';
                            }
                        }else{
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
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-tag"></i> Latest <? echo $numItems ?> Items
                        <span class="toggle-info pull-right">
                        <i class="fa fa-plus fa-lg"></i>
                    </span>
                    </div>
                    <div class="panel-body latest-users">
                        <?
                        if (! empty($latestItems)) {
                            foreach ($latestItems as $item) {
                                echo '<div class="panel-body">' . $item["Name"] .
                                    '<a href="items.php?do=Edit&itemid=' . $item["Item_ID"] . '">
                                <span class="btn btn-success pull-right"><i class="fa fa-edit"></i> Edit';
                                if ($item['Approve'] == 0) {//for fetch activation of users same a page Manage
                                    echo "
									<a href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "'
									   class='btn btn-info activate pull-right'>
									<i class='fa fa-check'></i> Approve</a>";
                                }
                                echo '</span></a></div>';
                            }
                        }else{
                            echo '
                             <div class="bs-calltoaction bs-calltoaction-info">
                                <div class="row">
                                    <div class="col-md-9 cta-contents">
                                        <h1 class="cta-title">There\'s No Items To Show</h1>
                                    </div>
                                    <div class="col-md-3 cta-button">
                                        <a href="items.php?do=Add" class="btn btn-lg btn-block btn-info">New Item</a>
                                    </div>
                                 </div>
                             </div>
                             ';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start Latst Comments -->
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-comment-o"></i>
                        Latest <? echo $numComments ?> Comments
                        <span class="toggle-info pull-right">
                        <i class="fa fa-plus fa-lg"></i>
                    </span>
                    </div>
                    <div class="panel-body">
                        <?php
                        $stmt = $con->prepare("SELECT 
										comments.*, users.Username AS Member  
									FROM 
										comments
									INNER JOIN 
										users 
									ON 
										users.UserID = comments.user_id
                                    ORDER BY 
                                        c_id DESC 
                                    LIMIT $numComments");

                        $stmt->execute();
                        $comments = $stmt->fetchAll();
                        if (! empty($comments)){
                        foreach ($comments as $comment){
                            echo "<div class='comment-box'>";
                                    echo "<span class='member-n'><a href='members.php?do=Edit&userid=".$comment["user_id"]."'>".$comment['Member']."</a></span>";
                                    echo "<p class='member-c'>".$comment['comment']."</p>";
                            echo "</div>";
                        }
                        }else{
                            echo '
                               <div class="bs-calltoaction bs-calltoaction-info">
                                <div class="row">
                                    <div class="col-md-9 cta-contents">
                                        <h1 class="cta-title">There\'s No Comments To Show</h1>
                                    </div>
                                    <div class="col-md-3 cta-button">
                                        <a href="comments.php?do=Add" class="btn btn-lg btn-block btn-info">New Comment</a>
                                    </div>
                                 </div>
                             </div>
                                ';
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

<?
    /*=========================*/
    /*  End Dashboard Content  */
    /*=========================*/
    include $tpl."footer.php";
}else{
    header("Location: index.php");
    exit();
}
ob_end_flush(); // Release The Output
?>