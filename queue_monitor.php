<?php

#$cmd_inicial = 'exiqgrep -i -r root@cpanel01.itfinden.com | xargs exim -Mrm';
#$num_mails_inicial = exec($cmd_inicial);

$cmd =  'exim -bpr | grep "<" | wc -l';
$num_mails = exec($cmd);

echo "Mensajes en Cola :  ".$num_mails. "\n";

if ($num_mails > 5)
        {
                $cmd_kill_frozen="exim -bpu | grep frozen | awk {'print $3'} | xargs exim -Mrm";
                $num_cmd_kill_frozen = shell_exec($cmd_inicial);

                $cmd_queue="exim -bpr";
                $exec_queue = shell_exec($cmd_queue);

                #var_dump($exec_queue);

                print_r($exec_queue);


                if (!$num_cmd_kill_frozen) $num_cmd_kill_frozen='0';

                $hostname = exec('/bin/hostname');
                $subject = 'Mail queue alert on ' . $hostname;

                #$mail_text = urlencode($mail_text);

                #$to_email = 'alerta@itfinden.com';
                #mail($to_email, $subject, $mail_text);

                $anis = array(170117686, 570392992);

                foreach ($anis as $ani) { //foreach loop of name array
                $mail_text = '('.$ani.')El servidor ' . $hostname . ' tiene ' . $num_mails . ' correos encolados y se borraron '.$num_cmd_kill_frozen.' correos Frozen' ;

                        $arr=array (
                        'gw' => 'TELEGRAM_BOT_API',
                        'id' => '13523452',
                        'bot' => 5,
                        'ani' => $ani,
                        'text' => $mail_text
                        );


                        $data_string = http_build_query($arr);
                        $ch = curl_init('http://api.itfinden.com/api.php'); // this url where my file will be uploaded
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        #curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
                        $final = curl_exec($ch);
                        #print_r ($ch);
                        #var_dump($final);
                        #print $mail_text;

                        $resp = curl_exec($curl);
                        // Close request to clear up some resources
                        curl_close($curl);
                }



        }
   ?>
