<?php 
require('header.php');
include_once("functions/functions.php");

$getServices = new Service();
$services = $getServices->getServices();

$getAnotherServices = new Service();
$anotherservice = $getAnotherServices->getAnotherServices();



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
          <img src="https://img.icons8.com/ios/100/000000/ibm.png"/>
        </div>

        <div class="row">

<?php
      if(isset($services) && count($services)>0){
        foreach($services as $service){ ?>
          <div class="col-md-6 mt-4">
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
          <div class="col-md-6 mt-4">
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