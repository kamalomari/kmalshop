<?php

/*
=================
||  Items Page ||
=================
*/

ob_start(); // Output Buffering Start

session_start();

$pageTitle = 'Items';

if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

        $stmt = $con->prepare("SELECT 
										items.*, 
										categories.Name AS category_name, 
										users.Username 
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
									ORDER BY 
										Item_ID DESC");
        // Execute The Statement

        $stmt->execute();

        // Assign To Variable

        $items = $stmt->fetchAll();
        if (!empty($items)){
        ?>
        <h1 class="text-center">Manage Items</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Item Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Adding Date</td>
                        <td>Category</td>
                        <td>Username</td>
                        <td>Control</td>
                    </tr>
                    <?php
                    foreach($items as $item)  {
                        echo "<tr>";
                        echo "<td>" . $item['Item_ID'] . "</td>";
                        echo "<td>" . $item['Name'] . "</td>";
                        echo "<td>" . $item['Description'] . "</td>";
                        echo "<td>" . $item['Price'] . "</td>";
                        echo "<td>" . $item['Add_Date'] ."</td>";
                        echo "<td>" . $item['category_name'] ."</td>";
                        echo "<td>" . $item['Username'] ."</td>";
                        echo "<td>
										<a href='items.php?do=Edit&itemid=" . $item['Item_ID'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='items.php?do=Delete&itemid=" . $item['Item_ID'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                        if ($item['Approve'] == 0) {
                            echo "<a 
													href='items.php?do=Approve&itemid=" . $item['Item_ID'] . "' 
													class='btn btn-info activate' style='margin-top: 4px'>
													<i class='fa fa-check'></i> Approve</a>";
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
            </div>
            <a href="items.php?do=Add" class="btn btn-primary">
                <i class="fa fa-plus"></i> New Item
            </a>
        </div>

<?php
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

    } elseif ($do == 'Add') {
        /*============*/
        /* Start Add */?>

        <h1 class="text-center">Add New Items</h1>
        <div class="container">
            <form class="form-horizontal" action="?do=Insert" method='POST'>
                <!-- Start Name Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10 col-md-6">
                        <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="Name of the item"
                                required="required" />
                    </div>
                </div>
                <!-- End Name Field -->
                <!-- Start Description Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Description</label>
                    <div class="col-sm-10 col-md-6">
                        <input
                                type="text"
                                name="description"
                                class="form-control"
                                placeholder="Description of the item"
                                required="required" /><!--   required="required"    -->
                    </div>
                </div>
                <!-- End Description Field -->
                <!-- Start Price Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Price</label>
                    <div class="col-sm-10 col-md-6">
                        <input
                                type="text"
                                name="price"
                                class="form-control"
                                placeholder="Price of the item"
                                required="required" />
                    </div>
                </div>
                <!-- End Price Field -->
                <!-- Start Country Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Country</label>
                    <div class="col-sm-10 col-md-6">
                        <input
                                type="text"
                                name="country"
                                class="form-control"
                                placeholder="Country of made"
                                required="required" />
                    </div>
                </div>
                <!-- End Country Field -->
                <!-- Start Status Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Status</label>
                    <div class="col-sm-10 col-md-6">
                       <select name="status" title="">
                           <option value="0">...</option>
                           <option value="1">New</option>
                           <option value="2">Like new</option>
                           <option value="3">Used</option>
                           <option value="4">Very old</option>
                       </select>
                    </div>
                </div>
                <!-- End Status Field -->
                <!-- Start Members Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Members</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="member">
                            <option value="0">...</option>
                            <?php
                            $allMembers = getAllFrom("*", "users", " WHERE GroupID=0", "", "UserID");
                            foreach ($allMembers as $user){
                                echo "<option value='".$user["UserID"]."'>".$user["Username"]."</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Members Field -->
                <!-- Start Categories Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Categories</label>
                    <div class="col-sm-10 col-md-6">
                        <select name="category">
                            <option value="0">...</option>
                            <?php
                            $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID");
                            foreach ($allCats as $cat) {
                                echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                $childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID");
                                foreach ($childCats as $child) {
                                    echo "<option value='" . $child['ID'] . "'>--- " . $child['Name'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- End Categories Field -->
                <!-- Start Rating Field -->
<!--                <div class="form-group form-group-lg">-->
<!--                    <label class="col-sm-2 control-label">Rating</label>-->
<!--                    <div class="col-sm-10 col-md-6">-->
<!--                        <select class="form-control" name="Rating">-->
<!--                            <option value="0">...</option>-->
<!--                            <option value="1">❤</option>-->
<!--                            <option value="2">❤❤</option>-->
<!--                            <option value="3">❤❤❤</option>-->
<!--                            <option value="4">❤❤❤❤</option>-->
<!--                            <option value="5">❤❤❤❤❤</option>-->
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->
                <!-- End Rating Field -->
                <!-- Start Tags Field -->
                <div class="form-group form-group-lg">
                    <label class="col-sm-2 control-label">Tags</label>
                    <div class="col-sm-10 col-md-6">
                        <input
                                type="text"
                                name="tags"
                                class="form-control"
                                placeholder="Separate Tags With Comma (,)" />
                    </div>
                </div>
                <!-- End Tags Field -->
                <!-- Start Submit Field -->
                <div class="form-group form-group-lg">
                    <div class="col-sm-offset-2 col-sm-10">
                        <input type="submit" value="Add Items" class="btn btn-primary btn-lg" />
                    </div>
                </div>
                <!-- End Submit Field -->
            </form>
        </div>
    <?php
        /* End  Add */
        /*==========*/
    }elseif ($do == 'Insert') {
        // Insert Item Page
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<h1 class='text-center'>Insert Items</h1>";
            echo "<div class='container'>";
            // Get Variables From The Form
            // Filter Name
            $name = trim($_POST['name']);
            $name = strip_tags($name);
            $name = filter_var($name, FILTER_SANITIZE_STRING);
            // Filter Desc
            $desc = trim($_POST['description']);
            $desc = strip_tags($desc);
            $desc = filter_var($desc, FILTER_SANITIZE_STRING);
            // Filter Price
            $price = trim($_POST['price']);
            $price = strip_tags($price);
            $price = filter_var($price, FILTER_SANITIZE_NUMBER_INT);
            // Filter Country
            $country = trim($_POST['country']);
            $country = strip_tags($country);
            $country = filter_var($country, FILTER_SANITIZE_STRING);
            // Filter Status
            $status = trim($_POST['status']);
            $status = strip_tags($status);
            $status = filter_var($status, FILTER_SANITIZE_NUMBER_INT);
            // Filter Members
            $member = trim($_POST['member']);
            $member = strip_tags($member);
            $member = filter_var($member, FILTER_SANITIZE_NUMBER_INT);
            // Filter Category
            $cat = trim($_POST['category']);
            $cat = strip_tags($cat);
            $cat = filter_var($cat, FILTER_SANITIZE_NUMBER_INT);
            // Filter Tags
            $tags = trim($_POST['tags']);
            $tags = strip_tags($tags);
            $tags = filter_var($tags, FILTER_SANITIZE_STRING);

            // Validate The Form
            $formErrors = array();
            if (empty($name)) {
                $formErrors[] = 'Name can\'t be <strong>empty</strong>';
            }
            if (empty($desc)) {
                $formErrors[] = 'Description can\'t be <strong>empty</strong>';
            }
            if (empty($price)) {
                $formErrors[] = 'Price can\'t be <strong>empty</strong>';
            }
            if (empty($country)) {
                $formErrors[] = 'Country can\'t be <strong>empty</strong>';
            }
            if ($status   == 0) {
                $formErrors[] = 'You must choose the <strong>Status</strong>';
            }
            if ($member   == 0) {
                $formErrors[] = 'You must choose the <strong>member</strong>';
            }
            if ($cat == 0) {
                $formErrors[] = 'You must choose the <strong>category</strong>';
            }
            // Loop Into Errors Array And Echo It
            foreach($formErrors as $error) {
                echo "<div class='container'>";
                echo "<div class='alert alert-danger'>" . $error . "</div>";
                echo "</div>";
            }
            // RedirectHome for Error validation
            if (!empty($formErrors)){
                echo "<div class='container'>";
                redirectHome("","back",4);
                echo "</div>";
            }
            // Check If There's No Error Proceed The Update Operation
            if (empty($formErrors)) {
                    // Insert Userinfo In Database

                $stmt = $con->prepare("INSERT INTO 
													items(Name, Description, Price, Add_Date, Country_Made, Status, Cat_ID, Member_ID, tags )
												VALUES(:zname, :zdesc, :zprice, now(), :zcountry, :zstatus,  :zcat, :zmember, :ztags )");
                    $stmt->execute(array(

                        'zname'      => $name,
                        'zdesc'      => $desc,
                        'zprice'     => $price,
                        'zcountry'   => $country,
                        'zstatus'    => $status,
                        'zcat'       => $cat,
                        'zmember'    => $member,
                        'ztags'		=> $tags

                    ));

                    // Echo Success Message

                    $theMsg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Inserted</div>";

                    redirectHome($theMsg, 'back');

            }

        } else {
            $theMsg = '<div class="container"><div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div></div>';
            redirectHome($theMsg);
        }
        echo "</div>";


    } elseif ($do == 'Edit') {
        /*=================*/
        /* Start Edit Items*/
        if (isset($_GET["itemid"]) && is_numeric($_GET["itemid"])) {// OR => $userid=isset($_GET["userid"]) && is_numeric($_GET["userid"]) ? intval($_GET["userid"]) : 0;
            $itemid = intval($_GET["itemid"]);
        } else {
            $itemid = 0;
        }
        $stmt = $con->prepare("SELECT * FROM items  WHERE Item_ID = ?");
        $stmt->execute(array($itemid));
        $item = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count > 0) {?>
            <h1 class="text-center">Edit Items</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method='POST'>
                    <input type="hidden" name="itemid" value="<? echo $itemid;?>">
                    <!-- Start Name Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    placeholder="Name of the item"
                                    required="required"
                                    value="<? echo $item['Name']?>"/>
                        </div>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Description Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="text"
                                    name="description"
                                    class="form-control"
                                    placeholder="Description of the item"
                                    required="required"
                                    value="<? echo $item['Description']?>"/><!--   required="required"    -->
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Price Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="text"
                                    name="price"
                                    class="form-control"
                                    placeholder="Price of the item"
                                    required="required"
                                    value="<? echo $item['Price']?>"/>
                        </div>
                    </div>
                    <!-- End Price Field -->
                    <!-- Start Country Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="text"
                                    name="country"
                                    class="form-control"
                                    placeholder="Country of made"
                                    required="required"
                                    value="<? echo $item['Country_Made']?>"/>
                        </div>
                    </div>
                    <!-- End Country Field -->
                    <!-- Start Status Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="status" title="">
                                <option value="0" <?php if ($item['Status'] == 0) { echo 'selected'; } ?>>...</option>
                                <option value="1" <?php if ($item['Status'] == 1) { echo 'selected'; } ?>>New</option>
                                <option value="2" <?php if ($item['Status'] == 2) { echo 'selected'; } ?>>Like new</option>
                                <option value="3" <?php if ($item['Status'] == 3) { echo 'selected'; } ?>>Used</option>
                                <option value="4" <?php if ($item['Status'] == 4) { echo 'selected'; } ?>>Very old</option>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->
                    <!-- Start Members Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Members</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="member">
                                <option value="0">...</option>
                                <?php
                                $allMembers = getAllFrom("*", "users", "WHERE GroupID=0", "", "UserID");
                                foreach ($allMembers as $user){
                                    echo "<option value='".$user["UserID"]."'";
                                    if ($item['Member_ID'] == $user['UserID']) { echo 'selected'; }
                                    echo ">".$user["Username"]."</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Members Field -->
                    <!-- Start Categories Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Categories</label>
                        <div class="col-sm-10 col-md-6">
                            <select name="category">
                                <option value="0">...</option>
                                <?php
                                $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID");
                                foreach ($allCats as $cat) {
                                    echo "<option value='" . $cat['ID'] . "'";
                                    if ($item['Cat_ID'] == $cat['ID']) { echo ' selected'; }
                                    echo ">" . $cat['Name'] . "</option>";
                                    $childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID");
                                    foreach ($childCats as $child) {
                                        echo "<option value='" . $child['ID'] . "'";
                                        if ($item['Cat_ID'] == $child['ID']) { echo ' selected'; }
                                        echo ">-- " . $child['Name'] . "</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <!-- End Categories Field -->
                    <!-- Start Tags Field -->
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Tags</label>
                        <div class="col-sm-10 col-md-6">
                            <input
                                    type="text"
                                    name="tags"
                                    class="form-control"
                                    placeholder="Separate Tags With Comma (,)"
                                    value="<?php echo $item['tags'] ?>" />
                        </div>
                    </div>
                    <!-- End Tags Field -->
                    <!-- Start Submit Field -->
                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                    <!-- End Submit Field -->
                </form>
            <?
            /*======================*/
            /* Manage Comment Items*/

            // Select All Users Except Admin

            $stmt = $con->prepare("SELECT 
										comments.*, users.Username AS Member  
									FROM 
										comments
									INNER JOIN 
										users 
									ON 
										users.UserID = comments.user_id
									WHERE item_id = ?");

            // Execute The Statement

            $stmt->execute(array($itemid));

            // Assign To Variable

            $comments = $stmt->fetchAll();

            if (! empty($comments)) {

                ?>

                <h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>
                    <div class="table-responsive">
                        <table class="main-table text-center table table-bordered">
                            <tr>
                                <td>Comment</td>
                                <td>User Name</td>
                                <td>Added Date</td>
                                <td>Control</td>
                            </tr>
                            <?php
                            foreach($comments as $comment) {
                                echo "<tr>";
                                echo "<td>" . $comment['comment'] . "</td>";
                                echo "<td>" . $comment['Member'] . "</td>";
                                echo "<td>" . $comment['comment_date'] ."</td>";
                                echo "<td>
										<a href='comments.php?do=Edit&comid=" . $comment['c_id'] . "' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
										<a href='comments.php?do=Delete&comid=" . $comment['c_id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                if ($comment['status'] == 0) {
                                    echo "<a href='comments.php?do=Approve&comid="
                                        . $comment['c_id'] . "' 
													class='btn btn-info activate'>
													<i class='fa fa-check'></i> Approve</a>";
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                            <tr>
                        </table>
                    </div>
            <?php }

            /* Manage Comment Items*/
            /*======================*/
          ?>
            </div>
<?php        }else {
            echo "<br /><br />";
            $theMsg = '<div class="container"><div class="alert alert-danger">this wrong your are not member here</div></div>';
            redirectHome($theMsg);
        }
        /* End Edit Items*/
        /*===============*/
    } elseif ($do == 'Update') {
        /*===================*/
        /* Start Update items*/
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "<h1 class=\"text-center\">Update Items</h1>";
            echo "<div class='container''>";
            //Get variables from the FORM
            $id 		= $_POST['itemid'];
            // Filter Name
            $name = trim($_POST['name']);
            $name = strip_tags($name);
            $name = filter_var($name, FILTER_SANITIZE_STRING);
            // Filter Desc
            $desc = trim($_POST['description']);
            $desc = strip_tags($desc);
            $desc = filter_var($desc, FILTER_SANITIZE_STRING);
            // Filter Price
            $price = trim($_POST['price']);
            $price = strip_tags($price);
            $price = filter_var($price, FILTER_SANITIZE_NUMBER_INT);
            // Filter Country
            $country = trim($_POST['country']);
            $country = strip_tags($country);
            $country = filter_var($country, FILTER_SANITIZE_STRING);
            // Filter Status
            $status = trim($_POST['status']);
            $status = strip_tags($status);
            $status = filter_var($status, FILTER_SANITIZE_NUMBER_INT);
            // Filter Members
            $member = trim($_POST['member']);
            $member = strip_tags($member);
            $member = filter_var($member, FILTER_SANITIZE_NUMBER_INT);
            // Filter Category
            $cat = trim($_POST['category']);
            $cat = strip_tags($cat);
            $cat = filter_var($cat, FILTER_SANITIZE_NUMBER_INT);
            // Filter Tags
            $tags = trim($_POST['tags']);
            $tags = strip_tags($tags);
            $tags = filter_var($tags, FILTER_SANITIZE_STRING);

            //Password Trick
            $pass = "";
            if (empty($_POST["newpassword"])) {// Or $pass=empty($_POST["newpassword"] ? $_POST["oldpassword"] : sha1($_POST["newpassword"] ;
                $pass = $_POST["oldpassword"];
            } else {
                $pass = sha1($_POST["newpassword"]);
            }
            // Validate The Form

            $formErrors = array();

            if (empty($name)) {
                $formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
            }

            if (empty($desc)) {
                $formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
            }

            if (empty($price)) {
                $formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
            }

            if (empty($country)) {
                $formErrors[] = 'Country Can\'t be <strong>Empty</strong>';
            }

            if ($status == 0) {
                $formErrors[] = 'You Must Choose the <strong>Status</strong>';
            }

            if ($member == 0) {
                $formErrors[] = 'You Must Choose the <strong>Member</strong>';
            }

            if ($cat == 0) {
                $formErrors[] = 'You Must Choose the <strong>Category</strong>';
            }
            // Loop Into Errors Array And Echo It

            foreach($formErrors as $error) {
                echo "<div class='container'>";
                echo "<div class='alert alert-danger'>" . $error . "</div>";
                echo "</div>";
            }
            // RedirectHome for Error validation
            if (!empty($formErrors)){
                redirectHome("","back",4);
            }
            // Check If There's No Error Proceed The Update Operation

            if (empty($formErrors)) {

                // Update The Database With This Info

                $stmt = $con->prepare("UPDATE 
												items 
											SET 
												Name = ?, 
												Description = ?, 
												Price = ?, 
												Country_Made = ?,
												Status = ?,
												Cat_ID = ?,
												Member_ID = ?,
												tags = ?
											WHERE 
												Item_ID = ?");

                $stmt->execute(array($name, $desc, $price, $country, $status, $cat, $member, $tags, $id));

                // Echo Success Message

                $theMsg = "<div class='container'><div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div></div>';

                redirectHome($theMsg, 'back');

            }
        } else {
            $theMsg= "<div class='container'><div class='alert alert-danger'>So sory you can't browse this page directly</div></div>";
            redirectHome($theMsg);
        }
        echo "</div>";

        /* End Update Items*/
        /*===============*/
    } elseif ($do == 'Delete') {// Delete items

        echo "<h1 class='text-center'>Delete Items</h1>";
        echo "<div class='container'>";

        // Check If Get Request userid Is Numeric & Get The Integer Value Of It

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        // Select All Data Depend On This ID

        $check = checkItem('Item_ID', 'items', $itemid);

        // If There's Such ID Show The Form

        if ($check > 0) {

            $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zitem");

            $stmt->bindParam(":zitem", $itemid);

            $stmt->execute();

            $theMsg = "<div class='container'><div class='alert alert-success'>" . $stmt->rowCount() . ' Record Deleted</div></div>';

            redirectHome($theMsg, 'back');

        } else {

            $theMsg = '<div class="container"><div class="alert alert-danger">This ID is Not Exist</div></div>';

            redirectHome($theMsg);

        }

        echo '</div>';


    } elseif ($do == 'Approve') {
        /*==================*/
        /* Start Page Approve*/

        echo "<h1 class='text-center'>Approve Item</h1>";
        echo "<div class='container'>";

        // Check If Get Request Item ID Is Numeric & Get The Integer Value Of It

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;

        // Select All Data Depend On This ID

        $check = checkItem('Item_ID', 'items', $itemid);

        // If There's Such ID Show The Form

        if ($check > 0) {

            $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");

            $stmt->execute(array($itemid));

            $theMsg = "<div class='container'><div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div></div>';

            redirectHome($theMsg, 'back');

        } else {

            $theMsg = '<div class="container"><div class="alert alert-danger">This ID is Not Exist</div></div>';

            redirectHome($theMsg);

        }

        echo '</div>';
        /* End page Aprove*/
        /*================*/
    }
    include $tpl . 'footer.php';

} else {

    header('Location: index.php');

    exit();
}

ob_end_flush(); // Release The Output
?>