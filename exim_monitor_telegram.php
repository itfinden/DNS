<?php
#$cmd_inicial = 'exiqgrep -i -r root@cpanel01.itfinden.com | xargs exim -Mrm';
#$num_mails_inicial = exec($cmd_inicial);
$cmd = 'exim -bpr | grep "<" | wc -l';
$num_mails = exec($cmd);
$token = '';
$chat_id = '';
$hostname = exec('/bin/hostname');
$exim_monitor = 3;
function sendMessage($chat_id, $text, $token) {

	$disable_web_page_preview = null;
	$reply_to_message_id = null;
	$reply_markup = null;

	$data = array(
		'chat_id' => urlencode($chat_id),
		'text' => $text,
		'disable_web_page_preview' => urlencode($disable_web_page_preview),
		'reply_to_message_id' => urlencode($reply_to_message_id),
		'reply_markup' => urlencode($reply_markup),
	);

	$url = "https://api.telegram.org/bot" . $token . "/sendMessage";

//  open connection
	$ch = curl_init();
//  set the url
	curl_setopt($ch, CURLOPT_URL, $url);
//  number of POST vars
	curl_setopt($ch, CURLOPT_POST, count($data));
//  POST data
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//  To display result of curl
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//  execute post
	$result = curl_exec($ch);
//  close connection
	curl_close($ch);
	return $result;
}

echo "Mensajes en Cola :  " . $num_mails . "\n";

if (intval($num_mails) > intval($exim_monitor)) {
	$cmd_kill_frozen = "exim -bpu | grep frozen | awk {'print $3'} | xargs exim -Mrm";
	$num_cmd_kill_frozen = exec($cmd_inicial);
	$cmd_queue = "exim -bpr";
	$exec_queue = exec($cmd_queue);
	#var_dump($exec_queue);
	#print_r($exec_queue);
	if (!$num_cmd_kill_frozen) {
		$num_cmd_kill_frozen = '0';
	}
	#$subject = 'Mail queue alert on ' . $hostname;
	$mail_text = 'El servidor ' . $hostname . ' tiene ' . $num_mails . ' correos encolados y se borraron '.$num_cmd_kill_frozen.' correos Frozen' ;
	echo $mail_text;
	sendMessage($chat_id, $mail_text, $token);
	sendMessage($chat_id, "jorge es puto", $token);
	
}
?>

