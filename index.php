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

        <div id="indexBoxDiv1">
            <div id="imgSlides">
                <img class="imgFirst" src="css/images/imgCenter1W650xH366.jpg" width="650" height="366" alt="" />
                <img class="imgOthers" src="css/images/imgCenter2W650xH366.jpg" width="650" height="366" alt="" />
                <img class="imgOthers" src="css/images/imgCenter3W650xH366.jpg" width="650" height="366" alt="" />
                <img class="imgOthers" src="css/images/imgCenter4W650xH366.jpg" width="650" height="366" alt="" />
                <img class="imgOthers" src="css/images/imgCenter5W650xH366.jpg" width="650" height="366" alt="" />
            </div>

            <ul id="ulThumbs">
                <li><a href="#"><img src="css/images/imgThumb1W116xH65.jpg" width="116" height="65" alt="thumbImage" /></a></li>
                <li><a class="middleThumb" href="#"><img src="css/images/imgThumb2W116xH65.jpg" width="116" height="65" alt="" /></a></li>
                <li><a class="middleThumb" href="#"><img src="css/images/imgThumb3W116xH65.jpg" width="116" height="65" alt="" /></a></li>
                <li><a class="middleThumb" href="#"><img src="css/images/imgThumb4W116xH65.jpg" width="116" height="65" alt="" /></a></li>
                <li><a href="#"><img src="css/images/imgThumb5W116xH65.jpg" width="116" height="65" alt="thumbImage" /></a></li>
            </ul>
        </div>
        <!--///////////////////////////////END OF BOX DIV 1  /////////////////////////-->

        <div id="indexBoxDiv2">
            <div id="content1">
                <!--World Class Service-->
            </div>
            <div id="content2">
                <div id="prodNavBG">
                    <ul id="prodNav">
                        <li><a id="right" href=""></a></li>
                        <li>
                            <h6>FEATURED PRODUCTS </h6>
                        </li>
                        <li><a id="left" href=""></a></li>
                    </ul>
                </div>

                <div id="featuredSlides">
                    <div class="slide">
                        <a href="prodInfo.php?prodId=04&amp;type=bed"> <img src="css/images/bed/bed4.jpg" width="158" height="158" alt="" /> </a>
                        <a href="prodInfo.php?prodId=08&amp;type=bed"> <img src="css/images/bed/bed8.jpg" width="158" height="158" alt="" /> </a>
                        <a href="prodInfo.php?prodId=03&amp;type=bed"> <img src="css/images/bed/bed3.jpg" width="158" height="158" alt="" /> </a>
                    </div>

                    <div class="slide">
                        <a href="prodInfo.php?prodId=13&amp;type=chair"> <img src="css/images/chair/chair1.jpg" width="158" height="158" alt="" /> </a>
                        <a href="prodInfo.php?prodId=14&amp;type=chair"> <img src="css/images/chair/chair2.jpg" width="158" height="158" alt="" /> </a>
                        <a href="prodInfo.php?prodId=15&amp;type=chair"> <img src="css/images/chair/chair3.jpg" width="158" height="158" alt="" /> </a>
                    </div>
                </div>
            </div>

        </div>
        <!--///////////////////////////////END OF BOX DIV 2  /////////////////////////-->

        <div id="indexBoxDiv3">
            <h4> <span class="orange"> DAVA&reg;</span> House Furniture and Decoration</h4>
            <p>
                We are one of the most successful retailers of luxury house furniture in the Pune. Our shop has established in 1989 and now
                is a leading retailer of house and office furniture and decoration. Our online shop has large selection of beds, chairs,
                and, storage cabinets. For those, who want to create most relaxing atmosphere and peace of mind in their homes, DAVA house furniture shop
                is the perfect place to start. We have over 10 branches all over the Pune and still we strive to offer best possible customer satisfaction
                with unrivalled choice of products and peace of mind. Our sales teams are known for their highly reliable support and commitment.
            </p>
        </div>

        <div id="footerDiv">
            <p>
                <a href="#">Terms of Use</a>
                &#124;
                <a href="#">Privacy Policy</a>
                &#124;
                <a href="#">&copy;2020 All Rights Reserved.</a>
            </p>
        </div>
    </div>
    <!--///////////////////////////////END OF CONTAINER /////////////////////////-->
</body>

</html>