<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
$url = $_POST['url'];
$data = json_decode(file_get_contents($url), true);
//$data = json_decode(file_get_contents('data4.json'), true);
$statics = array();
foreach($data as $key => $arr)
{
	$k = 0;
	if(strstr($arr['土地區段位置或建物區門牌'], '路')|| strstr($arr['土地區段位置或建物區門牌'], '大道') || strstr($arr['土地區段位置或建物區門牌'], '街'))
	{	
		$temp = strstr($arr['土地區段位置或建物區門牌'], '路', true);//php 5.3.0
		if(!$temp)
		{
			$temp = strstr($arr['土地區段位置或建物區門牌'], '街', true);
			if(!$temp)
			{
				$temp = strstr($arr['土地區段位置或建物區門牌'], '大道', true);
				$temp = $temp.'大道';
			}
			else
			{
				$temp = $temp.'街';
			}
		}
		else
		{
			$temp = $temp.'路';
		}
		$k = 1;
	}
	else if(strstr($arr['土地區段位置或建物區門牌'], '巷'))
	{
		$temp = strstr($arr['土地區段位置或建物區門牌'], '巷', true);
		$temp = $temp.'巷';
		$k = 1;
	}
	else;
	if($k == 1)//decide max, min
	{
		$statics[$temp][$arr['交易年月']] = 1;
		if($statics[$temp]['min'] == null)
		{
			$statics[$temp]['min'] = $arr['總價元'];
		}
		if($arr['總價元'] > $statics[$temp]['max'] )
		{
			$statics[$temp]['max'] = $arr['總價元'];
		}
		else if($arr['總價元'] < $statics[$temp]['min'])
		{
			$statics[$temp]['min'] = $arr['總價元'];
		}
		else;
	}
}
$max = 0;//here decide the max value
foreach($statics as $location => $array)
{
	$statics[$location]['count'] = count($array);
	if($statics[$location]['count'] > $max)
	{
		$max = $statics[$location]['count'];
	}
}
foreach($statics as $location => $array)//output answer
{
	if($array['count'] == $max)
	{
		echo $location.', 最高成交價: '.$array['max'].', 最低成交價: '.$array['min'].'<br>';
	}
}
?>