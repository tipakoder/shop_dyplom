<section id="search-page">
	<div class="container">
		<div class="content">
			<div class="filters">
				<label class="field-name" for="filter_name">Название</label>
				<div class="field">
					<input id="filter_name" type="text" placeholder="Что Вы хотите найти?" value="<?php echo (isset($_GET['q'])) ? $_GET['q'] : ""; ?>">
				</div>

				<label class="field-name" for="filter_category">Категория</label>
				<div class="field">
					<select id="filter_category">
						<option value="null">Категория не выбрана</option>
						<?php foreach($categorys as $category): ?>
							<option value="<?=$category['id']?>" <?=(isset($_GET['category']) && $_GET['category'] == $category['id']) ? "selected" : ""; ?>><?=$category['name']?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<label class="field-name" for="filter_subcategory">Подкатегория</label>
				<div class="field">
					<select id="filter_subcategory">
						<?php if($subcategorys == []): ?>
							<option value="null">Выберите категорию</option>
						<?php else: ?> 
							<option value="null">Не выбрана</option>
						<?php foreach($subcategorys as $subcategory): ?>
							<option value="<?=$subcategory['id']?>" <?=(isset($_GET['subcategory']) && $_GET['subcategory'] == $subcategory['id']) ? "selected" : ""; ?>><?=$subcategory['name']?></option>
						<?php endforeach; endif; ?>
					</select>
				</div>

				<button class="btn filled apply" onclick="search_page_apply()">Приминить</button>
			</div>

			<div class="results">
				<?php if(is_array($products) && count($products) > 0): ?>
					<h3 class="section-title">Результатов <?=count($products)?></h3>
				<?php endif; ?>

				<ul class="filters-header">
					<?php if($filters != []): ?>
					<li class="header">Фильтры:</li>
					<?php foreach($filters as $filter): ?>
						<li><a href="<?=$filter['link']?>"><?=$filter['title']?></a></li>
					<?php endforeach; ?>
					<?php else: ?>
						<li class="header">Фильтр не задан</li>
					<?php endif; ?>
				</ul>

				<?php if($products != null): ?>
				<ul class="catalog-container">
		            <?php foreach($products as $product): ?>
		            <div class="product" data-product='{"id": "<?=$product['id']?>", "name": "<?=$product['name']?>", "price": "<?=$product['price']?>", "photo": "<?=$product['photo']?>"}'>
		                <div class="product-wrapper">
		                    <div class="image" style="background-image: url('<?=$product['photo']?>');">
		                    </div>
		                    <div class="details">
		                        <h3 class="name"><?=$product['name']?></h3>
		                        <p class="price"><?=$product['price']?> руб./шт</p>
		                    </div>
		                    <div class="submenu">
		                        <div class="actions">
		                            <button class="btn" onclick="location.href='/product/<?=$product['id']?>/';"><i class="fas fa-print"></i> Страница товара</button>
		                            <button class="btn event-add-favorite" data-id="<?=$product['id']?>"><i class="fas fa-heart"></i> Нравится</button>
		                            <button class="btn filled event-add-cart" data-id="<?=$product['id']?>"><i class="fas fa-shopping-cart"></i> В корзину</button>
		                            <button class="btn gray filled close"><i class="fas fa-times"></i> Закрыть</button>
		                        </div>
		                    </div>
		                </div>
		            </div>
		            <?php endforeach; ?>
		        </ul>
		    	<?php else: ?>
		    	<div class="plug-box">
		    		<h3 class="text">Товаров с таким фильтром не найдено</h3>
		    	</div>
		    	<?php endif; ?>
			</div>
		</div>
	</div>
</section>