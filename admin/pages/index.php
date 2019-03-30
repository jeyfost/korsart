<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 11.08.2017
 * Time: 12:27
 */

session_start();
include("../../scripts/connect.php");

if($_SESSION['userID'] != 1) {
	header("Location: ../../");
}

if(!empty($_REQUEST['id'])) {
	$pageCheckResult = $mysqli->query("SELECT COUNT(id) FROM pages WHERE id = '".$mysqli->real_escape_string($_REQUEST['id'])."'");
	$pageCheck = $pageCheckResult->fetch_array(MYSQLI_NUM);

	if($pageCheck[0] == 0) {
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

	<title>Панель администрирования | Страницы</title>

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

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="/js/admin/common.js"></script>
	<script type="text/javascript" src="/js/notify.js"></script>
	<script type="text/javascript" src="/js/admin/pages/index.js"></script>

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
			<div class="menuPoint active">
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
            <div class="menuPoint">
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
		<span class="headerFont">Редактирование страниц</span>
		<br /><br />
		<form method="post" id="pagesForm">
			<label for="pageSelect"></label>
			<select id="pageSelect" name="page" onchange="window.location = '?id=' + this.options[this.selectedIndex].value">
				<option value="">- Выберите страницу -</option>
				<?php
					$pageResult = $mysqli->query("SELECT * FROM pages ORDER BY id");
					while($page = $pageResult->fetch_assoc()) {
						echo "<option value='".$page['id']."'"; if($_REQUEST['id'] == $page['id']) {echo " selected";} echo ">".$page['name']."</option>";
					}
				?>
			</select>
			<?php
				if(!empty($_REQUEST['id'])) {
					$pageResult = $mysqli->query("SELECT * FROM pages WHERE id = '".$mysqli->real_escape_string($_REQUEST['id'])."'");
					$page = $pageResult->fetch_assoc();

					if($_REQUEST['id'] == 1) {
						$textResult = $mysqli->query("SELECT text FROM text WHERE id = 1");
						$text = $textResult->fetch_array(MYSQLI_NUM);
						$text = str_replace("<br />", "\n", $text[0]);
					}

					echo "
						<br /><br />
						<label for='titleInput'>Заголовок:</label>
						<br />
						<input id='titleInput' name='title' value='".$page['title']."' />
						<br /><br />
						<label for='keywordsInput'>Ключевые слова:</label>
						<br />
						<textarea id='keywordsInput' name='keywords' onkeydown='textAreaHeight(this)'>".$page['keywords']."</textarea>
						<br /><br />
						<label for='descriptionInput'>Описание:</label>
						<br />
						<textarea id='descriptionInput' name='description' onkeydown='textAreaHeight(this)'>".$page['description']."</textarea>
					";

					if($_REQUEST['id'] == 1) {
						echo "
							<br /><br />
							<label for='hiddenTextInput'>Скрытый текст:</label>
							<br />
							<textarea id='hiddenTextInput' name='hiddenText' onkeydown='textAreaHeight(this)'>".$text."</textarea>
						";
					}

					echo "
						<br /><br />
						<input type='button' class='button' id='pageSubmit' value='Редактировать' onmouseover='buttonHover(\"pageSubmit\", 1)' onmouseout='buttonHover(\"pageSubmit\", 0)' onclick='edit()' />
					";
				}
			?>
		</form>
	</div>

</body>

</html>