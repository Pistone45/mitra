<?php 
require('header.php');
?>
  <!-- ======= Hero Section ======= -->
  <section id="hero1" class="d-flex flex-column justify-content-center align-items-center">
    <div class="container text-center text-md-left" data-aos="fade-up">
      <h1><span>Portfolio</span></h1>
    </div>
  </section><!-- End Hero -->
<br>
  <div class="section-title">
    <h2>Portifolio</h2>
  </div>
    <!-- ======= About Section ======= -->
    <section id="about" class="about">
      <div class="container">

        <div class="row">
          <div class="col-lg-6">
            <img src="<?php if(isset($portfolio)){ echo substr($portfolio['image_url'],3); }?>" class="img-fluid" alt="">
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0">

            <p>
             <?php if(isset($portfolio)){ echo nl2br($portfolio['content']); }?>
            </p>
            
           
          </div>
        </div>

      </div>
    </section><!-- End About Section -->

<?php 
require('footer.php');
?>