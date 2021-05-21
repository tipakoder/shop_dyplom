<section id="profile">
	<div class="container">
		<div class="userbox">
			<div class="column avatar-box">
				<img src="/view/res/img/avatar.jpg" class="avatar">
				<button class="btn gray filled" onclick="popup_edit_profile()">Редактировать</button>
				<button class="btn gray filled" onclick="popup_change_password()">Сменить пароль</button>
			</div>

			<div class="column maininfo">
				<h2 class="name"><?=$CURRENTUSER['name']?></h2>
				<p class="subinfo email"><i class="fas fa-envelope"></i> <?=$CURRENTUSER['email']?></p>
				<p class="subinfo telephone"><i class="fas fa-phone-alt"></i> <?=$CURRENTUSER['telephone']?></p>
				<div class="orders-wrapper">
					<h2 class="title">История заказов</h2>
					<?php if($orders): ?>
						<ul class="orders-list">
							<?php foreach($orders as $order): ?>
								<li class="order-card">
									<div class="order-content">
										<h3 class="name">Заказ #<?=$order['id']?></h3>
										<p class="items">Состав: <?=$order['items']?></p>
									</div>

									<div class="order-action">
										<p class="price"><?=$order['price']?> руб</p>
										<button class="btn gray filled" onclick="location.href = '/order/<?=$order['id']?>/'">Подробнее</button>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php else: ?>
						<div class="plug-box">
				    		<h3 class="text">Вы ничего не заказывали</h3>
				    	</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</section>