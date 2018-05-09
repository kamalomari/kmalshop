<?php
session_start();
$pageTitle = "Categories";//for function getTitle (default)
$noSlider  = "";
include 'init.php';
?>
<?php
        // This for show name of categories
        $decryptedPageid = my_simple_crypt($_GET['pageid'], 'd' );
        if (isset($decryptedPageid) && is_numeric($decryptedPageid)) {
            $categoryID = intval($decryptedPageid);
            $getAll = $con->prepare("SELECT Name FROM `categories` WHERE ID =  {$categoryID}");
            $getAll->execute();
            $allCats = $getAll->fetch();
        }
?>
    <div class="container">
        <?php
        if (ctype_upper($allCats["Name"])){
            echo "<h1 class='text-center'>DEPARTEMENT ".$allCats["Name"]."</h1>";
        }else{
            echo "<h1 class='text-center'>gategory ".$allCats["Name"]."</h1>";
        }
        ?>
        <div class="row">
            <?php
            $decryptedPageid = my_simple_crypt($_GET['pageid'], 'd' );
            if (isset($decryptedPageid) && is_numeric($decryptedPageid)) {
                $category = intval($decryptedPageid);
                if (strlen($decryptedPageid) === 2){// this for show item child
                    $allItems = getAllFrom("*", "items", "where Cat_ID = {$category}", "AND Approve = 1", "Item_ID");
                    foreach ($allItems as $item) {
                        echo '<div class="col-sm-6 col-md-3">';
                        echo '<div class="thumbnail item-box wow pulse-grow"  data-wow-duration="1s" data-wow-offset="400">';
                        echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                        echo "<div><img class='img-responsive img-rounded' src='admin/uploads/Images/" . $item['Image1'] . "' alt='Image one'></div>";
                        echo '<div class="caption">';
                        echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</a></h3>';
                        echo '<p>' . $item['Description'] . '</p>';
                        echo '<div class="date">' . $item['Add_Date'] . '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                     }
                }else{// this for show item parent {al item child}
                    $allCatChild = getAllFrom("*", "categories", "where parent = {$category}", "", "ID","ASC");
                    foreach ($allCatChild as $chlds){
                        $allItems = getAllFrom("*", "items", "where Cat_ID = {$chlds["ID"]}", "AND Approve = 1", "Item_ID");
                        foreach ($allItems as $item) {
                            echo '<div class="col-sm-6 col-md-3">';
                            echo '<div class="thumbnail item-box wow pulse-grow"  data-wow-duration="1s" data-wow-offset="400">';
                            echo '<span class="price-tag">$' . $item['Price'] . '</span>';
                            echo "<div><img class='img-responsive img-rounded' src='admin/uploads/Images/" . $item['Image1'] . "' alt='Image one'></div>";
                            echo '<div class="caption">';
                            echo '<h3><a href="items.php?itemid=' . $item['Item_ID'] . '">' . $item['Name'] . '</a></h3>';
                            echo '<p>' . $item['Description'] . '</p>';
                            echo '<div class="date">' . $item['Add_Date'] . '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }//end foreach two
                    }//end foreach one
                }// end else strlen ID category
            } else {
                echo '<div class="alert alert-warning">PageID Not Found Please Click To Category Again.</div>';
            }
            ?>
        </div>
    </div>

<?php include $tpl . 'footer.php'; ?>