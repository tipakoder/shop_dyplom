<section id="products-admin">
	<div class="container">
        <div class="section-path">
            <a class="section" href="/admin/">Админ панель</a>
            <a class="section" href="/admin/products/">Продукты</a>
        </div>

		<div class="section-title">
			<h3 class="text">Товары</h3> 
			<ul class="submenu">
                <li><button onclick="popup_new_product()" class="btn gray filled"><i class="fas fa-plus"></i> Новый товар</button></li>
            </ul>
		</div>

		<?php if(isset($products) && $products != null): ?>
		<div class="content catalog-container">
            <?php foreach($products as $product): ?>
            <div class="product">
                <div class="product-wrapper">
                    <div class="image" style="background-image: url('<?=$product['photo']?>');"></div>
                    <div class="details">
                        <h3 class="name"><?=$product['name']?></h3>
                        <p class="price"><?=$product['price']?> руб./шт</p>
                    </div>
                    <div class="submenu">
                        <div class="actions">
                            <button class="btn" onclick="location.href='/product/<?=$product['id']?>/';">Подробнее</button>
                            <button class="btn icon" onclick="product_edit(<?=$product['id']?>)"><i class="fas fa-edit"></i></button>
                            <button class="btn icon filled" onclick="product_sale_off(<?=$product["id"]?>)"><i class="fas fa-store-slash"></i></button>
                            <button class="btn filled close"><i class="fas fa-times"></i> Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
		<?php endif; ?>
	</div>
</section>