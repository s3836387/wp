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
        include 'tools.php'
    ?>
    <form name="myForm" action='' target="_blank"
        onsubmit="return formCheck()" method="post">
        <label for="name">Your name:</label>
        <input type="text" name="name1" id='name'>
        <br>
        <label for="email">Your email</label>
        <input type="text" name="email1" id='email'>
        <br>
        <label for="age">Age</label>
        <input type="number" name="age1" id='age' placeholder="Input your age">
        <br>
        <label for="gender">Genders</label>
        <select name="gender1" id="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <br>
        <label for="comment">Comments</label>
        <textarea name="comment" id="comment1" cols="30" rows="10"></textarea>
        <br>
        <label for="rating">Rating</label>
        <input type="radio" name="rating" id='good' value="good">Good
        <input type="radio" name="rating" id="bad" value="bad">Bad
        <br><button>Send</button>
    </form>
</body>
<footer>
<?php
    preShow($_GET);
    preShow($_POST);    
    preShow($_SESSION);
?>
</footer>
</html>