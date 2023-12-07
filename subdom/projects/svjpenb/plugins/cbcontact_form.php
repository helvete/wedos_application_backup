<?php
/*
Name: Simple Cumbe contact form
Description: It is a simple contact form
Version: 5.8.2
Author: Cumbe (Miguel Embuena Lance)
Author URI: http://www.cumbe.es/contact/
*/

// Relative
$relative = '../';
$path = $relative. 'data/other/';

# get correct id for plugin
$thisfile=basename(__FILE__, ".php");

# register plugin
register_plugin(
	$thisfile,
	'Cumbe_contact',
	'5.8.2',
	'Cumbe',
	'http://www.cumbe.es/contact/',
	'Description:  Getsimple contactform.',
	'pages', //page type
	'vermensajes'
);

//set internationalization
   global $LANG;
   i18n_merge('cbcontact', $LANG) || i18n_merge('cbcontact','en_US');

//add css to head
add_action('theme-header','cbcontact_css');

//añadimos al sidebar de la pestaña pages
add_action('pages-sidebar','createSideMenu',array('cbcontact_form', $i18n['cbcontact/CONTACT']));

//filter $content
add_filter('content','cbcontact_content');

function cbcontact_css(){
   global $SITEURL;
   echo '<link href="'.$SITEURL.'plugins/cbcontact/form/cbcontact.css" rel="stylesheet" type="text/css" />';
}

////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////

function vermensajes(){
	global $i18n; 

	$log_name = 'cbcontactform.log';
	$log_path = GSDATAOTHERPATH.'logs/';
	$log_file = $log_path . $log_name;

?>
	<script type="text/javascript">
        <!--
	function confirmar(formObj,count,msge,bck) {
	    if(!confirm(msge)) { 
                   return false; 
            } else {
                   if (bck == 'log'){
                       if (count =='n'){
                           window.location="load.php?id=cbcontact_form&action=delete";
                       } else {   
                           window.location="load.php?id=cbcontact_form&n_del=" + count + "";
                       }
                   } 
                   return false;
            }    
        }

        -->
	</script> 
<?php

	if(file_exists($log_file)) {
		$log_data = getXML($log_file);
		if (@$_GET['action'] == 'delete' && strlen($log_name)>0) {
			unlink($log_file);
			exec_action('logfile_delete');
?>
					<label>Log <?php echo $log_name;?> <?php echo $i18n['MSG_HAS_BEEN_CLR']; ?>			
				</div>
			</div>
			<div id="sidebar" >
				<?php include('template/sidebar-pages.php'); ?>
			</div>	
			<div class="clear"></div>
			</div>
			<?php get_template('footer'); ?>
<?php
			exit;
		}

		//delete one register:entry
		if (@$_GET['n_del'] != ''){
			$domDocument = new DomDocument();
			$domDocument->preserveWhiteSpace = FALSE; 
			$domDocument->load($log_file);
			$domNodeList = $domDocument->documentElement;
			$domNodeList = $domDocument->getElementsByTagname('entry');
			$ndel = @$_GET['n_del'];
			$ndL = $domNodeList ->item($ndel)->parentNode;
			$ndL -> removeChild($domNodeList ->item($ndel));

			//sase again modified document
			$domDocument->save($log_file);
		}     

		//load data of xml
		$log_data = getXML($log_file);
		//END delete one register

?>

		<label><?php echo $i18n['VIEWING'];?>&nbsp;<?php echo $i18n['LOG_FILE'];?>: &lsquo;<em><?php echo @$log_name; ?></em>&rsquo;</label>
		<div class="edit-nav" >
<?php
			echo '<a href="load.php?id=cbcontact_form&action=delete" accesskey="c" title="'.$i18n['CLEAR_ALL_DATA'].' '.$log_name.'" onClick="return confirmar(this,&quot;n&quot;,&quot;'.$i18n['CLEAR_ALL_DATA'].' '.$log_file.'. '.$i18n['cbcontact/delsure'].'&quot;,&quot;log&quot;)" />'.$i18n['CLEAR_THIS_LOG'].'</a>';
			echo '<div class="clear"></div>';
		echo '</div>';
		echo '<ol class="more" >';
			$count = 0;

			foreach ($log_data as $log) {
				echo '<li><p style="font-size:11px;line-height:15px;" ><b style="line-height:20px;" >'.$i18n['LOG_FILE_ENTRY'].':'.$count.'</b><a style="padding-left: 50px;" title="'.$i18n['cbcontact/ndel'].'" href="load.php?id=cbcontact_form" onClick="return confirmar(this,&quot;'.$count.'&quot;,&quot;'.$i18n['cbcontact/ndelc'].$count.'. '.$i18n['cbcontact/delsure'].'&quot;,&quot;log&quot;)"><b>X</b></a><br />';
;
				foreach($log->children() as $child) {
					$name = $child->getName();
					echo '<b>'. stripslashes(ucwords($name)) .'</b>: ';	  
					$d = $log->$name;
					$n = strtolower($child->getName());
					$ip_regex = '/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/';
					$url_regex = @"((https?|ftp|gopher|telnet|file|notes|ms-help):((//)|(\\\\))+[\w\d:#@%/;$()~_?\+-=\\\.&]*)";
					  
					  
					//check if its an url address
					if (do_reg($d, $url_regex)) {
						$d = '<a href="'. $d .'" target="_blank" >'.$d.'</a>';
					}
					  
					//check if its an ip address
					if (do_reg($d, $ip_regex)) {
						if ($d == $_SERVER['REMOTE_ADDR']) {
							$d = $i18n['THIS_COMPUTER'].' (<a href="http://www.geobytes.com/IpLocator.htm?GetLocation&IpAddress='. $d.'" target="_blank" >'.$d.'</a>)';
						} else {
							$d = '<a href="http://www.geobytes.com/IpLocator.htm?GetLocation&IpAddress='. $d.'" target="_blank" >'.$d.'</a>';
						}
					}
					  
					//check if there is an email address
					if (check_email_address($d)) {
						$d = '<a href="mailto:'.$d.'">'.$d.'</a>';
					}
					  
					//check if its a date
					if ($n === 'date') {
						$d = lngDate($d);
					}
					  	
					echo stripslashes(html_entity_decode($d));
					echo ' <br />';
				}
				echo "</p></li>";
				$count++;
			}				
				
?>
		</ol>
		
<?php
	} //END if file_exists
	else
	{   //If file does not exist
?>
		<label><?php echo $i18n['MISSING_FILE']; ?>: &lsquo;<em><?php echo @$log_name; ?></em>&rsquo;</label>
<?php
	}

}  // END vermensajes

