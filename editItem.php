<?php

/*
================================================
== Template Page
================================================
*/

ob_start(); // Output Buffering Start
session_start();
$noSlider ="";
$pageTitle = 'Edit Item';

if (isset($_SESSION['user'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

    if ($do == 'Manage') {

    } elseif ($do == 'EditItem') {
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
                <div class="row">
                    <style>
                        .asterisk {
                            right: 22px;
                            top: 13px;
                        }
                    </style>
                    <div class="col-md-8">
                  <form class="form-horizontal" action="?do=UpdateItem" method='POST'  enctype="multipart/form-data">
                    <input type="hidden" name="itemid" value="<? echo $itemid;?>">
                    <!-- Start Name Field -->
                      <div class="form-group form-group-lg">
                          <label class="col-sm-3 control-label">Name :</label>
                          <div class="col-sm-10 col-md-9">
                            <input
                                    pattern=".{4,}"
                                    title="This Field Require At Least 4 Characters"
                                    type="text"
                                    name="name"
                                    required="required"
                                    class="form-control live"
                                    placeholder="Name of The Item"
                                    autocomplete="off"
                                    data-class=".live-title"
                                    value="<? echo $item['Name']?>"/>
                        </div>
                    </div>
                    <!-- End Name Field -->
                    <!-- Start Description Field -->
                      <div class="form-group form-group-lg">
                          <label class="col-sm-3 control-label">Description :</label>
                          <div class="col-sm-10 col-md-9">
                            <input
                                    pattern=".{10,}"
                                    title="This Field Require At Least 10 Characters"
                                    type="text"
                                    name="description"
                                    required="required"
                                    class="form-control live"
                                    placeholder="Description of The Item"
                                    autocomplete="off"
                                    data-class=".live-desc"
                                value="<? echo $item['Description']?>"/><!--   required="required"    -->
                        </div>
                    </div>
                    <!-- End Description Field -->
                    <!-- Start Price Field -->
                      <div class="form-group form-group-lg">
                          <label class="col-sm-3 control-label">Price(1$ = 10Dh) :</label>
                          <div class="col-sm-10 col-md-9">
                            <input
                                    type="number"
                                    name="price"
                                    required="required"
                                    class="form-control live"
                                    placeholder="Price by $"
                                    autocomplete="off"
                                    data-class=".live-price"
                                value="<? echo $item['Price']?>"/>
                        </div>
                    </div>
                    <!-- End Price Field -->
                      <!-- Start Country Field -->
                      <div class="form-group form-group-lg">
                          <label class="col-sm-3 control-label">Country :</label>
                          <div class="col-sm-10 col-md-9">
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
                      <!-- Start Image Item Field -->
                      <div class="form-group form-group-lg">
                          <label class="col-sm-3 control-label">Upload Image One :</label>
                          <div class="col-sm-10 col-md-9">
                              <input
                                      type="file"
                                      name="image1"
                                      id="idupload1"
                                      class="form-control"
                                      onchange="imagepreview1(this);" />
                          </div>
                      </div>
                      <!-- End Image Item Field -->
                      <!-- Start Image Item Field -->
                      <div class="form-group form-group-lg">
                          <label class="col-sm-3 control-label">Upload Image Two :</label>
                          <div class="col-sm-10 col-md-9">
                              <input
                                      type="file"
                                      name="image2"
                                      id="idupload2"
                                      class="form-control"
                                      onchange="imagepreview2(this);"/>
                          </div>
                      </div>
                      <!-- End Image Item Field -->
                    <!-- Start Status Field -->
                      <div class="form-group form-group-lg">
                          <label class="col-sm-3 control-label">Status :</label>
                          <div class="col-sm-10 col-md-9">
                            <select name="status" title="" class="selectpicker" data-style="btn-info" multiple data-max-options="1" data-live-search="true" required >
                                <optgroup label="Status Of Your Item">
                                    <option value="0" <?php if ($item['Status'] == 0) { echo 'selected'; } ?>>...</option>
                                    <option value="1" <?php if ($item['Status'] == 1) { echo 'selected'; } ?>>New</option>
                                    <option value="2" <?php if ($item['Status'] == 2) { echo 'selected'; } ?>>Like new</option>
                                    <option value="3" <?php if ($item['Status'] == 3) { echo 'selected'; } ?>>Used</option>
                                    <option value="4" <?php if ($item['Status'] == 4) { echo 'selected'; } ?>>Very old</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <!-- End Status Field -->
                    <!-- Start Categories Field -->
                      <div class="form-group form-group-lg">
                          <label class="col-sm-3 control-label">Category :</label>
                          <div class="col-sm-10 col-md-9">
                            <select name="category" class="selectpicker" data-style="btn-info" multiple data-max-options="1" data-live-search="true" required >
                                <optgroup label="Category Of Your Item">
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
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <!-- End Categories Field -->
                    <!-- Start Tags Field -->
                      <div class="form-group form-group-lg">
                          <label class="col-sm-3 control-label">Tags :</label>
                          <div class="col-sm-10 col-md-9">
                            <input
                                type="text"
                                name="tags"
                                class="form-control"
                                placeholder="Separate Tags With Comma (,)"
                                value="<?php echo $item['tags'] ?>" />
                        </div>
                    </div>
                    <!-- End Tags Field -->
                      <? if ($item['Paypal_Ac'] != "notPaypal"){ ?>
                            <!-- Start enable & disable paypal service -->
                              <div class="form-group form-group-lg">
                                  <label class="col-sm-4 col-md-3 control-label"></label>
                                  <div class="col-sm-8 col-md-9">
                                      <input type="checkbox" onclick="disable_text(this.checked)"/> Check If You Want to disabled sell with paypal.
                                  </div>
                              </div>
                            <!-- Start enable & disable paypal service -->
                            <!-- Start Paypal Field -->
                              <div class="form-group form-group-lg">
                                  <label class="col-sm-3 control-label">Paypal Email :</label>
                                  <div class="col-sm-10 col-md-9">
                                      <input
                                              type="email"
                                              name="paypal_ac"
                                              class="form-control"
                                              id="textDisable"
                                              placeholder="Edit Paypal Account"
                                              value="<?php echo $item['Paypal_Ac'] ?>"
                                              required
                                      />
                                  </div>
                              </div>
                            <!-- End paypal Field -->
                          <!-- Start Service Shop Field -->
                              <div class="form-group form-group-lg">
                                  <label class="col-sm-3 control-label">Service Shop :</label>
                                  <div class="col-sm-10 col-md-9">
                                      <input
                                              type="radio"
                                              name="service"
                                              id="free"
                                              value="free"
                                          <?php if ($item['Service_shop_item'] === "free") { echo 'checked'; } ?> > <label for="free">Free</label> <br>
                                      <input
                                              type="radio"
                                              name="service"
                                              id="paid"
                                              value="paid"
                                          <?php if ($item['Service_shop_item'] === "paid") { echo 'checked'; } ?> > <label for="paid">Paid</label>
                                  </div>
                              </div>
                          <!-- End Service Shop Field -->
                     <? }else{ ?>
                          <!-- Start enable & disable paypal service -->
                          <div class="form-group form-group-lg">
                              <label class="col-sm-4 col-md-3 control-label"></label>
                              <div class="col-sm-8 col-md-9">
                                  <input type="checkbox" onclick="showHidePaypal()" checked/> remove Check If You Want to sell with paypal again.
                              </div>
                          </div>
                          <!-- End enable & disable paypal service style="display: none" -->
                          <!-- Start Paypal Field -->
                          <div class="form-group form-group-lg" id="paypalService" style="display: none">
                              <label class="col-sm-3 control-label">Paypal Email :</label>
                              <div class="col-sm-10 col-md-9">
                                  <input
                                          type="email"
                                          name="paypal_ac"
                                          class="form-control"
                                          id="textDisable"
                                          placeholder="Edit Paypal Account"
                                          value="<?php echo $item['Paypal_Ac'] ?>"
                                          required
                                  />
                              </div>
                          </div>
                          <!-- End paypal Field -->
                          <!-- Start Service Shop Field -->
                          <div class="form-group form-group-lg" style="display: none" id="serviceShop">
                              <label class="col-sm-3 control-label">Service Shop :</label>
                              <div class="col-sm-10 col-md-9">
                                  <input
                                          type="radio"
                                          name="service"
                                          id="free"
                                          value="free"
                                          required
                                      <?php if ($item['Service_shop_item'] === "free") { echo 'checked'; } ?> > <label for="free">Free</label> <br>
                                  <input
                                          type="radio"
                                          name="service"
                                          id="paid"
                                          value="paid"
                                          required
                                      <?php if ($item['Service_shop_item'] === "paid") { echo 'checked'; } ?> > <label for="paid">Paid</label>
                              </div>
                          </div>
                          <!-- End Service Shop Field -->

                <? } ?>
                    <!-- Start Submit Field -->
                      <div class="form-group form-group-lg">
                          <div class="col-sm-offset-3 col-sm-9">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
                    <!-- End Submit Field -->
                </form>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail item-box live-preview">
                                    <span class="price-tag">
                                        $<span class="live-price"><? echo $item['Price'] ?></span>
                                    </span>
                            <a href="admin/uploads/Images/<? echo $item["Image1"]?>">
                                <img
                                        id="imagepreview1"
                                        style="max-height: 280px;width: 350px"
                                        src="admin/uploads/Images/<? echo $item["Image1"]?>"
                                        class="img-responsive"
                                        alt="image one preview"/>
                            </a>
                            <div class="caption">
                                <h3 class="live-title"><? echo $item['Name'] ?></h3>
                                <p class="live-desc"><? echo $item['Description'] ?></p>
                            </div>
                        </div>
                        <div class="thumbnail item-box live-preview">
                            <a href="admin/uploads/Images/<? echo $item["Image2"]?>">
                                <img
                                        id="imagepreview2"
                                        class="img-responsive"
                                        style="max-height: 280px;width: 350px"
                                        src="admin/uploads/Images/<? echo $item["Image2"]?>"
                                        alt="image tow preview" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php        }else {
            echo "<br /><br />";
            $theMsg = '<div class="container"><div class="alert alert-danger">this wrong your are not member here</div></div>';
            redirectHome($theMsg);
        }
        /* End Edit Items*/
        /*===============*/
    } elseif ($do == 'UpdateItem') {
        /*===================*/
        /* Start Update items*/
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            echo "<h1 class='text-center'>Update Items</h1>";
            /*== Upload Variables ==*/
            // information image number one
            $image1Name    = $_FILES["image1"]["name"];
            $image1Size    = $_FILES["image1"]["size"];
            $image1TmpName = $_FILES["image1"]["tmp_name"];
            $image1Type    = $_FILES["image1"]["type"];
            // information image number one
            $image2Name    = $_FILES["image2"]["name"];
            $image2Size    = $_FILES["image2"]["size"];
            $image2TmpName = $_FILES["image2"]["tmp_name"];
            $image2Type    = $_FILES["image2"]["type"];
            // Filter Itemid
            $itemid = trim($_POST['itemid']);
            $itemid = strip_tags($itemid);
            $itemid = filter_var($itemid, FILTER_SANITIZE_NUMBER_INT);

            $stmt = $con->prepare("SELECT * FROM items  WHERE Item_ID = ?");
            $stmt->execute(array($itemid));
            $item = $stmt->fetch();

            if (isset($_POST['paypal_ac'])){
                    // Filter Email Paypal
                if ($_POST['paypal_ac'] != null){
                    $paypal = trim($_POST['paypal_ac']);
                    $paypal = strip_tags($paypal);
                    $paypal = filter_var($paypal, FILTER_SANITIZE_EMAIL);
                }else{
                    $paypal = "notPaypal";
                }
            }else{
                $paypal = "notPaypal";
            }
            // Filter Service Shop
            if (isset($_POST['service'])){
                $service = trim($_POST['service']);
                $service = strip_tags($service);
                $service = filter_var($service, FILTER_SANITIZE_STRING);
            }else{
                $service = "noService";
            }
            // List of allowed file typed to upload
            $imageAllowedExtension = array("jpeg","jpg","png","gif");

            // Get image one extension
            $exp1=explode(".",$image1Name);
            $endExp1=end($exp1);
            $image1Extension = strtolower($endExp1);
            // Get image two extension
            $exp2=explode(".",$image2Name);
            $endExp2=end($exp2);
            $image2Extension = strtolower($endExp2);
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
            // Filter Category
            $category = trim($_POST['category']);
            $category = strip_tags($category);
            $category = filter_var($category, FILTER_SANITIZE_NUMBER_INT);
            // Filter Tags
            $tags = trim($_POST['tags']);
            $tags = strip_tags($tags);
            $tags = filter_var($tags, FILTER_SANITIZE_STRING);

            $formErrors = array();

            if (strlen($name) < 4) {

                $formErrors[] = 'Item Title Must Be At Least 4 Characters';

            }

            if (strlen($desc) < 10) {

                $formErrors[] = 'Item Description Must Be At Least 10 Characters';

            }

            if (empty($price)) {

                $formErrors[] = 'Item Price Cant Be Empty';

            }

            if (strlen($country) < 2) {

                $formErrors[] = 'Item Title Must Be At Least 2 Characters';

            }


            if (empty($status)) {

                $formErrors[] = 'Item Status Cant Be Empty';

            }

            if (empty($category)) {

                $formErrors[] = 'Item Category Cant Be Empty';

            }
            // Form Error For Image One
            if (! empty($image1Name) && ! in_array($image1Extension, $imageAllowedExtension)) {
                $formErrors[] = 'This Extension For Image One IS Not  <strong>Allowed</strong>';
            }
            if ( $image1Size > 4194304 ) {
                $formErrors[] = 'Image One Can\'t Be Larger Than <strong>4MB</strong>';
            }
            if (strpos($image1Name, '.php') !== false)  {
                $formErrors[] = 'Must Name Of Image One Not Contains <strong>.php</strong>';
            }
            // Form Error For Image Two
            if (! empty($image2Name) && ! in_array($image2Extension, $imageAllowedExtension)) {
                $formErrors[] = 'This Extension For Image Two IS Not  <strong>Allowed</strong>';
            }
            if ( $image2Size > 4194304 ) {
                $formErrors[] = 'Image Two Can\'t Be Larger Than <strong>4MB</strong>';
            }
            if (strpos($image2Name, '.php') !== false)  {
                $formErrors[] = 'Must Name Of Image Two Not Contains <strong>.php</strong>';
            }
            // Loop Into Errors Array And Echo It

            foreach($formErrors as $error) {
                echo "<div class='container'>";
                echo "<div class='alert alert-danger'>" . $error . "</div>";
                echo "</div>";
            }
            // Check If There's No Error Proceed The Update Operation

            if (empty($formErrors)) {
            // Naming Avatar For Database
            $image1 = rand(0,100000000000)."_".$image1Name;
            $image2 = rand(0,100000000000)."_".$image2Name;

            move_uploaded_file($image1TmpName,'admin/uploads/Images/'.$image1);
            move_uploaded_file($image2TmpName,'admin/uploads/Images/'.$image2);
            // Update The Database With This Info
         if (empty($image1Name) && empty($image2Name)){
            $stmt = $con->prepare("UPDATE 
												items 
											SET 
												Name = ?, 
												Description = ?, 
												Price = ?, 
												Country_Made = ?,
												Status = ?,
												Cat_ID = ?,
												tags = ?,
												Paypal_Ac = ?,
												Service_shop_item = ?
											WHERE 
												Item_ID = ?");

            $stmt->execute(array($name, $desc, $price, $country, $status, $category, $tags, $paypal, $service, $itemid));

         }elseif (empty($image2Name) && !empty($image1Name)){
             $stmt = $con->prepare("UPDATE 
												items 
											SET 
												Name = ?, 
												Description = ?, 
												Price = ?, 
												Country_Made = ?,
												Image1 = ?,
												Status = ?,
												Cat_ID = ?,
												tags = ?,
												Paypal_Ac = ?,
												Service_shop_item = ?
											WHERE 
												Item_ID = ?");

             $stmt->execute(array($name, $desc, $price, $country, $image1,  $status, $category, $tags, $paypal, $service, $itemid));
         }elseif (empty($image1Name) && !empty($image2Name)){
             $stmt = $con->prepare("UPDATE 
												items 
											SET 
												Name = ?, 
												Description = ?, 
												Price = ?, 
												Country_Made = ?,
												Image2 = ?,
												Status = ?,
												Cat_ID = ?,
												tags = ?,
												Paypal_Ac = ?,
												Service_shop_item = ?
											WHERE 
												Item_ID = ?");

             $stmt->execute(array($name, $desc, $price, $country, $image2,  $status, $category, $tags, $paypal, $service, $itemid));
         }
                // Echo Success Message

                $theMsg = "<div class='container'><div class='alert alert-success'>" . $stmt->rowCount() . ' Record Updated</div></div>';
                redirectHome($theMsg, 'profile.php#my-ad', 3, 7);// msg + urlTo + secondRedirect + subStr-0-to-thisNumber
            }
        }else {
            $theMsg= "<div class='container'><div class='alert alert-danger'>So sory you can't browse this page directly</div></div>";
            redirectHome($theMsg);
        }
        /* End Update Items*/
        /*===============*/
    }elseif ($do == 'DeleteItem') {
        /*==================*/
        /* Start Delete Item*/

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

            redirectHome($theMsg,"profile");

        } else {

            $theMsg = '<div class="container"><div class="alert alert-danger">This ID is Not Exist</div></div>';

            redirectHome($theMsg);

        }

        echo '</div>';
        /* End Delete Item*/
        /*================*/
    }

    include $tpl . 'footer.php';

} else {

    header('Location: index.php');

    exit();
}

ob_end_flush(); // Release The Output

?>