<?php
session_start();
if(!isset($_SESSION['user_name'])) {
    header("Location:index.php");
} 

if(!isset($_GET['table'])) header("Location:home.php");

?>

<?php
    
    //current table
    $table = $_GET['table'];

    $toFind="";

    if(isset($_POST['toFind'])) $toFind = $_POST['toFind'];

?>

<?php require_once('header.php') ?>

<div class="container">

<?php 
    
    $sql = "select * from $table";
    $sql = $sql . ' order by 1';
    
    //resolve foreign keys
    switch ($table) {
        case 'customer':
            $result = $conn->getCustomerList($toFind);
            break;
        case 'category':
            $result = $conn->getCategoryList($toFind);
            break;
        case 'product':
            $result = $conn->getProductList($toFind);
            break;
        case 'orders':
            $result = $conn->getOrdersList($toFind);
            break;
        case 'cart':
            $result = $conn->getCartList($toFind);
            break;
        case 'shipment':
            $result = $conn->getShipmentList($toFind);
            break;
    
        default:
            $result = $conn->query($sql);
            break;
    }
    $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    echo "<div class='clearfix'>";
    echo "<div class='float-left'><h2 class='text-uppercase text-warning'>". $_GET['table'] ."</h2></div>";
    echo "<div class='float-right'>
            <form id='frmSearch' method='post' action='$url'><input name='toFind' placeholder='Search' value='$toFind' class='form-control'></input>
            <button onclick='frmSearch.submit();' class='btn btn-warning'>Search</button>
            </form>
            <button onclick='window.location=\"home.php\"' class='btn btn-primary'>Dashboard</button>
            <button onclick='window.location=\"detail.php?table=$table&action=add\"' class='btn btn-success'>Add New</button>
            
        </div>
        </div>";
    
    if(!$result) {
        echo "<div class='text-danger mt-5'><p>No Record Found</p></div>";    
        return;
    }

    echo "<table class='table table-hover'>";
    echo "<thead class='thead-dark'><tr>";
    
    foreach($result[0] as $col => $val){
        echo "<th>$col</th>";
    }
    echo "<th></th>";
    echo "</tr></thead>";
    foreach($result as $row) {
        $pkid = array_values($row)[0];
        echo "<tr>";
        foreach($row as $col => $val){
            echo "<td>$val</td>";
        }
        if (in_array($table, ['orders']))
            $btnView = "<a class='btn btn-outline-dark btn-sm' href='detail2.php?table=$table&action=view&pkid=$pkid'>View</a>";
        else
            $btnView = "<a class='btn btn-outline-dark btn-sm' href='detail.php?table=$table&action=edit&pkid=$pkid'>Edit</a>";
 
        $btnDelete = "<a class='btn btn-outline-dark btn-sm' href='delete.php?table=$table&action=delete&pkid=$pkid'>Delete</a>";
        echo "<td>{$btnView} {$btnDelete} </td>";
        echo "</tr>";   
    }
    echo "</table>";
?>

</div>


<?php require_once('footer.php') ?>
