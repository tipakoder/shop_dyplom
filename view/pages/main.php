<section id="best-deals">
    <div class="container">
        <div class="section-title">
            <h3 class="text">Лучшие предложения</h3> 
            <ul class="submenu">
                <li class="selected" onclick="load_products('news')">Новинки</li>
                <li onclick="load_products('hits')">Хиты</li>
                <li><a href="#">Весь каталог</a></li>
            </ul>
        </div>

        <div class="content catalog-container">
            <?php for($i = 0; $i < 8; $i++): ?>
            <div class="product" data-product='{"id": "1", "name": "Бейсболка 5.11", "price": "490", "photo": "/view/res/img/image.jpg"}'>
                <div class="product-wrapper">
                    <div class="image" style="background-image: url(/view/res/img/image.jpg);">
                        <p class="tag new">Новинка</p>
                    </div>
                    <div class="details">
                        <h3 class="name">Бейсболка 5.11</h3>
                        <p class="price">490 руб./шт</p>
                    </div>
                    <div class="submenu">
                        <div class="actions">
                            <button class="button to_favorite"><i class="fas fa-heart"></i> Нравится</button>
                            <button class="button to_cart"><i class="fas fa-shopping-cart"></i> В корзину</button>
                            <button class="button close fas fa-times"></button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endfor; ?>
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