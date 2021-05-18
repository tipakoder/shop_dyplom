<section id="search-page">
	<div class="container">
		<div class="content">
			<div class="filters">
				<div class="field">
					<input type="text" placeholder="Что Вы хотите найти?" value="<?=$_GET['q']?>">
				</div>
				<button class="btn filled apply">Приминить</button>
			</div>

			<div class="results">
				<ul class="filters-header">
					<li class="header">Фильтры:</li>
					<?php foreach($filters as $filter): ?>
						<li><a href="<?=$filter['link']?>"><?=$filter['title']?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
</section>