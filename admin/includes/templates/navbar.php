<!-- Start Our Navbar -->

<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#ournavbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand wobble-horizontal" href="index.php?lang=eng"><? echo lang("BRAND");?><span >Shop</span></a>
        </div>
        <div class="collapse navbar-collapse" id="ournavbar">
            <ul class="nav navbar-nav navbar-right">
                <li  <? if ($pageTitle === "Categories"){echo "class='active'";} ?>><a href="categories.php"><? echo lang("CATEGORIES");?></a></li>
                <li <? if ($pageTitle === "Items"){echo "class='active'";} ?>><a href="items.php"><? echo lang("ITEMS");?></a></li>
                <li  <? if ($pageTitle === "Members"){echo "class='active'";} ?>><a href="members.php"><? echo lang("MEMBERS");?></a></li>
                <li <? if ($pageTitle === "Comments"){echo "class='active'";} ?> ><a href="comments.php"><? echo lang("COMMENTS");?></a></li>
                <li <? if ($pageTitle === "Contact US"){echo "class='active'";} ?>><a href="contactUS.php">Contact US</a></li>
                <li class="dropdown" <? if ($pageTitle === "Categories"){echo "class='active'";} ?>>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Options <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="../index.php">show shop</a></li>
                        <li><a href="members.php?do=Edit&userid=<? echo $_SESSION["ID"]; ?>">Edit Profiles</a></li>
                        <li><a href="?do=Add">Add</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div> <!-- End Of The Container -->
</nav>

<!-- End Our Navbar -->
<div style="margin-bottom: 20px;margin-top: 60px"></div>