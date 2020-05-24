<!DOCTYPE html>
<?php 
require 'tools.php';
?>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">

  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  <!-- Popper JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

  <!-- Latest compiled JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="style.css">
  <?php
  
  $errorCount = 0;
  $emptySeat = 0;

  // -----Form processing-----
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer = $_POST["cust"];
    $movie = $_POST['movie'];
    $seatings = $_POST['seats'];
    $total =  calToltal($seatings, $movie['day'], $movie['hour']);

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

    if ($errorCount == 0) {
      $_SESSION['cart'] = $_POST;
      //header("Location: receipt.php");
    }else{
      $formErr= "One of your input is wrong please try again.";
    }
  }
  if (isset($_POST['session-reset'])) {
    unset($_SESSION["email"]);
  }
  ?>
</head>

<body>

  <nav>
    <!--<ul id="mainNav">
      <li class="nav-link"><a href="#home">Home</a></li>
      <li class="nav-link"><a href="#work">Work</a></li>
      <li class="nav-link"><a href="#about">About</a></li>
      <li class="nav-link"><a href="#contact">Contact</a></li>
    </ul>-->
  </nav>
  <section id="home">
    <h2>Home</h2>
    <div id="ACT" name="avenger-end-game">
      <h4 id="titleACT">Anvenger</h4>
      <button id="wedACT" onclick="initializeSynop('ACT', 'wed')">Wednesday | 12pm (T12)</button>
      <button id="thuACT" onclick="initializeSynop('ACT', 'thu')">Thursday | 9pm (T21)</button>
    </div>
    <span class="error"> <?php echo $formErr; ?></span>
    <div class="container" id='booking' style="display:none">
      <form name="myForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" target="_self" method="post">
        <input type="hidden" id="movieId" name="movie[id]" value="">
        <input type="hidden" id="movieDay" name="movie[day]" value="">
        <input type="hidden" id="movieHour" name="movie[hour]" value="">

        <span id="formHeader"></span>

        <div class="form-row">
          <div class="col-md-4">
            <h4>Standard</h4>
            <div style="border: solid black; padding: 10px; margin: 10px;">
              <div class="form-inline">
                <label class="mb-2 mr-sm-2" for="numAdultsSTA">Adult</label>
                <select class="form-control mb-2 mr-sm-2 seats" name="seats[STA]" id="STA" onchange="updateTotal()">
                  <option value='' selected>Choose</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                </select>
              </div>

              <div class="form-inline">
                <label class="mb-2 mr-sm-2 right-align-item" for="numConcSTP">Concession</label>
                <select class="form-control mb-2 mr-sm-2 seats" name="seats[STP]" id="STP" onchange="updateTotal()">
                  <option value="" selected>Choose</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                </select>
              </div>
              <div class="form-inline">
                <label class="mb-2 mr-sm-2" for="numChildSTC">Children</label>
                <select class="form-control mb-2 mr-sm-2 seats" name="seats[STC]" id="STC" onchange="updateTotal()">
                  <option value="" selected>Choose</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                </select>
              </div>
            </div>
            <h4>First Class</h4>
            <div style="border: solid black; padding: 10px; margin: 10px;">
              <div class="form-inline">
                <label class="mb-2 mr-sm-2" for="numAdultsFCA">Adult</label>
                <select class="form-control mb-2 mr-sm-2 seats" name="seats[FCA]" id="FCA" onchange="updateTotal()">
                  <option value="" selected>Choose</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                </select>
              </div>

              <div class="form-inline">
                <label class="mb-2 mr-sm-2 right-align-item" for="numConcFCP">Concession</label>
                <select class="form-control mb-2 mr-sm-2 seats" name="seats[FCP]" id="FCP" onchange="updateTotal()">
                  <option value="" selected>Choose</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                </select>
              </div>
              <div class="form-inline">
                <label class="mb-2 mr-sm-2" for="numChildFCC">Children</label>
                <select class="form-control mb-2 mr-sm-2 seats" name="seats[FCC]" id="FCC" onchange="updateTotal()">
                  <option value="" selected>Choose</option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                </select>
              </div>
            </div>
            <!-- Total -->
            <div class="form-group row">
              <label for="Total" class="col-sm-4 col-form-label">Total &#36;</label>
              <div class="col-sm-5">
                <input type="text" readonly name="total" class="form-control-plaintext" id="Total" placeholder="0.00" required>
              </div>
              <span class="error"> <?php echo $totalErr; ?></span>
            </div>
          </div>

          <div class="col-md-6">
            <h4>Billing</h4>
            <div class="form-group">
              <label for="cusName">Name&#42;</label>
              <input type="text" name="cust[name]" class="form-control billing" id="cusName" placeholder="Personie Person">
              <span class="error"> <?php echo $nameErr; ?></span>
            </div>

            <div class="form-group">
              <label for="inputPhonenum">Phone number&#42;</label>
              <input type="tel" name="cust[mobile]" class="form-control billing" id="inputPhonenum" placeholder="04 1234 5678">
              <span class="error"> <?php echo $mobileErr; ?></span>
            </div>

            <div class="form-group">
              <label for="inputEmail4">Email&#42;</label>
              <input type="email" name="cust[email]" class="form-control billing" id="inputEmail" placeholder="Email">
              <span class="error"> <?php echo $emailErr; ?></span>
            </div>

            <div class="form-group">
              <label for="ccnum">Credit card number&#42;</label>
              <input type="text" name="cust[card]" class="form-control billing" id="inputccnum" placeholder="1111 2222 3333 4444">
              <span class="error"> <?php echo $cardErr; ?></span>
            </div>
            <div class="form-group form-inline">
              <label class="mb-2 mr-sm-2" for="inputExpdate">Expiry:</label>
              <select type="month" name="cust[expMonth]" class="form-control mb-2 mr-sm-2" id="expMonth">
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
              <select type="year" name="cust[expYear]" class="form-control mb-2 mr-sm-2" id="expYear">
                <option value="2000">YYYY</option>
              </select>
              <span class="error"><?php echo $expErr; ?></span>
            </div>
          </div>
        </div>
        <button type="submit" name="book" class="btn btn-primary" value='book'>Book</button>

      </form>
    </div>


  </section>
  <section id="about">
    <h2>About</h2>
  </section>
  <section id="contact">
    <h2>Contact</h2>
  </section>

  <script src="tool.js"></script>
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
  <?php printMyCode();
  ?>
</footer>