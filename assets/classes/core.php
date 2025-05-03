<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    die("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\r\n<html><head>\r\n<title>404 Not Found</title>\r\n</head><body>\r\n<h1>Not Found</h1>\r\n<p>The requested URL " . $_SERVER['SCRIPT_NAME'] . " was not found on this server.</p>\r\n</body></html>");
}
/*
    Класът Core (Ядро) съдържа основните функции за манипулация с MySQL и потребителската информация.
    Този клас се ползва за публично/анонимно и оторизитани потребители в админа.
*/
class Core{
	private $dblink;
	public $config;
    //-------------------------------------------------------
	// Core Конструктор
	//-------------------------------------------------------	
	function __construct() {
		// Зареждаме настройките на сървъра от конфигурационния файл
		require("webconf.php");
		// настройваме MySQL връзката с UFT-8 многоезичен енкодинг
		mb_internal_encoding('UTF-8');
		mb_regex_encoding('UTF-8');
		mysqli_report(MYSQLI_REPORT_STRICT);
		try {
            // опитваме се да се вържем към MySQL сървъра
			$this->dblink = new mysqli("localhost", $this->config["mysql_username"], $this->config["mysql_password"], $this->config["mysql_db"]);
			$this->dblink->set_charset("utf8");
		} catch (Exception $e) {
            //опа, нещо гръмна и не се вързахме.
			die('Unable to connect to database');
		}
		if ($this->dblink->connect_errno) {
            //опа, грешно име на база данни или потребител
			die("Database Connection FAILED!");
		}
	}

    // Функция за запитване на MySQL база данни,
	function Query($query,$params=[]){
		$result=$this->dblink->execute_query($query,$params);
		// връща резултатите:
		switch(true){
			// true за insert, update, delete
			case $result===true : return true;
			// false за грешка
			case $result===false : {
				die("ERROR ".$this->dblink->error."In Query: ".$query." Params: ".json_encode($params));
				return false;
			}	
			// array за select
			default : {
				$array=$result->fetch_all(MYSQLI_ASSOC);
				mysqli_free_result($result);
				return $array;
			}	
		}
	}
	
	function GetVar($var)
    {
        if (isset($_GET[$var])) {
            return filter_input(INPUT_GET, $var, FILTER_SANITIZE_SPECIAL_CHARS);
        } elseif (isset($_POST[$var])) {
            return filter_input(INPUT_POST, $var, FILTER_SANITIZE_SPECIAL_CHARS);
        } else {
            return null;
        }
    }

    function EncodeQuotes($text){
		$bad_characters=array("'","\"");
		$good_characters=array("&lsquo;","&quot;");
		$out=str_replace($bad_characters,$good_characters,$text);
		return $out;
	}
	function DecodeQuotes($text){
		$bad_characters=array("'","\"");
		$good_characters=array("&lsquo;","&quot;");
		$out=str_replace($good_characters,$bad_characters,$text);
		return $out;	
	}
		
	function SafeURL($url){
		$tmp_url=strtolower($url);
		$bad_characters=array(" ","'","\"","/","?","!",".",",",":",";","&");
		$good_characters=array("-","","","","","","","","","","");
		$safe_url=str_replace($bad_characters,$good_characters,$tmp_url);
		$tmp_url=substr($safe_url,0,150).".html";
		$safe_url=$tmp_url;
		return $safe_url;
	}
	
	function GetWebContent($url){
		@set_time_limit(400);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
		// Increase IE Stats! Google dislikes non-browser user agents :(
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		$content = curl_exec($ch);
		curl_close($ch);
		return $content;
	}

	function GetValue($CurrentBlock,$StartTag,$EndTag){
		$ParameterStartPosition=strpos($CurrentBlock,$StartTag)+strlen($StartTag);
		$ParameterEndPosition=strpos($CurrentBlock,$EndTag,$ParameterStartPosition);
		return substr($CurrentBlock,$ParameterStartPosition,$ParameterEndPosition-$ParameterStartPosition);
	}

	function SendMail($emailaddress, $fromaddress, $emailsubject, $body, $attachments=[]){
		$eol="\r\n";
		$mime_boundary=md5(time());
		# Common Headers
		$headers = "";
		$headers .= 'From: System<'.$fromaddress.'>'.$eol;
		$headers .= 'Reply-To: System<'.$fromaddress.'>'.$eol;
		$headers .= 'Return-Path: System<'.$fromaddress.'>'.$eol;    // these two to set reply address
		$headers .= "Message-ID: <".time()." System@".$_SERVER['SERVER_NAME'].">".$eol;
		$headers .= "X-Mailer: PHP v".phpversion().$eol;          // These two to help avoid spam-filters
	
		# Boundry for marking the split & Multitype Headers
		$headers .= 'MIME-Version: 1.0'.$eol;
		$headers .= "Content-Type: multipart/related; boundary=\"".$mime_boundary."\"".$eol;
	
		$msg = "";      
		if (count($attachments)<1){
			for($i=0; $i < count($attachments); $i++){
				if (is_file($attachments[$i]["file"])){   
					# File for Attachment
					$file_name = substr($attachments[$i]["file"], (strrpos($attachments[$i]["file"], "/")+1));
					$handle=fopen($attachments[$i]["file"], 'rb');
					$f_contents=fread($handle, filesize($attachments[$i]["file"]));
					$f_contents=chunk_split(base64_encode($f_contents));    //Encode The Data For Transition using base64_encode();
					fclose($handle);
			
					# Attachment
					$msg .= "--".$mime_boundary.$eol;
					$msg .= "Content-Type: ".$attachments[$i]["content_type"]."; name=\"".$file_name."\"".$eol;
					$msg .= "Content-Transfer-Encoding: base64".$eol;
					$msg .= "Content-Disposition: attachment; filename=\"".$file_name."\"".$eol.$eol; // !! This line needs TWO end of lines !! IMPORTANT !!
					$msg .= $f_contents.$eol.$eol;
				}
			}
		}
		# Setup for text OR html
		$msg .= "Content-Type: multipart/alternative".$eol;
		# Text Version
		$msg .= "--".$mime_boundary.$eol;
		$msg .= "Content-Type: text/plain; charset=iso-8859-1".$eol;
		$msg .= "Content-Transfer-Encoding: 8bit".$eol;
		$msg .= strip_tags(str_replace("<br>", "\n", $body)).$eol.$eol;
		# HTML Version
		$msg .= "--".$mime_boundary.$eol;
		$msg .= "Content-Type: text/html; charset=iso-8859-1".$eol;
		$msg .= "Content-Transfer-Encoding: 8bit".$eol;
		$msg .= $body.$eol.$eol;
		# Finished
	  	$msg .= "--".$mime_boundary."--".$eol.$eol;  // finish with two eol's for better security. see Injection.
		
		# SEND THE EMAIL
	  	ini_set('sendmail_from',$fromaddress);  // the INI lines are to force the From Address to be used !
		mail($emailaddress, $emailsubject, $msg, $headers);
		ini_restore('sendmail_from');
	}
}