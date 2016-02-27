<?php session_start(); ?>
<?php require_once('connect.php'); ?>
<?php 
    if(!isset($_SESSION['user']))
    {
        echo "<script>window.location='index.php';</script>";
    }
    $userSessionData = $_SESSION['user'];
    $pathData = $_SERVER['REQUEST_URI'];
    $pathDataArr = explode("/", $pathData);
    $fileNameWithQryString = end($pathDataArr);
    $fileNameArr = explode("?", $fileNameWithQryString);
    $fileName = $fileNameArr[0];
    if($fileName != "cannotaccess.php")
    {
        $groupPerm = ORM::for_table('jst_page_permission')->where(array(
                                                                'user_type_id'   => $userSessionData['type'],
                                                                'page_name'      => $fileName
                                                            ))->find_one();
        if(!$groupPerm)
        {
            $userPerm = ORM::for_table('jst_page_permission')->where(array(
                                                                'user_id'        => $userSessionData['id'],
                                                                'page_name'      => $fileName
                                                            ))->find_one(); 
            if(!$userPerm)
            {
                echo "<script>window.location='cannotaccess.php';</script>";
            }
        }
    }  

?>
<?php require_once('reusables/messagingutils.php'); ?>
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

    <!-- Timeline CSS -->
    <link href="css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="thirdparty/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="thirdparty/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- DataTables CSS -->
    <link href="thirdparty/dataTableUpgrade/dataTables.css" rel="stylesheet">

    <!-- Bootstrap jQuery Chosen CSS -->
    <link href="css/bootstrap-chosen.css" rel="stylesheet">


    <!-- Bootstrap Datepicker Support Style sheet -->
    <!-- URL : https://bootstrap-datepicker.readthedocs.org/en/latest/index.html -->
    <!-- GIT : https://github.com/eternicode/bootstrap-datepicker/blob/master/docs/index.rst -->
    <link href="css/bootstrap-datepicker.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Adding JS in header as some plugins will not work if included in footer -->
    <!-- Do not remove for optimization purpose -->

    <!-- jQuery -->
    <script src="thirdparty/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="thirdparty/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="thirdparty/metisMenu/dist/metisMenu.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="thirdparty/dataTableUpgrade/dataTables.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>

    <!-- Form Validatior plugin -->
    <!-- URL : http://1000hz.github.io/bootstrap-validator/# -->
    <!-- GIT : https://github.com/1000hz/bootstrap-validator -->
    <script src="js/validator.js"></script>

    <!-- BootStrap jQuery Chosem (Autocomplete) plugin -->
    <!-- URL : http://alxlit.name/bootstrap-chosen/ -->
    <!-- GIT : https://github.com/alxlit/bootstrap-chosen -->
    <script src="js/chosen.jquery.js"></script>

    <!-- BootStrap Datepicker plugin -->
    <!-- URL : https://bootstrap-datepicker.readthedocs.org/en/latest/index.html -->
    <!-- GIT : https://github.com/eternicode/bootstrap-datepicker/blob/master/docs/index.rst -->
    <script src="js/bootstrap-datepicker.js"></script>

    <!-- BootBox for Bootstrap Alert -->
    <script src='js/bootbox.min.js'></script>

    <!-- All application level javascript code -->
    <script src='js/app.js'></script>

    
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">jStoromatic</a>
            </div>
            <!-- /.navbar-header -->

            <?php require_once('notifications.php'); ?>

            <?php require_once('sidebar.php'); ?>

        </nav>
        <script> var msgSect = '<?php echo $msgSection; ?>';</script>
