<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="assets\css\style.css" rel="stylesheet" type="text/css">
    <link rel="icon" type="image/png" href="assets\images\logo.png" />
    <!------ Include the above in your HEAD tag ---------->
    <title>Tool Facebook</title>
</head>
<?php
require('includes\navbar.php');
?>

<body>
    <div class="wrapper fadeInDown">
        <div id="formContent">
            <!-- Tabs Titles -->

            <!-- Icon -->
            <div class="fadeIn first">
                <img src="assets\images\logobig.png" id="icon" alt="User Icon" />
            </div>

            <!-- Login Form -->
            <form action="functions/getGroupName.php" method="post">
                <input type="text" id="login" class="fadeIn second" name="token" placeholder="Token">
                <input type="text" id="cookie" class="fadeIn third" name="cookie" placeholder="Cookie">
                <input type="text" id="keyword" class="fadeIn third" name="groupName" placeholder="Keyword">
                <input type="submit" class="fadeIn fourth" name="submit" value="Tìm">
            </form>
        </div>
    </div>
</body>

</html>