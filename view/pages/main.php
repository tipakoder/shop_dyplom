<section id="best-deals">
    <div class="container">
        <div class="section-title">
            <h3 class="text">Новинки</h3> 
            <ul class="submenu">
                <li><a href="/search">Весь каталог</a></li>
            </ul>
        </div>

        <div class="content catalog-container">
            <?php foreach($best_deals as $best_deal): ?>
            <div class="product" data-product='{"id": "<?=$best_deal['id']?>", "name": "<?=$best_deal['name']?>", "price": "<?=$best_deal['price']?>", "photo": "<?=$best_deal['photo']?>"}'>
                <div class="product-wrapper">
                    <div class="image" style="background-image: url('<?=$best_deal['photo']?>');">
                        <p class="tag new">Новинка</p>
                    </div>
                    <div class="details">
                        <h3 class="name"><?=$best_deal['name']?></h3>
                        <p class="price"><?=$best_deal['price']?> руб./шт</p>
                    </div>
                    <div class="submenu">
                        <div class="actions">
                            <button class="btn" onclick="location.href='/product/<?=$best_deal['id']?>/';">Подробнее</button>
                            <button class="btn icon event-add-favorite" data-id="<?=$best_deal['id']?>"><i class="fas fa-heart"></i></button>
                            <button class="btn icon filled event-add-cart" data-id="<?=$best_deal['id']?>"><i class="fas fa-shopping-cart"></i></button>
                            <button class="btn filled close"><i class="fas fa-times"></i> Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>