<?php
//парсер наушников с розетки
ini_set('max_execution_time', '0'); 
set_time_limit(0);
ignore_user_abort (true);
ini_set('memory_limit', '512M');
//системные настройки, чтобы парсер не останавливался
error_reporting(E_ALL); 
if(!empty($_POST["url"])){
$mainurl=$_POST["url"];//ссылка на категорию парсинга
}else{
	$mainurl='https://www.avito.ru/vladimir/kvartiry/sdam/na_dlitelnyy_srok?i=1';
}
$filename='avito.xlsx';//название exel файла, name.xlsx
?>
<a href="/" style="margin-left: 30px;text-decoration: none;text-align: center;	font-size: 20px;color: blue;">парсинг начался,вернуться назад</a>
<p style="margin-left: 30px;">1 минуту посмотрите чтобы страница работала,потом можете закрывать</p>
<p style="margin-left: 30px;font-size: 20px;">Парсер баниться Авито периодически. Eсли окно страницы загрузилось сразу, значит парсер не работает, запустите его через час или напишите мне, <a style="color: blue;text-decoration: none;" href="https://vk.com/id100210955">Сергей</a>.</p>
<?php
$max = 15;//последняя страница парсинга
$max_a;


$elem_in_page=50;//количество элементов на странице
//header('Location: /');

//начало сбора данных парсера
require_once 'function.php';


$n=0;
$i=2;
parce_in_page($mainurl,$n,$filename);
/*if($max_a!=null){
	$max=$max_a;
}*/
		while($i<$max)
		{
		parce_in_page($mainurl."&p=$i",$n+$elem_in_page*($i-1),$filename);
		$n++;
		$i++;
		}




//if($max>2){


/*
	конец сбора данных, начало записи в exel файл

*/


//echo '<pre>';
//var_dump($obj);//вывод массива с данными
//echo '</pre>';



