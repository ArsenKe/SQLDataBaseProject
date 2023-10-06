<?php
session_start();
if(!isset($_SESSION['user_name'])) header("Location:index.php"); 
if(!isset($_GET['table'])) header("Location:home.php");

?>

<?php require_once('header.php') ?>

<?php
    $action = 'view';
    if(isset($_GET['action'])) $action=$_GET['action'];

    //current table
    $table = ''; $pkname = ''; $pkid = '';

    $table = $_GET['table'];
    $pkname = $conn->getPK($table);
    if(isset($_GET['pkid'])) $pkid=$_GET['pkid'];

    $formView='';
    if(isset($_GET['formView'])) $formView=$_GET['formView'];

    $fktable = '';  $fkname=''; $fkid = '';
    if(isset($_GET['fktable'])) {
        $fktable=$_GET['fktable'];
        $fkname = $conn->getPK($fktable);
        $fkid=$_GET['fkid'];
    }

?>


<div class="container">

<?php 
    $sql = "select * from $table where $pkname = $pkid";
    
    if ($action == 'add')
        $sql = "select * from $table fetch next 1 rows only";

    $result = $conn->query($sql);
    echo "<div class='row'>";
    echo "<div class='col-md-6'><h2 class='text-uppercase text-warning'>$table"; 
    if($fkid!="") echo " <small class='text-info'>($fkname = $fkid)</small>";
    echo "<h2></div>";
    echo "<div class='col-md-6 text-right'>
            <button onclick=\"window.location='home.php'\" class='btn btn-info'>Dashboard</button>
            <button onclick=\"window.location='list.php?table=$table'\" class='btn btn-info'>Back to List</button>";
    echo "</div>";
    echo "</div>";

    echo "<div class='row'>";
    echo "<div class='col-md-6'>";

    echo "<form id='detail' action='update.php' method='post'>";
    echo "<input type='hidden' name='table' value='$table' />";
    echo "<input type='hidden' name='action' value='$action' />";
    echo "<input type='hidden' name='pkname' value='$pkname' />";
    echo "<input type='hidden' name='pkid' value='$pkid' />";
    echo "<input type='hidden' name='formView' value='$formView' />";
    echo "<input type='hidden' name='fktable' value='$fktable' />";
    echo "<input type='hidden' name='fkname' value='$fkname' />";
    echo "<input type='hidden' name='fkid' value='$fkid' />";
    


    echo "<table class='table table-hover form'>";
    
    foreach($result[0] as $col => $val){
        echo "<tr>";
        echo "<th>$col</th>";
        
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

        echo "</tr>";
    }

    echo "</table>";
    echo "</form>";

    echo "<div class='mb-5'>";
    if($action=='view')
        echo "<a onclick=\"window.location='detail.php?table=$table&action=edit&pkid=$pkid'\" class='btn btn-warning'>Edit</a>";
    
    if($action=='edit' || $action=='add'){
        echo "<a onclick=\"$('#detail').submit();\" class='btn btn-warning m-2'>Save</a>";
        $retunrTo = $_SERVER['HTTP_REFERER'];
        echo "<a onclick=window.location='$retunrTo' class='btn btn-danger m-2'>Cancel</a>";
        // if($fktable=="") 
        //     echo "<a onclick=window.location='detail.php?table=$table&action=view&pkid=$pkid' class='btn btn-danger m-2'>Cancel</a>";
        // else
        //     echo "<a onclick=window.location='detail2.php?table=$fktable&action=view&pkid=$fkid' class='btn btn-danger m-2'>Cancel</a>";
    }
    echo "</div>";
    echo "</div>";
    
    echo "</div>";

?>
</div>
<?php require_once('footer.php') ?>
