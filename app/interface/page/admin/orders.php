<section id="orders-admin">
	<div class="container">
		<div class="section-path">
			<a class="section" href="/admin/">Админ панель</a>
			<a class="section" href="/admin/orders/">Заказы</a>
		</div>

		<div class="section-title">
			<h3 class="text">Заказы</h3> 
		</div>

		<?php if(isset($orders) && $orders != null): ?>
		<div class="cards-container">
			<?php foreach($orders as $order): ?>
		    <div class="card-wrapper">
				<div class="content">
					<h3 class="name"><?=$order['name']?></h3>
					<p class="phone"><i class="fas fa-phone-alt"></i><?=$order['phone']?></p>
				</div>
				<div class="action btn gray filled" onclick="location.href='/order/<?=$order['id']?>/'">Перейти <i class="fas fa-angle-right"></i> </div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>