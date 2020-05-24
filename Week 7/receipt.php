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
  if (empty($_SESSION)) {
    header("Location: index.php");
  }

  $now = date('d/m h:i');
  $cartid = 0;
  $cusName = $_SESSION["cart"]['cust']['name'];
  $cusMobile = $_SESSION["cart"]['cust']['mobile'];
  $cusEmail = $_SESSION["cart"]['cust']['email'];
  $total = $_SESSION["cart"]['total'];
  $seats = $_SESSION["cart"]['seats'];
  $movie = $_SESSION["cart"]['movie'];
  $row = array_merge(
    [$now],
    [$cartid],
    [$cusName],
    [$cusEmail],
    [$cusMobile],
    $_SESSION["cart"]['movie'],
    $_SESSION["cart"]['seats'],
    [$total]
  );

  ?>
  <script>
    function printInvoice(eid) {
      let original = document.body.innerHTML
      let toPrint = document.getElementById(eid).innerHTML
      console.log(toPrint)
      document.body.innerHTML = toPrint
      window.print()
      document.body.innerHTML = original
    }
  </script>
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
  <!--Template https://codepen.io/daplo/pen/xYVQPz -->
  <section id="home">
    <h2>Home</h2>
    <div class="container">
      <div class="card" id='invoice'>
        <div class="card-header">
          Invoice
          <strong><?php echo $now ?></strong>
        </div>
        <div class="card-body">
          <!------Invoice head------->
          <div class="row mb-4">
            <div class="col-sm-6">
              <h6 class="mb-3">From:</h6>
              <div>
                <strong>Cinemax cinema</strong>
              </div>
              <div>71-101 Kangaroo, Australia</div>
              <div>Email: info@cinemax.com.php</div>
              <div>Phone: 04 1234 3333</div>
              <div>ABN number: 00 123 456 789 </div>
            </div>

            <div class="col-sm-6">
              <h6 class="mb-3">To:</h6>
              <div>
                <strong><?php echo $cusName ?></strong>
              </div>
              <div>Email: <?php echo $cusEmail ?></div>
              <div>Phone: <?php echo $cusMobile ?></div>
            </div>



          </div>

          <div class="table-responsive-sm">
            <table class="table table-striped">
              <thead>
                <tr>
                  <th class="center">#</th>
                  <th>Seat</th>
                  <th>Description</th>

                  <th class="right">Unit Cost</th>
                  <th class="center">Qty</th>
                  <th class="right">Total</th>
                </tr>
              </thead>
              <tbody>
                <?php
                printRow($seats, $movie['day'], $movie['hour']);
                ?>
              </tbody>
            </table>
          </div>
          <div class="row">
            <div class="col-lg-4 col-sm-5">
            </div>

            <div class="col-lg-4 col-sm-5 ml-auto">
              <table class="table table-clear">
                <tbody>
                  <tr>
                    <td class="left">
                      <strong>Subtotal</strong>
                    </td>
                    <td class="right">$ <?php echo $total ?></td>
                  </tr>
                  <tr>
                    <td class="left">
                      <strong>VAT (10%)</strong>
                    </td>
                    <td class="right">$ <?php echo $total*0.1 ?></td>
                  </tr>
                  <tr>
                    <td class="left">
                      <strong>Total</strong>
                    </td>
                    <td class="right">
                      <strong>$ <?php echo $total*1.1 ?></strong>
                    </td>
                  </tr>
                </tbody>
              </table>

            </div>

          </div>

        </div>
      </div>
      <button onclick="printInvoice('invoice')">Print</button>
    </div>
  </section>
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