<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reciept</title>
    <?php
    require 'tools.php';
    if (empty($_SESSION)) {
        header("Location: index.php");
    }

    $now = date('d/m h:i');
    $cartid = 0;
    $cusName =$_SESSION["cart"]['cust']['name'];
    $cusMobile= $_SESSION["cart"]['cust']['mobile'];
    $cusEmail= $_SESSION["cart"]['cust']['email'];
    $total = $_SESSION["cart"]['total'];
    $row = array_merge(
        [$now],
        [$cartid],
        [$cusName],
        [$cusEmail],
        [$cusMobile],
        $_SESSION ["cart"]['movie'],
        $_SESSION["cart"]['seats'],
        [$total]
    );
    $myfile = fopen("bookings.csv","a");
    fputcsv($myfile, $row);
    fclose($myfile);
    print_r($row);

    ?>
</head>

<body>
    /* link: localhost:8888/wp/Week%208/index.php */
    



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