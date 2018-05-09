<?php
ob_start();
session_start();
$pageTitle = 'Create New Item';
$noSlider="";
include 'init.php';
if (isset($_SESSION['user'])) {
    if ($_SERVER["REQUEST_METHOD"] == 'POST') {
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
        if (isset($_POST['quantity'])) {
            // Filter quantity
            $quantity = trim($_POST['quantity']);
            $quantity = strip_tags($quantity);
            $quantity = filter_var($quantity, FILTER_SANITIZE_NUMBER_INT);
        }else{
            $quantity = "1";
        }
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
        // Filter Paypal Account
        if (isset($_POST['paypal_ac'])){
            $paypal_ac = trim($_POST['paypal_ac']);
            $paypal_ac = strip_tags($paypal_ac);
            $paypal_ac = filter_var($paypal_ac, FILTER_SANITIZE_EMAIL);
        }else{
            $paypal_ac = "notPaypal";
        }
        if (isset($_POST['service'])){
            $service = trim($_POST['service']);
            $service = strip_tags($service);
            $service = filter_var($service, FILTER_SANITIZE_STRING);
        }else{
            $service = "noService";
        }

        $formErrors = array();

        if (strlen($name) < 4) {

            $formErrors[] = 'Item Title Must Be At Least 4 Characters';

        }

        if (strlen($desc) < 10) {

            $formErrors[] = 'Item Description Must Be At Least 10 Characters';

        }

        if (empty($price)) {

            $formErrors[] = 'Item Price Can\'t Be Empty';

        }
        if (!is_numeric($price)) {

            $formErrors[] = 'Item Price Can\'t character';

        }

        if (strlen($country) < 2) {

            $formErrors[] = 'Item Title Must Be At Least 2 Characters';

        }
        if ($quantity > 99){

            $formErrors[] = 'Quantity must be less than 100';

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
        if (empty($image1Name)) {
            $formErrors[] = 'Image One Is  <strong>Required</strong>';
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


        // Check If There's No Error Proceed The Update Operation

        if (empty($formErrors)) {
            // Naming Avatar For Database
            $image1 = rand(0,100000000000)."_".$image1Name;
            $image2 = rand(0,100000000000)."_".$image2Name;

            // Insert Userinfo In Database
           if (!empty($image2Name)){
               move_uploaded_file($image1TmpName,'admin/uploads/images/'.$image1);
               move_uploaded_file($image1TmpName,'admin/uploads/images/'.$image2);
            $stmt = $con->prepare("INSERT INTO 

					items(Name, Description, Price, Country_Made, Status, Image1, Image2, Quantity, Add_Date, Cat_ID, Member_ID, tags, Paypal_Ac, Service_shop_item)

					VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, :zimage1, :zimage2, :zquantity, now(), :zcat, :zmember, :ztags, :zpaypal_ac, :zservice_shop_item)");

            $stmt->execute(array(

                'zname' 	        => $name,
                'zdesc' 	        => $desc,
                'zprice' 	        => $price,
                'zcountry' 	        => $country,
                'zstatus' 	        => $status,
                'zimage1' 	        => $image1,
                'zimage2' 	        => $image2,
                'zquantity'         => $quantity,
                'zcat'		        => $category,
                'zmember'	        => $_SESSION['uid'],
                'ztags'		        => $tags,
                'zpaypal_ac'        => $paypal_ac,
                'zservice_shop_item'=> $service

            ));
           }
           if (empty($image2Name)){

               move_uploaded_file($image1TmpName,'admin/uploads/images/'.$image1);

               $stmt = $con->prepare("INSERT INTO 

					items(Name, Description, Price, Country_Made, Status, Image1, Quantity, Add_Date, Cat_ID, Member_ID, tags, Paypal_Ac,Service_shop_item)

					VALUES(:zname, :zdesc, :zprice, :zcountry, :zstatus, :zimage1, :zquantity, now(), :zcat, :zmember, :ztags, :zpaypal_ac, :zservice_shop_item)");

               $stmt->execute(array(

                   'zname' 	=> $name,
                   'zdesc' 	=> $desc,
                   'zprice' 	=> $price,
                   'zcountry' 	=> $country,
                   'zstatus' 	=> $status,
                   'zimage1' 	=> $image1,
                   'zquantity' => $quantity,
                   'zcat'		=> $category,
                   'zmember'	=> $_SESSION['uid'],
                   'ztags'		=> $tags,
                   'zpaypal_ac'=> $paypal_ac,
                   'zservice_shop_item'=> $service


               ));
           }
            // Echo Success Message

            if ($stmt) {

                $succesMsg = 'Item Has Been Added And Wait Approve Admin';

            }

        }
    }
?>
    <style>
        .asterisk {
            position: absolute;
            font-size: 24px;
            color: #d23030;
            right: 21px;
            top: 12px;
        }
    </style>
    <h1 class="text-center">Create My Ad</h1>
    <div class="information block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">Create My Ad</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Start Loopiong Through Errors -->
                            <?php
                            if (! empty($formErrors)) {
                                foreach ($formErrors as $error) {
                                    echo '<div class="alert alert-danger">' . $error . '</div>';
                                }
                            }
                            if (isset($succesMsg)) {
                                echo "
                                      <div class='alert alert-success'>" . $succesMsg . "</div>
                                      <div class='alert alert-info'>You Will Be Redirected To Ypur Items After 3 Seconds.</div>
                                      ";
                                header("refresh:3;url=profile.php#my-ad");

                                exit();
                            }
                            ?>
                            <!-- End Loopiong Through Errors -->
                            <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
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
                                             />
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
                                             />
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
                                            placeholder="Price By Dollar (1$ = 10 dirham)"
                                            autocomplete="off"
                                            data-class=".live-price"
                                            value="1"
                                            min="1"
                                            max="999"
                                            onKeyUp="if(this.value>999){this.value='999';}else if(this.value<0){this.value='1';}"
                                             />
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
                                            required="required"
                                            class="form-control"
                                            placeholder="Country of Made"
                                            autocomplete="off"
                                             />
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
                                                onchange="imagepreview1(this);"
                                                required="required"/>
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
                                <!-- Start Image Item Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Quantity :</label>
                                    <div class="col-sm-10 col-md-9">
                                        <label>
                                            <input
                                                    type="number"
                                                    name="quantity"
                                                    class="form-control"
                                                    placeholder="1"
                                                    onKeyUp="if(this.value>99){this.value='99';}else if(this.value<0){this.value='1';}"
                                                    max="99"
                                                    min="1"
                                                    required
                                            />
                                        </label>
                                    </div>
                                </div>
                                <!-- End Image Item Field -->
                                <!-- Start Status Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label" for="selectStatNew">Status :</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="status"
                                                id="selectStatNew"
                                                class="selectpicker"
                                                data-style="btn-info"
                                                multiple data-max-options="1"
                                                data-live-search="true"
                                                required >
                                            <optgroup label="Status Of Your Item">
                                                <option value="">...</option>
                                                <option value="1">New</option>
                                                <option value="2">Like New</option>
                                                <option value="3">Used</option>
                                                <option value="4">Very Old</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <!-- End Status Field -->
                                <!-- Start Categories Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label" for="selectCatNew">Category :</label>
                                    <div class="col-sm-10 col-md-9">
                                        <select name="category"
                                                id="selectCatNew"
                                                class="selectpicker"
                                                data-style="btn-info"
                                                multiple data-max-options="1"
                                                data-live-search="true"
                                                required>
                                            <optgroup label="Category Of Your Item">
                                                <option value="">...</option>
                                                <?php
                                                $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID");
                                                foreach ($allCats as $cat) {
                                                    echo "<option value='" . $cat['ID'] . "' disabled";
                                                    echo ">" . $cat['Name'] . "</option>";
                                                    $childCats = getAllFrom("*", "categories", "where parent = {$cat['ID']}", "", "ID");
                                                    foreach ($childCats as $child) {
                                                        echo "<option value='" . $child['ID'] . "'";
                                                        echo ">-- " . $child['Name'] . "</option>";
                                                    }
                                                }
                                                ?>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <!-- Start Categories Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Tags :</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input
                                                type="text"
                                                name="tags"
                                                class="form-control"
                                                placeholder="Separate Tags With Comma (,)" />
                                    </div>
                                </div>
                                <!-- End Tags Field -->
                                <!-- Start PaypalCheckBox Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-4 col-md-3 control-label"></label>
                                    <div class="col-sm-8 col-md-9">
                                        <input type="checkbox" onclick="enable_text(this.checked)" /> Check If You Want Sell This Product With Paypal(sell with paypal)
                                    </div>
                                </div>
                                <!-- End PaypalCheckBox Field -->
                                <!-- Start Paypal Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label">Paypal Email :</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input
                                                type="email"
                                                name="paypal_ac"
                                                class="form-control"
                                                placeholder="let empty if you not want sell by paypal"
                                                id="textDisable"
                                                disabled="disabled"/>
                                    </div>
                                </div>
                                <!-- End Paypal Field -->
                                <!-- Start Service Shop Field -->
                                <div class="form-group form-group-lg">
                                    <label class="col-sm-3 control-label" for="free">Service Shop :</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input type="radio" name="service" id="free" value="free" disabled> Free <br>
                                        <input type="radio" name="service" id="paid" value="paid" disabled> Paid
                                    </div>
                                </div>
                                <!-- End Service Shop Field -->
                                <!-- Start Submit Field -->
                                <div class="form-group form-group-lg">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <input
                                                type="submit"
                                                value="Add Item"
                                                class="btn btn-primary btn-sm" />
                                    </div>
                                </div>
                                <!-- End Submit Field -->
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="thumbnail item-box live-preview">
                                    <span class="price-tag">
                                        $<span class="live-price">0</span>
                                    </span>
                                    <div style="display: block">
                                        <img id="imagepreview1" class="img-responsive" alt="image One preview"/>
                                    </div>
                                    <div class="caption" style="display: block">
                                        <h3 class="live-title">Title</h3>
                                        <p class="live-desc">Description</p>
                                    </div>
                            </div>
                            <div class="thumbnail item-box live-preview">
                                <di><img id="imagepreview2" class="img-responsive" alt="image tow preview" /></di>
                            </div>
                        </div>
                    </div>
                    <!-- Start Loopiong Through Errors -->
                </div>
            </div>
        </div>
    </div>

    <?php
} else {
    header('Location: login.php');
    exit();
}
include $tpl . 'footer.php';
ob_end_flush();
?>
