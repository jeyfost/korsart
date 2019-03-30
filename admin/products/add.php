<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 24.03.2019
 * Time: 15:50
 */

session_start();
include("../../scripts/connect.php");

if($_SESSION['userID'] != 1) {
    header("Location: ../../");
}

if(!empty($_REQUEST['service_id'])) {
    $idCheckResult = $mysqli->query("SELECT COUNT(id) FROM prices_subcategories WHERE id = '".$mysqli->real_escape_string($_REQUEST['service_id'])."'");
    $idCheck = $idCheckResult->fetch_array(MYSQLI_NUM);

    if($idCheck[0] == 0) {
        header("Location: index.php");
    }
}

?>

<!DOCTYPE html>

<!--[if lt IE 7]><html lang="ru" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="ru" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="ru" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="ru">
<!--<![endif]-->

<head>

    <meta charset="utf-8" />

    <title>Панель администрирования | Добавление продукта</title>

    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link rel="shortcut icon" href="/img/system/favicon.ico" />

    <link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Arimo" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Didact+Gothic" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/css/fonts.css" />
    <link rel="stylesheet" type="text/css" href="/css/admin.css" />
    <link rel="stylesheet" href="/plugins/font-awesome-4.7.0/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="/plugins/lightview/css/lightview/lightview.css" />

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="/plugins/lightview/js/lightview/lightview.js"></script>
    <script type="text/javascript" src="/js/admin/common.js"></script>
    <script type="text/javascript" src="/js/notify.js"></script>
    <script type="text/javascript" src="/js/admin/products/add.js"></script>

    <style>
        #page-preloader {position: fixed; left: 0; top: 0; right: 0; bottom: 0; background: #fff; z-index: 100500;}
        #page-preloader .spinner {width: 32px; height: 32px; position: absolute; left: 50%; top: 50%; background: url('/img/system/spinner.gif') no-repeat 50% 50%; margin: -16px 0 0 -16px;}
    </style>

    <script type="text/javascript">
        $(window).on('load', function () {
            var $preloader = $('#page-preloader'), $spinner = $preloader.find('.spinner');
            $spinner.delay(500).fadeOut();
            $preloader.delay(850).fadeOut();
        });
    </script>

    <!-- Yandex.Metrika counter --><!-- /Yandex.Metrika counter -->
    <!-- Google Analytics counter --><!-- /Google Analytics counter -->
</head>

<body>
<div id="page-preloader"><span class="spinner"></span></div>

<div id="topLine">
    <div id="logo">
        <a href="/"><span><i class="fa fa-home" aria-hidden="true"></i> korsart.by</span></a>
    </div>
    <a href="/admin/admin.php"><span class="headerText">Панель администрирвания</span></a>
    <div id="exit" onclick="exit()">
        <span>Выйти <i class="fa fa-sign-out" aria-hidden="true"></i></span>
    </div>
</div>
<div id="leftMenu">
    <a href="/admin/pages/">
        <div class="menuPoint">
            <i class="fa fa-file-text-o" aria-hidden="true"></i><span> Страницы</span>
        </div>
    </a>
    <a href="/admin/sliders/">
        <div class="menuPoint">
            <i class="fa fa-picture-o" aria-hidden="true"></i><span> Слайдеры</span>
        </div>
    </a>
    <a href="/admin/reviews/">
        <div class="menuPoint">
            <i class="fa fa-commenting" aria-hidden="true"></i><span> Отзывы</span>
        </div>
    </a>
    <a href="/admin/services/">
        <div class="menuPoint">
            <i class="fa fa-list-ul" aria-hidden="true"></i><span> Услуги</span>
        </div>
    </a>
    <a href="/admin/products/">
        <div class="menuPoint active">
            <i class="fa fa-clone" aria-hidden="true"></i><span> Продукты</span>
        </div>
    </a>
    <a href="/admin/galleries/">
        <div class="menuPoint">
            <i class="fa fa-camera" aria-hidden="true"></i><span> Галереи</span>
        </div>
    </a>
    <a href="/admin/blog/">
        <div class="menuPoint">
            <i class="fa fa-bullhorn" aria-hidden="true"></i><span> Блог</span>
        </div>
    </a>
    <a href="/admin/security/">
        <div class="menuPoint">
            <i class="fa fa-shield" aria-hidden="true"></i><span> Безопасность</span>
        </div>
    </a>
</div>

<div id="content">
    <span class="headerFont">Услуги</span>
    <br /><br />
    <form method="post" id="serviceForm">
        <label for="serviceSelect">Услуга:</label>
        <br />
        <select id="serviceSelect" name="service" onchange="window.location = '?service_id=' + this.options[this.selectedIndex].value">
            <option value="">- Выберите услугу -</option>
            <?php
            $serviceNameResult = $mysqli->query("SELECT * FROM prices_subcategories ORDER BY priority");
            while($serviceName = $serviceNameResult->fetch_assoc()) {
                echo "
                        <option value='".$serviceName['id']."'"; if($serviceName['id'] == $_REQUEST['service_id']) {echo " selected";} echo ">".$serviceName['name']."</option>
                            ";
            }
            ?>
        </select>
        <br /><br />
        <input type='button' id='editServiceSubmit' value='Редактирование продуктов' onmouseover='buttonHover("editServiceSubmit", 1)' onmouseout='buttonHover("editServiceSubmit", 0)' onclick='window.location.href = "/admin/products/index.php"' class='button' />

        <?php
        if(!empty($_REQUEST['service_id'])) {
            echo "
                <br /><br />
                <label for='productNameInput'>Заголовок продукта:</label>
                <br />
                <input id='productNameInput' name='productName' />
                <br /><br />
                <label for='productDescriptionInput'>Описание продукта:</label>
                <br />
                <textarea id='productDescriptionInput' name='productDescription' onkeydown='textAreaHeight(this)'></textarea>
                <br /><br />
                <div style='width: 100%;'>
				    <input type='button' id='addProductSubmit' value='Добавить' onmouseover='buttonHover(\"addProductSubmit\", 1)' onmouseout='buttonHover(\"addProductSubmit\", 0)' onclick='addProduct()' class='button' />
			    </div>
            ";
        }
        ?>
    </form>
</div>

</body>

</html>