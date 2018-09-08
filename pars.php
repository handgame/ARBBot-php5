<?

#Основные настройки бота



//Ваши ключи от api yobit. Чтобы сгенерировать ключи, зайдите в настройки вашего аккаунта, создайте ключ со всеми параметрами, кроме возможности вывода
$YoBitNetAPI = new YoBitNetAPI(
                    '', // API KEY
                    '' // API SECRET
                      );
					  
					  

#Пары к btc, которыми мы торгуем

$parsarb[] = "scs";
$parsarb[] = "cks";
$parsarb[] = "cmc";
$parsarb[] = "smbr";
$parsarb[] = "am";
$parsarb[] = "ntrn";
$parsarb[] = "007";
$parsarb[] = "dox";
$parsarb[] = "ext";
$parsarb[] = "cb";
$parsarb[] = "xde";
$parsarb[] = "stv";
$parsarb[] = "sls";
$parsarb[] = "grow"; 
$parsarb[] = "xpy";
$parsarb[] = "noo";
$parsarb[] = "ok";
$parsarb[] = "con";
$parsarb[] = "obs";
$parsarb[] = "rice";
/*
$parsarb[] = "npc";
$parsarb[] = "ene"; 
$parsarb[] = "hire"; 
$parsarb[] = "xbtc21";
$parsarb[] = "chemx";
$parsarb[] = "gakh";
$parsarb[] = "lizi";
$parsarb[] = "units";
$parsarb[] = "bsty";
$parsarb[] = "sic";
$parsarb[] = "pen";
$parsarb[] = "boom";
$parsarb[] = "bitz";
$parsarb[] = "crave";
$parsarb[] = "bbcc";
$parsarb[] = "metal";
$parsarb[] = "clam";
$parsarb[] = "mrp";
$parsarb[] = "etrust";
$parsarb[] = "tp1";
$parsarb[] = "gram";
$parsarb[] = "qtz";
$parsarb[] = "epy";
*/
$parsarb[] = "pkb";
$parsarb[] = "m1";
$parsarb[] = "krak";
$parsarb[] = "cs";
$parsarb[] = "sed";
$parsarb[] = "snrg";
$parsarb[] = "rad";
$parsarb[] = "psy";
$parsarb[] = "moin";
$parsarb[] = "arpa";
$parsarb[] = "drkt";
$parsarb[] = "ge";
$parsarb[] = "tam";
$parsarb[] = "circ";
$parsarb[] = "heel";
$parsarb[] = "scrt";
$parsarb[] = "hsp";
$parsarb[] = "lun";
$parsarb[] = "av";
$parsarb[] = "nlg";
$parsarb[] = "clint";
$parsarb[] = "ghs";
$parsarb[] = "synx";
$parsarb[] = "zyd";
$parsarb[] = "psb";
$parsarb[] = "argus";
$parsarb[] = "mavro";
$parsarb[] = "tag";
$parsarb[] = "crm";
$parsarb[] = "lunyr";
 




//сумма для торгов. Уменьшайте или увеличивайте ее в зависимости от свободного остатка на балансе, который вы хотели бы, чтобы был в работе. Указывается для каждой пары
$mo = 40; 

//Пока это строка раскомментирована - происходит только продажа. Полезно во время отладки или когда вы хотите вывести все деньги с альтов по наилучшей цене
//$nobuy = 2;

//Разница в процентах, которая должна быть у пары, для того, чтобы заключать по ней сделки. иначе пара не проходит фильтр
$raznicaprocentov = 90;

//к какой паре торгуем. рынок большой
$dotpars = "btc";

//Пауза в секундах между запросами к каждой паре. Антибан 
$sleep = 1;


$global_ua = "Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.18"; //Для обхода cloudfare. Юзер-агент
$global_cookie = ""; //Для обхода cloudfare. Юзер-агент
$global_proxy = ""; //Ип прокси. Не обязательно
$global_port = ""; //Порт прокси. Не обязательно
$global_login = ""; //Логин прокси. Не обязательно
$global_password = ""; //Пароль прокси. Не обязательно



//Автообновление при открытой странице браузера, от и до, секунд, рандом
$rand_o_1 = 10;
$rand_o_2 = 20;

//Кодировка сервера. При наличии проблем поставьте utf-8
$cd = "windows-1251";
 

?>