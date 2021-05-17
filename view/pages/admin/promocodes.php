<section id="promocodes">
	<div class="container">
		<div class="section-title">
			<h3 class="text">Промокоды</h3> 
			<ul class="submenu">
                <li><button onclick="popup_promocodes_new()" class="btn gray filled"><i class="fas fa-plus"></i> Новый промокод</button></li>
            </ul>
		</div>

		<?php if(isset($promocodes) && $promocodes != null): ?>
		<table class="content">
			<tbody>
				<tr>
					<th>Код</th>
					<th>Скидка</th>
					<th>Статус</th>
					<th class="action">Действие</th>
				</tr>

				<?php foreach($promocodes as $promocode): ?>
			    <tr>
					<td><?=$promocode['code']?></td>
					<td><?=$promocode['percent']?>%</td>
					<td><?=($promocode['active'] == "y") ? "Активен" : "Неактивен"?></td>
					<td class="action"><button onclick="turn_promocode(<?=$promocode['id']?>)" class="btn gray filled"><?=($promocode['active'] == "y") ? "Деактивировать" : "Активировать"?></button></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
		</table>
		<?php endif; ?>
	</div>
</section>