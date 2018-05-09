<?php
ob_start();
session_start();
$pageTitle="Search";// for function getTitle (default)
$noSlider ="";// for Need Slider Yes Or No?
//$noMap ="";// for Need Map Yes Or No?
include 'init.php';
if (isset($_POST["searchN"])){
    $searchN = filter_var(trim($_POST["searchN"]), FILTER_SANITIZE_STRING);
?>
    <div class="container">
        <div class="row">
            <?php

            $allItems = getAllFrom("*", "items", "where Approve = 1 AND `Name` LIKE '%$searchN%' OR `Description` LIKE '%$searchN%'", "", "Item_ID");
            foreach ($allItems as $item) {
                echo '<div class="col-sm-6 col-md-3">';
                echo '<div class="thumbnail item-box wow pulse" data-wow-duration="8s" data-wow-offset="400">';
                echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                echo "<div><img class='img-responsive img-rounded' src='admin/uploads/Images/" . $item['Image1'] . "' alt='Image one'></div>";
                echo '<div class="caption">';
                echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'">' . $item['Name'] .'</a></h3>';
                echo '<p>' . $item['Description'] . '</p>';
                echo '<div class="date">' . $item['Add_Date'] . '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            if (empty($allItems)){
                echo "<div style='margin-bottom: 1000px' class='container'><div class='alert alert-warning'>There's No Item Have This Name Or Containts This Part.<br />
                 Please Try To Use Appropriate Meanings </div></div>";
            }
            ?>
        </div>
    </div>
<?
}else{
    echo "<div style='margin-bottom: 320px' class='container'><div class='alert alert-warning'>Please Put Name Of Item Your Input Is Empty.</div></div>";
}
include $tpl.'footer.php';
ob_end_flush();
?>