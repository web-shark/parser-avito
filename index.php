<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Парсер сайта</title>
<style>
	.button-submit{
	width: 100%;
	border:0px solid transparent;
	cursor: pointer;
	background-color: blue;
	color: #fff;
	font-size: 18px;
	padding-top: 15px;
	padding-bottom: 15px;
	transition: all 0.3s;
	margin-top: -4px;
	font-weight: 300;
	}
	.button-submit:hover{
	background-color: #fff;
	color: blue;
	border: 1px solid blue;
	}
</style>
</head>
<body>
	<h1 style="margin-top: 20px;margin-bottom: 50px;text-align: center;color: blue;">Сбор данных с avito</h1>
	<div style="display: flex;justify-content: space-around;background: #fbfbfb;padding: 5px;">
		<form id="begin" style="flex-direction: column;" method="post" action="parser.php">
			<input type="text" name = "url" style="width: 100%;margin-bottom: 15px; padding-top: 15px;padding-bottom: 15px;font-weight: 300;	font-size: 18px;"  placeholder="Ссылка для сбора данных">
			<input type="submit" class="button-submit" id="btn-begin" value="Начать сбор данных">
			<p style="margin-top: 15px;">Первая версия парсера может работать больше часа(один поток только)</p>
		</form>	
	<div style="border: 1px solid blue;">
		<a style="margin-left: 200px;text-decoration: none;text-align: center;	font-size: 20px;color: blue;" href="avito.xlsx" download>Скачать файл avito.xlsx</a>
		<p style="text-align: center;	font-size: 20px;">скачивайте только после 1.5 часов с нажатия кнопки "Начать сбор данных"</p>
	</div>
</div>

</body>
</html>