<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$SYS_TITLE?></title>
    <link rel="stylesheet" href="/view/res/css/fonts.css">
    <style>
        *{
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            box-sizing: border-box;
        }

        #wrapper{
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;

            position: fixed;
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
        }

        .code{
            font-family: Montserrat-Bold;
            font-size: 72px;
            font-weight: 400;
            color: #000;
        }

        .codeinfo{
            font-family: Montserrat-Light;
            font-size: 16px;
            font-weight: 400;
            color: #000;
        }
    </style>
</head>
<body>
    <div id="wrapper">
        <h1 class="code"><?=$code?></h1>
        <p class="codeinfo"><?php echo ($more != null) ? $more : "ошибка"; ?></p>
    </div>
</body>
</html>