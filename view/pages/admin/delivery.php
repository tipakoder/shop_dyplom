<section id="delivery">
	<div class="container">
		<div class="section-title">
			<h3 class="text">Службы доставки</h3> 
			<ul class="submenu">
                <li><button onclick="popup_new_delivery_service()" class="btn gray filled"><i class="fas fa-plus"></i> Новая служба доставки</button></li>
            </ul>
		</div>

		<?php if(isset($delivery) && $delivery != null): ?>
		<table class="content">
			<tbody>
				<tr>
					<th>Кодовое имя</th>
					<th>Название</th>
					<th class="action">Действие</th>
				</tr>

				<?php foreach($delivery as $delivery_service): ?>
			    <tr>
					<td><?=$delivery_service['name']?></td>
					<td><?=$delivery_service['title']?></td>
					<td class="action"><button onclick="remove_delivery_service(<?=$delivery_service['id']?>)" class="btn gray filled">Удалить</button></td>
				</tr>
				<?php endforeach; ?>
				</tbody>
		</table>
		<?php endif; ?>
	</div>
</section>