<?php
    // Include DatabaseHelper.php file
    require_once('DatabaseHelper.php');
    $conn = new DatabaseHelper();
    $shopname = $conn->shopname;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title> <?php echo $shopname ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    
<?php 
    if(isset($_SESSION['user_name'])) {
        echo "<div class='jumbotron text-center'>
                <div class='row'>
                    <div class='col-md-6 col-xs-12'>
                        <h1>$shopname</h1>
                    </div>
                    <div class='col-md-6 col-xs-12'>
                    <h3>Welcome " . $_SESSION['full_name'] . "</h3>
                        <a href='logout.php'>Logout</a>
                    </div>
                </div>
            </div>";
    } else {
        echo "<div class='jumbotron text-center'><h1>$shopname</h1></div>";
    }
