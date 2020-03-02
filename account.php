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
    <title>Account &#124; DAVA</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <link href="css/home.css" rel="stylesheet" type="text/css" />
    <link href="css/login.css" rel="stylesheet" type="text/css" />
    <link href="css/account.css" rel="stylesheet" type="text/css" />
    <!--///////////////////////////////END OF STYLE SHEET ///////////////////////-->
    <script src="javascript/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="javascript/validation.js" type="text/javascript"></script>

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
        <?php
        include_once("php/connect.php");
        include_once "php/phpValidation.php";
        $errorMessage = "";

        if ((isset($_POST["btnLogout"]))) {
            unset($_SESSION["customer"]);
            header("Location: index.php");
        }

        if (!isset($_SESSION["customer"])) {
            header("Location: index.php");
        } else if (isset($_POST["btnUpdate"])) {

            $firstName = $_POST["txtFirstName"];
            $lastName = $_POST["txtLastName"];
            $postEmail = $_POST["txtEmail"];
            $pwd = $_POST["txtPwd"];
            $address = $_POST["txtAddress"];
            $postCode = $_POST["txtPostCode"];

            $rdyAddress = preg_replace('/\s+/', '', $address);



            if (!preg_match("/^[A-Z]+$/i", $firstName) || strlen($firstName) > 30) {
                $errorMessage = "ERROR: First name must contain only letters";
                if (strlen($firstName) > 30) {
                    $errorMessage = "ERROR: First name length must be less than 30 characters";
                }
            } else if (!preg_match("/^[A-Z]+$/i", $lastName) || strlen($lastName) > 30) {
                $errorMessage = "ERROR: Last name must contain only letters";
                if (strlen($lastName) > 30) {
                    $errorMessage = "ERROR: Last name length must be less than 30 characters";
                }
            } else if (!isEmail($postEmail) || strlen($postEmail) > 50) {
                $errorMessage = "ERROR: Email is not valid email!";
                if (strlen($postEmail) > 50) {
                    $errorMessage = "ERROR: Email length must be less than 50 characters";
                }
            } else if (!preg_match("/^.*(?=.{8,})(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).*$/", $pwd) || strlen($pwd) > 30 || preg_match('/\s+/', $pwd)) {
                $errorMessage = "ERROR: Password is invalid! password length must be 8, it must contain at least one upper case letter and one number";
                if (strlen($pwd) > 30) {
                    $errorMessage = "ERROR: Password length must be less than 30 characters";
                }
                if (preg_match('/\s+/', $pwd)) {
                    $errorMessage = "ERROR: Password must not contain spaces";
                }
            } else if (strlen($rdyAddress) > 50) {
                $errorMessage = "ERROR: Address length must be less than 50 characters";
            }
            // else if (!preg_match("/^([A-Z])([A-Z0-9]+)\s([0-9])([A-Z])([A-Z])$/i", $postCode) || strlen($postCode) > 8)
            // {
            //     $errorMessage = "ERROR: Post code is invalid!";
            //     if(strlen($postCode) > 8)
            //     {
            //         $errorMessage = "ERROR: Post code length be less than 8 characters";
            //     }
            // }
            else {
                /////////////////// QUERY EMAIL EXISTS ? ///////////////
                $currentEmail = $_SESSION["customer"]["email"];

                $query = "SELECT * FROM customer where email = '$postEmail' and email != '$currentEmail'";
                $resultSet = mysqli_query($connection, $query);
                if (!$resultSet) die("<ERROR: Cannot execute $query>");
                $fetchedRow = mysqli_fetch_assoc($resultSet);
                if ($fetchedRow != null) {
                    $errorMessage = "ERROR: Email you want to change is already registered with another account.";
                } else {
                    $salt = "*@!";
                    $hashedPwd = md5($salt . $pwd . $salt);
                    $createQuery = "UPDATE customer
                                             SET firstName = '$firstName', 
                                                 lastName = '$lastName',
                                                 email = '$postEmail', 
                                                 password = '$hashedPwd', 
                                                 address = '$address', 
                                                 postCode = '$postCode'
                                                 where email = '$currentEmail'";
                    $createResult = mysqli_query($connection, $createQuery);
                    if (!$createResult) die("<ERROR: Cannot execute $createQuery>");
                    $tempName = $firstName . " " . $lastName;
                    $_SESSION["customer"]["name"] = $tempName;
                    $_SESSION["customer"]["firstName"] = $firstName;
                    $_SESSION["customer"]["lastName"] = $lastName;
                    $_SESSION["customer"]["email"] = $postEmail;
                    $_SESSION["customer"]["password"] = $pwd;
                    $_SESSION["customer"]["address"] = $address;
                    $_SESSION["customer"]["postCode"] = $postCode;
                    header("Location: account.php?congrat=yes");
                }
            }
        }
        if (isset($_REQUEST["congrat"])) {
            echo "  <script> 
                                    $(function() 
                                        {
                                            $('#frmHasNot').fadeOut('slow');
                                            $('#h3Id').replaceWith('<h3>Congratulation</h3>');
                                            $('#paraId').replaceWith('<p>Your account has been updated.</p>');
                                        })
                            </script>
                            ";
        }
        $firstName = $_SESSION["customer"]["firstName"];
        $lastName = $_SESSION["customer"]["lastName"];
        $email = $_SESSION["customer"]["email"];
        $pwd = $_SESSION["customer"]["password"];
        $address = $_SESSION["customer"]["address"];
        $postCode = $_SESSION["customer"]["postCode"];
        ?>
        <div id="accountBoxDiv">
            <div id="accountThickLine"></div>
            <!--////////////////////////////// ACCOUNT DIV //////////////////////////////-->
            <div id="accountDiv">
                <h3 id="h3Id">Your Account</h3>
                <p id="paraId">Here you can edit your personal information.</p>
                <?php
                if ($errorMessage != "")    // IF ERROR IS NOT EMPTY DISPLAY ERROR MESSAGE
                {
                    echo "  <div id='accountErrorDiv'>
                                        $errorMessage 
                                    </div>
                            <script> 
                            $(function() 
                                {
                                    $('#accountErrorDiv').fadeIn('slow');
                                })
                           </script>";
                }
                ?>
                <form id="frmAccount" method="post">
                    <span class="spanInputs2">First Name:</span>
                    <input type="text" name="txtFirstName" value="<?php echo $firstName ?>" />

                    <span class="spanInputs2">Last Name:</span>
                    <input type="text" name="txtLastName" value="<?php echo $lastName ?>" />

                    <span class="spanInputs2">Email Address:</span>
                    <input type="text" name="txtEmail" value="<?php echo $email ?>" />

                    <span class="spanInputs2">Password:</span>
                    <input type="text" name="txtPwd" value="<?php echo $pwd ?>" />

                    <span class="spanInputs2">Address:</span>
                    <input type="text" name="txtAddress" value="<?php echo $address ?>" />

                    <span class="spanInputs2">Post Code:</span>
                    <input type="text" name="txtPostCode" value="<?php echo $postCode ?>" />

                    <input id="btnUpdate" type="submit" name="btnUpdate" value="Update" />

                    <input id="btnLogout" type="submit" name="btnLogout" value="Logout" />
                </form>
            </div>
            <!--/////////////////////////// END OF ACCOUNT DIV //////////////////////////-->
            <div id="accountImage">
                <img src="css/images/davaW340xH600.jpg" width="340" height="600" alt="account image" />
            </div>
            <div id="loginThickLine">

            </div>
        </div>
        <!--////////////////////////////// END OF LOGIN BOX DIV /////////////////////-->

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