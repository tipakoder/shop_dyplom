<section id="product-page">
	<div class="container">
		<div class="content">
			<div class="slider-wrapper">
				<div class="active-slide"  style="background-image: url(<?=$product["photo"]?>)"></div>
				<div class="slide selected" style="background-image: url(<?=$product["photo"]?>)"></div>
				<?php if($product["photos"]):
				foreach($product["photos"] as $photo): ?>
					<div class="slide" style="background-image: url(<?=$photo["path"]?>)"></div>
				<?php endforeach; 
				endif;?>
			</div>

			<div class="main-info">
				<h3 class="title"><?=$product['name']?></h3>
				<p class="description"><?=$product['description']?></p>
			</div>
		</div>
	</div>
</section>