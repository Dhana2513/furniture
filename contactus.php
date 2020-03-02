<?php
session_start();
if (isset($_COOKIE["basket"])) {
    foreach ($_COOKIE["basket"] as $name => $value) {
        if ($name == "id") {
            $ids = explode(":", $value);
        }
        if ($name == "name") {
            $names = explode(":", $value);
        }
        if ($name == "price") {
            $prices = explode(":", $value);
        }
        if ($name == "qty") {
            $qtys = explode(":", $value);
        }
        if ($name == "imageName") {
            $imageNames = explode(":", $value);
        }
        if ($name == "type") {
            $type = explode(":", $value);
        }
    }
    $sizeIds = sizeof($ids);
    for ($i = 0; $i <  $sizeIds; $i++) {
        $basket[] = array("id" => $ids[$i], "name" => $names[$i], "price" => $prices[$i], "qty" => $qtys[$i], "imageName" => $imageNames[$i], "type" => $type[$i]);
    }
    $_SESSION["basket"] = $basket;
} else if (!isset($_SESSION["basket"])) {
    $basket = array();
    $_SESSION["basket"] = $basket;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Furniture &amp; House Decoration &#124; DAVA</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <link href="css/home.css" rel="stylesheet" type="text/css" />
    <!--///////////////////////////////END OF STYLE SHEET ///////////////////////-->

    <script src="javascript/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="javascript/jquery.cycle.all.js" type="text/javascript"></script>
    <script src="javascript/jquery.easing.1.3.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(
            function() {
                $('#imgSlides').cycle({
                    fx: 'fade',
                    speed: 800,
                    timeout: 3000,
                    pager: '#ulThumbs',
                    pause: 1,
                    pagerAnchorBuilder: function(idx, slide) {
                        // return sel string for existing anchor
                        return '#ulThumbs li:eq(' + (idx) + ') a';
                    }
                });

                $('#featuredSlides').cycle({
                    fx: 'scrollHorz',
                    timeout: 0,
                    next: '#right',
                    prev: '#left',
                    nowrap: 0
                });
            });
    </script>
</head>
<!--///////////////////////////////END OF HEAD///////////////////////////////-->

<body>
    <div id="containerDiv">
        <div id="headerDiv">
            <!--/////////////////////////// WELCOME USER ////////////////////////////////-->
            <?php
            if (isset($_POST["btnLogout"])) {
                unset($_SESSION["customer"]);
            }
            if (isset($_SESSION["customer"])) {
                $custName = $_SESSION["customer"]["name"];
                echo "<span id='welcomeSpan'><a id='aWelcome' href='account.php'>Welcome, $custName</a></span>";
                echo "  <script> 
                            $(function() 
                                {
                                    $('#login').remove();
                                })
                            </script>";
            }
            ?>
            <!--///////////////////////// END OF WELCOME USER ///////////////////////////-->
            <p>
                <a id="login" href="login.php">login &#124;</a>
                <a id="cart" href="basket.php">
                    <img src="css/images/imgCartW26xH26.png" width="26" height="26" alt="Cart Image" />
                    my cart&nbsp;<?php $size = sizeof($_SESSION["basket"]);
                                    echo "$size"; ?>&nbsp;items
                </a>
            </p>
        </div>
        <!--///////////////////////////////NAVIGATION PANEL//////////////////////////-->
        <form action="search.php" method="post">
            <div id="navigationDiv">
                <ul>
                    <li> <a class="logo" href="index.php"></a> </li>
                    <li> <a class="button" style="width:110px" href="prodList.php?prodType=bed">BEDS</a> </li>
                    <li> <a class="button" style="width:110px" href="prodList.php?prodType=chair">CHAIRS</a> </li>
                    <li> <a class="button" style="width:110px" href="prodList.php?prodType=chest">CHESTS</a> </li>
                    <li> <a class="button" style="width:120px" href="contactus.php">Contact Us</a> </li>
                    <li class="txtNav"> <input type="text" name="txtSearch" /> </li>
                    <li class="searchNav"> <input type="submit" name="btnSearch" value="" /> </li>
                </ul>
            </div>
        </form>
        <!--///////////////////////////////END OF NAVIGATION/////////////////////////-->

      
</body>

</html>