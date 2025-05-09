<?php
$lang["bg"]=[	
	"changesettings_success"=>"Настройките на сайта бяха променени успешно!",
	"changetemplate_success"=>"Шаблона на сайта беше променен успешно!",
	"settings"=>"Настройки"	
];
$lang["en"]=[	
	"changesettings_success"=>"Website Settings Updated!",
	"changetemplate_success"=>"Website Template Changed!",
	"settings"=>"CMS Settings"];


$tags["[{MESSAGE}]"] = "";
$content = "";
$section = $this->GetVar("section");
switch ($section) {

	case "changesettings": {
			foreach (["name", "slogan", "url", "author"] as $key) {
				$this->Query("UPDATE `web_settings` SET `value`=? WHERE `key`=? limit 1;", [$_POST[$key], $key]);
			}
			$tags["[{MESSAGE}]"] = "<p class=\"notify\">".$lang[$this->config["lang"]]["changesettings_success"]."</p><br>";
		}

	case "changetemplate" : {
		if($section=="changetemplate"){
			$this->Query("UPDATE `web_settings` SET `value`=? WHERE `key`=? limit 1;", [$_POST["template"], "template"]);
			$tags["[{MESSAGE}]"] = "<p class=\"notify\">".$lang[$this->config["lang"]]["changetemplate_success"]."</p><br>";
		}
	}	

	default: {
			$tags["[{CONFIG-NAME}]"]=$this->config["name"];
			$tags["[{CONFIG-SLOGAN}]"]=$this->config["slogan"];
			$tags["[{CONFIG-URL}]"]=$this->config["url"];
			$tags["[{CONFIG-AUTHOR}]"]=$this->config["author"];
			
			$tags["[{TEMPLATES}]"]="";
			$templates=scandir(getcwd()."/assets/templates", 1);
			foreach ($templates as $key => $template){
				if(($template!=".") && ($template!="..") && ($template!="index.html"))
					if($template==$this->config["template"]){						
						$tags["[{TEMPLATES}]"] .= "<option selected=\"selected\">$template</option>";
					} else {
						$tags["[{TEMPLATES}]"] .= "<option>$template</option>";
					}					
			}
			$tags["[{TITLE}]"]=$lang[$this->config["lang"]]["settings"];
			echo $this->LoadHTML($tags, "settings");
			break;
		}
}
