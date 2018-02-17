<?php
//$path = $_SERVER['DOCUMENT_ROOT'];
require_once('lib/PHPExcel.php');
require_once('lib/PHPExcel/Writer/Excel5.php');
require_once('lib/PHPExcel/IOFactory.php');
require_once ('lib/phpQuery.php');
require_once ('lib/cURL.php');
require_once ('lib/cURL2.php');
//require_once ('lib/src/TesseractOCR.php');
//подключение библиотек
function parce_in_page($mainurl,$n,$filename){

$results_page = get_xml_page($mainurl); 
$results = phpQuery::newDocument($results_page); 
$blocks = $results->find('div.item_table-description');

//берем данные с каждого элемента

//$obj[0]=['Телефон', 'контактное лицо', 'Тип продавца', 'агентство','Заголовок','Описание','Цена','Процент','доп. Условия (залог)','Город','Район', 'адресс','Ссылка на объявление','Дата объявления','Кол-во просмотров','Номер объявления','Ссылки на картинки'];
global $max_a;
if($n==0){
	$max_a = $results->find('div.pagination-pages.clearfix:last-child')->attr('href');
	$max_a = stristr(substr(strrchr($max_a, "="),1), "&", true);
}
foreach ($blocks as $block){ 
global $url;
$el = pq($block);

$title = $el->find('a.item-description-title-link')->attr('title');//заголовок

$url = 'https://www.avito.ru'.$el->find('a.item-description-title-link')->attr('href');//ссылка

$agency = $el->find('div.data a')->text();//агенство
$el->find('div.data div.date.c-2')->remove();//убираем лишнее

if( !empty( trim( $el->find('div.data')->text() ) ) ){//отслеживание типа
	$type = 'Агенство';
}else{
	$type = 'Арендодатель';
}
$address = $el->find('p.address.fader')->text();//адресс

$time = $el->find('div.popup-prices.popup-prices__wrapper.clearfix')->attr('data-date');//время

$num = substr(strrchr($url, "_"), 1);

$prosent = $el->find('span.about__commission')->text();//процент
//обрезаем первый символ
//echo $results_page;
sleep(3);
$next_page=get_xml_page($url);
//echo $next_page;
$next_result=phpQuery::newDocument($next_page);
if($type !== 'Агенство'){
	$name = $next_result->find('div.seller-info-name a:first')->text();//имя если без имени агенства
}else{
	$name  = $next_result->find('div.seller-info-prop.seller-info-prop_short_margin > div.seller-info-value:first')->text();//имя если агенство
}
$viewers=stristr($next_result->find('a.js-show-stat.pseudo-link')->text(),'(',true);//просмотры
$price=stristr($next_result->find('li.price-value-prices-list-item.price-value-prices-list-item_size-normal.price-value-prices-list-item_pos-first')->text(),'₽',true);//цена
$zalog = $next_result->find('div.item-price-sub-price')->text();//доп условия
$text = $next_result->find('div.item-description')->text();//описание
$img = [];
$image_inner = $next_result->find('div.gallery-imgs-container div.gallery-img-wrapper');
$i=0;
foreach ($image_inner as $image) {
    	$img[$i] = 'https:'.pq($image)->find('div.gallery-img-frame')->attr('data-url');
    	$i++;
}
$img = implode(",", $img);//картинка
if(($next_result->find('div.item-map-location > span[itemprop="name"]')->text())!==null){
	$city=$next_result->find('div.item-map-location > span[itemprop="name"]')->text();//город
	$next_result->find('span.item-map-address > span > span[itemprop="streetAddress"]')->remove();
	$district = stristr($next_result->find('span.item-map-address > span')->text(),',',true);//район
}else{
	$city = stristr($address,',',true);
	$district = stristr(stristr($address,','),true);
}

$mob_next = 'https://m.avito.ru'.$el->find('a.item-description-title-link')->attr('href');
//echo($mob_next);
sleep(2);
$mob_el = get_xml_page($mob_next,'https://m.avito.ru');
$mob_pq = phpQuery::newDocument($mob_el);
$mob_href = $mob_pq->find('a.action-show-number')->attr('href');
//echo $mob_href;
$mob_content = get_xml_page('https://m.avito.ru'.$mob_href.'?async',$mob_next);
//var_dump($mob_content);
$phone_get_array = json_decode($mob_content,true);
$phone = $phone_get_array['phone'];
//var_dump($mob_content);
//exit();
global $obj;
if(file_exists($filename)){
	$objPHPExcel = PHPExcel_IOFactory::load($filename);
}else{
	$objPHPExcel = new PHPExcel();
}
$obj=[$phone,trim($name),trim($type),trim($agency),$title,trim($text),trim($price),trim($prosent),trim($zalog),trim($city),trim($district),trim($address),$url,trim($time),trim($viewers),$num,$img];
$objPHPExcel->getActiveSheet(0)->fromArray($obj, NULL, 'A'.$n);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($filename);

$n++;
} 
}
