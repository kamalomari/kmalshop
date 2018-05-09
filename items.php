<?php
ob_start();
session_start();
$pageTitle="Show Items";//for function getTitle (default)
$noSlider ="";
include 'init.php';

// Check If Get Request item Is Numeric & Get Its Integer Value
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

// Select All Data Depend On This ID
$stmt = $con->prepare("SELECT 
										items.*, 
										categories.Name AS category_name, 
										users.Username AS username,
										users.Phone AS phone
									FROM 
										items
									INNER JOIN 
										categories 
									ON 
										categories.ID = items.Cat_ID 
									INNER JOIN 
										users 
									ON 
										users.UserID = items.Member_ID
									WHERE 
									     Item_ID = ?
									AND 
									     Approve = 1");

// Execute Query
$stmt->execute(array($itemid));

$count = $stmt->rowCount();

if ($count > 0) {
    // Fetch The Data
    $item = $stmt->fetch();
    /* Start Page Items*/ ?>
<style>
    .item-box{
        display: block;
    }
</style>
    <h1 class="text-center"><? echo $item["Name"] ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3 item-box">
                <?php  echo empty($item["Image1"]) ? "<div><a href='admin/uploads/Images/new.png'>
                                                       <img class='img-rimg-responsive' src='admin/uploads/Images/new.png'>
                                                      </a></div>":"";
                ?>
                    <div>
                        <a href="admin/uploads/Images/<? echo $item["Image1"]; ?>">
                           <img class="img-responsive" src="admin/uploads/Images/<? echo $item["Image1"]; ?>" alt="" />
                        </a>
                    </div><br />
                <? if (!empty($item["Image2"])){ ?>
                    <div>
                        <a href="admin/uploads/Images/<? echo $item["Image2"]; ?>">
                          <img class="img-responsive" src="admin/uploads/Images/<? echo $item["Image2"]; ?>" alt="" />
                         </a>
                    </div>
           <? } ?>
            </div>
            <div class="col-md-9 item-info">
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Added Date</span> : <?php echo $item['Add_Date'] ?>
                    </li>
                    <li>
                        <i class="fa fa-money fa-fw"></i>
                        <span>Price</span> : $<?php echo $item['Price']." (".$item['Price']*10 ."MAD)" ?>
                    </li>
                    <li>
                        <i class="fa fa-paypal fa-fw"></i>
                        <span>Sales Tax</span> : <?php echo (($item['Price']*8)/100)."$ (".($item['Price']*8/100)*10 ."MAD)" ?>
                    </li>
                    <li>
                        <i class="fa fa-plane fa-fw"></i>
                        <span>Service Shop</span> : <?php echo (($item['Price']*5*0)/100)."$ (Free Shopping)" ?>
                    </li>
                    <li>
                        <i class="fa fa-pie-chart fa-fw"></i>
                        <span>Quantity</span> : <?php if ($item['Quantity'] != 0){ echo $item['Quantity'];}else{echo "This Item Is Expired, Wait Some Time For Fill Stock.";}?>
                    </li>
                    <li>
                        <i class="fa fa-building fa-fw"></i>
                        <span>Made In</span> : <?php echo $item['Country_Made'] ?>
                    </li>
                    <li>
                        <i class="fa fa-tags fa-fw"></i>
                        <span>Category</span> : <a href="categories.php?pageid=<?php echo $item['Cat_ID'] ?>"><?php echo $item['category_name'] ?></a>
                    </li>
                    <li>
                        <i class="fa fa-user fa-fw"></i>
                        <span>Added By</span> : <a href="profileOthers.php?userN=<? echo $item['username']; ?>"><?php echo $item['username']; ?></a>
                    </li>
                    <li>
                        <i class="fa fa-phone-square fa-fw"></i>
                        <span>Phone</span> : <?php echo empty($item['phone']) ? "phone not available!" : $item['phone']; ?>
                    </li>
                    <li>
                        <i class="fa fa-eye fa-fw"></i>
                        <span>Views</span> : <?php echo $item['count_views']; ?>
                        <?
                        $counterViews = $item['count_views']+1;
                        $stmt = $con->prepare("UPDATE items SET count_views = ? WHERE Item_ID = ?");

                        $stmt->execute(array($counterViews, $itemid));
                        ?>
                    </li>
                    <li class="tags-items">
                        <i class="fa fa-user fa-fw"></i>
                        <span>Tags</span> :
                        <?php
                        $allTags = explode(",", $item['tags']);
                        foreach ($allTags as $tag) {
                            $tag = str_replace(' ', '', $tag);
                            $lowertag = strtolower($tag);
                            if (! empty($tag)) {
                                echo "<a href='tags.php?name={$lowertag}'>" . $tag . '</a>';
                            }
                        }
                        ?>
                    </li>
                </ul>
                <div class="panel panel-default">
                    <div class="panel-heading">Description Item : <?php echo $item['Name'] ?></div>
                    <div class="panel-body text-info"><?php echo $item['Description'] ?></div>
                </div>
                <?php
                $yourUserid = $item["Member_ID"];
                $stmt=$con->prepare("SELECT Username FROM `users` WHERE UserID = $yourUserid");
                $stmt->execute();
                $otherUser=$stmt->fetch();
                if (isset($_SESSION['user'])){
                if ($_SESSION['user'] === $otherUser['Username']) {
                    ?>
                    <a
                            href="editItem.php?do=EditItem&itemid=<? echo $item["Item_ID"] ?>"
                            class="btn btn-success"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="This for edit your item.">Edit Item
                    </a>
                    <a
                            href="editItem.php?do=DeleteItem&itemid=<? echo $item["Item_ID"] ?>"
                            class="btn btn-danger confirm"
                            data-toggle="tooltip"
                            data-placement="top"
                            title="This button will delete your item!">Delete Item
                    </a><!-- tooltip bootstrap -->
                    <style>
                        .submit_paypal{
                            margin-top: 10px;
                        }
                        .not_submit_paypal{
                            margin-top: 10px;
                        }
                    </style>
                    <!--======================================-->
                    <!-- Start form paypal to checkout online -->
                    <?
                }
                    if ($_SESSION['user'] != $otherUser['Username']){
                    ?>
                    <? if ($item["Paypal_Ac"] != "notPaypal" && !empty($item["Paypal_Ac"])){
                        if ($item['Quantity'] != 0){ ?>
                            <!--no scrpt sbmt three -->
                            <noscript  id="barNoScript" style="display: inline-block;"><code>Please Allow Javascript</code></noscript>
                    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" id="frmH">
                        <!-- Identify your business so that you can collect the payments. -->
                        <input type="hidden" name="business" value="kamal12345@shop.com"><!-- if dynamic transations echo $item["Paypal_Ac"] -->

                        <!-- Specify a Buy Now button. -->
                        <input type="hidden" name="cmd" value="_xclick">

                        <!-- Specify details about the item that buyers will purchase. -->
                        <input type="hidden" name="item_name" value="<? echo $item["Name"] ?>">
                        <input type="hidden" name="item_number" value="<? echo $item["Item_ID"] ?>">
                        <input type="hidden" name="amount" value="<? echo $item["Price"] ?>">
                        <input type="hidden" name="currency_code" value="USD">
                        <!-- Prompt buyers to enter the quantities they want. -->
<!--                        <input type="hidden" name="undefined_quantity" value="1"> if want to choice wuantity with paypal put this rather than below-->
                        <!-- Prompt buyers to enter the quantities they want. -->
                        <label>Quantitiy :</label>
                        <input
                                type="number"
                                name="quantity"
                                id="qnty"
                                value=""
                                required
                                autocomplete="off"
                                min="1"
                                max="<? echo $item["Quantity"]; ?>"
                                onKeyUp="if(this.value><? echo $item["Quantity"];?>){this.value='<? echo $item["Quantity"];?>';}else if(this.value<0){this.value='1';}"
                                style="display: inline-block;margin-top: 10px;background: white"
                        >  <? echo $item["Quantity"]; ?> available
                        <style>
                            .asterisk{
                                display: none;
                            }
                        </style>

                        <input type="hidden"
                               name="return"
                               value="http://127.0.0.1/php/Ecommerces/My-Kamal/checkout.php?do=success&itemid=<? echo $itemid;?>" />
                        <input type="hidden"
                               name="cancel_return"
                               value="http://127.0.0.1/php/Ecommerces/My-Kamal/checkout.php?do=cancel&itemid=<? echo $itemid;?>" />

                        <!-- Display the payment button. -->
                        <input type="image"
                               name="submit" border="0"
                               style="display: block"
                               class="submit_paypal"
                               id="sbtPP"
                               src="paypal.png"
                               alt="Buy Now">
                        <img border="0" width="1" height="1"
                             src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif"
                             alt="PayPal - The safer, easier way to pay online" >
                    </form>
                        <label class="alert alert-info">first of all you must add real address shopping in your paypal for send item without problem.</label>
                    <?}else{
                        echo "<div class='alert alert-warning not_submit_paypal'>This Item Is Expired To Buy It Online.</div>";
                    }
                    //End form paypal to checkout online
                    //==================================
                     }else{
                            echo "<div class='alert alert-warning not_submit_paypal'>This Product Not Pay With Paypal.</div>";
                        }
                                             }//end check checkout for owner
                   }//end for isset session user
                   ?>
            </div>
        </div>
        <hr class="custom-hr">
    <?php if (isset($_SESSION['user'])) { ?>
        <!--        Start add comment-->
        <div class="row">
            <div class="col-md-offset-3">
                    <div class="add-comment">
                        <h3>Add your comment</h3>
                        <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['Item_ID'] ?>" method="POST">
                            <textarea name="comment" required></textarea>
                            <input class="btn btn-primary" type="submit" value="Add comment">
                        </form>
                    </div>
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    $comment 	= filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                    $itemid 	= $item['Item_ID'];
                    $userid 	= $_SESSION['uid'];

                    if (! empty($comment)) {

                        $stmt = $con->prepare("INSERT INTO 
								comments(comment, status, comment_date, item_id, user_id)
								VALUES(:zcomment, 0, NOW(), :zitemid, :zuserid)");

                        $stmt->execute(array(

                            'zcomment' => $comment,
                            'zitemid' => $itemid,
                            'zuserid' => $userid

                        ));

                        if ($stmt) {

                            echo '<br /><div class="alert alert-success">Comment Added</div>';

                        }

                    } else {

                        echo '<br /><div class="alert alert-danger">You Must Add Comment</div>';

                    }

                }
                ?>
            </div>
        </div>
        <!--        End add comment-->
    <?php }else {
        echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> To Add Comment';
    } ?>
        <hr class="custom-hr">
        <?php

        // Select All Users Except Admin
        $stmt = $con->prepare("SELECT 
										comments.*, users.Username AS Member,users.Avatar  AS Avatar
									FROM 
										comments
									INNER JOIN 
										users 
									ON 
										users.UserID = comments.user_id
									WHERE 
										item_id = ?
									AND 
										status = 1
									ORDER BY 
										c_id DESC");

        // Execute The Statement

        $stmt->execute(array($item['Item_ID']));

        // Assign To Variable

        $comments = $stmt->fetchAll();

        ?>
        <?php foreach ($comments as $comment) { ?>
            <div class="comment-box">
                <div class="row">
                    <div class="col-sm-2 text-center">
                        <?php
                        if (empty($comment["Avatar"])){?>
                            <img class='img-responsive img-thumbnail img-circle center-block' src='admin/uploads/avatars/johnDoe.png' alt="" />
                        <? }else{?>
                            <img class='img-responsive img-thumbnail img-circle center-block' src='admin/uploads/avatars/<? echo $comment['Avatar']; ?>' alt='' />
                        <? } ?>
                        <?php echo $comment['Member'] ?>
                    </div>
                    <div class="col-sm-10">
                        <p class="lead"><?php echo $comment['comment'] ?></p>
                    </div>
                </div>
            </div>
            <hr class="custom-hr">
        <?php } ?>

    </div>
    <!--no scrpt sbmt four -->
    <noscript>
        <style>
            #sbtPP{opacity: .5;pointer-events: none;}
        </style><!-- bideH belemE tormF -->
    </noscript>
    <script>
        // this for block button if U know value code go to :
        // https://msdn.microsoft.com/en-us/library/aa243025(v=vs.60).aspx
        //    $(document).keydown(function(e){
        //        if(e.which === 123){// f1s2
        //            return false;
        //        }
        //        for (var i=65;i<=90;i++){ // a to z except r for reload page
        //            if(e.which === i){
        //                if (i === 82) continue;
        //                return false;
        //            }
        //        }
        //    });
        //    $(document).bind("contextmenu",function(e) {// preven clickRight
        //        e.preventDefault();
        //    });
    </script>
    <?/* End Page Items*/
}else{
    $thMsg = '<div class="container"><div class="alert alert-danger">There\'s no Such ID Or This Item Is Waiting Approval</div></div>';
    redirectHome($thMsg,"back");
}
include $tpl.'footer.php';
ob_end_flush();
?>
