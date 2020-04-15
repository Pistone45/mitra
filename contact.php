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
          <h4>Location:</h4>
          <p>Area 2
          Bwalo la Mjovu
          P.O Box 445
          Lilongwe</p><br>
          <p>230 Sherwood Drive
          Avondale West
          Harare</p>
        </div>
      </div>
    </section>

    <section id="contact" class="contact section-bg">
          <div class="container">
            <div class="col-lg-4 info mt-4 mt-lg-0">
              <i class="icofont-envelope"></i>
              <h4>Email:</h4>
              <p>support@mitra.mw</p>
            </div>
          </div>
    </section>

    <section id="contact" class="contact section-bg">
          <div class="container">
                <div class="col-lg-4 info">
                  <i class="icofont-phone"></i>
                  <h4>Call:</h4>
                  <p>+265994885227<br>+265994885227</p>
                </div>
          </div>
          </div>
    </section>

    </div>
  </div>

  </main><!-- End #main -->
<?php 
require('footer.php');
?>