<?php
if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
    die("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\r\n<html><head>\r\n<title>404 Not Found</title>\r\n</head><body>\r\n<h1>Not Found</h1>\r\n<p>The requested URL " . $_SERVER['SCRIPT_NAME'] . " was not found on this server.</p>\r\n</body></html>");
}
require("assets/classes/core.php");

class Result {
    public $ok = false;
    public $menu = [];
    public $content = [];
    public $error = "";
    public $debug = "";
}

class API extends Core{
    var $response;
    //-------------------------------------------------------
	// Headless CMS API Конструктор
	//-------------------------------------------------------	
	function __construct() {
		// трябва да заредим конструктора на майчиния клас
        parent::__construct();   
		/*
		// load settings from Database
		$result=$this->Query("select * from `web_settings`");
		foreach($result as $row) {
			$this->config[$row["key"]]=$row["value"];
		}
        */

        $this->response = new Result();
        $this->Loader();
	}

    function ReturnResponse(){
        header('Content-Type: application/json; charset=utf-8');
        ini_set("serialize_precision", '-1');
        die(json_encode($this->response, JSON_UNESCAPED_UNICODE));
    }

    function Loader(){
        $action = $this->GetVar("action");
        switch($action){
            case "LoadContent" : {
                //load content
                $this->response->ok=true;
                $this->response->menu=$this->Query("SELECT * FROM `web_menu` WHERE `type`=? ORDER BY `priority` ASC",["main"]);
                $this->response->content=$this->Query("SELECT * FROM `web_pages` WHERE `moved` IS NULL ORDER BY `date` DESC");
                $this->ReturnResponse();
                break;
            }
            case "getconfig" : {
                //config
                $this->response->ok=true;
                $this->response->data=$this->Query("select * from `web_settings`");
                $this->ReturnResponse();
                break;
            }
            case "getmainmenu" : {
                //print menu items
                $this->response->ok=true;
                $this->response->data=$this->Query("SELECT * FROM `web_menu` WHERE `type`=? ORDER BY `priority` ASC",["main"]);
                $this->ReturnResponse();
                break;
            }
            case "getcontent" : {
                //print menu items
                $this->response->ok=true;
                $this->response->data=$this->Query("SELECT * FROM `web_pages` WHERE `moved` IS NULL ORDER BY `date` DESC");
                $this->ReturnResponse();
                break;
            }                        
            default : {
                $this->response->error="UNKNOWN PAGE";
                $this->response->debug="POST::".implode("!!!",$_POST)."!!!GET::".implode("!!!",$_GET);
                $this->ReturnResponse();    
            }
        }
    }
}
?>