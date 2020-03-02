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
    <title>Checkout &#124; DAVA</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <link href="css/home.css" rel="stylesheet" type="text/css" />
    <link href="css/basket.css" rel="stylesheet" type="text/css" />
    <link href="css/checkout.css" rel="stylesheet" type="text/css" />
    <!--///////////////////////////////END OF STYLE SHEET ///////////////////////-->
    <script src="javascript/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="javascript/validation.js" type="text/javascript"></script>

    <script type="text/javascript">
        //////// USING RAY'S JAVASCRIPT VALIDATION ///////
        function btnUpdate() {
            var frmUpdate = document.getElementById("frmUpdate");
            var qty = frmUpdate.qtyUpdate.value;

            if (isEmpty(qty)) {
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
        }
    </script>
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

        <div id="basketDiv">
            <h3 id="basketHeading"> Review Order </h3>

            <!--/////////////////////////////// BASKET TABLE ////////////////////////////-->
            <table id="basketTable">
                <tr>
                    <th id="thProdName" colspan="2">Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th id="thLineTotal">&nbsp;&nbsp;Line Total</th>
                </tr>
                <tr>
                    <td class='tdFirstThinLine' colspan='5'> </td>
                </tr>
                <?php
                if (!isset($_SESSION["customer"]) || !isset($_SESSION["basket"])) {
                    header("Location: index.php");
                } else {
                    $basket = $_SESSION["basket"];
                    $total = 0;

                    /////////////// DISPLAY BASKET DATA ////////////////////
                    foreach ($basket as $key => $item) {
                        $id = $item["id"];
                        $type = $item["type"];
                        $imgName = $item["imageName"];
                        $name = $item["name"];
                        $price = $item["price"];
                        $qty = $basket[$key]["qty"];
                        $cost = $qty * $price;
                        $total = $total + ($price * $qty);
                        echo "<tr id='tr$id'>
                                        <td class='tdProdImg'> <img src='css/images/$type/$imgName' width='50' height='52' alt='image $imgName'/> </td>
                                        <td class='tdName'> <p>$name</p> </td>
                                        <td class='tdPrice'> &#x20B9$price </td>
                                        <td class='tdQty'> $qty </td>
                                        <td class='tdLineTotal'>&nbsp;&nbsp;&#x20B9$cost </td>
                                      </tr>
                                      <tr><td class='tdThinLine' colspan='5'> </td></tr>";
                    }
                    /////////////// END OF DISPLAYING BASKET DATA //////////

                    $shippingCost = 50;
                    define("VATRATE", 0.2);
                    // $vat = VATRATE * $total;
                    $grandTotal = $total + $shippingCost;
                    echo "<tr class='trEmptySpace'><td colspan='5'></td></tr> ";
                    echo "<tr>  <td colspan='2'></td>  <td class='tdEnd' colspan='2'>  Subtotal:       </td>  <td class='tdEndData'>&nbsp;&nbsp;&#x20B9;$total         </td>  </tr>";

                    echo "<tr>  <td colspan='2'></td>  <td class='tdEnd' colspan='2'>  Shipping Cost:  </td>  <td class='tdEndData'>&nbsp;&nbsp;&#x20B9;$shippingCost  </td>  </tr>";

                    // echo "<tr>  <td colspan='2'></td>  <td class='tdEnd' colspan='2'>  VAT:            </td>  <td class='tdEndData'>&nbsp;&nbsp;&#x20B9;$vat           </td>  </tr>";

                    echo "<tr>  <td colspan='2'></td>  <td class='tdGrandTotal' colspan='2'>  Grand Total:    </td>  <td class='tdGrandTotalData'>&nbsp;&nbsp;&#x20B9;$grandTotal    </td>  </tr>";
                ?>
            </table>
            <div id="custDetailsDiv">
            <?php
                    $name = $_SESSION["customer"]["name"];
                    $email = $_SESSION["customer"]["email"];
                    $address = $_SESSION["customer"]["address"];
                    $postCode = $_SESSION["customer"]["postCode"];

                    echo "<h6>Your order will be sent</h6>
                                      <p><strong>To:</strong>&nbsp;$name</p>
                                      <p><strong>Address:</strong>&nbsp;$address</p>
                                      <p><strong>PostCode:</strong>&nbsp;$postCode</p>";
                    // echo "<h6>Card details:</strong></h6>
                    //       <p><strong>CardNo:</strong>&nbsp;$cardNo</p>
                    //       <h6>Confirmation email will be sent to:</h6>
                    //       <p><strong>Email:</strong>&nbsp;$email</p>";
                }
            ?>
            </div>

            <div id="checkOutDiv">
                <a id="aContinueShop" href="basket.php">Back to Basket</a>
                <a id="aCheckout" href="thankyou.php">Confirm Order</a>
            </div>



            <div id="basketThickLine">

            </div>
        </div>
        <!--///////////////////////////////END OF BASKET TABLE DIV/////////////////////////-->

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
    <!--///////////////////////////////END OF CONTAINER/////////////////////////-->
</body>

</html>