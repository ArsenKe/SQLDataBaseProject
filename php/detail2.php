<?php
session_start();

if(!isset($_SESSION['user_name'])) header("Location:index.php"); 
if(!isset($_GET['table'])) header("Location:home.php");

?>

<?php require_once('header.php') ?>

<?php
    //current table
    $table = $_GET['table'];
    $pkname = $conn->getPK($table);

    $action = 'view';
    if(isset($_GET['action'])) $action=$_GET['action'];

    $pkid = '';
    if(isset($_GET['pkid'])) $pkid=$_GET['pkid'];

?>


<div class="container">

<?php 
    $sql = "select * from $table where $pkname = $pkid";
    
    if ($action == 'add')
        $sql = "select * from $table fetch next 1 rows only";

    $result = $conn->query($sql);
    echo "<div class='row'>";
    echo "<div class='col-md-6'><h2 class='text-uppercase text-warning'>$table</h2></div>";
    echo "<div class='col-md-6 text-right'>
            <button onclick=\"window.location='home.php'\" class='btn btn-info'>Dashboard</button>
            <button onclick=\"window.location='list.php?table=$table'\" class='btn btn-info'>Back to List</button>";
    echo "</div>";
    echo "</div>";

    echo "<form id='detail' action='update.php' method='post'>";
    echo "<input type='hidden' name='table' value='$table' />";
    echo "<input type='hidden' name='action' value='$action' />";
    echo "<input type='hidden' name='pkname' value='$pkname' />";
    echo "<input type='hidden' name='pkid' value='$pkid' />";
    echo "<input type='hidden' name='formView' value='pc' />";
    echo "<input type='hidden' name='fktable' value='' />";
    echo "<input type='hidden' name='fkid' value='' />";

    
    echo "<table class='table table-hover form'>";
    
    echo "<tr>";
    foreach($result[0] as $col => $val){
        echo "<th>$col</th>";
    }
    echo "</tr>";
    
    echo "<tr>";
    foreach($result[0] as $col => $val){

        $state = $action == 'view' ? 'disabled' : '';
        
        if(strtoupper($col)==strtoupper($pkname) && $action=='edit') {
            $state = 'readonly';
        } 

        if(strtoupper($col)==strtoupper($pkname) && $action=='add') {
            $state = 'required';
        } 

        if ($action=='add'){
            echo "<td><input name='$col' class='form-control' $state></td>";
        } else {
            echo "<td><input name='$col' value='". $val ."' class='form-control' $state></td>";
        }

    }
    echo "<td>";
    if($action=='view')
        echo "<a onclick=\"window.location='detail2.php?table=$table&action=edit&pkid=$pkid'\" class='btn btn-warning'>Edit</a>";
    
    if($action=='edit' || $action=='add'){
        echo "<a onclick=\"$('#detail').submit();\" class='btn btn-warning'>Save</a>";
        echo "<a onclick=window.location='detail2.php?table=$table&action=view&pkid=$pkid' class='btn btn-danger'>Cancel</a>";
    }
    echo "</td>";
    echo "</tr>";

    echo "</table>";
    echo "</form>";
?>

<?php
//child table
    $table = 'cart';
    $fkid = $pkid;
    $result = $conn->getCartList($fkid);

    echo "<div class='clearfix'>";
    echo "<div class='float-left'><h2 class='text-uppercase text-warning'>". $table ."</h2></div>";
    echo "<div class='float-right'>
            <button onclick='window.location=\"detail.php?table=$table&action=add&formView=pc&fktable=orders&fkid=$fkid\"' class='btn btn-success'>Add New</button>
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

        $btnView = "<a class='btn btn-outline-dark btn-sm' href='detail.php?table=$table&action=edit&pkid=$pkid&formView=pc&fktable=orders&fkid=$fkid'>Edit</a>";
        $btnDelete = "<a class='btn btn-outline-dark btn-sm' href='delete.php?table=$table&action=delete&pkid=$pkid'>Delete</a>";
        echo "<td>{$btnView} {$btnDelete} </td>";
        echo "</tr>";   
    }
    echo "</table>";

?>




</div>
<?php require_once('footer.php') ?>
