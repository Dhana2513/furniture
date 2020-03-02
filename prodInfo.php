<?php
session_start();
ob_start();
if (!isset($_SESSION["basket"])) {
    $basket = array();
    $_SESSION["basket"] = $basket;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Product Information &#124; DAVA</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <link href="ratingfiles/ratings.css" rel="stylesheet" type="text/css" />

    <link href="css/home.css" rel="stylesheet" type="text/css" />
    <link href="css/prodInfo.css" rel="stylesheet" type="text/css" />
    <!--///////////////////////////////END OF STYLE SHEET ///////////////////////-->

    <script src="ratingfiles/ratings.js" type="text/javascript"></script>
    <script src="javascript/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="javascript/jquery.cycle.all.js" type="text/javascript"></script>
    <script src="javascript/validation.js" type="text/javascript"></script>

    <script type="text/javascript">
        $.fn.cycle.updateActivePagerLink = function(pager, currSlideIndex) {
            $(pager).find('li').removeClass('activeLI').filter('li:eq(' + currSlideIndex + ')').addClass('activeLI');
        };

        $(document).ready(
            function() {
                $('#prodSlides').cycle({
                    fx: 'fade',
                    speed: 'slow',
                    timeout: 0,
                    pager: '#prodThumbs',
                    pagerAnchorBuilder: function(idx, slide) {
                        return '<li><a href="#"><img src="' + slide.src + '" width="50" height="50" /></a></li>';
                    }

                });
            });


        //////// USING RAY'S JAVASCRIPT VALIDATION ///////
        function checkQty() {
            var frmAddToBasket = document.getElementById("frmAddToBasket");
            var qty = frmAddToBasket.txtQty.value;

            if (isEmpty(qty) || qty == 0) {
                alert("Please enter quantity!");
                return false;
            }

            if (!isInteger(qty)) {
                alert("Please enter whole number!");
                return false;
            }

            if (qty < 0) {
                alert("Please enter positive number!")
                return false;
            }

            if (qty > 50) {
                alert("Please contact sales team when ordering more than 50!")
                return false;
            }

        }
    </script>
</head>
<!--///////////////////////////////END OF HEAD///////////////////////////////-->

<body>
    <div id="container">
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
                                    echo "<span id='nItems'>$size</span>"; ?>&nbsp;items
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
        <div id="prodInfoBoxDiv">
            <hr class='thickLine' />
            <div id="addNotificaiton">
                <h3><img src="css/images/rightSign.png" width="20" height="20" alt="rightSign" />
                    This item is added to your basket!</h3>
            </div>
            <?php
            include_once("php/connect.php");
            $prodId = null;
            if (isset($_GET["prodId"]))     // GET PRODUCT ID
            {
                $prodId = $_GET["prodId"];
            }
            if (isset($_GET["itemAdded"])) // IF ADDED SUCCESSFUL DISPLAY NOTIFICATION 
            {
                echo " <script> 
                        $(function() 
                            {
                                $('#addNotificaiton').slideDown();
                            })
                       </script>";
            }
            ///////////// SELECTING ITEM FROM DATABASE ///////////////
            $query = "SELECT * FROM product where prodId='$prodId'";
            $resultSet = mysqli_query($connection, $query);
            if (!$resultSet) die("<ERROR: Cannot execute $query>");
            $fetchedRow = mysqli_fetch_assoc($resultSet);
            ///////////// END OF SELECTING ITEM FROM DATABASE ///////////////
            if ($fetchedRow == null) {
                echo "  <div id='infoNotFoundThickLine'></div>
                            <div id='prodNotFoundDiv'> 
                                <h3>Sorry we couldn't find the product you are looking for...</h3> 
                                <h5>Can't find what you are looking for? Contact our sales assisstants.</h5>
                                <a id='aContinueShop' href='index.php'>Continue shopping</a>
                            </div>";
            } else {
                $id = $fetchedRow["prodId"];
                $name = $fetchedRow["prodName"];
                $imgName = $fetchedRow["prodImage"];
                $prodDesc = $fetchedRow["prodDesc"];
                $type = $fetchedRow["type"];
                $price = $fetchedRow["price"];
                $BigImgNames = $fetchedRow["bigImageNames"];
                $bigImgNamesArray = explode(":", $BigImgNames);
                $bigImageArraySize = sizeof($bigImgNamesArray);
                echo "  
                            <div id='imgDivBg'>
                                <div id='imgDiv'>
                                    <h2>$name</h2>
                                    <div id='prodSlides'>";
                ///////////// SLIDE IMAGES DISPLAYED HERE ///////////////
                for ($i = 0; $i < $bigImageArraySize; $i++) {
                    if ($i == 0) {
                        $className = "firstProdSlide";  // GIVE FIRST CLASS
                    } else {
                        $className = "otherProdSlides"; // GIVE OTHERS CLASS
                    }
                    echo "<img class='$className' src='css/images/$type/$bigImgNamesArray[$i]' alt='Big $type image'/>";
                }
                //////////// END OF SLIDE IMAGES ///////////////

                echo "</div>"; // END OF PROD SLIDES DIV

                echo "<ul id='prodThumbs'></ul>"; // IMAGES THUMBNAILS SELECTORS

                echo "</div>"; // END OF PROD SLIDES DIV IMG DIV

                echo "</div>"; // END OF IMG DIV BG
                $descArray = explode("!!stop!!", $prodDesc);    // EXPLODE PRODUCT DESCRIPTION
                echo "$descArray[0]";
                ///////////// FORMS ARE DISPLAYED HERE///////////////
                echo "<form id='frmAddToBasket' method='post'> 
                              <p>
                                  Price : &#x20B9; $price
                                  <input type='text' name='txtQty' value='1'/> 
                                  <input type='submit' name='btnAddToBasket' value='Add to Basket' onclick='return checkQty();'/> 
                              </p>
                          </form>";
                echo "$descArray[1]";

                if (isset($_POST["btnAddToBasket"])) // IF ADD TO BASKET BUTTON CLICKED
                {
                    $basket = $_SESSION["basket"];
                    $qty = $_POST["txtQty"];
                    $item = array("id" => $id, "name" => $name, "price" => $price, "qty" => $qty, "imageName" => $imgName, "type" => $type);

                    $itemFound = false;
                    $size = sizeof($basket);
                    $i = 0;
                    while ($i < $size && !$itemFound) {
                        $oldId = $basket[$i]["id"];
                        if ($id == $oldId)              // IF PRODUCT ALREADY EXIST ADD QUANTITY
                        {
                            $oldQty = $basket[$i]["qty"];
                            $basket[$i]["qty"] = $oldQty + $qty;
                            $itemFound = true;
                        }
                        $i++;
                    }
                    if (!$itemFound)  // IF PRODUCT NOT FOUND THEN ADD IT TO THE BASKET
                    {
                        $basket[] = $item;
                    }

                    $_SESSION["basket"] = $basket; // UDPATE SESSION VARIABLE

                    $setId = "";
                    $setName = "";
                    $setPrice = "";
                    $setQty = "";
                    $setImageName = "";
                    $setType = "";

                    $sessionSize = sizeof($_SESSION["basket"]);

                    for ($i = 0; $i < $sessionSize; $i++) {
                        $setId        .= ":" . $_SESSION["basket"][$i]["id"];
                        $setName      .= ":" . $_SESSION["basket"][$i]["name"];
                        $setPrice     .= ":" . $_SESSION["basket"][$i]["price"];
                        $setQty       .= ":" . $_SESSION["basket"][$i]["qty"];
                        $setImageName .= ":" . $_SESSION["basket"][$i]["imageName"];
                        $setType      .= ":" . $_SESSION["basket"][$i]["type"];
                    }
                    $subId = substr($setId, 1);
                    $subName = substr($setName, 1);
                    $subPrice = substr($setPrice, 1);
                    $subQty = substr($setQty, 1);
                    $subImageName = substr($setImageName, 1);
                    $subType = substr($setType, 1);

                    setcookie("basket[id]", $subId, time() + 3600);
                    setcookie("basket[name]", $subName, time() + 3600);
                    setcookie("basket[price]", $subPrice, time() + 3600);
                    setcookie("basket[qty]", $subQty, time() + 3600);
                    setcookie("basket[imageName]", $subImageName, time() + 3600);
                    setcookie("basket[type]", $subType, time() + 3600);

                    header("Location: prodInfo.php?prodId=" . $id . "&itemAdded=added"); //DISABLE REFRESH
                }
            }   // END OF ELSE IF FETCHED ROW NULL
            ?>
            <div id="infoThickLine"> </div> <!-- THICK LINE AT THE BOTTOM -->
        </div>
        <!--///////////////////////////////END OF PRODUCT INFO BOX //////////////////-->

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
<?php ob_flush(); ?>