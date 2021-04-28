<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$SYS_TITLE?></title>
    <link rel="stylesheet" href="/view/res/css/fontawesome.min.css">
    <link rel="stylesheet" href="/view/res/css/fonts.css">
    <link rel="stylesheet" href="/view/res/css/animation.css">
    <link rel="stylesheet" href="/view/res/css/main.css">
    <link rel="stylesheet" href="/view/res/css/adaptive.css">
    <link rel="stylesheet" href="/view/res/css/template.css">
</head>
<body>
    <header id="header-wrapper">
        <div id="header-nav-button" class="header-adaptive-close"><i class="fas fa-bars"></i></div>

        <div class="container">
            <div class="header-logotype">
                <h1 class="symbol">SHOP</h1>
                <p class="text">интернет-магазин<br>одежды и аксессуаров</p>
            </div>

            <nav id="header-wrapper-navigation" class="header-navigation">
                <ul>
                    <li class="search">
                        <h3 class="name">Поиск</h3>
                        <i class="fas fa-search"></i>
                    </li>
                    <li>
                        <h3 class="name">Личный кабинет</h3>
                        <i class="fas fa-user-circle"></i>
                    </li>
                    <li>
                        <h3 class="name">Избранное</h3>
                        <i class="fas fa-heart"></i>
                        <p class="count">0</p>
                    </li>
                    <li class="cart">
                        <div class="li-body">
                            <h3 class="name">Корзина</h3>
                            <i class="fas fa-shopping-cart"></i>
                            <p class="count">0</p>
                        </div>
                        <div class="cart-body">
                            <div class="plug-box">
                                <i class="fas fa-shopping-cart"></i>
                                <p class="text">Ваша корзина пуста</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main id="main-wrapper">
        <?php require_once "pages/{$SYS_PAGE}.php"; ?>
    </main>

    <footer id="footer-wrapper">

    </footer>

    <script src="/view/res/js/adaptive.js"></script>
</body>
</html>