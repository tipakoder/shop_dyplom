<section id="product-page">
	<div class="container">
		<?php if($AUTH): ?>
			<?php if($SYS_LEVELACCESS == 0): ?>
				<div class="section-path">
					<a class="section" href="/admin/">Админ панель</a>
					<a class="section" href="/admin/products/">Продукты</a>
					<a class="section" href="#"><?=$product['name']?></a>
				</div>
			<?php else: ?>
				<div class="section-path">
					<a class="section" href="/search">Поиск</a>
					<a class="section" href="/search?category=<?=$product['category_id']?>"><?=$product['category']?></a>
					<a class="section"  href="/search?category=<?=$product['category_id']."&subcategory=".$product['subcategory_id']?>"><?=$product['subcategory']?></a>
					<a class="section" href="#"><?=$product['name']?></a>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		

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
				<?php else: ?>
					<p class="price"><b>Цена: </b><?=$product['price']?> руб</p>
				<?php endif; ?>

				<div class="actions">
					<?php if($SYS_LEVELACCESS == 0){ ?>
						<button class="btn gray" onclick="product_remove(<?=$product["id"]?>)"><i class="fas fa-trash"></i>Удалить</button>
						<button class="btn gray" onclick="product_sale_off(<?=$product["id"]?>)"><i class="fas fa-store-slash"></i>Снять с продаж</button>
						<button class="btn gray filled" onclick="product_edit(<?=$product['id']?>)"><i class="fas fa-pen"></i>Редактировать</button>
					<?php }else{ ?>
						<button data-id="<?=$product["id"]?>" class="btn gray event-add-favorite" <?=($product['on_sale'] == 'n') ? "disabled" : "" ?>><i class="fas fa-heart"></i>В избранное</button>
						<div class="count-bar">
							<button data-id="<?=$product["id"]?>" class="btn gray filled event-product-less">-</button>
							<p id="product-count">1</p>
							<button data-id="<?=$product["id"]?>" class="btn gray filled event-product-more">+</button>
						</div>
						<button data-id="<?=$product["id"]?>" class="btn gray filled event-product-add-cart" <?=($product['on_sale'] == 'n') ? "disabled" : "" ?>><i class="fas fa-shopping-cart"></i>Добавить в корзину</button>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</section>