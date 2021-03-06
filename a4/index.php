<!DOCTYPE html>
<html lang='en'>
<!--
   Author: Ngo QUang Khai
 -->

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Assignment 4</title>

  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

  <!-- Keep wireframe.css for debugging, add your css to style.css -->
  <link id='wireframecss' type="text/css" rel="stylesheet" href="../wireframe.css" disabled>
  <link id='stylecss' type="text/css" rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="mystyle.css">
  <script src='../wireframe.js'></script>

  <?php
  require 'tools.php';
  $errorCount = 0;
  $emptySeat = 0;

  // -----Form processing-----
  if (isset($_POST['session-reset'])) {
    foreach ($_SESSION as $something => &$whatever) {
      unset($whatever);
    }
  }
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer = $_POST["cust"];
    $movie = $_POST['movie'];
    $seatings = $_POST['seats'];
    $total =  calToltal($seatings, $movie['day'], $movie['hour']);
    $_POST['total'] = $total;
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
      header("Location: receipt.php");
    } else {
      $formErr = ' <h4 class="error" style="background: #f4a24f; color: red; padding: 10px; align-content: center;font-weight: 800;">
      There is error in your form. Please try again. </h4>';
    }
  }
  ?>

</head>

<body>

  <header>
    <div id="logo">Cinemax</div>
  </header>

  <main>

    <!--Navigation area-->
    <nav id="navbar" class="navbar justify-content-center sticky-top">
      <!--Links-->
      <a class="navbar-brand active" href="#home">Home</a>
      <a class="navbar-brand" href="#aboutUs">About Us</a>
      <a class="navbar-brand" href="#nowShowing">Now Showing</a>
      <a class="navbar-brand" href="#prices">Prices</a>
    </nav>

    <div class="parallax1" id="home"></div>
    <!--About us section-->
    <div id="aboutUs">
      <div class="sectionHeader">
        <h2>ABOUT US</h2>
      </div>
      <div class="parallax2">
        <div id="welcome" class="container float-box">
          <h5 id="lobster">Welcome back!</h5>
          <br>
          <h4>Loyal customer and newcomer.</h4>
          <p>We are happy to introduce you to our new and improve cinema after our period of renovation. For our new
            customers, we are a small long-running cinema at (location). We take pride in our high-quality service at an
            affordable price which you can view below. For our loyal customers, our newly improve sound and new 3D
            projection facilities will surely make you want more.</p>
          <div>Scroll down for more detail</div>
        </div>
      </div>

      <!--What's new-->
      <div id="new">
        <h4>What's new?</h4>
        <span>Introducing our new services with the Dolby Atmos sound system and Dolby 3D Vision projection.</span>
      </div>
      <!--Seatings-->
      <div id="seating" class="container-fluid">
        <div id="seating-content">
          <div class="container">
            <h5>New seating</h5>
          </div>
          <p>We have upgraded our seating options for your comfort. Our options range from your standard seats to
            newly
            super comfy first-class seats. </p>
        </div>
      </div>

      <!--Dolby system-->
      <div id="Dolby-system" class="container-fluid">
        <div id="Dolby-content">
          <div class="container">
            <h5>New Dolby system</h5>
          </div>
          <p><strong>The Dolby Atmos</strong> sound system will enhance your movie experience. With audio objects and
            overhead speakers
            all around the room, the Dolby Atmos sound system capable of delivering a powerful and moving audio.
            <br> <strong>The Dolby 3D vision</strong> projection, along with the Dolby 3D glasses, will deliver a
            full, vivid and crisp
            image for your viewing. It will undoubtedly bring your movie experience to a whole new level.</p>
        </div>
      </div>
    </div>

    <!--Now Showing-->
    <div id="nowShowing" class="container-fluid">
      <div class="container-fluid sectionHeader">
        <h2>NOW SHOWING</h2>
      </div>
      <div id="nowShowing-content" class="container-fluid" style="padding-top: 10px;">
        <div class="row">
          <!----- Avenger card ----->
          <div class="col-md">
            <div class="card sm-3" style="max-width: 600px;">
              <div class="row no-gutters">
                <div class="col-md-5">
                  <img class="card-img" src="Avenger-end-game.jpg" alt="Card image">
                </div>
                <div class="col-md-7 text-center">
                  <div class="card-body">
                    <h4 class="card-title">Avengers: Endgame</h4>
                    <p class="card-text">Rated: PG-13</p>
                    <ul class="card-text">
                      <li>Wednesday | 9pm (T21) </li>
                      <li>Thursday | 9pm (T21) </li>
                      <li>Friday | 9pm (T21) </li>
                      <li>Saturday | 6pm (T18) </li>
                      <li>Sunday | 6pm (T18) </li>
                    </ul>
                    <a href="#synopsisACT" class="stretched-link" onclick="moreDetailToggle('synopsisACT')">
                      Detail
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--Wedding card-->
          <div class="col-md">
            <div class="card mb-3" style="max-width: 600px;">
              <div class="row no-gutters">
                <div class="col-md-5">
                  <img class="card-img" src="top-end-wedding.jpg" alt="Card image">
                </div>
                <div class="col-md-7 text-center">
                  <div class="card-body">
                    <h4 class="card-title">Top End Wedding</h4>
                    <p class="card-text">Rated: MA</p>
                    <ul class="card-text">
                      <li>Monday | 6pm (T18) </li>
                      <li>Tuesday | 6pm (T18) </li>
                      <li>Saturday | 3pm (T15) </li>
                      <li>Sunday | 3pm (T15) </li>
                    </ul>
                    <a id="RMC-btn" href="#synopsisRMC" class="stretched-link" onclick="moreDetailToggle('synopsisRMC')"> Detail</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <!--Dumbo card-->
          <div class="col-md">
            <div class="card mb-3" style="max-width: 600px;">
              <div class="row no-gutters">
                <div class="col-md-5">
                  <img class="card-img" src="dumbo.jpg" alt="Card image">
                </div>
                <div class="col-md-7 text-center">
                  <div class="card-body">
                    <h4 class="card-title">Dumbo</h4>
                    <p class="card-text">Rated: PG</p>
                    <ul class="card-text">
                      <li>Monday | 12pm (T12) </li>
                      <li>Tuesday | 12pm (T12) </li>
                      <li>Wednesday | 6pm (T18) </li>
                      <li>Thursday | 6pm (T18) </li>
                      <li>Friday | 6pm (T18) </li>
                      <li>Saturday | 12pm (T12) </li>
                      <li>Sunday | 12pm (T12) </li>
                    </ul>
                    <a href="#synopsisANM" class="stretched-link" onclick="moreDetailToggle('synopsisANM')">Detail</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!--The prince-->
          <div class="col-md">
            <div class="card mb-3" style="max-width: 600px;">
              <div class="row no-gutters">
                <div class="col-md-5">
                  <img class="card-img" src="The-Happy-Prince-New-Poster.jpg" alt="Card image">
                </div>
                <div class="col-md-7 text-center">
                  <div class="card-body">
                    <h4 class="card-title">The Happy Prince</h4>
                    <p class="card-text">Rated: R</p>
                    <ul class="card-text">
                      <li>Wednesday | 12pm (T12) </li>
                      <li>Thursday | 12pm (T12) </li>
                      <li>Friday | 12pm (T12) </li>
                      <li>Saturday | 9pm (T21) </li>
                      <li>Sunday | 9pm (T21) </li>
                    </ul>
                    <a href="#synopsisAHF" class="stretched-link" onclick="moreDetailToggle('synopsisAHF')">Detail</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ------ Synopsis Area ------- -->
    <!-- Avenger -->
    <div id="synopsisACT" name="Avengers" class="synopsis">
      <div class="container synopsis-content">
        <div class="row">
          <div class="col-md-7 ">
            <h3 id="titleACT">Avengers Endgame</h3>
            <h4>Rated: PG-13</h4>
            <h5>Plot summary</h5>
            <p>After the devastating events of Avengers: Infinity war (2018), the universe is in ruins. With the help
              of remaining allies, the Avengers assemble once more in order to reverse Thanos' actions and restore
              balance
              to the universe.</p>
            <h5>Make a Booking:</h5>
            <ul class="nav">
              <a href="#booking">
                <button id="wedACT" onclick="initializeSynop('ACT', 'wed')">Wednesday | 9pm (T21)</button>
              </a>
              <a href="#booking">
                <button id="thuACT" onclick="initializeSynop('ACT', 'thu')">Thursday | 9pm (T21)</button>
              </a>
              <a href="#booking">
                <button id="friACT" onclick="initializeSynop('ACT', 'fri')">Friday | 9pm (T21)</button>
              </a>
              <a href="#booking"><button id="satACT" onclick="initializeSynop('ACT', 'sat')">Saturday | 6pm
                  (T18)</button></a>

              <a href="#booking"> <button id="sunACT" onclick="initializeSynop('ACT', 'sun')">Sunday | 6pm
                  (T18)</button></a>
            </ul>
          </div>
          <div class="col-md-5">
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/TcMBFSGVi1c" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          </div>
        </div>
      </div>
    </div>

    <!-- Wedding -->
    <div id="synopsisRMC" class="synopsis">
      <div class="container synopsis-content">
        <div class="row">
          <div class="col-md-7 ">
            <h3 id="titleRMC">Top End Wedding</h3>
            <h4>Rated: MA</h4>
            <h5>Plot summary</h5>
            <p>Lauren and Ned are engaged, they are in love, and they have just ten days to find Lauren's mother who has
              gone AWOL somewhere in the remote far north of Australia, reunite her parents and pull off their dream
              wedding.</p>
            <h5>Make a Booking:</h5>
            <ul class="nav">
              <a href="#booking">
                <button id="monRMC" onclick="initializeSynop('RMC', 'mon')">Monday | 6pm (T18)</button>
              </a>
              <a href="#booking">
                <button id="tueRMC" onclick="initializeSynop('RMC', 'tue')">Tuesday | 6pm (T18)</button>
              </a>
              <a href="#booking">
                <button id="satRMC" onclick="initializeSynop('RMC', 'sat')">Saturday | 3pm (T15)</button>
              </a>
              <a href="#booking"><button id="sunRMC" onclick="initializeSynop('RMC', 'sun')">Sunday | 3pm (T15)</button>
              </a>
            </ul>
          </div>
          <div class="col-md-5">
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/uoDBvGF9pPU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          </div>
        </div>
      </div>
    </div>

    <!-- Dumbo -->
    <div id="synopsisANM" class="synopsis">
      <div class="container synopsis-content">
        <div class="row">
          <div class="col-md-7 ">
            <h3 id="titleANM">Dumbo</h3>
            <h4>Rated: PG</h4>
            <h5>Plot summary</h5>
            <p>A young elephant, whose oversized ears enable him to fly, helps save a struggling circus, but when the
              circus plans a new venture, Dumbo and his friends discover dark secrets beneath its shiny veneer.</p>
            <h5>Make a Booking:</h5>
            <ul class="nav">
              <a href="#booking">
                <button id="monANM" onclick="initializeSynop('ANM', 'mon')">Monday | 12pm (T12)</button>
              </a>
              <a href="#booking">
                <button id="tueANM" onclick="initializeSynop('ANM', 'tue')">Tuesday | 12pm (T12)</button>
              </a>
              <a href="#booking">
                <button id="wedANM" onclick="initializeSynop('ANM', 'wed')">Wednesday | 6pm (T18)</button>
              </a>
              <a href="#booking">
                <button id="thuANM" onclick="initializeSynop('ANM', 'thu')">Thursday | 6pm (T18)</button>
              </a>
              <a href="#booking">
                <button id="friANM" onclick="initializeSynop('ANM', 'fri')">Friday | 6pm (T18)</button>
              </a>
              <a href="#booking">
                <button id="satANM" onclick="initializeSynop('ANM', 'sat')">Saturday | 12pm (T12)</button>
              </a>
              <a href="#booking">
                <button id="sunANM" onclick="initializeSynop('ANM', 'sun')">Sunday | 12pm (T12)</button>
              </a>
            </ul>
          </div>
          <div class="col-md-5">
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/7NiYVoqBt-8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          </div>
        </div>
      </div>
    </div>

    <!-- The Prince -->
    <div id="synopsisAHF" class="synopsis">
      <div class="container synopsis-content">
        <div class="row">
          <div class="col-md-7 ">
            <h3 id="titleAHF">The Happy Prince</h3>
            <h4>Rated: R</h4>
            <h5>Plot summary</h5>
            <p>
              The untold story of the last days in the tragic times of Oscar Wilde, a person who observes his own
              failure with ironic distance and regards the difficulties that beset his life with detachment and humor.
            </p>
            <h5>Make a Booking:</h5>
            <ul class="nav">
              <a href="#booking">
                <button id="wedAHF" onclick="initializeSynop('AHF', 'wed')">Wednesday | 12pm (T12)</button>
              </a>
              <a href="#booking">
                <button id="thuAHF" onclick="initializeSynop('AHF', 'thu')">Thursday | 12pm (T12)</button>
              </a>
              <a href="#booking">
                <button id="friAHF" onclick="initializeSynop('AHF', 'fri')">Friday | 12pm (T12)</button>
              </a>
              <a href="#booking">
                <button id="satAHF" onclick="initializeSynop('AHF', 'sat')">Saturday | 9pm (T21)</button>
              </a>
              <a href="#booking">
                <button id="sunAHF" onclick="initializeSynop('AHF', 'sun')">Sunday | 12pm (T12)</button>
              </a>
            </ul>
          </div>
          <div class="col-md-5">
            <iframe width="100%" height="100%" src="https://www.youtube.com/embed/bE2a5fzLvYI" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          </div>
        </div>
      </div>
    </div>

    <!--Prices-->
    <div id="prices" class="container-fluid">
      <div class="sectionHeader">
        <h2>OUR PRICES</h2>
      </div>

      <table id="tprice" class="table table-border table-striped table-hover">
        <tr>
          <th>Seat Type</th>
          <th>Seat Code</th>
          <th>All day Monday and Wednesday AND 12pm on Weekdays</th>
          <th>All other times</th>
        </tr>
        <tr>
          <td>Standard Adult</td>
          <td>STA</td>
          <td>14.00 &#36;</td>
          <td>19.80 &#36;</td>
        </tr>
        <tr>
          <td>Standard Concession</td>
          <td>STP</td>
          <td>12.50 &#36;</td>
          <td>17.50 &#36;</td>
        </tr>
        <tr>
          <td>Standard Child</td>
          <td>STC</td>
          <td>11.00 &#36;</td>
          <td>15.30 &#36;</td>
        </tr>
        <tr>
          <td>First Class Adult</td>
          <td>FCA</td>
          <td>24.00 &#36;</td>
          <td>30.00 &#36;</td>
        </tr>
        <tr>
          <td>First Class Concession</td>
          <td>FCP</td>
          <td>22.50 &#36;</td>
          <td>27.00 &#36;</td>
        </tr>
        <tr>
          <td>First Class Child</td>
          <td>FCC</td>
          <td>21.00 &#36;</td>
          <td>24.00 &#36;</td>
        </tr>
      </table>
    </div>

    <!--Booking are-->
    <div id="booking" class="container-fluid">
      <div class="sectionHeader">
        <h2>Booking</h2>
      </div>
      <div class="container-fluid parallax2 row justify-content-center">
        <div id="bookingPlaceholder">
          <span>
            <?php echo $formErr; ?>
          </span>
          <h3> Please select a movie and time to display booking form</h3>
        </div>
        <div id="bookingForm" class="col-md-7">
          <form name="myForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" target="_self" method="post">
            <input type="hidden" id="movieId" name="movie[id]" value="">
            <input type="hidden" id="movieDay" name="movie[day]" value="">
            <input type="hidden" id="movieHour" name="movie[hour]" value="">

            <span id="formHeader"></span>

            <div class="form-row">
              <div class="col-md-5">
                <h4>Standard</h4>
                <div style="border: solid black; padding: 10px; margin: 10px;">
                  <div class="form-inline">
                    <label class="mb-2 mr-sm-2" for="numAdultsSTA">Adult</label>
                    <select class="form-control mb-2 mr-sm-2 seats" name="seats[STA]" id="STA" onchange="updateTotal()">
                      <option value='0' selected>Please select</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10">10</option>
                    </select>
                  </div>

                  <div class="form-inline">
                    <label class="mb-2 mr-sm-2 right-align-item" for="numConcSTP">Concession</label>
                    <select class="form-control mb-2 mr-sm-2 seats" name="seats[STP]" id="STP" onchange="updateTotal()">
                      <option value='0' selected>Please select</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10">10</option>
                    </select>
                  </div>
                  <div class="form-inline">
                    <label class="mb-2 mr-sm-2" for="numChildSTC">Children</label>
                    <select class="form-control mb-2 mr-sm-2 seats" name="seats[STC]" id="STC" onchange="updateTotal()">
                      <option value='0' selected>Please select</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10">10</option>
                    </select>
                  </div>
                </div>
                <h4>First Class</h4>
                <div style="border: solid black; padding: 10px; margin: 10px;">
                  <div class="form-inline">
                    <label class="mb-2 mr-sm-2" for="numAdultsFCA">Adult</label>
                    <select class="form-control mb-2 mr-sm-2 seats" name="seats[FCA]" id="FCA" onchange="updateTotal()">
                      <option value='0' selected>Please select</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10">10</option>
                    </select>
                  </div>

                  <div class="form-inline">
                    <label class="mb-2 mr-sm-2 right-align-item" for="numConcFCP">Concession</label>
                    <select class="form-control mb-2 mr-sm-2 seats" name="seats[FCP]" id="FCP" onchange="updateTotal()">
                      <option value='0' selected>Please select</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10">10</option>
                    </select>
                  </div>
                  <div class="form-inline">
                    <label class="mb-2 mr-sm-2" for="numChildFCC">Children</label>
                    <select class="form-control mb-2 mr-sm-2 seats" name="seats[FCC]" id="FCC" onchange="updateTotal()">
                      <option value='0' selected>Please select</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10">10</option>
                    </select>
                  </div>
                </div>
                <!-- Total -->
                <div class="form-group row">
                  <label for="Total" class="col-sm-4 col-form-label">Total:</label>
                  <div class="col-sm-5">
                    <input type="text" readonly name="total" class="form-control-plaintext" id="Total" placeholder="0.00">
                  </div>
                  <span class="error"> <?php echo $totalErr; ?></span>
                </div>
              </div>

              <div class="col-md-6">
                <h4>Billing</h4>
                <div class="form-group">
                  <label for="cusName">Name&#42;</label>
                  <input type="text" name="cust[name]" class="form-control billing" id="cusName" placeholder="Personie Person" pattern="^[A-Za-z \-.']{1,100}$" onblur="blankCheck('cusName','nameError' )" required>
                  <span class="error" id="nameError"></span>
                  <span><?php echo $nameErr; ?></span>
                </div>

                <div class="form-group">
                  <label for="inputPhonenum">Phone number&#42;</label>
                  <input type="tel" name="cust[mobile]" class="form-control billing" id="inputPhonenum" placeholder="04 1234 5678" pattern="^(\(04\)|04|\+614)( ?\d){8}$" onblur="blankCheck('inputPhonenum','phoneError')" required>
                  <span class="error" id="phoneError"></span>
                  <span class="error"> <?php echo $mobileErr; ?></span>
                </div>

                <div class="form-group">
                  <label for="inputEmail4">Email&#42;</label>
                  <input type="email" name="cust[email]" class="form-control billing" id="inputEmail" placeholder="Email" pattern="^[a-zA-Z0-9_\-.]+@[a-zA-Z0-9\-.]+$" onblur="blankCheck('inputEmail','emailError')" required>
                  <span class="error" id="emailError"></span>
                  <span class="error"> <?php echo $emailErr; ?></span>
                </div>

                <div class="form-group">
                  <label for="ccnum">Credit card number&#42;</label>
                  <input type="text" name="cust[card]" class="form-control billing" id="inputccnum" placeholder="1111 2222 3333 4444" pattern="^[0-9]( ?\d){14,19}$" onblur="blankCheck('inputccnum','ccError')" required>
                  <span class="error" id="ccError"></span>
                  <span class="error"> <?php echo $cardErr; ?></span>
                </div>
                <div class="form-group form-inline">
                  <label class="mb-2 mr-sm-2" for="inputExpdate">Expiry:</label>
                  <select type="month" name="cust[expMonth]" class="form-control mb-2 mr-sm-2" id="expMonth" required>
                    <option value="0">MM</option>
                    <option value="01">01</option>
                    <option value="2">02</option>
                    <option value="3">04</option>
                    <option value="4">04</option>
                    <option value="5">05</option>
                    <option value="6">06</option>
                    <option value="7">07</option>
                    <option value="8">08</option>
                    <option value="9">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                  </select>
                  <select type="year" name="cust[expYear]" class="form-control mb-2 mr-sm-2" id="expYear" required>
                    <option value="2000">YYYY</option>
                  </select>
                  <span class="error" id="expError"></span>
                  <span class="error"><?php echo $expErr; ?></span>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary" name='session-reset' value='Reset the session'>Book</button>

          </form>


        </div>
      </div>

    </div>
    <script src="script.js"></script>
  </main>

  <footer>
    <div>&copy;
      <script>
        document.write(new Date().getFullYear());
      </script> Ngo Quang Khai, s3836387 , githut link: https://github.com/s3836387/wp. Last modified
      <?= date("Y F d  H:i", filemtime($_SERVER['SCRIPT_FILENAME'])); ?>.</div>
    <div>Disclaimer: This website is not a real website and is being developed as part of a School of Science Web
      Programming course at RMIT University in Melbourne, Australia.</div>
    <div><button id='toggleWireframeCSS' onclick='toggleWireframe()'>Toggle Wireframe CSS</button></div>

  </footer>
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
</body>

</html>