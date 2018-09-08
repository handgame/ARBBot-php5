<?

set_time_limit(6000);
error_reporting (E_ERROR | E_PARSE);

//Логгирование
include "function.log.php";

bcscale(8); //Не трогать
ob_implicit_flush(1); //Не трогать
ini_set('precision', 8); //Не трогать
require_once('YoBitNet-api.php'); //библиотека апи









//настройки скрипта
include "pars.php";

 



  
  
?> 

<html>
  <head>
    <meta charset="windows-1251">
<meta http-equiv="refresh" content="<?=mt_rand($rand_o_1,$rand_o_2)?>"> <!-- Автообновление страницы. 1 или 2 секунды. Как часто скрипт будет срабатывать, если вы его откроете -->


 
<?

 



//Получаем баланс
print "Все балансы: <br>";
try 
{

$params = array(); 
	$balance = $YoBitNetAPI->apiQuery('getInfo', $params);

}
catch(YoBitNetAPIException $e) {
	
        echo $e->getMessage();
    }

print "<br><br><br>";
	



	
	
	
	
	
	
//перебираем каждую пару указанную в pars.php
foreach ($parsarb as $xpair) {
	//sleep(1);
	$hi++;
	$buy_orders = "";
	$sell_orders = "";
	$sell_status = "";
	
	print "<hr><br><br>$hi. Пара $xpair. <br>Баланс: ".$balance['return']['funds'][$xpair]."<br>Баланс с ордерами: ".$balance['return']['funds_incl_orders'][$xpair]." <br><br>";
	
	
	//Проверяем наличие ордеров
	try 
	{
	$params = array('pair' => $xpair."_btc");print 1;
	$actord = $YoBitNetAPI->apiQuery('ActiveOrders', $params);
	
	sleep($sleep);
	//Получаем цены по ордерам

	$data['depth'] = $YoBitNetAPI->getPairDepth($xpair."_".$dotpars);
	
	//print_r($data);
	$asks = $data['depth']['asks'];
	$bids = $data['depth']['bids'];
	$price_max_bids = sprintf ("%.8f", $asks[0][0]);
	$price_min_asks = sprintf ("%.8f", $bids[0][0]);
	$price_max_bids2 = sprintf ("%.8f", $asks[1][0]);
	$price_min_asks2 = sprintf ("%.8f", $bids[1][0]);
	
	//подсчитываем сколько стоят монеты если их сразу продать
	$btc_in_alt = $btc_in_alt + ($balance['return']['funds_incl_orders'][$xpair] * $price_min_asks);
	$btc_in_alt2 = $btc_in_alt2 + ($balance['return']['funds_incl_orders'][$xpair] * $price_max_bids);

	//рассчет процентной разницы
	$num[0]=$price_max_bids; 
	$num[1]=$price_min_asks; 
	$procent=$num[0]/100; 
	$resultproc=$num[1]/$procent;
		
		

	
	
	
	
	
	
	//Если какие-то ордера есть
	if ($actord['return'] != "") {
		//получаем все id ордеров
		$valuti = array_keys ($actord['return']);
		//Выводим ордера
		foreach ($valuti as $value) {
				
				//нахуя здесь сансел ордер? ебобо??????
				/*
				try 
				{
					$paramstwo = array('order_id' => $value);
					
					$restwo = $YoBitNetAPI->apiQuery('CancelOrder', $paramstwo);
					//print_r($restwo); 
				}
				catch(YoBitNetAPIException $e) {
					echo $e->getMessage();

					}
				*/
					
			print "<br> ".$actord['return'][$value]['type']." - (id) "; print $value; //id ордера  
			print " - (пара) "; print $actord['return'][$value]['pair']; //получаем пару
			print " - (монет) "; print $actord['return'][$value]['amount']; //количество монет в ордере
			print " - (цена) "; print $actord['return'][$value]['rate']; //цена
			print " - (время) "; print $actord['return'][$value]['timestamp_created']; //Время создания
	
	
	
	

			
	
	

	
	
			//если есть ордер на продажу
			if ($actord['return'][$value]['type'] == "sell") {
				print "<br><br>Продажа монеты.<br>1. Имеет ордер на продажу";
				print "<br>2. Ордер имеет наивысшую цену?";
				
				$prodasha_count++;
				
				$sell_status = "1";
				if ($price_max_bids >= $actord['return'][$value]['rate']) {
					
					print "<br><b>3. Да - под нами ".sprintf ("%.8f", $price_max_bids2)." наша цена ".sprintf ("%.8f", $actord['return'][$value]['rate']).". Стоимость актива по asks ".sprintf ("%.8f", $actord['return'][$value]['amount'] * $price_min_asks)."</b><br>";
					
					if ($price_max_bids2 > (sprintf ("%.8f", $actord['return'][$value]['rate'] + 0.00000001))) {
						print "<b>3.1 Слишком низкая цена. Перевыставляем ордер. Отменяем монеты ".$actord['return'][$value]['amount']." : ".$price_max_bids2." - ".sprintf ("%.8f", $actord['return'][$value]['rate'] + 0.00000001)."</b>";
						$balance['return']['funds'][$xpair] = $actord['return'][$value]['amount'];

						$price_max_bids = $price_max_bids2;
						try 
					{
						$paramstwo = array('order_id' => $value);
					
						$restwo = $YoBitNetAPI->apiQuery('CancelOrder', $paramstwo);
						//print_r($restwo); 
					}
					catch(YoBitNetAPIException $e) {
						echo $e->getMessage();
					
					}
					} else {
						$sell_orders = 1;
					}
					
				} else {
					print "<br><u>3. Нет. отменяем и пересоздаем. Их ".$actord['return'][$value]['rate']." наш $price_max_bids</u>";
					
					//отмена ордера
					$balance['return']['funds'][$xpair] = $actord['return'][$value]['amount'];
					try 
					{
						$paramstwo = array('order_id' => $value);
					
						$restwo = $YoBitNetAPI->apiQuery('CancelOrder', $paramstwo);
						//print_r($restwo); 
					}
					catch(YoBitNetAPIException $e) {
						echo $e->getMessage();
					
					}
				}
			}
	
	

	
	
	
	
	
	
	
	
	
	
	
	
		
			//если есть ордер на покупку и нет ордеров на продажу
			if ($actord['return'][$value]['type'] == "buy") {
				
				$pokupka_count++;
			 
				
				print "<br>Покупка монеты<br>1. Имеет ордер на покупку";
				print "<br>2. Ордер имеет самую высокую цену? Есть ли запрет на покупку? Есть ли sell ордера?";
				if (($price_min_asks <= $actord['return'][$value]['rate']) or $nobuy !=2 or $sell_status != "1") {
					print "<br><b>3. Да - $price_min_asks $price_min_asks2</b><br>";
					if ($price_min_asks2 < (sprintf ("%.8f", $actord['return'][$value]['rate'] - 0.00000001))) {
						print "<br><b>3.1 Можно снизить до $price_min_asks2 - ".sprintf ("%.8f", $actord['return'][$value]['rate'] - 0.00000001)."</b><br>";
						$price_min_asks = $price_min_asks2;
							try 
							{
								$paramstwo = array('order_id' => $value);
					
								$restwo = $YoBitNetAPI->apiQuery('CancelOrder', $paramstwo);
								//print_r($restwo); 
							}
							catch(YoBitNetAPIException $e) {
							echo $e->getMessage();
					
							}
					} else {
						//Подаем сигнал, что в этой паре не нужно делать ордер на покупку
							$buy_orders = 1;

					}
				
				} else {
					print "<br><b><u>3. Нет. Отменяем и пересоздаем</u></b>";
					//отмена ордера
				try 
				{
					$paramstwo = array('order_id' => $value);
					
					$restwo = $YoBitNetAPI->apiQuery('CancelOrder', $paramstwo);
					//print_r($restwo); 
				}
				catch(YoBitNetAPIException $e) {
					echo $e->getMessage();
					
					}
				}

			}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		//Конец вывода ордеров
		}
		
		


				
	}

	
 

		if ($buy_orders != "1" ) {
		print "<br><br>Покупка монеты: Блок 2<br>1. Ордеров на покупку нет: Создаем ордер? Цены: $price_max_bids $price_min_asks. Можно купить монет: ".$balance['return']['funds']['btc'] / $price_max_bids."";
		
		
		if (($resultproc > $raznicaprocentov or $price_max_bids < 0.00000010) or $nobuy == 2) {
			
			if ($resultproc > $raznicaprocentov) {$filter_procent++; $count_procent = $count_procent + $resultproc; }
			
			print "<br><b>2. Отмена: Разница в цене: $resultproc или порог минимальной цены, или запрет $nobuy</b><br>";} else {
		
		
		$filter_procent2++; $count_procent2 = $count_procent2 + $resultproc2;
		
		if ($sell_status == "1") {print "<br><b>2. Отмена: ордер в продаже</b>";} else {
			//Покупаем монету - ордер
			$prob_pair = $xpair."_".$dotpars;
			$monet = $mo / sprintf ("%.8f", $price_min_asks + 0.00000001) / 100000;
			$params['pair'] = $prob_pair;
			$params['type'] = "buy";
			$params['rate'] = sprintf ("%.8f", $price_min_asks + 0.00000001);
			$params['amount'] = sprintf ("%.8f", $monet);
			
			print "<br>2. Разница в цене менее 93. Скупаем. Монет для покупки: $monet";
			if (($price_max_bids * $balance['return']['funds'][$xpair]) > 0.0001) {
				print "<br><b>3. Отмена. В ордерах ".$price_max_bids * $balance['return']['funds'][$xpair]."</b><br>";
			} else {
				print "<br><b>3. Создан ордер на покупку</b>";
				$res = $YoBitNetAPI->apiQuery('Trade', $params); 
			}
		}
		}
		}
		
		
	
	
		if ($sell_orders != "1") {
		print "<br>Продажа монеты: Блок 2<br>1. Ордеров на продажу нет: Создаем ордер? Цены: $price_max_bids $price_min_asks";
		if ($price_max_bids < 0.00000010) {print "<br><b>2. Отмена: порог минимальной цены или запрет $nobuy</b>";} else {
			//продаем монету - ордер
			$prob_pair = $xpair."_".$dotpars;
			$params['pair'] = $prob_pair;
			$params['type'] = "sell";
			$params['rate'] = sprintf ("%.8f", $price_max_bids - 0.00000001);
			$params['amount'] = sprintf ("%.8f", $balance['return']['funds'][$xpair]);
			
			print "<br>2. Создаем. Монет: ".$balance['return']['funds'][$xpair]."";
			if (($params['rate'] * $balance['return']['funds'][$xpair]) < 0.0001) {
				print "<br><b>3. Отмена. Ждем пока появятся монеты. Суммарная стоимость ".sprintf ("%.8f", $params['rate'] * $balance['return']['funds'][$xpair])." монет ".$balance['return']['funds'][$xpair]."</b>";
			} else {
				print "<br><b>3. Создан ордер на продажу. Суммарная стоимость ".sprintf ("%.8f", $params['rate'] * $balance['return']['funds'][$xpair])."</b>";
				$res = $YoBitNetAPI->apiQuery('Trade', $params); 
			}
		}
		} else {print "<br>------------------------------- А продажа не удалась - $sell_orders";}
	
	
	
	
	
	
	
	//if (($actord['return'][$value]['amount'] * $actord['return'][$value]['rate']) > 0.0001) {
	
 

	}
	catch(YoBitNetAPIException $e) {
		
        $get_message = $e->getMessage();
		$get_count++;
		print "! $get_message !";
		$error_message .= "$get_message <br>";
		
    }
	
	
	
	}
	

 

 

