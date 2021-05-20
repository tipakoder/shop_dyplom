<section id="best-deals">
    <div class="container">
        <div class="section-title">
            <h3 class="text">Лучшие предложения</h3> 
            <ul class="submenu switch">
                <li class="selected" onclick="load_products('news')">Новинки</li>
                <li onclick="load_products('hits')">Хиты</li>
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
                            <button class="btn" onclick="location.href='/product/<?=$best_deal['id']?>/';"><i class="fas fa-print"></i> Страница товара</button>
                            <button class="btn event-add-favorite" data-id="<?=$best_deal['id']?>"><i class="fas fa-heart"></i> Нравится</button>
                            <button class="btn filled event-add-cart" data-id="<?=$best_deal['id']?>"><i class="fas fa-shopping-cart"></i> В корзину</button>
                            <button class="btn gray filled close"><i class="fas fa-times"></i> Закрыть</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="previously-watched" class="background">
    <div class="container">
        <div class="section-title">Ранее вы смотрели</div>
        <div class="content">
            <div class="hover-block">
                <div class="inner-wrapper">
                    <img src="/view/res/img/recently_watched.jpg" alt="" class="image">
                    <div class="text">
                        <h5 class="name">Худи</h5>
                        <p class="price">2 399 руб./шт</p>
                    </div>
                </div>
            </div>
            <div class="block"></div>
            <div class="block"></div>
        </div>
    </div>
</section>