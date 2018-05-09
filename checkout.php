<?php

/*
================================================
== Template Page
================================================
*/

ob_start(); // Output Buffering Start

session_start();

$pageTitle = 'Checkout';
$noSlider="";
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Default';

    if ($do == 'Default') {

    }elseif ($do == 'success') {
        $userName   = $_SESSION['user'];
        $itemID   = $_GET["itemid"];
//         Pypal Info
        $amount   = $_GET["amt"];// Must go to my selling tools business account paypal and go to Website preferences for add URL return info
        $currency = $_GET["cc"];
        $trx_id   = $_GET["tx"];
        $item_name= $_GET["item_name"];
        $status   = $_GET["st"];
//         Fetch All Informations Of Product
        $stmt1 = $con->prepare("SELECT * FROM `items`  WHERE Item_ID = $itemID LIMIT 1");
        $stmt1->execute();
        $rowProduct = $stmt1->fetch();
        $quantity    = $rowProduct["Quantity"];
        $numberQuantity = $amount/$rowProduct["Price"];
        if ($quantity >= $numberQuantity){
            $editQuantity = $quantity - $numberQuantity;
        // Update info quantity
        $stmt = $con->prepare("UPDATE `items` SET Quantity = ? WHERE Item_ID = ? LIMIT 1");
        $stmt->execute(array($editQuantity, $itemID));

        $User_sell_id = $rowProduct["Member_ID"];
        // Fetch All Informations Of User Product
        $stmt2 = $con->prepare("SELECT * FROM `users`  WHERE UserID = $User_sell_id LIMIT 1");
        $stmt2->execute();
        $rowProductUser = $stmt2->fetch();
        // Fetch All Informations Of User buyer
        $stmt3 = $con->prepare("SELECT * FROM `users`  WHERE Username = '$userName' LIMIT 1");
        $stmt3->execute();
        $rowPayUser = $stmt3->fetch();
        // Insert Information Of Transaction
        $stmt = $con->prepare("
                       INSERT INTO
                        payments
                              (Id_trans_paypal, Id_product, Name_product, Sender_paypal, Receiver_paypal, User_pay, User_pay_id, User_sell, User_sell_id, User_phone, Quantity, Currency, Service_shop_pay, Status, DATE)
                        VALUES
                              (:zidtranspaypal, :zidproduct, :znameproduct, :zsendpaypal, :zreceivpaypal, :zuserpay, :zuserpayid, :zusersell, :zusersellid, :zuserphone, :zquantity, :zcurrency, :zservice_shop_pay, :zstatus, NOW())
								        ");

        $stmt->execute(array(

            'zidtranspaypal'   => $trx_id,
            'zidproduct'       => $rowProduct["Item_ID"],
            'znameproduct'     => $item_name,
            'zsendpaypal'      => "kamalbuyer123@gmil.com",
            'zreceivpaypal'    => $rowProduct["Paypal_Ac"],
            'zuserpay'         => $userName,
            'zuserpayid'       => $rowPayUser["UserID"],
            'zusersell'        => $rowProductUser["Username"],
            'zusersellid'      => $User_sell_id,
            'zuserphone'      => $rowProductUser["Phone"],
            'zquantity'        => $numberQuantity,
            'zcurrency'        => $currency,
            'zservice_shop_pay'=> "Standard Int'l Shipping(0$)",
            'zstatus'          => $status

        ));

          // for send message to buyer successfully
          // mail($rowPayUser["Email"], "Purchased successfully", "Purchased Is compl eted Please wait some day to receive item.\n Kamal Omari ", "kamalpaypal6@gmail.com");

            echo "<div class='container'><div class='alert alert-success'> Purchased successfully </div></div>";
            header("Refresh: 3; url=profile.php#Purchases");

        }//end check equal numberQuantity, Quantity

    } elseif ($do == 'cancel') {
        $cancelItemId = $_GET["itemid"]; ?>
    <div class="alert alert-warning text-center" style="margin: 60px auto;font-size: 20px;font-weight: bold"><em><q>Your Payment Was <u>Not</u> Completed.</q></em></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center" style="font-weight: bold">
                        <p><strong><mark><ins>NOTE : </ins></mark></strong>Either The Transaction Was Cancelled By User Or A Servet-Side Error Terminated The Transaction.</p>
                        <p>Please Refer To Any Notifications You Receive Or <a href="contact.php">Contact-US Via Email</a> If You Any Questions.</p>
                         <p>To start Over,Go To Your <a href="items.php?itemid=<? echo $cancelItemId ;?>">Selection Item</a>.</p>
                    </div>
                </div>
            </div>
        </div>
   <? }
    echo "<div style='margin-bottom: 500px'></div>";
    include $tpl . 'footer.php';

ob_end_flush(); // Release The Output

?>