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

		<div class="row ">
		
		<?php
			if(isset($news) && count($news)>0){
				foreach($news as $new){ ?>
				<div class="col-lg-6">
				<div class="icon-box">
					<div class="row">
					
						<div class="col-lg-4">
							<img height="100%" width="100%" src="<?php echo substr($new['image_url'],3); ?>" />
						</div>
						
						<div class="col-lg-8">
							<h4><a href="#"><?php echo $new['title']; ?></a></h4>
					<p class="text-justify overflow-hidden">
						<?php echo substr($new['news'],0,150); ?> </p>

						<a href="news-details.php?id=<?php echo $new['id']; ?>">
						<button class="btn btn-outline-success btn-sm"> READ MORE</button>
						</a>
						
					
						</div>					
						
					</div>
					
					</div>
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