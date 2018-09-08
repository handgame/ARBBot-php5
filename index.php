<?

set_time_limit(6000);
error_reporting (E_ERROR | E_PARSE);

//������������
include "function.log.php";

bcscale(8); //�� �������
ob_implicit_flush(1); //�� �������
ini_set('precision', 8); //�� �������
require_once('YoBitNet-api.php'); //���������� ���









//��������� �������
include "pars.php";

 



  
  
?> 

<html>
  <head>
    <meta charset="windows-1251">
<meta http-equiv="refresh" content="<?=mt_rand($rand_o_1,$rand_o_2)?>"> <!-- �������������� ��������. 1 ��� 2 �������. ��� ����� ������ ����� �����������, ���� �� ��� �������� -->


 
<?

 



//�������� ������
print "��� �������: <br>";
try 
{

$params = array(); 
	$balance = $YoBitNetAPI->apiQuery('getInfo', $params);

}
catch(YoBitNetAPIException $e) {
	
        echo $e->getMessage();
    }

print "<br><br><br>";
	



	
	
	
	
	
	
//���������� ������ ���� ��������� � pars.php
foreach ($parsarb as $xpair) {
	//sleep(1);
	$hi++;
	$buy_orders = "";
	$sell_orders = "";
	$sell_status = "";
	
	print "<hr><br><br>$hi. ���� $xpair. <br>������: ".$balance['return']['funds'][$xpair]."<br>������ � ��������: ".$balance['return']['funds_incl_orders'][$xpair]." <br><br>";
	
	
	//��������� ������� �������
	try 
	{
	$params = array('pair' => $xpair."_btc");print 1;
	$actord = $YoBitNetAPI->apiQuery('ActiveOrders', $params);
	
	sleep($sleep);
	//�������� ���� �� �������

	$data['depth'] = $YoBitNetAPI->getPairDepth($xpair."_".$dotpars);
	
	//print_r($data);
	$asks = $data['depth']['asks'];
	$bids = $data['depth']['bids'];
	$price_max_bids = sprintf ("%.8f", $asks[0][0]);
	$price_min_asks = sprintf ("%.8f", $bids[0][0]);
	$price_max_bids2 = sprintf ("%.8f", $asks[1][0]);
	$price_min_asks2 = sprintf ("%.8f", $bids[1][0]);
	
	//������������ ������� ����� ������ ���� �� ����� �������
	$btc_in_alt = $btc_in_alt + ($balance['return']['funds_incl_orders'][$xpair] * $price_min_asks);
	$btc_in_alt2 = $btc_in_alt2 + ($balance['return']['funds_incl_orders'][$xpair] * $price_max_bids);

	//������� ���������� �������
	$num[0]=$price_max_bids; 
	$num[1]=$price_min_asks; 
	$procent=$num[0]/100; 
	$resultproc=$num[1]/$procent;
		
		

	
	
	
	
	
	
	//���� �����-�� ������ ����
	if ($actord['return'] != "") {
		//�������� ��� id �������
		$valuti = array_keys ($actord['return']);
		//������� ������
		foreach ($valuti as $value) {
				
				//����� ����� ������ �����? �����??????
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
					
			print "<br> ".$actord['return'][$value]['type']." - (id) "; print $value; //id ������  
			print " - (����) "; print $actord['return'][$value]['pair']; //�������� ����
			print " - (�����) "; print $actord['return'][$value]['amount']; //���������� ����� � ������
			print " - (����) "; print $actord['return'][$value]['rate']; //����
			print " - (�����) "; print $actord['return'][$value]['timestamp_created']; //����� ��������
	
	
	
	

			
	
	

	
	
			//���� ���� ����� �� �������
			if ($actord['return'][$value]['type'] == "sell") {
				print "<br><br>������� ������.<br>1. ����� ����� �� �������";
				print "<br>2. ����� ����� ��������� ����?";
				
				$prodasha_count++;
				
				$sell_status = "1";
				if ($price_max_bids >= $actord['return'][$value]['rate']) {
					
					print "<br><b>3. �� - ��� ���� ".sprintf ("%.8f", $price_max_bids2)." ���� ���� ".sprintf ("%.8f", $actord['return'][$value]['rate']).". ��������� ������ �� asks ".sprintf ("%.8f", $actord['return'][$value]['amount'] * $price_min_asks)."</b><br>";
					
					if ($price_max_bids2 > (sprintf ("%.8f", $actord['return'][$value]['rate'] + 0.00000001))) {
						print "<b>3.1 ������� ������ ����. �������������� �����. �������� ������ ".$actord['return'][$value]['amount']." : ".$price_max_bids2." - ".sprintf ("%.8f", $actord['return'][$value]['rate'] + 0.00000001)."</b>";
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
					print "<br><u>3. ���. �������� � �����������. �� ".$actord['return'][$value]['rate']." ��� $price_max_bids</u>";
					
					//������ ������
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
	
	

	
	
	
	
	
	
	
	
	
	
	
	
		
			//���� ���� ����� �� ������� � ��� ������� �� �������
			if ($actord['return'][$value]['type'] == "buy") {
				
				$pokupka_count++;
			 
				
				print "<br>������� ������<br>1. ����� ����� �� �������";
				print "<br>2. ����� ����� ����� ������� ����? ���� �� ������ �� �������? ���� �� sell ������?";
				if (($price_min_asks <= $actord['return'][$value]['rate']) or $nobuy !=2 or $sell_status != "1") {
					print "<br><b>3. �� - $price_min_asks $price_min_asks2</b><br>";
					if ($price_min_asks2 < (sprintf ("%.8f", $actord['return'][$value]['rate'] - 0.00000001))) {
						print "<br><b>3.1 ����� ������� �� $price_min_asks2 - ".sprintf ("%.8f", $actord['return'][$value]['rate'] - 0.00000001)."</b><br>";
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
						//������ ������, ��� � ���� ���� �� ����� ������ ����� �� �������
							$buy_orders = 1;

					}
				
				} else {
					print "<br><b><u>3. ���. �������� � �����������</u></b>";
					//������ ������
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		//����� ������ �������
		}
		
		


				
	}

	
 

		if ($buy_orders != "1" ) {
		print "<br><br>������� ������: ���� 2<br>1. ������� �� ������� ���: ������� �����? ����: $price_max_bids $price_min_asks. ����� ������ �����: ".$balance['return']['funds']['btc'] / $price_max_bids."";
		
		
		if (($resultproc > $raznicaprocentov or $price_max_bids < 0.00000010) or $nobuy == 2) {
			
			if ($resultproc > $raznicaprocentov) {$filter_procent++; $count_procent = $count_procent + $resultproc; }
			
			print "<br><b>2. ������: ������� � ����: $resultproc ��� ����� ����������� ����, ��� ������ $nobuy</b><br>";} else {
		
		
		$filter_procent2++; $count_procent2 = $count_procent2 + $resultproc2;
		
		if ($sell_status == "1") {print "<br><b>2. ������: ����� � �������</b>";} else {
			//�������� ������ - �����
			$prob_pair = $xpair."_".$dotpars;
			$monet = $mo / sprintf ("%.8f", $price_min_asks + 0.00000001) / 100000;
			$params['pair'] = $prob_pair;
			$params['type'] = "buy";
			$params['rate'] = sprintf ("%.8f", $price_min_asks + 0.00000001);
			$params['amount'] = sprintf ("%.8f", $monet);
			
			print "<br>2. ������� � ���� ����� 93. �������. ����� ��� �������: $monet";
			if (($price_max_bids * $balance['return']['funds'][$xpair]) > 0.0001) {
				print "<br><b>3. ������. � ������� ".$price_max_bids * $balance['return']['funds'][$xpair]."</b><br>";
			} else {
				print "<br><b>3. ������ ����� �� �������</b>";
				$res = $YoBitNetAPI->apiQuery('Trade', $params); 
			}
		}
		}
		}
		
		
	
	
		if ($sell_orders != "1") {
		print "<br>������� ������: ���� 2<br>1. ������� �� ������� ���: ������� �����? ����: $price_max_bids $price_min_asks";
		if ($price_max_bids < 0.00000010) {print "<br><b>2. ������: ����� ����������� ���� ��� ������ $nobuy</b>";} else {
			//������� ������ - �����
			$prob_pair = $xpair."_".$dotpars;
			$params['pair'] = $prob_pair;
			$params['type'] = "sell";
			$params['rate'] = sprintf ("%.8f", $price_max_bids - 0.00000001);
			$params['amount'] = sprintf ("%.8f", $balance['return']['funds'][$xpair]);
			
			print "<br>2. �������. �����: ".$balance['return']['funds'][$xpair]."";
			if (($params['rate'] * $balance['return']['funds'][$xpair]) < 0.0001) {
				print "<br><b>3. ������. ���� ���� �������� ������. ��������� ��������� ".sprintf ("%.8f", $params['rate'] * $balance['return']['funds'][$xpair])." ����� ".$balance['return']['funds'][$xpair]."</b>";
			} else {
				print "<br><b>3. ������ ����� �� �������. ��������� ��������� ".sprintf ("%.8f", $params['rate'] * $balance['return']['funds'][$xpair])."</b>";
				$res = $YoBitNetAPI->apiQuery('Trade', $params); 
			}
		}
		} else {print "<br>------------------------------- � ������� �� ������� - $sell_orders";}
	
	
	
	
	
	
	
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
��������� ������� � ���������� �� asks: $btc_in_alt (������� �� ����, � ������ �������)<br>
��������� ������� � ���������� �� bids: $btc_in_alt2 (������� �� ��������� ������� ��� ������� �������� �����)<br><br>

� ������ ������� ��������, 1: ".sprintf ("%.8f", $balance['return']['funds_incl_orders']['btc'] + $btc_in_alt)." <br>
� ������ ������� ��������, 2: ".sprintf ("%.8f", $balance['return']['funds_incl_orders']['btc'] + $btc_in_alt2)."<br>

";

$time = date("m.d H:i:s");
$time2 = date("i");

 
//if ($time2 == (37 or 38 or 39 or 40 or 41)) {
	$text = file_get_contents("arbmoney-".$whohave.".html");
	sleep(1);
	$fp = fopen("arbmoney-$whohave.html", 'c');
	
  
				 
 
if ($get_count > 0) {
	$text = "$info$time: $get_count ������ �������� ������ <br>$text";
} else {
	if (($balance['return']['funds_incl_orders']['btc'] + $btc_in_alt2) == "0.00000000") {
		$text = "$info$time: Cloudfare protection on <br>$text";
	} else {
		$text = "$info$time: (btc ".sprintf ("%.8f", $balance['return']['funds']['btc']).") <u>alt asks: </u>".sprintf ("%.8f", $btc_in_alt)." <u>alt bids: </u>".sprintf ("%.8f", $btc_in_alt2)." <u>btc+alt asks:</u> ".sprintf ("%.8f", $balance['return']['funds_incl_orders']['btc'] +$btc_in_alt)." $noor <u>btc+alt bids:</u> ".sprintf ("%.8f", $balance['return']['funds_incl_orders']['btc'] + $btc_in_alt2)." ������� �� �������: <u>$pokupka_count</u> ������� �� �������: <u>$prodasha_count</u> ������� ��������� �� ��������: <u>$filter_procent</u> ������� ��������� �� ��������: <u>$filter_procent2</u> 
		
		
		<br>$text";
	}
}
	
	fwrite($fp, $text); // ������ � ����
	fclose($fp); //�������� ����
	
	print "<br>��������: $get_count <br> $error_message"; $error_message = "������: ".$get_count - $get_count_cusse." ��� �� ��������. ";
//}
?>
