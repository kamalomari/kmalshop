<!doctype html>
<html lang="en">
<head>
    <title><? getTitle()?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap core CSS -->
    <link href="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <!-- selectboxt CDN CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.selectboxit/3.8.0/jquery.selectBoxIt.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.js"></script>
    <![endif]-->
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,700,900,900i">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <link rel="icon" href="images/one.png">
    <link rel="stylesheet" href="<? echo $css; ?>frontend.css">
    <link rel='stylesheet' href='<? echo $css;?>hover.css' />
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <link href="https://fonts.googleapis.com/css?family=Press+Start+2P" rel="stylesheet">
</head>
<!--<body oncontextmenu="return false" onkeydown="return false;" onmousedown="return false;" > this for disable auto all ctr=r f12 .... -->
<body>
<!-- Start Section Tool Box -->
<section class="option-box">
    <div class="color-option">
        <h3 class="text-center" style="font-family: 'Cinzel Decorative', cursive !important;color: #dc143c">My Box</h3>
        <?php
        if (basename($_SERVER["PHP_SELF"]) === "rr"){// "index.php" i'm change because there is google translate while develop My Translate
        ?>
        <h4 class="text-success">- Select Languge </h4>
        <ul>
            <?php
            if ($_COOKIE["chooseLang"] === "eng"){
                echo "
                        <li><a href='index.php' onclick='chooseLang(\"arb\")'>Arabic</a></li>
                        <li><a href='index.php' onclick='chooseLang(\"frn\")'>Francais</a></li>
                ";}elseif($_COOKIE["chooseLang"] === "frn"){
                echo "
                        <li><a href='index.php' onclick='chooseLang(\"eng\")'>English</a></li>
                        <li><a href='index.php' onclick='chooseLang(\"arb\")'>Arabic</a></li>
                ";}elseif($_COOKIE["chooseLang"] === "arb"){
                 echo "
                        <li><a href='index.php' onclick='chooseLang(\"eng\")'>English</a></li>
                        <li><a href='index.php' onclick='chooseLang(\"frn\")'>Francais</a></li>
                    ";}elseif(!$_COOKIE["chooseLang"]){
                echo "
                        <li><a href='index.php' onclick='chooseLang(\"arb\")'>Arabic</a></li>
                        <li><a href='index.php' onclick='chooseLang(\"frn\")'>Francais</a></li>
                "; ?>

         <?   } ?>
        </ul>
         <?   } ?>
            <h4 class="text-success">- New Message </h4>
            <h5 class="text-info">* My Sales </h5>
        <?

        ?>
    </div>
    <i class="fa fa-gear fa-2x gear-check"></i>
</section>
<!-- End Section Tool Box -->
<div class="upper-bar">
    <div class="container">

        <?php
        if (isset($_SESSION['user'])){
            $getAll = $con->prepare("SELECT Avatar,UserID FROM `users` WHERE Username = ?");

            $getAll->execute(array($_SESSION['user']));

            $row = $getAll->fetch();
            if (empty($row["Avatar"])){
                echo "<a href='admin/uploads/avatars/johnDoe.png'><img class='my-image img-thumbnail img-circle'  src='admin/uploads/avatars/johnDoe.png' alt='johnDoe'></a>";
            }else {
                echo "<a href='admin/uploads/avatars/". $row['Avatar']."'><img class='my-image img-thumbnail img-circle'  src='admin/uploads/avatars/" . $row['Avatar'] . "' alt=''></a>";
            }
            ?>
            <div class="btn-group my-info" style="float: left">
                    <span class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <? echo $sessionUser ?>
                        <span class="caret"></span>
                    </span>
                    <ul class="dropdown-menu">
                        <?
                        $encryptedUserid = my_simple_crypt($row["UserID"] , 'e' );
                        ?>
                        <li><a href="profile.php?userid=<? echo $encryptedUserid; ?>">My profile</a></li>
                        <li><a href="newad.php">Newad</a></li>
                        <li><a href="profile.php#my-ad">My Items</a></li>
                        <li><a href="logout.php">Logout <i class="fa fa-sign-out"></i></a></li>
                    </ul>
            </div>
      <?  }else{?>
            <div style="float: left">
            <a href="login.php">
                <span class="pull-right">Login/Singup</span>
            </a>
            </div>
        <? } ?>
        <!--no scrpt sbmt one -->
        <noscript id="barNoScript">
            <code>Your Browser Not Support Javascript Or You Block Javascript if that Please Allow Javascript</code>
        </noscript>
        <div id="google_translate_element" style="float: right;top: 0;right: 0"></div>
    </div>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
        }
    </script>
