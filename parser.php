<?php

//Загружаем в переменную страницу официальныъ курсов валют с сайта НБУ
$url = "http://www.bank.gov.ua/control/uk/curmetal/detail/currency?period=daily";
$curl = curl_init($url);
if (!$curl)
    die ('Err initial Curl library');
// Скачанные данные не выводить поток
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
// Скачиваем
$page = curl_exec($curl); //В переменную $page помещается страница
curl_close($curl); // Закрываем соединение

//Парсим
preg_match_all('|USD(.*)</td>\s+</tr>|isU', $page, $content, PREG_SET_ORDER);
preg_match_all("/[0-9.]+$/", $content[0][1], $matches);
$USD_NBU = $matches[0][0]; //в $USD_NBU харнится курс валют для сегодняшнего дня
$USD_NBU = $USD_NBU / 100; //получаем курс 1 грн к 1 доллару

preg_match_all('|EUR(.*)</td>\s+</tr>|isU', $page, $content, PREG_SET_ORDER);
preg_match_all("/[0-9.]+$/", $content[0][1], $matches);
$EUR_NBU = $matches[0][0];
$EUR_NBU = $EUR_NBU / 100;

preg_match_all('|RUB(.*)</td>\s+</tr>|isU', $page, $content, PREG_SET_ORDER);
preg_match_all("/[0-9.]+$/", $content[0][1], $matches);
$RUB_NBU = $matches[0][0];
$RUB_NBU = $RUB_NBU / 10; //у рубля отличается количество сравнения, потому делим на 10 а не на 100

preg_match_all('|CNY(.*)</td>\s+</tr>|isU', $page, $content, PREG_SET_ORDER);
preg_match_all("/[0-9.]+$/", $content[0][1], $matches);
$CNY_NBU = $matches[0][0];
$CNY_NBU = $CNY_NBU / 100;



?> 