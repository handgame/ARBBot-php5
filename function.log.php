<?

//лог ошибок скачки страниц
function loge($info) {
	$fp = fopen('log.txt', 'a');
	$text = "$info\r\n";
	fwrite($fp, $text); // Запись в файл
	fclose($fp); //Закрытие файл
	return(1);
}




//лог дохода
 function logemoney($info) {
	$whohave = spirit($_GET['whohave']);
	$dotpars = spirit($_GET['dotpars']);
	$fp = fopen("$whohave".$dotpars."_money.txt", 'a');
	$text = "$info\r\n";
	fwrite($fp, $text); // Запись в файл
	fclose($fp); //Закрытие файл
	return(1);
}


//Линия ордера, при котором валюта была продана
 function logtest($info) {

	$fp = fopen("logtest.txt", 'a');
	$text = "$info\r\n";
	fwrite($fp, $text); // Запись в файл
	fclose($fp); //Закрытие файл
}

//Вычисляем нужные валюты для внутрибиржевого арбитража
 function logarb($info) {

	$fp = fopen("logarb.txt", 'a');
	$text = "$info\r\n";
	fwrite($fp, $text); // Запись в файл
	fclose($fp); //Закрытие файл
}




?>