////////////////////////////////////////////////
////////////////////////////////////////////////
////////////////////////////////////////////////

function cbcontact_content($content){
  ////////////////////////////////////////////////////////////////////  
  //         filter content of page searching $cbcontact 
  ////////////////////////////////////////////////////////////////////  

	if ( preg_match("/\(%\s*(cbcontact)(\s+(?:%[^%\)]|[^%])+)?\s*%\)/", $content, $coinc)){
		$array_coinc = explode(',', $coinc[2]);
		array_filter($array_coinc, 'trim_value');
		$array_coinc = str_replace("'","",$array_coinc); 
		$array_coinc = str_replace(" ","",$array_coinc); 
		$cuantos = count($array_coinc);
		if (array_key_exists ('1', $array_coinc) === false) {
			$array_coinc[1] = 'true';
		} else {
			$array_coinc[1] = trim($array_coinc[1]);
		}  
		if (array_key_exists ('2', $array_coinc) === false) {
			$array_coinc[2] = 'false';
		} else {
			$array_coinc[2] = trim($array_coinc[2]);
		} 

		$usus = '';   
		for ($q = 4; $q < $cuantos; $q++ ){
			$usus[$q] = $array_coinc[$q];
		}  
		$usus = str_replace("'","",$usus); 
		$usus = str_replace(" ","",$usus); 
		$content_cbcontact = cbcontact_page (trim($array_coinc[0]), $array_coinc[1], $array_coinc[2], false, $usus);
		$content = str_replace($coinc[0], $content_cbcontact, $content);
	} 

	return $content;
}   