print "<br><br><br>
Стоимость активов в альткоинах по asks: $btc_in_alt (Зависит от цены, с учетом убытков)<br>
Стоимость активов в альткоинах по bids: $btc_in_alt2 (Зависит от возможной продажи при текущих условиях рынка)<br><br>

С учетом ордеров биткоина, 1: ".sprintf ("%.8f", $balance['return']['funds_incl_orders']['btc'] + $btc_in_alt)." <br>
С учетом ордеров биткоина, 2: ".sprintf ("%.8f", $balance['return']['funds_incl_orders']['btc'] + $btc_in_alt2)."<br>

";

$time = date("m.d H:i:s");
$time2 = date("i");

 
//if ($time2 == (37 or 38 or 39 or 40 or 41)) {
	$text = file_get_contents("arbmoney-".$whohave.".html");
	sleep(1);
	$fp = fopen("arbmoney-$whohave.html", 'c');
	
  
				 
 
if ($get_count > 0) {
	$text = "$info$time: $get_count ошибки передачи данных <br>$text";
} else {
	if (($balance['return']['funds_incl_orders']['btc'] + $btc_in_alt2) == "0.00000000") {
		$text = "$info$time: Cloudfare protection on <br>$text";
	} else {
		$text = "$info$time: (btc ".sprintf ("%.8f", $balance['return']['funds']['btc']).") <u>alt asks: </u>".sprintf ("%.8f", $btc_in_alt)." <u>alt bids: </u>".sprintf ("%.8f", $btc_in_alt2)." <u>btc+alt asks:</u> ".sprintf ("%.8f", $balance['return']['funds_incl_orders']['btc'] +$btc_in_alt)." $noor <u>btc+alt bids:</u> ".sprintf ("%.8f", $balance['return']['funds_incl_orders']['btc'] + $btc_in_alt2)." Ордеров на покупку: <u>$pokupka_count</u> Ордеров на продажу: <u>$prodasha_count</u> Покупка исключена по проценту: <u>$filter_procent</u> Покупка разрешена по проценту: <u>$filter_procent2</u> 
		
		
		<br>$text";
	}
}
	
	fwrite($fp, $text); // Запись в файл
	fclose($fp); //Закрытие файл
	
	print "<br>Запросов: $get_count <br> $error_message"; $error_message = "Ошибка: ".$get_count - $get_count_cusse." пар не получено. ";
//}
?>
