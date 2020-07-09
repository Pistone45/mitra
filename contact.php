<?php
ob_start();
require("header.php");
include_once("functions/functions.php");

if(isset($_POST['submit'])){

  $recipient ="support@mitra.mw";
  $name = $_POST['name'];
  $email = $_POST['email'];
  $subject = "CONTACT FROM WEBSITE";
  $message = $_POST['message'];
  
  
  $mailBody =$message;
  mail($recipient, $subject, $mailBody, "From: $name <$email>");
  $_SESSION["message-sent"] = true;;
}



?>

  <!-- ======= Hero Section ======= -->
  <section id="hero1" class="d-flex flex-column justify-content-center align-items-center">
    <div class="container text-center text-md-left" data-aos="fade-up">
      <h1><span>Contact Us</span></h1>
    </div>
  </section><!-- End Hero -->

  <main id="main">

<br>
  <div class="row container-fluid">
    <div class="col-lg-8">
          <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact section-bg">
      <div class="container">

        <div class="section-title">
          <h2>Contact Us</h2>
        </div>

        <div class="row mt-5 justify-content-center">
          <div class="col-lg-10">
            <form action="contact.php" method="POST">
              <div class="form-row">
                <div class="col-md-6 form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
                  <div class="validate"></div>
                </div>
                <div class="col-md-6 form-group">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email" />
                  <div class="validate"></div>
                </div>
              </div>
              <div class="form-group">
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
                <div class="validate"></div>
              </div>
              <div class="form-group">
                <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Message"></textarea>
                <div class="validate"></div>
              </div>
              <div class="text-center"><button class="btn btn-primary" name="submit" type="submit">Send Message</button></div>
            </form>
          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->
    </div>

    <div class="col-lg-4">
    <section id="contact" class="contact section-bg">
      <div class="container">
        <div class="info">
          <i class="icofont-google-map"></i>
          <h4>Malawi:</h4>
          <p>2nd Floor<br>
          Pamodzi Park, Unit 19<br>
          P.O Box 762<br>
          Blantyre<br>
		  
		  <strong>Phone:</strong> +265 888 876 995<br>
              <strong>Email:</strong> sales@mitra.mw<br></p><br>
		  <br>
              
		  <i class="icofont-google-map"></i>
          <h4>Zimbabwe:</h4>
          <p>230 Sherwood Drive<br>
          Avondale West<br>
          Harare<br>
		   <strong>Phone:</strong> +263 772591154<br>
		   <strong>Email:</strong> sales@mitra.co.zw<br>
		  </p>
		  <br>
		  <i class="icofont-google-map"></i>
		  <h4>Zambia Office</h4>
            <p>
              9, THORN PARK, <br>
              Mungulube Road Off Makishi Road<br>
              Lusaka <br>
              <strong>Phone:</strong> +260 97 7977165<br>
			   <strong>Email:</strong> info@mitra.co.zw<br>
        </div>
      </div>
    </section>

    
    

    </div>
  </div>

  </main><!-- End #main -->
<?php 
require('footer.php');
?>