</div>
<!-- Start Our Navbar -->

<nav class="navbar navbar-inverse" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#ournavbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand wobble-horizontal" href="index.php"><? echo lang("BRAND");?><span >Shop</span></a>
        </div>
        <div class="collapse navbar-collapse" id="ournavbar">
            <form class="navbar-form navbar-left" action="indexSearch.php" method="POST">
                <div class="input-group noAstrisk">
                    <input
                            type="search"
                            name="searchN"
                            class="form-control"
                            placeholder="Search..."
                            maxlength="50"
                            required/>
                    <div class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                            <i class="glyphicon glyphicon-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <ul class="nav navbar-nav navbar-right">
                <li <? if ($pageTitle === "Profile"){echo "class='active'";} ?>><a href="index.php"><? echo lang("HOME_ADMIN");?></a></li>
                <li <? if ($pageTitle === "About US"){echo "class='active'";} ?>><a href="about.php"><? echo lang("ABOUT");?></a></li>
                <li <? if ($pageTitle === "FAQ"){echo "class='active'";} ?>><a href="faq.php"><? echo lang("FAQ");?></a></li>
                <li><a href="#" class="mapClick">Map</a></li>
                <li <? if ($pageTitle === "Categories"){echo "class='active'";} ?> class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">Categories<span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <?php
                        $allCats = getAllFrom("*", "categories", "where parent = 0", "", "ID", "ASC");
                        foreach ($allCats as $cats){
                            $encryptedPageidparent = my_simple_crypt($cats['ID'] , 'e' );
                            ?>
                            <li>
                                <a href='categories.php?pageid=<? echo $encryptedPageidparent ?>'>
                                    <span class="spanCatParent"><? echo  $cats['Name']; ?></span>
                                </a>
                            </li>
                            <?php
                            $childCats = getAllFrom("*", "categories", "where parent = {$cats['ID']}", "", "ID");
                            foreach ($childCats as $child) {//$child['ID']
                                $encryptedPageidchild = my_simple_crypt($child['ID'] , 'e' );
                                echo "<li><a href='categories.php?pageid=".$encryptedPageidchild."'";
                                echo ">-- " . $child['Name'] . "</a></li>";
                            }
                            ?>
                      <? }
                        ?>
                    </ul>
                </li>
                <li <? if ($pageTitle === "Contact US"){echo "class='active'";} ?>><a href="contact.php">Contact US</a></li>
            </ul>
        </div>
    </div> <!-- End Of The Container -->
</nav>

<!-- End Our Navbar -->
<?php
if (!isset($noSlider)){
?>
<!-- Start Carousel -->
<div id="first-slider">
    <div id="carousel-example-generic" class="carousel slide carousel-fade">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
            <li data-target="#carousel-example-generic" data-slide-to="3"></li>
        </ol>
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <!-- Item 1 -->
            <div class="item active slide1">
                <div class="row"><div class="container">
                        <div class="col-md-10 text-center">
                            <h3 data-animation="animated bounceInDown">Add images, or even your logo!</h3>
                            <h4 data-animation="animated bounceInUp">Easily use stunning effects</h4>
                        </div>
                    </div></div>
            </div>
            <!-- Item 2 -->
            <div class="item slide2">
                <div class="row"><div class="container">
                    </div></div>
            </div>
            <!-- Item 3 -->
            <div class="item slide3">
                <div class="row"><div class="container">
                    </div></div>
            </div>
            <!-- Item 4 -->
            <div class="item slide4">
                <div class="row"><div class="container">
                    </div></div>
            </div>
            <!-- End Item 4 -->

        </div>
        <!-- End Wrapper for slides-->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <i class="fa fa-angle-left"></i><span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <i class="fa fa-angle-right"></i><span class="sr-only">Next</span>
        </a>
    </div>
</div>
<!-- End Carousel -->
<?php  } ?>
<!--no scrpt sbmt two -->
<noscript>
    <style>
        #frmH{display: none;}
        #qnty{opacity: .5;pointer-events: none;}
    </style><!-- bideH belemE tormF -->
</noscript>
