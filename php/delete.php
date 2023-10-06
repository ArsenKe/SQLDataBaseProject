<?php
session_start();
if(!isset($_SESSION['user_name'])) {
    header("Location:index.php");
} 
?>

<?php require_once('header.php'); ?>

<?php
    if(!isset($_GET['table']) || !isset($_GET['pkid'])) header("Location:home.php");

    //current table
    $table = $_GET['table'];
    $pkid=$_GET['pkid'];

    echo "<div class='container'>";
    $res = $conn->delete($table,$pkid);
    if($res!='OK') {
        echo "<div class='text-danger'><h2>Error</h2><p>Can not delete ". strtoupper($table) . " ID $pkid</p><p>$res</p></div>";
    } else {
        echo "<div class='text-success'><h2>Deleted</h2><p>Record ID $pkid successfully deleted</p></div>";
    }
    echo "<a class='btn btn-primary' href='" . $_SERVER['HTTP_REFERER']  . "'>Back to List</a>";
    echo "</div>";

?>

<?php require_once('footer.php'); ?>
