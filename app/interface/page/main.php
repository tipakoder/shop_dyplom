<section id="best-deals">
    <div class="container">
        <div class="section-title">
            <h3 class="text">Новинки</h3> 
            <ul class="submenu">
                <li><a href="/search">Весь каталог</a></li>
            </ul>
        </div>

        <div class="content catalog-container">
            <?php foreach($products as $product): ?>
            <div class="product" data-product='{"id": "<?=$product['id']?>", "name": "<?=$product['name']?>", "price": "<?=$product['price']?>", "photo": "<?=$product['photo']?>"}'>
                <div class="product-wrapper">
                    <div class="image" style="background-image: url('<?=$product['photo']?>');">
                        <p class="tag new">Новинка</p>
                    </div>
                    <div class="details">
                        <h3 class="name"><?=$product['name']?></h3>
                        <p class="price"><?=$product['price']?> руб./шт</p>
                    </div>
                    <div class="submenu">
                        <div class="actions">
                            <button class="btn" onclick="location.href='/product/<?=$product['id']?>/';">Подробнее</button>
                            <button class="btn icon event-add-favorite" data-id="<?=$product['id']?>"><i class="fas fa-heart"></i></button>
                            <button class="btn icon filled event-add-cart" data-id="<?=$product['id']?>"><i class="fas fa-shopping-cart"></i></button>
                            <button class="btn filled close"><i class="fas fa-times"></i> Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>