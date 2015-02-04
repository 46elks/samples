<?
// Example to get cost history from the 46elks service
// Change $username, $password and the mobile number to send to


// Set your 46elks API username and API password here
// You can find them at https://dashboard.46elks.com/
$username = '';
$password = '';

$response = file_get_contents('https://'.$username.':'.$password.'@api.46elks.com/a1/SMS');

if (!strstr($http_response_header[0],"200 OK"))
    die($http_response_header[0]);

$decodedlist = json_decode($response);
$list = $decodedlist->data;

while(true){
	if(isset($decodedlist->next)){
		$response = file_get_contents('https://'.$username.':'.$password.'@api.46elks.com/a1/SMS?start='.$decodedlist->next);
		if (!strstr($http_response_header[0],"200 OK"))
    		die($http_response_header[0]);
		$decodedlist = json_decode($response);
		$list = array_merge($list, $decodedlist->data);
	}
	else{
		break;
	}
}

$costmonth = array();
$numbermonth = array();

foreach($list as $sms){
	if(isset($sms->cost)){
		$month = substr($sms->created,0,7);
		if(isset($costmounth[$month]) == FALSE)
			$costmounth[$month] = 0;
		$costmonth[$month] = $costmonth[$month] + $sms->cost;
		$numbermonth[$month] = $numbermonth[$month] + 1;
	}
}

print "Month\tCost\tCount\n";
foreach($costmonth as $month => $cost){
	$cost = $cost/10000;
	print $month."\t".$cost."\t". $numbermonth[$month]."\n";
}

?>