function cbcontact_page($usu, $captcha=true, $sendphpmail=false, $echocontact=true, $usus='') {
	global $EMAIL;
	global $SITEURL;
	global $SITENAME;
	global $LANG;
	global $PRETTYURLS;
	global $i18n;
	global $language;

	$log_name = 'cbcontactform.log';
	$log_path = GSDATAOTHERPATH.'logs/';
	$log_file = $log_path . $log_name;
	$fich = return_page_slug();
	$idpret = find_url($fich,'');
	if ($PRETTYURLS !='') {
		$idpret = $idpret.'?';
	}

	if (file_exists('gsconfig.php')) {
		include_once('gsconfig.php');
	}

	// Debugging
	if (defined('GSDEBUG')){
		error_reporting(E_ALL | E_STRICT);
		ini_set('display_errors', 1);
	} else {
		error_reporting(0);
		@ini_set('display_errors', 0);
	}

	//Check session: important for captcha and contact form	
	if (!isset($_SESSION)) { session_start(); }

	$err = '';

	if (file_exists(GSDATAPATH.'users/'.$usu.'.xml')) {
		$data = getXML(GSDATAPATH.'users/'.$usu.'.xml');  
		$EMAIL = $data->EMAIL;
		$LANG = $data->LANG;
	}    

	//i18n compatible
	if (isset($_GET['setlang'])){
		$LANG = $_GET['setlang']. '_'.strtoupper($_GET['setlang']);
	}
	if (isset($language)){
		$LANG = $language. '_'.strtoupper($language);
	}
    
	//i18n lang  
	i18n_merge('cbcontact', $LANG) || i18n_merge('cbcontact','en_US'); 

	//check other users or emails
	if ($usus != ''){
		if (is_array($usus)){
			foreach ($usus as $key=> $value){   
				if (!check_email_address(trim($value))){ 
					if (file_exists(GSDATAPATH.'users/'.trim($value).'.xml')) {
						$data = getXML(GSDATAPATH.'users/'.trim($value).'.xml');  
						$qusus[$key] = $data->EMAIL;
					}  else {
						unset ($usus[$key]);
					}   
				} else {
					$qusus[$key] = trim($value);
				}
			}
		} else {
			$qusus[4] = $usus;
		} 
	} 

//////////////////////////////////////////////////////////////////////////////////// 
  //	check if submit form
  //	$mi_array =  data passed in form if exists error in captcha or empty fields
  //	$mi_arrayp = if !error in captcha writes correspondient form with data =""
////////////////////////////////////////////////////////////////////////////////////

	global $mi_array;
	$mi_array = array();
	if (isset($_POST['contact-submit'])) {
		include ("cbcontact/comprueba.php");
	}

	//if captcha wrong then write form with data entered
	global $mi_arrayp;
	$mi_arrayp= array(
		i18n_r('cbcontact/Nb') =>  '',
		i18n_r('cbcontact/Em') =>  '',
		i18n_r('cbcontact/Sub') =>  '',
		i18n_r('cbcontact/Ms') =>  '',
	);

 	if($err != ''){
		$mi_arrayp = $mi_array;        
	}

	$mi_arrayq = $mi_arrayp;
	if ($mi_arrayp[i18n_r('cbcontact/Em')] ==''){
		$mi_arrayq[i18n_r('cbcontact/Em')] = i18n_r('cbcontact/em_text');
	}

?>
	<script type="text/javascript">
	<!--
		function rec_cpt(id,ourl){
			var aleat = Math.random();
			var mf = document.getElementById(id);
			mf.src = ourl + "/cbcontact/&" + aleat;
		}
	-->
	</script>

<?php
	//control uri
	$request_uri = getenv ("REQUEST_URI");       // Requested URI

	//Show in page   
	$mGSPLUGINPATH = str_replace("\\", "/", GSPLUGINPATH);
	$mGSPLUGINPATH = substr($mGSPLUGINPATH, 0, -1);

	// FORM
	$cbcontactform = '<form id="cbform" class="" action="'.	$idpret.'&amp;tp=contact" method="post">';
		//call form 
		include  ('cbcontact/form/cbcontact.php');

		//hidden fields
		$cbcontactform .= "\r\n\t".'<input type="hidden" name="contact[q_email]" value="'.$EMAIL.'">';
		$cbcontactform .= "\r\n\t".'<input type="hidden" name="contact[q_file]" value="'.$log_file.'">';
		$cbcontactform .= "\r\n\t".'<input type="hidden" name="contact[q_uri]" value="'.$request_uri.'">';
		$cbcontactform .= "\r\n\t".'<input type="hidden" name="contact[q_send]" value="'.$sendphpmail.'">';
		$cbcontactform .= "\r\n\t".'<input type="hidden" name="contact[q_captcha]" value="'.$captcha.'">';
		$cbcontactform .= "\r\n\t".'<input type="hidden" name="contact[q_sitename]" value="'.$SITENAME.'">';
		$cbcontactform .= "\r\n\t".'<input type="hidden" name="contact[q_lang]" value="'.$LANG.'">';
		$cbcontactform .= "\r\n\t".'<input type="hidden" name="contact[leaveblank]" value="">';
		$cbcontactform .= "\r\n\t".'<input type="hidden" name="contact[leaveso]" value="leaveso">';
	$cbcontactform .= "\r\n".'</form>'."\r\n\t";

	if ($echocontact === true){
		echo $cbcontactform;
	} else {
		return $cbcontactform;
	} 

}

function trim_value(&$value){
	$value = trim($value); 
}
 
?>
