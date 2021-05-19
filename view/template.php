<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$SYS_TITLE?></title>
    <link rel="stylesheet" href="/view/res/css/fontawesome.min.css">
    <link rel="stylesheet" href="/view/res/css/fonts.css">
    <link rel="stylesheet" href="/view/res/css/animation.css">
    <link rel="stylesheet" href="/view/res/css/popup.css">
    <link rel="stylesheet" href="/view/res/css/main.css">
    <link rel="stylesheet" href="/view/res/css/adaptive.css">
    <link rel="stylesheet" href="/view/res/css/template.css">
</head>
<body>
    <div id="loading-page">
        <i class="fas fa-spinner"></i>
    </div>

    <div id="page-wrapper">
        <header id="header-wrapper">
            <div id="header-nav-button" class="header-adaptive-close"><i class="fas fa-bars"></i></div>

            <div class="container">
                <div class="header-logotype">
                    <a href="<?php echo ($SYS_LEVELACCESS > 0) ? "/" : "/admin/";?>" class="symbol">SHOP</a>
                    <p class="text">интернет-магазин<br>одежды и аксессуаров</p>
                </div>

                <nav id="header-wrapper-navigation" class="header-navigation">
                    <ul>
                        <li class="search" onclick="search_show()">
                            <h3 class="name">Поиск</h3>
                            <i class="fas fa-search"></i>
                        </li>
                        <li onclick="<?php if(!$AUTH){echo 'popup_auth()';}else{echo "location.href='/profile/';";}?>">
                            <h3 class="name">Личный кабинет</h3>
                            <i class="fas fa-user-circle"></i>
                        </li>

                        <?php if($SYS_LEVELACCESS > 0){ ?>
                        <li>
                            <a href="/favorite/">
                                <h3 class="name">Избранное</h3>
                                <i class="fas fa-heart"></i>
                                <p class="count" id="favorite-count-text">0</p>
                            </a>
                        </li>
                        <li class="cart">
                            <div class="li-body">
                                <h3 class="name">Корзина</h3>
                                <i class="fas fa-shopping-cart"></i>
                                <p class="count" id="cart-count-text">0</p>
                            </div>
                            <div class="cart-body" id="cart-body">
                                <div class="plug-box">
                                    <i class="fas fa-shopping-cart"></i>
                                    <p class="text">Ваша корзина пуста</p>
                                </div>
                                <div class="content">
                                    <ul></ul>
                                    <a href="/cart/" class="open-cart">Перейти в корзину</a>
                                </div>
                            </div>
                        </li>
                        <?php } ?>

                        <?php if($AUTH == true): ?>
                            <li>
                                <a href="/logout/">
                                    <h3 class="name">Выйти</h3>
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </header>

        <main id="main-wrapper">
            <?php require_once "pages/{$SYS_PAGE}.php"; ?>
        </main>

        <footer id="footer-wrapper">
            <div class="container">
                <div class="column logotype">
                    <p class="text">2021 © Shop. All rights reserved.</p>
                </div>

                <div class="column social-icons">
                    <a class="icon" href="#" target="__blank"><i class="fab fa-vk"></i></a>
                    <a class="icon" href="#" target="__blank"><i class="fab fa-facebook-f"></i></a>
                    <a class="icon" href="#" target="__blank"><i class="fab fa-instagram"></i></a>
                </div>

                <div class="column contacts">
                    <p class="text"><b>8 800 111 92 22</b></p>
                    <p class="text"><i class="fas fa-envelope"></i> support@shop.d</p>
                    <!-- <p class="text"><i class="fas fa-map-marker-alt"></i> г. Оренбург, ул. Чкалова, 11 </p> -->
                </div>
            </div>
        </footer>
    </div>

    <script src="/view/res/js/popup.js"></script>
    <script src="/view/res/js/search.js"></script>
    <script src="/view/res/js/main.js"></script>
    <script src="/view/res/js/product.js"></script>
    <script src="/view/res/js/catalog.js"></script>
    <?php if($AUTH){ ?>
        <script src="/view/res/js/user.js"></script>
    <?php } ?>
    <?php if($SYS_LEVELACCESS == 0){ ?>
        <script src="/view/res/js/admin.js"></script>
    <?php } ?>
</body>
</html>