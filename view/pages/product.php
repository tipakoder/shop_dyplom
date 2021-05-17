<section id="product-page">
	<div class="container">
		<div class="section-path">
			<a class="section" href=""><?=$product['category']?></a>
			<a class="section" href=""><?=$product['subcategory']?></a>
		</div>

		<div class="content">
			<div class="slider-wrapper">
				<div class="active-slide"  style="background-image: url(<?=$product["photo"]?>)"></div>
				<div class="slide selected" style="background-image: url(<?=$product["photo"]?>)"></div>
				<?php 
				$emptyCount = ($product["photos"] != false) ? 3 - count($product["photos"]) : 3;
				if($product["photos"]):
				foreach($product["photos"] as $photo): ?>
					<div class="slide" style="background-image: url(<?=$photo["path"]?>)"></div>
				<?php endforeach; 
				endif; 
				for($i = 0; $i < $emptyCount; $i++){?>
					<div class="slide off"></div>
				<?php } ?>
			</div>

			<div class="main-info">
				<h3 class="title"><?=$product['name']?></h3>
				<p class="description"><?=$product['description']?></p>
				<?php if($product["on_sale"] == 'n'): ?>
				<p class="active"><i class="fas fa-exclamation"></i>Товар отсутствует в продаже</p>
				<?php endif; ?>

				<div class="actions">
					<?php if($SYS_LEVELACCESS == 0){ ?>
						<button class="btn" onclick="product_remove(<?=$product["id"]?>)"><i class="fas fa-trash"></i>Удалить</button>
						<button class="btn" onclick="product_sale_off(<?=$product["id"]?>)"><i class="fas fa-store-slash"></i>Снять с продаж</button>
						<button class="btn filled"><i class="fas fa-pen"></i>Редактировать</button>
					<?php }else{ ?>
						<button class="btn" <?=($product['on_sale'] == 'n') ? "disabled" : "" ?>><i class="fas fa-heart"></i>В избранное</button>
						<button class="btn filled" <?=($product['on_sale'] == 'n') ? "disabled" : "" ?>><i class="fas fa-shopping-cart"></i>Добавить в корзину</button>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>