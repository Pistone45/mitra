<?php 
require('header.php');
include_once("functions/functions.php");
$getNews = new News();
$news1 = $getNews->getNews();

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$getNews = new News();
	$news = $getNews->getSpecificNews($id);

}

?>

  <!-- ======= Hero Section ======= -->
  <section id="hero1" class="d-flex flex-column justify-content-center align-items-center">
    <div class="container text-center text-md-left" data-aos="fade-up">
      <h1><span>News</span></h1>
    </div>
  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= Services Section ======= -->
    <section id="services" class="services section-bg">
      <div class="container">

        <div class="section-title">
          <h2>News</h2>
          <p>Read our latest news here</p>
        </div>

<div class="row">
	<div class="col-lg-8">
		<div class="icon-box">
		<div class="row">
		
				<img height="100%" class="img-fluid" width="100%" src="<?php echo substr($news['image_url'],3); ?>" />
			
				<h4 style="padding-top:20px;"><a href="#"><?php echo $news['title']; ?></a></h4>

		<p class="text-justify overflow-hidden">
			<?php echo nl2br($news['news']); ?> </p>
						
		</div>
		
		</div>
	</div>
	<div class="col-lg-4">
						<div class="icon-box">
					<div class="row">
					<?php
						if(isset($news1) && count($news1)>0){
							foreach($news1 as $new){ ?>
						<div class="col-lg-4">
							<img height="100" width="100" src="<?php echo substr($new['image_url'],3); ?>" />
						</div>
						
						<div class="col-lg-8">
							<h4><a href="#"><?php echo $new['title']; ?></a></h4>
					<p class="text-justify overflow-hidden">
						<?php echo substr($new['news'],0,100); ?> </p>

						<a href="news-details.php?id=<?php echo $new['id']; ?>">
						<button class="btn btn-outline-success btn-sm"> READ MORE</button>
						</a>
						
					<br><br><br>
						</div>
					<?php
					
				}
			}
		?>
			
						
					</div>
					
					</div>
	</div>
</div>
       

      </div>
    </section><!-- End Services Section -->
<br><br>
  


<?php 
require('footer.php');
?>