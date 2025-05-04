<?php
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
require("assets/classes/core.php");
class ADMIN extends Core{
	public string $page_title = '';
    public string $body = '';
    public string $size = '';
    public $head_includes = '';
    public $footer_includes = '';
    public bool $menu = false;
	private $html = "";
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
        $this->config["rootdir"]=getcwd()."/";
	}

	// За AJAX jQuery Комуникация
	function ReturnResponse($ok = false, $data = [], $error = "", $debug = []){
		$response = ["ok"=>$ok,"data"=>$data,"error"=>$error,"debug"=>$debug];
		header('Content-Type: application/json; charset=utf-8');
		ini_set("serialize_precision", '-1');
		die(json_encode($response, JSON_UNESCAPED_UNICODE));
	}


	// Зареждане на HTML страница от файл
	function LoadPage($file, $tags=null){
		$content = "";
		$filename=$this->config["rootdir"] . $file;
        if(file_exists($file)){
            $handle = fopen($filename, "r");
            $template = fread($handle, filesize($this->config["rootdir"] . $file));
            fclose($handle);
            if(is_null($tags)){
                $content=$template;
            } else {
                foreach($tags as $key => $value){
                    $keys[]=$key;
                    $values[]=$value;
                }
                $content=str_replace($keys,$values,$template);   
            }
            return $content;
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
		$items=$this->Query("SELECT * FROM `web_mainmenu` WHERE `type`=? ORDER BY `priority` ASC",[$menu]);
		foreach($items as $item){		
				$menu_items.="<li class=\"nav-item\"><a href=\"".$item["url"]."\" class=\"nav-link\" title=\"".$item["title"]."\">".$item["text"]."</a></li>";
			}
		return $menu_items;
	}

    function Generate_HTML($page_info){
		$tags = [
			"[{TITLE}]"=>$page_info["title"],
			"[{HEADINCLUDES}]"=>$this->head_includes,
			"[{WEBSITENAME}]"=>$this->config["name"],
			"[{MENUITEMS}]"=>$this->GenerateMenu("admin"),
			"[{MAINCONTENT}]"=>$page_info["content"],
			"[{FOOTERINCLUDES}]"=>$this->footer_includes
		];
		$filename="assets/templates/".$this->config["template"]."/admin-home.html";
		if(!file_exists($filename)) {
            $error = ($this->config["lang"]=="bg") ? "ГРЕШКА: HTML шаблонът не е намерен!" : "ERROR: HTML template not found!";
            die($error);
        }
		return $this->LoadPage($filename,$tags);
    }
	
	function GenerateLogin(){
        if($this->config["lang"]=="bg"){
            $content='<form name="login" action="/admin.php" method="post">
                    <div align="center">
                        <h1>Уеб CMS Админ Система</h1>
                        <label for="username">Администраторско име:</label> <input type="text" name="username" id="username" size="50" maxlength="50" tabindex="1" /><br />
                        <label for="password">Администраторска парола:</label> <input type="password" id="password" name="password" size="50" maxlength="50"  tabindex="2" /><br />
                        <br />
                        <input type="hidden" name="login" value="true">
                        <input type="submit" value="ВЛЕЗ" />
                    </div>
                </form>';
        } else {
		    $content='<form name="login" action="/admin.php" method="post">
                    <div align="center">
                        <h1>Website CMS  Administration System</h1>
                        <label for="username">ADMINISTRATOR USERNAME:</label> <input type="text" name="username" id="username" size="50" maxlength="50" tabindex="1" /><br />
                        <label for="password">ADMINISTRATOR PASSWORD:</label> <input type="password" id="password" name="password" size="50" maxlength="50"  tabindex="2" /><br />
                        <br />
                        <input type="hidden" name="login" value="true">
                        <input type="submit" value="LOG IN" />
                    </div>
                </form>';
        }      
		die($content);
	}

	function LoadAdministrator(){
        $bypass_login = false;

		if(isset($_POST["login"]) && $_POST["login"]==true){
            $res=$this->Query("SELECT * FROM `web_users` WHERE `username`=? AND `password`=MD5(?) AND `admin`=1",[$_POST["username"],$_POST["password"]]);
            if($res===false || count($res)==0  ){
				$error= ($this->config["lang"]=="bg") ? "Невалидно администраторско име или парола!" : "Invalid administrator username or password!";
				print("<h1 style=\"color:red\" align=\"center\">$error</h1>");
			} else {
				// LOGIN SUCCESSFULL!
				$secret = $_POST["username"].":".md5($_POST["password"]);
                setcookie("admin_loggedin", $secret, time() + (86400 * 30), "/"); // 86400 = 1 day
                $bypass_login = true;
			}
		}
		
		if($bypass_login){
				// all good, admin is authenticated!
				// do nothing, just continue running script            
		} elseif(isset($_COOKIE["admin_loggedin"])) {
            $params=explode(":",$_COOKIE["admin_loggedin"]);
            if(count($params)==2) {
                $res=$this->Query("SELECT * FROM `web_users` WHERE `username`=? AND `password`=? AND `admin`=1",[$params[0],$params[1]]);
                if($res===false || count($res)==0){
                    $this->GenerateLogin();
                } else {
                    // all good, admin is authenticated!
                    // do nothing, just continue running script            
                }
            } else {
                $this->GenerateLogin();
            }
		} else {
			$this->GenerateLogin();
		}
		
		$page = $this->GetVar("page") ?? "main";
		if(file_exists($this->config["rootdir"]."assets/views/admin-".$page.".php")) {
			require("assets/views/admin-".$page.".php");
		} else {
            header("HTTP/1.0 404 Not Found");
            die();
        }
	
	}
}    

$admin = new ADMIN();
$admin->LoadAdministrator();
?>