<?php
/* same thing with item.php */
ob_start();
session_start();
$pageTitle="Order details sale";//for function getTitle (default)
$noSlider ="";
include 'init.php';

// Check If Get Request id trans paypal is viral
if (isset($_GET['idtranspyl'])):
    $idtranspyl=trim($_GET['idtranspyl']);
    $idtranspyl=strip_tags($idtranspyl);
    $idtranspyl=filter_var($idtranspyl, FILTER_SANITIZE_STRING);
else:
    $idtranspyl = "false";
endif;
// fetch Item informations
$stmtPro = $con->prepare(" SELECT  *
                                     FROM   `items`,`payments`
                                     WHERE   payments.User_sell = '$sessionUser'
                                     AND     items.Item_ID = payments.Id_product
                                     AND payments.Id_trans_paypal = '$idtranspyl' ");
$stmtPro->execute();
$item = $stmtPro->fetch();
?>
    <style>
        .item-box{
            display: block;
        }
    </style>
    <h1 class="text-center">Order details</h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3 item-box">
                <?php  echo empty($item["Image1"]) ? "<div><a href='admin/uploads/Images/new.png'>
                                                       <img class='img-rimg-responsive' src='admin/uploads/Images/new.png'>
                                                      </a></div>":"";
                ?>
                <div><a href="admin/uploads/Images/<? echo $item["Image1"]; ?>">
                        <img class="img-responsive" src="admin/uploads/Images/<? echo $item["Image1"]; ?>" alt="" />
                    </a></div><br />
                <? if (!empty($item["Image2"])){ ?>
                    <div><a href="admin/uploads/Images/<? echo $item["Image2"]; ?>">
                            <img class="img-responsive" src="admin/uploads/Images/<? echo $item["Image2"]; ?>" alt="" />
                        </a></div>
                <? } ?>
            </div>
            <div class="col-md-9 item-info">
                <ul class="list-unstyled">
                    <li>
                        <i class="fa fa-shopping-basket fa-fw"></i>
                        <span>Name Item</span> : <a href="items.php?itemid=<? echo $item['Item_ID']; ?>"><?php echo $item['Name_product'] ?></a>
                    </li>
                    <li>
                        <i class="fa fa-calendar fa-fw"></i>
                        <span>Buy Date</span> : <?php echo $item['Date'] ?>
                    </li>
                    <?php
                    @$date=date_create($item['Date']);
                    @date_add($date,date_interval_create_from_date_string("30 days"));
                    ?>
                    <li>
                        <i class="fa fa-info fa-fw"></i>
                        <span>Delivery Date</span>  : <?php echo $item['Date']." - ". date_format($date,"Y-m-d")?>
                    </li>
                    <li>
                        <i class="fa fa-dollar fa-fw"></i>
                        <span>Price Item</span> : $<?php echo $item['Price']." (".$item['Price']*10 ."MAD)" ?>
                    </li>
                    <li>
                        <i class="fa fa-pie-chart fa-fw"></i>
                        <span>Quantity</span> : <?php if ($item['Quantity'] != 0){ echo $item['Quantity'];}else{echo "This Item Is Expired, Wait Some Time For Fill Stock.";}?>
                    </li>
                    <li>
                        <i class="fa fa-gg-circle fa-fw"></i>
                        <span>Total Price</span> : $<?php echo $item['Price']*$item['Quantity']." (".$item['Price']*10*$item['Quantity'] ."MAD)" ?>
                    </li>
                    <li>
                        <i class="fa fa-building fa-fw"></i>
                        <span>Made In</span> : <?php echo $item['Country_Made'] ?>
                    </li>
                    <li>
                        <i class="fa fa-info-circle fa-fw"></i>
                        <span>ID Transaction</span> : <?php echo $item['Id_trans_paypal'] ?>
                    </li>
                    <li>
                        <i class="fa fa-user-circle fa-fw"></i>
                        <span>Seller</span> : <a href="profileOthers.php?userN=<? echo $item['User_sell'];?>"><?php echo $item['User_sell']; ?></a>
                    </li>
                    <li>
                        <i class="fa fa-phone-square fa-fw"></i>
                        <span>Phone Seller</span> : <? if (!empty($item['User_phone'])){echo "<a href='tel:".$item['User_phone']."'>".$item['User_phone']."</a>";}else{echo "Not Available!";} ?>
                    </li>
                    <li>
                        <i class="fa fa-money fa-fw"></i>
                        <span>Currency</span> : <? echo $item['Currency']; ?>
                    </li>
                    <li>
                        <i class="fa fa-eye fa-fw"></i>
                        <span>Views</span> : <?php echo $item['count_views']; ?>
                    </li>
                    <li>
                        <i class="fa fa-plane fa-fw"></i>
                        <span>Shop Service</span> : <?php echo $item['Service_shop_pay']; ?>
                    </li>
                    <li>
                        <i class="fa fa-hourglass-half fa-fw"></i>
                        <span>Purchase Status </span> : <?php echo $item['Status']; ?>
                    </li>
                    <li>
                        <i class="fa fa-hourglass-end fa-fw"></i>
                        <span>Delivery Status</span> : <?php if ($item['Status_final'] === '0'){echo "Still Arrive <i style='color: darkolivegreen' class='fa fa-remove'></i>";}else{echo "Arrive <i style='color: green' class='fa fa-check'></i>";} ?>
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
                ?>
            </div>
        </div>
    </div>

<?php
include $tpl.'footer.php';
ob_end_flush();
?>