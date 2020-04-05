<?php 
require('header.php');
include_once("functions/functions.php");
$getNews = new News();
$news = $getNews->getNews();

?>

  <main id="main">

    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
      <div class="container">

        <div class="section-title">
          <h2>News</h2>
          <p>Read our latest news here</p>
        </div>

        <div class="row">

<?php
      if(count($news)>0){
        foreach($news as $new){ ?>
          <div class="col-md-6 mt-4">
            <div class="icon-box">
              <div class="row">
                <div class="col-md-3">
                  <img width="150" height="150" src="<?php $trim = $new['image_url']; echo ltrim($trim, './'); ?>"/>
                </div>

                <div class="col-md-9">
                <h4><a href="#"><?php echo $new['title']; ?></a></h4>
                <p class="text-justify"><?php echo substr($new['news'],0,150); ?> <a href="news-details.php?id=<?php echo $new['id']; ?>"><button class="btn btn-outline-success btn-sm"><?php $new['id']; ?>READ MORE</button></a></p>
                </div>
              </div>
            </div>
          </div>
              <?php
    }
    
  } ?>

        </div>

      </div>
    </section><!-- End Services Section -->

<div class="row container-fluid">
  <div class="col-md-4">
    <div class="alert alert-secondary" role="alert">
      <?php
      if(count($news)>0){
        foreach($news as $new){ ?>
          <img width="150" height="150" src="<?php $trim = $new['image_url']; echo ltrim($trim, './'); ?>"/>
          <br>
          <h4><a href="#"><?php echo $new['title']; ?></a></h4>
          <p class="text-justify"><?php echo substr($new['news'],0,150); ?> <a href="news-details.php?id=<?php echo $new['id']; ?>"><button class="btn btn-outline-success btn-sm"><?php $new['id']; ?>READ MORE</button></a></p>

      <?php
    }
    
  } ?>
      
    </div>
  </div>
</div>

<?php 
require('footer.php');
?>