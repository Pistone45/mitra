<?php 
ob_start();
require('header.php');
include_once("functions/functions.php");


$getPortifolio = new Portfolio();
$portifolio = $getPortifolio->getPortifolio();





?>

  <!-- ======= Hero Section ======= -->
  <section id="hero1" class="d-flex flex-column justify-content-center align-items-center">
    <div class="container text-center text-md-left" data-aos="fade-up">
      <h1><span>Portifolio</span></h1>
    </div>
  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
      <div class="container">

        <div class="section-title">
          <h2>Our Portifolio</h2>
         
          <br>
		   <div class="row">
			<div class="col-lg-1" style="padding-bottom:30px;">
				<a href="#ibm"><img src="images/ibm.png" class="img-fluid"/></a>
			</div>
			<div class="col-lg-1" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/lenovo.png" class="img-fluid"/></a>
			</div>
			<div class="col-lg-1" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/dell.png" class="img-fluid"/></a>
			</div>
			<div class="col-lg-1" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/fujitsu.png" class="img-fluid"/></a>
			</div>
			
			<div class="col-lg-1" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/veeam.png" class="img-fluid"/></a>
			</div>
			<div class="col-lg-1" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/hp.png" class="img-fluid"/></a>
			</div>
			<div class="col-lg-1" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/cisco.png" class="img-fluid"/></a>
			</div>
			
			<div class="col-lg-1" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/palaalto.png" class="img-fluid"/></a>
			</div>
			
			<div class="col-lg-1" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/kemp.png" class="img-fluid"/></a>
			</div>
			
			<div class="col-lg-1" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/oracle.png" class="img-fluid"/></a>
			</div>
			
			<div class="col-lg-1" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/vmware.png" class="img-fluid"/></a>
			</div>
			
			<div class="col-lg-1" style="padding-bottom:30px;">
				<a href="#vm"><img src="images/rsa.png" class="img-fluid"/></a>
			</div>
		  </div>
          
          
		  
        </div>
		
		

        <div class="row">

<?php
      if(isset($portifolio) && count($portifolio)>0){
        foreach($portifolio as $port){ ?>
          <div class="col-md-6 mt-4" id="ibm">
            <div class="icon-box">
              <h4><a href="#"><?php echo $port['portifolio']; ?></a></h4>
              <p><?php echo $port['description']; ?></p>
            </div>
          </div>
          <?php
          
        }
      }
    ?>

        </div>
        <br><br>

      

      </div>
    </section><!-- End Services Section -->



<?php 
require('footer.php');
?>