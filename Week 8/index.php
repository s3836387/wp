<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hello World</title>
</head>

<body>
    /* link: localhost:8888/wp/Week%208/index.php */
    <?php
    require 'tools.php';
    $errorCount = 0;
    $emptySeat = 0;
    if (isset($_SESSION["cart"])) {
        unset($_SESSION["cart"]);
    }
    
    // -----Form processing-----
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $customer = $_POST["cust"];
        $movie = $_POST['movie'];
        $seatings = $_POST['seats'];
        $total =  calToltal($seatings,$movie['day'], $movie['hour']);
        $_POST['total']= $total;
        //-----validate if all seat is empty-----
        if ($total == 0) {
            $totalErr = "You must choose at lease 1 seat.";
            $errorCount++;
        }

        // ----- Name validation -----
        if (empty($customer["name"])) {
            $nameErr = "Name is required";
            $errorCount++;
        } else {
            $name = test_input($customer["name"]);
            if (!preg_match("/^[A-Za-z \-.']{1,100}$/", $name)) {
                $nameErr = "'" . $name . "' " . "Only letters and whitespace are allowed.";
                $errorCount++;
            }
        }

        // ----- Phone validation -----
        if (empty($customer["mobile"])) {
            $mobileErr = "Your phone number is required";
            $errorCount++;
        } else {
            $mobile = test_input($customer["mobile"]);
            if (!preg_match("/^(\(04\)|04|\+614)( ?\d){8}$/", $mobile)) {
                $mobileErr = "'" . $mobile . "' " . "Only Australia number are allowed.";
                $errorCount++;
            }
        }
        // ----- Validate email -----
        if (!isset($customer["email"])) {
            $emailErr = "Email is required";
            $errorCount++;
        } else {
            $email = test_input($customer["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
                $errorCount++;
            }
        }
        //----- Validate credit card -----
        if (empty($customer["card"])) {
            $cardErr = "Your credit card number is required";
            $errorCount++;
        } else {
            $card = test_input($customer["card"]);
            if (!preg_match("/^[0-9]( ?\d){14,19}$/", $card)) {
                $cardErr = "'" . $card . "' " . "Only valid card number allowed.";
                $errorCount++;
            }
        }

        //----- Validate credit card exp date -----
        if (empty($customer["expMonth"])) {
            $expErr = "Your credit card expiry Month is required";
            $errorCount++;
        } else if (empty($customer['expYear'])) {
            $expErr = "Your credit card expiry Year is required";
            $errorCount++;
        } else {
            $expMonth = test_input($customer["expMonth"]);
            $expYear = test_input($customer["expYear"]);
            $expDate = date_create($expYear . '-' . $expMonth . '-01');
            $currentDate = date_create();
            $diff = dateDifference($currentDate, $expDate, '%R%a');
            if ($diff <= 28) {
                $expErr = "Your credit card expired in $diff days. Please enter card that don't expire in a month";
                $errorCount++;
            }
        }

        if ($errorCount == 0){
            $_SESSION['cart'] = $_POST;
            header("Location: receipt.php");
          }
    }
    ?>

    <form name="myForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" target="_self" method="post">

        <input type="hidden" id="movieId" name="movie[id]" value="ACT">
        <input type="hidden" id="movieDay" name="movie[day]" value="FRI">
        <input type="hidden" id="movieHour" name="movie[hour]" value="T12">
        <div>
            <label for="name">Your name:</label>
            <input type="text" name="cust[name]" id='name'>
            <span class="error"> <?php echo $nameErr; ?></span>
            <br>
            <label for="inputPhonenum">Phone number&#42;</label>
            <input type="tel" name="cust[mobile]" class="form-control billing" id="inputPhonenum" placeholder="04 1234 5678">
            <span class="error"> <?php echo $mobileErr; ?></span>
            <br>

            <label for="inputEmail4">Email&#42;</label>
            <input type="email" name="cust[email]" class="form-control billing" id="inputEmail" placeholder="Email">
            <span class="error"> <?php echo $emailErr; ?></span>
            <br>
            <label for="ccnum">Credit card number&#42;</label>
            <input type="text" name="cust[card]" class="form-control billing" id="inputccnum" placeholder="1111 2222 3333 4444">
            <span class="error"> <?php echo $cardErr; ?></span>

            <br>
            <br>
            <label class="mb-2 mr-sm-2" for="inputExpdate">Expiry:</label>
            <select type="month" name="cust[expMonth]" class="form-control mb-2 mr-sm-2" id="expMonth" required>
                <option value="0">MM</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">4</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>
            <select type="year" name="cust[expYear]" class="form-control mb-2 mr-sm-2" id="expYear" required>
                <option value="2020">YYYY</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
            </select>
            <span class="error"> <?php echo $expErr; ?></span>
        </div>
        <div>
            <label class="mb-2 mr-sm-2" for="numAdultsSTA">Adult</label>
            <select class="form-control mb-2 mr-sm-2 seats" name="seats[STA]" id="STA">
                <option value='0' selected>Choose</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
            <label class="mb-2 mr-sm-2 right-align-item" for="numConcSTP">Concession</label>
            <select class="form-control mb-2 mr-sm-2 seats" name="seats[STP]" id="STP" onchange="updateTotal()">
                <option value="0" selected>Choose</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
            <label for="Total" class="col-sm-4 col-form-label">Total &#36;</label>
            <input type="text" readonly name="total" class="form-control-plaintext" id="Total" placeholder="0.00" value="">
        </div>
        <span class="error"> <?php echo $totalErr; ?></span>
        <br><button>Send</button>
        <input type="submit" name="session-reset" value="Reset the session">
    </form>

</body>
<footer>

    <?php
    echo "<h4>POST</h4>";
    preShow($_POST);
    echo "<h4>GET</h4>";
    preShow($_GET);
    echo "<h4>SESSION</h4>";
    preShow($_SESSION);
    ?>
    <h4>POST code</h4>
    <?php
    printMyCode();
    ?>
</footer>

</html>