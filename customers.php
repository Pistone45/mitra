<?php 
require('header.php');
include_once("functions/functions.php");
$getCustomers = new Customer();
$customers = $getCustomers->getCustomers();

?>

  <!-- ======= Hero Section ======= -->
  <section id="hero1" class="d-flex flex-column justify-content-center align-items-center">
    <div class="container text-center text-md-left" data-aos="fade-up">
      <h1><span>Customers</span></h1>
    </div>
  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
      <div class="container">

        <div class="section-title">
          <h2>Customers</h2>
          <p>Some of our Customers;</p>
        </div>

		<div class="row ">
		
		<?php
			if(isset($customers) && count($customers)>0){
				foreach($customers as $new){ ?>
				<div class="col-lg-6">
				<div class="icon-box">
					<div class="row">
					
						<div class="col-lg-4">
							<img height="100%" width="100%" src="<?php echo substr($new['logo'],3); ?>" />
						</div>
						
						<div class="col-lg-8">
							<h4><a href="#"><?php echo $new['name']; ?></a></h4>
					<p class="text-justify overflow-hidden">
						<?php echo substr($new['description'],0,255); ?> </p>

						
						
					
						</div>				
						
					</div>
					
					</div>
					<br><br>
				</div>
				<?php
					
				}
			}
		?>
			
			
			
		</div>
       

      </div>
    </section><!-- End Services Section -->
<br><br>
  



<?php 
require('footer.php');
?>