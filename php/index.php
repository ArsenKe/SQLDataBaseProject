<?php  
session_start();  
$error_message = "";
 
if(isset($_SESSION['user_name']))   
    header("Location:home.php"); 
 
if(isset($_POST['user_name'])){
    
    $user = $_POST['user_name'];
    $pass = $_POST['user_pass'];
    
    if ($user=='admin' && $pass=="admin"){
        $_SESSION['user_name'] = $user;
        $_SESSION['full_name'] = 'Admin';

        header("Location:home.php"); 
    } else {
        $error_message = "Invalid UserName or Password";        
    }
}
?>

<?php require_once('header.php') ?>

    <div class="row p-5 justify-content-md-center">
        <div class="col-md-5">
            <div class="form  p-5">
                <form id="login" action="index.php" method="post">
                    <table>
                        <tr>
                            <td>User Name</td>
                            <td><input name="user_name" class="form-control full-width" required value=""></td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td><input name="user_pass" class="form-control full-width" required value="" type="password"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <p class="mt-3"><?php echo $error_message?></p>
                                <p>User Name = admin <br/>Password = admin</p>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <br />
                                <button type="submit" class="btn btn-success btn-lg btn-block">Login</button>
                            </td>
                        </tr>
                    </table>
                    
                </form>
            </div>
        </div>
    </div>

<?php require_once('footer.php') ?>