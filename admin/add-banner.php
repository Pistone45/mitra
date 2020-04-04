<?php
include_once("../functions/functions.php");
if(!isset($_SESSION['user'])){
		header("Location: login.php");
		exit;
	}

if(isset($_POST['submit'])){
	
		//validate ID attachment
	//validate  file
	 if(isset($_FILES['banner_image'])){
      $errors= array();
      $file_name = $_FILES['banner_image']['name'];
      $file_size =$_FILES['banner_image']['size'];
      $file_tmp =$_FILES['banner_image']['tmp_name'];
      $file_type=$_FILES['banner_image']['type'];
	  $dot = ".";

     // $file_ext=strtolower(end(explode($dot,$file_name)));

	  $imagePath = "../banners/";
	  $imagePath = $imagePath . basename($file_name);
	   $file_ext = pathinfo($imagePath,PATHINFO_EXTENSION);
      $expensions= array("JPG", "jpg","PNG","png","GIF","gif");

      if(in_array($file_ext,$expensions)=== false){
         $errors[]="This file extension is not allowed.";
      }

      if($file_size > 3007152){

         $errors[]='File size must be not more than 3 MB';

      }

      if(empty($errors)==true){
		move_uploaded_file($file_tmp, $imagePath);

      }else{
		   $errors[]='Error Uploading file';

         //print_r($errors);
      }
	   
	  $image_Path = $imagePath;
	 // echo $image_Path; die();
	 }

	  $title = $_POST['title'];
	 $addBanner = new Banner();
	 $addBanner->addBanner($image_Path,$title);
	

	
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Add Banner | Immunology Conference</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <?php include_once("header.html"); ?>
  <!-- Left side column. contains the logo and sidebar -->
   <?php include_once('sidebar.html'); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Add Banner
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="add-banner.php">Add Banner</a></li>
       
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
	<!-- form start -->
            <form role="form" action="add-banner.php" method="POST" enctype="multipart/form-data">
			<?php
                            if(isset($_SESSION["banner-added"]) && $_SESSION["banner-added"]==true)
                            {
                                echo "<div class='alert alert-success'>";
                                echo "<button type='button' class='close' data-dismiss='alert'>*</button>";
                                echo "<strong>Success! </strong>"; echo "You have successfully added a banner";
                                unset($_SESSION["banner-added"]);
                                echo "</div>";
								 header('Refresh: 5; URL= view-banners.php');
                            }
							?>
      <div class="row box box-primary">
        <!-- left column -->
        <div class="col-md-6">
          <!-- general form elements -->
              <div class="box-body">
                
				
				<div class="form-group">
                  <label for="fatherMiddleName">Banner Image</label>
                  <input type="file" class="" name="banner_image" required>
                </div>
				
				 <div class="form-group">
                  <label for="fatherName">Short Description</label>
                  <input class="form-control" name="title" required>
                </div>
				
              </div>
			  
              <!-- /.box-body -->
			  <div class="box-footer">
                <button type="submit" name="submit" class="btn btn-primary btn-block">Submit</button>
              </div>
          <!-- /.box -->

        

        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
            
		
			
			
        </div>
        <!--/.col (right) -->
      </div>
      <!-- /.row -->
	  </form>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php include_once("footer.html"); ?>

  
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
