<?php
$lang["bg"] = [
	"main" => "Главно меню",
	"quick" => "Бързо меню",
	"footer" => "Футър меню",
	"title" => "Админ Менюта",
	"add" => "Добавяне на елемент",
	"remove" => "Премахване на елемент",
	"moveup" => "Повишаване на елемента",
	"movedown" => "Понижаване на елемента",
	"edit" => "Редактиране на елемент",
	"note" => "Внимание: Всички елементи на Главното меню автоматично се включват в Футър менюто!",
	"confirm" => "Сигурни ли сте, че искате да премахнете този елемент от менюто?"
];
$lang["en"] = [
	"main" => "Main Menu",
	"quick" => "Quick Menu",
	"footer" => "Footer Menu",
	"title" => "Admin Menus",
	"add" => "Add Element",
	"remove" => "Remove Element",
	"moveup" => "Move Up",
	"movedown" => "Move Down",
	"edit" => "Edit Element",
	"note" => "Note: All Main Menu entries are automatically included in Footer Menu!",
	"confirm" => "Are you sure you want to remove this item from the menu?"
];


switch ($this->GetVar("section")) {
	case "menuup": {
		$ar=explode("_",$_POST["id"]);				
		$result=$this->Query("SELECT `id`,`type`,`priority` FROM `web_menu` WHERE `id`=?",[$ar[1]]);		
		$item=current($result);		
		$result=$this->Query("SELECT `id`,`priority` FROM `web_menu` WHERE `type`=? AND `priority`<=? ORDER BY `priority` DESC LIMIT 2",[$item["type"],$item["priority"]]);
		if(count($result)>1){
			$row=end($result);
			$result=$this->Query("UPDATE `web_menu` SET `priority`=? WHERE `id`=?",[$row["priority"]-1,$item["id"]]);
			$this->ReturnResponse(true);	
		} else {
			$this->ReturnResponse(false,[],"No other elements found!");	
		}
		break;
	}

	case "menudown": {
		$ar=explode("_",$_POST["id"]);				
		$result=$this->Query("SELECT `id`,`type`,`priority` FROM `web_menu` WHERE `id`=?",[$ar[1]]);		
		$item=current($result);
		$result=$this->Query("SELECT `id`,`priority` FROM `web_menu` WHERE `type`=? AND `priority`>=? ORDER BY `priority` ASC LIMIT 2",[$item["type"],$item["priority"]]);
		if(count($result)>1){
			$row=end($result);
			$result=$this->Query("UPDATE `web_menu` SET `priority`=? WHERE `id`=?",[$row["priority"]+1,$item["id"]]);
			$this->ReturnResponse(true);	
		} else {
			$this->ReturnResponse(false,[],"No other elements found!");	
		}
		break;
	}
		
	case "delete" : {
		$ar=explode("_",$_POST["id"]);	
		if($ar[0]=="main") {
			$result=$this->Query("DELETE FROM `web_menu` where `id`=? limit 1",[$ar[1]]);
		}
		$this->ReturnResponse($result);
	}

//====================================================================================
// Добавяне и Редактиране на елементи от менюто
//====================================================================================
	case "add" : {
		//ignore and skip to next one
	}
	case "edit": {
		if($this->GetVar("section")=="add"){
			$row=["id"=>0,"type"=>$this->GetVar("menu"),"text"=>"","title"=>"","url"=>"","priority"=>100];
		} else {
			$result=$this->Query("SELECT * FROM `web_menu` where `id`=? limit 1",[$this->GetVar("id")]);
			$row=current($result);
		}

		switch($row["type"]){
			case "main":
				$tags["[{MENU}]"] = "<option value=\"main\" selected=\"selected\">".$lang[$this->config["lang"]]["main"]."</option><option value=\"quick\">".$lang[$this->config["lang"]]["quick"]."</option><option value=\"footer\">".$lang[$this->config["lang"]]["footer"]."</option>";
				break;
			case "quick":
				$tags["[{MENU}]"] = "<option value=\"main\">".$lang[$this->config["lang"]]["main"]."</option><option value=\"quick\" selected=\"selected\">".$lang[$this->config["lang"]]["quick"]."</option><option value=\"footer\">".$lang[$this->config["lang"]]["footer"]."</option>";
				break;
			case "footer":
				$tags["[{MENU}]"] = "<option value=\"main\">".$lang[$this->config["lang"]]["main"]."</option><option value=\"quick\">".$lang[$this->config["lang"]]["quick"]."</option><option value=\"footer\" selected=\"selected\">".$lang[$this->config["lang"]]["footer"]."</option>";
				break;
		}		

		$tags["[{ID}]"] = $row["id"];
		$tags["[{URL}]"] = $row["url"];
		$tags["[{TITLE}]"] = $row["title"];
		$tags["[{TEXT}]"] = $row["text"];
		$tags["[{PRIORITY}]"] = $row["priority"];
		
		$tags["[{TITLE}]"] = $lang[$this->config["lang"]]["title"];
		echo $this->LoadHTML($tags,"menu");
		break;
	}


	case "saveupdate" : {
		if($_POST["id"]==0){
			$this->Query("INSERT INTO `web_menu` (`type`,`text`,`title`,`url`,`priority`) VALUES (?,?,?,?,?);",[$_POST["menu"],$_POST["text"],$_POST["title"],$_POST["url"],$_POST["priority"]]);
		} else {
			$this->Query("UPDATE `web_menu` SET `type`=?,`text`=?,`title`=?,`url`=?,`priority`=? WHERE `id`=?",[$_POST["menu"],$_POST["text"],$_POST["title"],$_POST["url"],$_POST["priority"],$_POST["id"]]);
		}

	}

    default: {
        $content="<h2>".$lang[$this->config["lang"]]["main"]."</h2>";		
		$content.="<a href=\"admin.php?page=menu&section=add&menu=main\"><strong>".$lang[$this->config["lang"]]["add"]."</strong></a><br><br>";
		$result=$this->Query("SELECT * FROM `web_menu` where `type`=? order by `priority`",["main"]);
		$content.="<table width=\"100%\"><tr><td width=\"75\">Action</td><td>URL:</td></tr>";
		foreach($result as $row){
			$content.="<tr><td id=\"main_".$row["id"]."\"><a href=\"admin.php?page=menu&section=edit&id=".$row["id"]."\"><img src=\"/assets/images/edit-small.png\" alt=\"".$lang[$this->config["lang"]]["edit"]."\"></a>
            <img class=\"menu_remove\" src=\"/assets/images/delete-small.jpg\" alt=\"".$lang[$this->config["lang"]]["remove"]."\">
            <img class=\"menu_up\" src=\"/assets/images/up-small.jpg\" alt=\"".$lang[$this->config["lang"]]["moveup"]."\">
            <img class=\"menu_down\" src=\"/assets/images/down-small.jpg\" alt=\"".$lang[$this->config["lang"]]["movedown"]."\">
            </td><td><strong>".$row["text"]."</strong> <br>URL: ".$row["url"]."</td></tr>";
		}
		$content.="</table><br>";	
		
		//-----------------------------------------------------------------------------------------------
		$result=$this->Query("SELECT * FROM `web_menu` WHERE `type`=? order by `priority`",["quick"]);
        $content.="<h2>".$lang[$this->config["lang"]]["quick"]."</h2><a href=\"admin.php?page=menu&section=add&menu=quick\"><strong>".$lang[$this->config["lang"]]["add"]."</strong></a><br><br>";
        $content.="<table width=\"100%\"><tr><td width=\"75\">Action</td><td>URL:</td></tr>";
		foreach($result as $row){			
			$content.="<tr><td id=\"quick_".$row["id"]."\"><a href=\"admin.php?page=menu&section=edit&id=".$row["id"]."\"><img src=\"/assets/images/edit-small.png\" alt=\"".$lang[$this->config["lang"]]["edit"]."\"></a>
			<img class=\"menu_remove\" src=\"/assets/images/delete-small.jpg\" alt=\"".$lang[$this->config["lang"]]["remove"]."\">
			<img class=\"menu_up\" src=\"/assets/images/up-small.jpg\" alt=\"".$lang[$this->config["lang"]]["moveup"]."\">
			<img class=\"menu_down\" src=\"/assets/images/down-small.jpg\" alt=\"".$lang[$this->config["lang"]]["movedown"]."\">
			</td><td><strong>".$row["text"]."</strong><br>URL: ".$row["url"]."</td></tr>";
		} 
        $content.="</table><br>";			
		
		
		$content.="<h2> ".$lang[$this->config["lang"]]["footer"]."</h2><strong>".$lang[$this->config["lang"]]["note"]."</strong></h3><a href=\"admin.php?page=menu&section=add&menu=footer\"><strong>".$lang[$this->config["lang"]]["add"]."</strong></a><br><br>";
		$result=$this->Query("SELECT * FROM `web_menu` WHERE `type`=? order by `priority`",["footer"]);
		$content.="<table width=\"100%\"><tr><td width=\"75\">Action</td><td>URL:</td></tr>";
		foreach($result as $row){
			$content.="<tr><td  id=\"footer_".$row["id"]."\"><a href=\"admin.php?page=menu&section=edit&id=".$row["id"]."\"><img src=\"/assets/images/edit-small.png\" alt=\"".$lang[$this->config["lang"]]["edit"]."\"></a>
			<img class=\"menu_remove\" src=\"/assets/images/delete-small.jpg\" alt=\"".$lang[$this->config["lang"]]["remove"]."\">
			<img class=\"menu_up\" src=\"/assets/images/up-small.jpg\" alt=\"".$lang[$this->config["lang"]]["moveup"]."\">
			<img class=\"menu_down\" src=\"/assets/images/down-small.jpg\" alt=\"".$lang[$this->config["lang"]]["movedown"]."\">
			</td><td><strong>".$row["text"]."</strong><br>URL: ".$row["url"]."</td></tr>";
		}
        $this->footer_includes="<script src=\"/assets/scripts/menu.js\"></script>";
		$content.="</table><br>";	
        $tags["[{TITLE}]"] = "Admin Homepage";
        $tags["[{MAINCONTENT}]"] = $content;
        echo $this->LoadHTML($tags,"menu");
        break;
    }
}
