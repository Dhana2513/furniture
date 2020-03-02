<?php
session_start();
if (!isset($_SESSION["basket"])) {
    $basket = array();
    $_SESSION["basket"] = $basket;
}
?>
<!DOCTYPE html>
<html>

<head>
    <?php
    $prodType = "";
    $title = "";
    $image1 = "";
    $image2 = "";
    if (isset($_GET["prodType"]))     // Get product type
    {
        $prodType = $_GET["prodType"];
    }
    if ($prodType == "bed") {
        $title = "Beds";
        $image1 = "<a href='prodInfo.php?prodId=12&amp;type=bed'><img src='css/images/bed/imgBed1W300xH439.jpg' width='300' height='439' alt='Bed images'/></a>";
        $image2 = "<img src='css/images/bed/imgBed2W300xH439.jpg' width='300' height='439' alt='Bed images'/>";
    } else if ($prodType == "chair") {
        $title = "Chairs";
        $image1 = "<a href='prodInfo.php?prodId=13&amp;type=chair'> <img src='css/images/chair/imgChair1W300xH439.jpg' width='300' height='439' alt='Chair images'/> </a>";
        $image2 = "<a href='prodInfo.php?prodId=07&amp;type=chair'><img src='css/images/chair/imgChair2W300xH439.jpg' width='300' height='439' alt='Chair images'/></a>";
    } else if ($prodType == "chest") {
        $title = "Chests";
        $image1 = "<img src='css/images/chest/imgChest1W300xH439.jpg' width='300' height='439' alt='Chest images'/>";
        $image2 = "<img src='css/images/chest/imgChest2W300xH439.jpg' width='300' height='439' alt='Chest images'/>";
    }
    ?>
    <title><?php echo $title; ?> &#124; DAVA</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <link href="css/pagination.css" rel="stylesheet" type="text/css" />
    <link href="css/grey.css" rel="stylesheet" type="text/css" />

    <link href="css/home.css" rel="stylesheet" type="text/css" />
    <link href="css/prodList.css" rel="stylesheet" type="text/css" />
    <!--///////////////////////////////END OF STYLE SHEET ///////////////////////-->
    <script src="javascript/jquery-1.8.3.min.js" type="text/javascript"></script>
</head>

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
                                    echo "$size"; ?>&nbsp;items
                </a>
            </p>
        </div>
        <!--///////////////////////////////NAVIGATION DIV ///////////////////////////-->
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
        <!--///////////////////////////////END OF NAVIGATION ////////////////////////-->
        <div id="prodListDiv">
            <div id="greyBG">
                <h6> Luxury Beds </h6>
            </div>
            <div class="prodImages">
                <?php echo $image1; ?>
            </div>
            <table id="productTable">
                <?php
                include_once("php/connect.php");
                include_once("php/function.php");

                $page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
                $limit = 9;
                $startpoint = ($page * $limit) - $limit;
                $statement = "FROM product where type='$prodType'";
                $query = "SELECT * FROM product where type = '$prodType' LIMIT {$startpoint} , {$limit}";
                $resultSet = mysqli_query($connection, $query);
                if (!$resultSet) die("<ERROR: Cannot execute $query>");
                $fetchedRow = mysqli_fetch_row($resultSet);

                if ($fetchedRow == null) // IF NULL THEN DIRECT TO PRODINFO.PHP   
                {
                    header("Location: prodInfo.php");
                } else {
                    for ($rowNumber = 0; $rowNumber < 3; $rowNumber++) {
                        echo "<tr>";
                        for ($columnNumber = 0; $columnNumber < 3; $columnNumber++) {
                            if ($fetchedRow == null) {
                                echo "<td></td>";
                            } else {
                                $id = $fetchedRow[0];
                                $name = $fetchedRow[1];
                                $imageName = $fetchedRow[2];
                                $price = $fetchedRow[5];
                                $displayImage = "<img src='css/images/$prodType/$imageName' width='158' height='158' alt='tableImage'/>";
                                echo " <td><a href='prodInfo.php?prodId=$id'> $displayImage <p>$name <span class='price'>&#x20B9;$price</span></p></a></td> ";
                                $fetchedRow = mysqli_fetch_row($resultSet);
                            }
                        }
                        echo "</tr>";
                    }
                }
                ?>
            </table>
            <div class="prodImages">
                <?php echo $image2; ?>
            </div>

            <div id="paginationBoxDiv">
                <div id="paginationDiv"><?php echo pagination($statement, $limit, $page, "?prodType=$prodType&amp;", $connection); ?></div>
            </div>
        </div>
        <!--///////////////////////////////END OF PRODUCT LIST //////////////////////-->

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