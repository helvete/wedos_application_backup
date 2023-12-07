<?php
//-----------------------------------------------------------------//
//--- comprueba.php: action if form is submit                      //
//-----------------------------------------------------------------//
if (!isset($_SESSION)) { session_start(); }

  $imagenCadena = $_SESSION["imagencadena"]; 
  $pot = trim(strtolower($imagenCadena));
  $server_name = getenv ("SERVER_NAME");       // Server Name
  $request_uri = getenv ("REQUEST_URI");       // Requested UR

// check antispam 
if (isset($_POST['contact-submit'])) {
    if ($_POST['contact']['leaveso'] != 'leaveso' or $_POST['contact']['leaveblank']!=''){
       echo '<html>';
       echo '<head>';
       echo '</head>';
       echo '<body>';
       echo '<div style="padding: 20px; border: 4px double; margin: 20%;">';
       echo i18n_r('cbcontact/spam').'<br />';
       echo '<a href="'.$idpret.'">'.i18n_r('cbcontact/back').'</a>';
       echo '</div>';
       echo '</body>';
       echo '</html>';   
       exit; 
    }  
}

//------------------------
//START CONTACT
//------------------------
	if (@$_GET['tp'] == 'contact') { 
		if (isset($_POST['contact-submit'])) {
			$log_file= $_POST['contact']['q_file'];
			$EMAIL = $_POST['contact']['q_email'];
			$sendphpmail = $_POST['contact']['q_send'];
			$qcaptcha =  $_POST['contact']['q_captcha'];
			$sitename =  stripslashes($_POST['contact']['q_sitename']);

			//check captcha
			//$qcaptcha = '1' (true); $pot = value of $imagenCadena
			if ($qcaptcha == "true" or $qcaptcha == true) {
				if ( $pot == trim(strtolower($_POST['contact']['pot']))) {					$err = '';
				} else {
					$err = i18n_r('cbcontact/MSG_CAPTCHA_FAILED');
				}
			}
			//check name
			if ( $_POST['contact'][i18n_r('cbcontact/Nb')] != '' ) {
				if ($_POST['contact'][i18n_r('cbcontact/Nb')] == i18n_r('cbcontact/Nb')){
					$_POST['contact'][i18n_r('cbcontact/Nb')] = '';
				}
			}

			//check email
			if ( $_POST['contact'][i18n_r('cbcontact/Em')] != '' ) {
				if ($_POST['contact'][i18n_r('cbcontact/Em')] == '(*)'.i18n_r('cbcontact/Em')) {
					$_POST['contact'][i18n_r('cbcontact/Em')] = ''; //i18n_r('cbcontact/Em');
				}
				if ($_POST['contact'][i18n_r('cbcontact/Em')] != i18n_r('cbcontact/Em')){
					$from =  $_POST['contact'][i18n_r('cbcontact/Em')];
				} else {
					$_POST['contact'][i18n_r('cbcontact/Em')]='';
				}
			}
       
			//check subject
			$subject2 = ''; 
			if ( $_POST['contact'][i18n_r('cbcontact/Sub')] != '' ) {
				if ($_POST['contact'][i18n_r('cbcontact/Sub')] != i18n_r('cbcontact/Sub')){
					$subject = stripslashes(html_entity_decode($_POST['contact'][i18n_r('cbcontact/Sub')]));
				} else { 
					$subject = i18n_r('cbcontact/CONTACT_FORM_SUB').' '.i18n_r('cbcontact/WHO').' '.$sitename;
					$_POST['contact'][i18n_r('cbcontact/Sub')] = i18n_r('cbcontact/CONTACT_FORM_SUB').' '.i18n_r('cbcontact/WHO').' '.$sitename;
					$subject2 =  i18n_r('cbcontact/Sub');
				}
			} 

			//check message
			if ( $_POST['contact'][i18n_r('cbcontact/Ms')] != '' ) {
				if ($_POST['contact'][i18n_r('cbcontact/Ms')] == '(*)'.i18n_r('cbcontact/Ms')) {
					$_POST['contact'][i18n_r('cbcontact/Ms')] = '';
				}
				if ($_POST['contact'][i18n_r('cbcontact/Ms')] == i18n_r('cbcontact/Ms')){
					$_POST['contact'][i18n_r('cbcontact/Ms')] = '';
				}
			} 

			$temp = $_POST['contact'];

			//release variables
			unset($temp['pot']);
			unset($temp['contact-submit']);
			unset($temp['submit']);
			unset($temp['leaveso']);
			unset($temp['leaveblank']);

			if ($err == '' && trim($_POST['contact'][i18n_r('cbcontact/Em')]) !='' && trim($_POST['contact'][i18n_r('cbcontact/Ms')]) !='') {

				$headers = "From: ".$from."\r\n";
				$headers .= "Return-Path: ".$from."\r\n";
				$headers .= "Content-type: text/html; charset=UTF-8 \r\n";

				$body = '"'.$subject.'": <a href="'.substr($idpret,0,-1).'">'.substr($idpret,0,-1).'</a>';
				$body .= "<hr><br />";

				if ( ! file_exists($log_file) ) {
					$xml = new SimpleXMLExtended('<channel></channel>');
				} else {
					$xmldata = file_get_contents($log_file);
					$xml = new SimpleXMLExtended($xmldata);
				}

				$thislog = $xml->addChild('entry');
				$thislog->addChild('date', date('r'));
				$cdata = $thislog->addChild('captcha');
				$cdata->addCData($captcha);
				$cdata = $thislog->addChild('ip_address');
				$ip = getenv("REMOTE_ADDR"); 
				$cdata->addCData(htmlentities($ip, ENT_QUOTES, 'UTF-8'));
				$body .= "Ip: ". $ip ."<br />"; 
				foreach ( $temp as $key => $value ) {
					if (substr($key, 0, 2) != 'q_') {
						$body .= ucfirst($key) .": ". stripslashes(html_entity_decode($value, ENT_QUOTES, 'UTF-8')) ."<br />";
						$cdata = $thislog->addChild(clean_url($key));
						$cdata->addCData(stripslashes(html_entity_decode($value, ENT_QUOTES, 'UTF-8')));
					}
				}
				XMLsave($xml, $log_file);
				//$seg = 2;  
				if ($sendphpmail == false or $sendphpmail == 'false') {
					$result = mail($EMAIL,$subject,$body,$headers);
					if ($usus != ''){
						foreach ($qusus as $key=>$value){
							$result =  mail($value,$subject,$body,$headers);
							$result1[$key] =  $result;
						}
					}
				} else if ($sendphpmail == true or $sendphpmail== 'true') {
					if (is_dir('../PHPMailer_v5.1') and file_exists('../PHPMailer_v5.1/class.phpmailer.php')){
						require('../PHPMailer_v5.1/class.phpmailer.php');   
						$message = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
						$message->CharSet = "utf-8"; 
						$message->SMTPDebug = false;    // enables SMTP debug information (for testing)
                                                        // false = disabled debug
                                                        // 1 = errors and messages
                                                        // 2 = messages only

						$message->IsSMTP();            // telling the class to use SMTP
						$message->SMTPAuth = true;     // enable SMTP authentication

						//GMAIL Configuration
						/*$message->SMTPSecure = "ssl";                 // sets the prefix to the servier
						$message->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP se
						$message->Port       = 465;                   // set the SMTP port for the GMAIL server
						$message->Username   = "...@gmail.com";   // GMAIL user account: youuser@gmail.com
						$message->Password   = "...";         // GMAIL password 
						$message->From   = "...@gmail.com";       // you GMAIL email 
						*/ //end GMAIL Configuration

						//ONO configuration     
						/*       $message->SMTPSecure = "";                  // sets the prefix to the servier
						$message->Host       = "smtp.ono.com";       // sets ONO as the SMTP server
						$message->Port       = 25;                   // set the SMTP port for the ONO server
						$message->Username   = "username";               // ONO username 
						$message->Password   = "pass";            // ONO password
						$message->From   = "user@ono.com";           // you ono email 
						*/       //end ONO Configuration

						//HOTMAIL configuration     
						/*		$message->SMTPSecure = "tls";                   // sets the prefix to the servier
						$message->Host       = "smtp.live.com";         // sets hotmail as the SMTP server
						$message->Port       = 587;                     // set the SMTP port for hotmail server
						$message->Username   = "youruser@hotmail.com";  // hotmail user account
						$message->Password   = "yourpass";               // hotmail password
						$message->From   = "youruser@hotmail.com";  */    // you hotmail email 
						//end HOTMAIL Configuration


						$message->AddAddress($EMAIL, '');  //Recipient's address set 
						$message->Subject = $subject;
						$message->FromName   = $from;
						$message->MsgHTML("$body");
						$result=$message->Send();
					} else {
						echo strtoupper(i18n_r('cbcontact/errphphmail'))."\n";
					}
				}

				//results	
				if ($result=='1') {
					$msgshw = i18n_r('cbcontact/MSG_CONTACTSUC');
				} else {
					//$seg = 10;  
					$msgshw = '<p>'.i18n_r('cbcontact/MSG_guestERR')."</p>\n";
					$msgshw .= "Mailer Error: " . $mail->ErrorInfo;
				}
			} //finaliza if $err=''
			else 
			{ //if $err != ''
				//release variables
				unset ($temp['q_email']);
				unset ($temp['q_file']);
				unset ($temp['q_uri']);
				unset ($temp['q_send']);
				unset ($temp['q_captcha']);
				if ($subject2 != '') {
					$temp[i18n_r('cbcontact/Sub')] = $subject2;
				}
				foreach($temp as $key=>$value){
					$mi_array[$key] = stripslashes(html_entity_decode($value));
				}

				if (trim($err) !=''){
					$msgshw = $err.'\nCaptcha code: '.$pot.'\nCode wrote: '.$_POST['contact']['pot'];
				} else {
					$msgshw = '*** '.strtoupper(i18n_r('cbcontact/Co')).' ***';
					$err = $msgshw;
				}
			}

		}
	}

////////////////////////////////////////////////////////////////
//
//     html page or alert of javascript
//
////////////////////////////////////////////////////////////////
?>
	<script type="text/javascript">
<!--
		alert ("<?php echo $msgshw; ?>");
-->
	</script>
<?php

/*
  echo '<html>';
  echo '<head>';
  echo '<meta http-equiv="Refresh" content="'.$seg.';url=http://'.$server_name.$MIURL.$miarr.'">';
  echo '</head>';
  echo '<body>';
  echo '<div style="padding: 20px; border: 4px double; margin: 20%;">';
  echo $msgshw;
  echo i18n_r('cbcontact/redir'].$seg.i18n_r('cbcontact/redir1'].i18n_r('cbcontact/back'].'.<br />';
  echo '<a href="http://'.$server_name.$MIURL.$miarr.'">'.i18n_r('cbcontact/back'].'</a>';
  echo '</div>';
  echo '</body>';
  echo '</html>';*/
?>
