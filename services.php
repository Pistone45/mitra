<?php 
ob_start();
require('header.php');
include_once("functions/functions.php");

$id=1; //IBM category
$getServicesPerCategory = new Service();
$services = $getServicesPerCategory->getServicesPerCategory($id);

$id=2; //Veem category
$getServicesPerCategory = new Service();
$anotherservice = $getServicesPerCategory->getServicesPerCategory($id);



?>

  <!-- ======= Hero Section ======= -->
  <section id="hero1" class="d-flex flex-column justify-content-center align-items-center">
    <div class="container text-center text-md-left" data-aos="fade-up">
      <h1><span>Services</span></h1>
    </div>
  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
      <div class="container">

        <div class="section-title">
          <h2>Services</h2>
          <p>Our IBM enterprise software portfolio on offer includes:</p>
          <br>
		  <div class="row">
			<div class="col-lg-3" style="padding-bottom:30px;">
				<a href="#ibm"><img src="images/ibm.png" class="img-fluid"/></a>
			</div>
			<div class="col-lg-3" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/lenovo.png" class="img-fluid"/></a>
			</div>
			<div class="col-lg-3" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/dell.png" class="img-fluid"/></a>
			</div>
			<div class="col-lg-3" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/fujitsu.png" class="img-fluid"/></a>
			</div>
			
			<div class="col-lg-3" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/veem.png" class="img-fluid"/></a>
			</div>
			<div class="col-lg-3" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/hp.png" class="img-fluid"/></a>
			</div>
		  </div>
          
		  
        </div>

        <div class="row">

<?php
      if(isset($services) && count($services)>0){
        foreach($services as $service){ ?>
          <div class="col-md-6 mt-4" id="ibm">
            <div class="icon-box">
              <h4><a href="#">• <?php echo $service['service']; ?></a></h4>
              <p><?php echo $service['description']; ?></p>
            </div>
          </div>
          <?php
          
        }
      }
    ?>

        </div>
        <br><br>

        <div class="section-title">
          <p>Our VIM virtualisation services includes:</p>
          <img src="https://img.icons8.com/ios-filled/100/000000/vmware.png"/>
        </div>

        <div class="row">

          <?php
      if(isset($anotherservice) && count($anotherservice)>0){
        foreach($anotherservice as $another){ ?>
          <div class="col-md-6 mt-4" id="vm">
            <div class="icon-box">
              <h4><?php echo $another['service']; ?></h4>
              <p><?php echo $another['description']; ?></p>
            </div>
          </div>
          <?php
          
        }
      }
    ?>

        </div>

      </div>
    </section><!-- End Services Section -->



<?php 
require('footer.php');
?>