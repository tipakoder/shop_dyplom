<section id="order-page">
	<div class="container">
		<?php if($AUTH): ?>
			<?php if($SYS_LEVELACCESS == 0): ?>
			<div class="section-path">
				<a class="section" href="/admin/">Админ панель</a>
				<a class="section" href="/admin/orders/">Заказы</a>
				<a class="section" href="#">Заказ #<?=$order['id']?></a>
			</div>
			<?php else: ?>
				<div class="section-path">
					<a class="section" href="/profile/">Личный кабинет</a>
					<a class="section" href="#">Заказ #<?=$order['id']?></a>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<h2 class="section-title">Заказ #<?=$order['id']?></h2>

		<div class="content">
			<div class="contact-wrapper">
				<label class="field-name">Имя</label>
				<div class="field">
					<input type="text" value="<?=$order['name']?>" disabled>
				</div>
				<label class="field-name">Номер телефона</label>
				<div class="field">
					<input type="tel" value="<?=$order['phone']?>" disabled>
				</div>
				<label class="field-name">Электронная почта</label>
				<div class="field">
					<input type="email" value="<?=$order['email']?>" disabled>
				</div>

				<?php if($order['paid'] == 'n'): ?>
					<button class="btn filled">Оплатить</button>
				<?php else: ?>
					<div class="paid-success"><i class="far fa-check-circle"></i> Успешная оплата</div>
				<?php endif; ?>
			</div>

			<div class="items-wrapper">
				<ul class="items-list">
					<?php 
					$full_price = 0;
					foreach($items as $product): ?>
					<li class="item-card">
						<div class="image" style="background-image: url('<?=$product['photo']?>')"></div>
						<div class="cart-content">
							<h3 class="title"><?=$product['name']?></h3>
							<p class="price"><?=$product['price']?> руб</p>
						</div>
					</li>
					<?php 
					$full_price += $product['price'] * $product['count'];
					endforeach; 
					$promo_price = $full_price;
					if($promocode){
						$promo_price = intval($promo_price - ($promo_price/100*$promocode['percent']));
					}
					?>
				</ul>
				<div class="total-wrapper">
					<div class="row">
						<h3 class="title">Стоймость заказа:</h3>
						<p class="value"><?=$full_price?> руб</p>
					</div>
					<?php if($full_price != $promo_price): ?>
					<div class="row">
						<h3 class="title">Скидка:</h3>
						<p class="value">Промокод "<?=$promocode['code']?>" <?=$promocode['percent']?>%</p>
					</div>
					<?php endif; ?>
					<div class="row">
						<h3 class="title">Итоговая цена:</h3>
						<p class="value"><?=$promo_price?> руб</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>