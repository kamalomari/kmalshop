<?php
     ob_start();
     session_start();
     $pageTitle="Profile";//for function getTitle (default)
include 'init.php';
?>
    <div class="container">
        <style>
            @media (max-width: 538px) {
                .information ul li .noVerifyEmailOthers{
                    background-color: #e58511;
                    text-align: center;
                    padding: 1px;
                    display: inline-block;
                    margin-left: 70px;
                    margin-top: 5px;
                    border: 2px solid red;
                }
        </style>
        <div class="row">
            <?php
            $allItems = getAllFrom('*', 'items', 'where Approve = 1', '', 'Item_ID');
            foreach ($allItems as $item) {
                echo '<div class="col-xs-6 col-sm-4 col-md-3">';
                        echo '<div class="thumbnail item-box wow pulse-grow" data-wow-duration="2s" data-wow-offset="400">';
                                echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                                if (empty($item["Image1"])){
                                    echo "<div><img class='img-responsive img-rounded' src='admin/uploads/Images/new.png'></div>";
                                }elseif (!empty($item["Image1"])){
                                echo "<div><img class='img-responsive img-rounded' src='admin/uploads/Images/" . $item['Image1'] . "' alt='Image one'></div>";
                                }
                                echo '<div class="caption">';
                                        echo '<h3><a href="items.php?itemid='. $item['Item_ID'] .'">' . $item['Name'] .'</a></h3>';
                                        echo '<p>' . $item['Description'] . '</p>';
                                        echo '<div class="date">' . $item['Add_Date'] . '</div>';
                                echo '</div>';
                        echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
<?
include $tpl.'footer.php';
ob_end_flush();
?>