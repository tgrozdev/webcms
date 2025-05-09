<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
require("assets/classes/core.php");
class ADMIN extends Core{
    public $user = [];
    public $menu = true;
    public $head_includes = '';
    public $footer_includes = '';

	//-------------------------------------------------------
	// CMS ADMIN Конструктор
	//-------------------------------------------------------	
	function __construct() {
		// трябва да заредим конструктора на майчиния клас
        parent::__construct();   
		
		// load settings from Database
		$result=$this->Query("select * from `web_settings`");
		foreach($result as $row) {
			$this->config[$row["key"]]=$row["value"];
		}
	}

	// За AJAX jQuery Комуникация
	function ReturnResponse($ok = false, $data = [], $error = "", $debug = []){        
		$response = ["ok"=>$ok,"data"=>$data,"error"=>$error,"debug"=>$debug];
		header('Content-Type: application/json; charset=utf-8');
		ini_set("serialize_precision", '-1');
		die(json_encode($response, JSON_UNESCAPED_UNICODE));
	}


	// Зареждане на HTML Темплейт
	function LoadTemplate($tags){
        $tags["[{HEADINCLUDES}]"]=$this->head_includes;
		$tags["[{WEBSITENAME}]"]=$this->config["name"];
        if($this->menu){
            $tags["[{MENUITEMS}]"]=$this->GenerateMenu($this->user["type"]);
        }
		$tags["[{FOOTERINCLUDES}]"]=$this->footer_includes;
		$content = "";
		$filename=$this->config["rootdir"] . "assets/templates/".$this->config["template"]."/admin-home.html";
        if(file_exists($filename)){
            $handle = fopen($filename, "r");
            $template = fread($handle, filesize($filename));
            fclose($handle);
            $content=str_replace(["[{MAINCONTENT}]"],$tags["[{MAINCONTENT}]"],$template);
            $keys = [];
            $values = [];
            foreach($tags as $key => $value){
                $keys[]=$key;
                $values[]=$value;
            }
            $html=str_replace($keys,$values,$content);   
            echo $html;
        } else {
            $error = ($this->config["lang"]=="bg") ? "ГРЕШКА: HTML шаблонът неможе да се зареди! файл: ".$filename : "ERROR: HTML template could not be loaded! file: ".$filename;
            throw new ErrorException($error);
        }
    }    

	/*
		Няколко функции за управлението на HTML съдържанието
	*/ 
	function GenerateMenu($menu="main"){
		$menu_items="";
        $page = $this->GetVar("page") ?? "main";
        $current_page = "/admin.php?page=".$page;
		$items=$this->Query("SELECT * FROM `web_menu` WHERE `type`=? ORDER BY `priority` ASC",[$menu]);
		foreach($items as $item){		
                if(strpos($item["url"],$current_page)!==false){ $active="active"; } else { $active=""; }
				$menu_items.="<li class=\"nav-item\"><a href=\"".$item["url"]."\" class=\"nav-link $active\" title=\"".$item["title"]."\">".$item["text"]."</a></li>";
			}
		return $menu_items;
	}

    // Зареждане на HTML страница от файл
    function LoadHTML($tags, $page = "main"){
        if(!isset($tags["[{MAINCONTENT}]"])){
            $filename="assets/html/".$this->config["lang"]."/".$this->user["type"]."/".$page.".html";
            if(!file_exists($filename)) {
                $error = ($this->config["lang"]=="bg") ? "ГРЕШКА: HTML сорс шаблонът не е намерен!" : "ERROR: HTML source template not found!";
                die($error);
            }
            $handle = fopen($filename, "r");
            $tags["[{MAINCONTENT}]"] = fread($handle, filesize($filename));
            fclose($handle);        
        }
		$this->LoadTemplate($tags);
    }
	
	function GenerateLogin(){
        if($this->config["lang"]=="bg"){
            $content='<form name="login" action="/admin.php" method="post">
                    <div align="center">
                        <h1>Уеб CMS Админ Система</h1>
                        <label for="username">Потребителско име:</label> <input type="text" name="username" id="username" size="50" maxlength="50" tabindex="1" /><br />
                        <label for="password">Потребителска парола:</label> <input type="password" id="password" name="password" size="50" maxlength="50"  tabindex="2" /><br />
                        <br />
                        <input type="hidden" name="login" value="true">
                        <input type="submit" value="ВЛЕЗ" />
                    </div>
                </form>';
        } else {
		    $content='<form name="login" action="/admin.php" method="post">
                    <div align="center">
                        <h1>Website CMS  Administration System</h1>
                        <label for="username">USERNAME:</label> <input type="text" name="username" id="username" size="50" maxlength="50" tabindex="1" /><br />
                        <label for="password">PASSWORD:</label> <input type="password" id="password" name="password" size="50" maxlength="50"  tabindex="2" /><br />
                        <br />
                        <input type="hidden" name="login" value="true">
                        <input type="submit" value="LOG IN" />
                    </div>
                </form>';
        }      
		die($content);
	}

    function LoadAdmin(){
        $page = $this->GetVar("page") ?? "main";
        if($this->user["type"]=="admin"){
            $type = "admin";
        } else {
            $type = "user";
        }
		if(file_exists($this->config["rootdir"]."assets/views/".$type."-".$page.".php")) {
			require("assets/views/".$type."-".$page.".php");
		} else {
            header("HTTP/1.0 404 Not Found");            
        }        
        die();
    }

	function Login(){
        if(isset($_GET["logout"])){
            setcookie("loggedin","",time()-3600,"/");
            $this->GenerateLogin();
            exit();
        }

		if(isset($_POST["login"]) && $_POST["login"]==true){
            $res=$this->Query("SELECT * FROM `web_users` WHERE `username`=? AND `password`=MD5(?) AND `enabled`=1",[$_POST["username"],$_POST["password"]]);
            if($res===false || count($res)==0  ){
				$error= ($this->config["lang"]=="bg") ? "Невалидно потребителско име или парола!" : "Invalid username or password!";
				print("<h1 style=\"color:red\" align=\"center\">$error</h1>");
                $this->GenerateLogin();
			}
		    // LOGIN SUCCESSFULL!
            $this->user = current($res);
			$secret = $_POST["username"].":".md5($_POST["password"]);
            setcookie("loggedin", $secret, time() + (86400 * 30), "/"); // 86400 = 1 day
            $this->LoadAdmin();
		}
		
		if(isset($_COOKIE["loggedin"])) {
            $params=explode(":",$_COOKIE["loggedin"]);
            if(count($params)==2) {
                $res=$this->Query("SELECT * FROM `web_users` WHERE `username`=? AND `password`=? AND `enabled`=1",[$params[0],$params[1]]);
                if($res===false || count($res)==0){
                    $this->GenerateLogin();
                } else {
                    // all good, admin is authenticated!
                    $this->user = current($res);
                    $this->LoadAdmin();
                }
            }		
	    }
        $this->GenerateLogin();
	}
} 
$admin = new ADMIN();
$admin->Login();
?>