<?php
session_start();
require_once('connect.php');
if(isset($_SESSION['user']))
{
    echo "<script>window.location='dashboard.php';</script>";
}
$showAlert = false;
$alertMsg = "";
if(isset($_POST['loginBtn']))
{
    $userName = $_POST['email'];
    $password = $_POST['password'];

    $user = ORM::for_table('jst_users')->where(array(
                                                'email'     => $userName,
                                                'password'  => $password
                                            ))->find_one();

    if($user)
    {
        if($user->status == 'A')
        {
            $_SESSION['user']['id'] = $user->id;
            $_SESSION['user']['email'] = $user->eamil;
            $_SESSION['user']['type'] = $user->type;
            $_SESSION['user']['status'] = $user->status;
            
            
            if(!isset($_SESSION['redirectTo']))
            {
                $redirectTo = 'dashboard.php';
            }
            else
            {
                $redirectTo = $_SESSION['redirectTo'];
                unset($_SESSION['redirectTo']);   
            }
            echo "<script>window.location='".$redirectTo."';</script>";
            die();
        }
        else
        {
            $showAlert = true;
            $alertMsg = "Your account is not active, please contact system admin";
        }        
    }
    else
    {
        $showAlert = true;
        $alertMsg = "Email / password is incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>jStoromatic</title>

    <!-- Bootstrap Core CSS -->
    <link href="thirdparty/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="thirdparty/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="thirdparty/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->



    <!-- jQuery -->
    <script src="thirdparty/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="thirdparty/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="thirdparty/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

    <!-- Form Validatior plugin -->
    <!-- URL : http://1000hz.github.io/bootstrap-validator/# -->
    <!-- GIT : https://github.com/1000hz/bootstrap-validator -->
    <script src="js/validator.js"></script>

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <?php 
                        if($showAlert)
                        {
                        ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <?php echo $alertMsg; ?>
                        </div>
                        <?php 
                        }
                        ?>
                        <form role="form" method="post" data-toggle="validator">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus required>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="" required>
                                </div>
                                <button type="submit" name="loginBtn" class="btn btn-lg btn-success btn-block">Login</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
