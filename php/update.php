<?php
session_start();
if(!isset($_SESSION['user_name'])) {
    header("Location:index.php");
} 
if(!isset($_POST['table'])) header("Location:home.php");

?>

<?php require_once('header.php') ?>

<?php
    echo "<div class='container'>";

    //current table
    $table = $_POST['table'];
    $action = $_POST['action'];
    $pkname = $_POST['pkname'];
    $pkid = $_POST['pkid'];
    
    $formView = $_POST['formView'];
    $fktable = $_POST['fktable'];
    $fkid = $_POST['fkid'];

    $sql = "select * from $table fetch next 1 rows only";
    $result = $conn->query($sql);

    if($action=='edit'){ 
        $sql = "update $table set ";

        foreach($result[0] as $col => $val){
            if($_POST[$col]==''){
                $sql .= " $col = NULL, ";
            } else {
                $sql .= " $col  = '" . $_POST[$col] . "', ";
            }
        }
        $sql = rtrim($sql, ", ") . " where $pkname = $pkid";
        $return = "detail.php?table=$table&action=view&pkid=$pkid";
    }

    if($action=='add'){
        $sql = "insert into $table values (";
        foreach($result[0] as $col => $v){
            $val = $_POST[$col];
            if ($val == '') 
                $sql .= "NULL, ";
            else 
                $sql .= "'" . $val . "', ";
        }
        $sql = rtrim($sql, ", ") . ")";
        $return = "list.php?table=$table";
    }
    $res = $conn->update($sql);
    
    if($res!='OK') {
        echo "<div class='text-danger'><h2>Error</h2><p>$res</p></div>";
    } else {
        echo "<div class='text-success'><h2>Success</h2><p>Record updated successfully</p></div>";
    }
    $returnTo = "list.php?table=$table";
    if ($formView=="pc"){
        if ($fktable=="") $returnTo = "detail2.php?table=$table&action=view&pkid=$pkid";
        else $returnTo = "detail2.php?table=$fktable&action=view&pkid=$fkid";
    }

    echo "<a class='btn btn-primary' href='$returnTo'>Continue</a>";
    echo "</div>"
?>