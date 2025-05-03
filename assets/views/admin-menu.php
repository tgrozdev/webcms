<?php
$content = "";
switch ($this->GetVar("section")) {

	case "menuup": {
		$ar=explode("_",$_POST["id"]);		
		$result=$this->Query("UPDATE `web_mainmenu` SET `priority`=`priority`-10 WHERE `id`=?",[$ar[1]]);		
		$this->ReturnResponse($result);
		break;
	}

	case "menudown": {
		$ar=explode("_",$_POST["id"]);	
		if($ar[0]=="main") {
			$result=$this->Query("UPDATE `web_mainmenu` SET `priority`=`priority`+10 WHERE `id`=?",[$ar[1]]);
		}
		$this->ReturnResponse($result);
		break;
	}
		
	case "delete" : {
		$ar=explode("_",$_POST["id"]);	
		if($ar[0]=="main") {
			$result=$this->Query("DELETE FROM `web_mainmenu` where `id`=? limit 1",[$ar[1]]);
		}
		$this->ReturnResponse($result);
	}

//====================================================================================
// EDIT MENU BELOW
//====================================================================================
	case "add" : {
		//ignore and skip to next one
	}
	case "edit": {
		if($this->GetVar("section")=="add"){
			$row=["id"=>0,"text"=>"","title"=>"","url"=>"","priority"=>100];
		} else {
			$result=$this->Query("SELECT * FROM `web_mainmenu` where `id`=? limit 1",[$_GET["id"]]);
			$row=current($result);
		}		
		$content.='<form name="menueditor" action="/admin.php" method="post">
<strong>CHOOSE WHICH MENU TO INCLUDE IN : </strong><select name="menu"><option selected="selected">main</option><option>quick</option><option>footer</option></select><br><br>
<strong>URL</strong> if page is external please include <strong>https://www.domain.com/fullurl.html</strong> else just put <strong>/filename.html</strong><br>
<input name="url" type="text" size="100" maxlength="180" value="'.$row["url"].'"><br><br>
<strong>LINK TITLE</strong><br><input name="title" type="text" size="100" maxlength="200" value="'.$row["title"].'"><br><br>
<strong>LINK TEXT</strong><br><input name="text" type="text" size="50" maxlength="100" value="'.$row["text"].'"><br><br>
<strong>LINK ORDERING PRIORITY</strong><br><input name="priority" type="text" size="10" maxlength="10" value="'.$row["priority"].'"><br><br>
<input type="hidden" name="page" value="menu">
<input type="hidden" name="id" value="'.$row["id"].'">
<input type="hidden" name="section" value="saveupdate">
<input type="submit" value="POST NEW MENU/SAVE CHANGES"> | <input type="reset" value="CLEAR FORM">
</form>';
		$page_info = [
			"title" => "Admin Homepage",
			"content" => $content
		];
		echo $this->Generate_HTML($page_info);
		break;
	}


	case "saveupdate" : {
		if($_POST["id"]==0){
			$this->Query("INSERT INTO `web_mainmenu` (`type`,`text`,`title`,`url`,`priority`) VALUES (?,?,?,?,?);",[$_POST["menu"],$_POST["text"],$_POST["title"],$_POST["url"],$_POST["priority"]]);
		} else {
			$this->Query("UPDATE `web_mainmenu` SET `type`=?,`text`=?,`title`=?,`url`=?,`priority`=? WHERE `id`=?",[$_POST["menu"],$_POST["text"],$_POST["title"],$_POST["url"],$_POST["priority"],$_POST["id"]]);
		}

	}

    default: {
        $content.="<h2>MAIN MENU ENTRIES</h2>";
		
		$content.="<a href=\"admin.php?page=menu&section=add&menu=main\"><strong>ADD MANUALLY ENTRY TO MAIN MENU</strong></a><br><br>";
		$result=$this->Query("SELECT * FROM `web_mainmenu` where `type`=? order by `priority`",["main"]);
		$content.="<table width=\"100%\"><tr><td width=\"75\">Action</td><td>URL:</td></tr>";
		foreach($result as $row){
			$content.="<tr><td id=\"main_".$row["id"]."\"><a href=\"admin.php?page=menu&section=edit&id=".$row["id"]."\"><img src=\"/assets/images/edit-small.png\" alt=\"EDIT MAIN MENU TAGS\"></a>
            <img class=\"menu_remove\" src=\"/assets/images/delete-small.jpg\" alt=\"REMOVE FROM MAIN MENU\">
            <img class=\"menu_up\" src=\"/assets/images/up-small.jpg\" alt=\"MOVE UP (INCREASE PRIORITY)\">
            <img class=\"menu_down\" src=\"/assets/images/down-small.jpg\" alt=\"MOVE DOWN (DECREASE PRIORITY)\">
            </td><td><strong>".$row["text"]."</strong> <br>URL: ".$row["url"]."</td></tr>";
		}
		$content.="</table><br>";	
		
		//-----------------------------------------------------------------------------------------------
		$result=$this->Query("SELECT * FROM `web_mainmenu` WHERE `type`=? order by `priority`",["quick"]);
        $content.="<h2>QUICK/HEADER MENU ENTRIES</h2><a href=\"admin.php?page=menu&section=add&menu=quick\"><strong>ADD MANUALLY ENTRY TO QUICK MENU</strong></a><br><br>";
        $content.="<table width=\"100%\"><tr><td width=\"75\">Action</td><td>URL:</td></tr>";
		foreach($result as $row){			
			$content.="<tr><td><a href=\"admin.php?page=menu&section=edit&id=".$row["id"]."\"><img src=\"/assets/images/edit-small.png\" alt=\"EDIT QUICK MENU TAGS\"></a> <a onclick=\"javascript:return confirm('Are you sure you want to this item from the quick menu ?')\" href=\"admin.php?page=menu&section=delete&id=".$row["id"]."\"><img src=\"/assets/images/delete-small.jpg\" alt=\"REMOVE FROM QUICK MENU\"></a> <a href=\"admin.php?page=menu&section=moveup&id=".$row["id"]."\"><img src=\"/assets/images/up-small.jpg\" alt=\"MOVE UP (INCREASE PRIORITY)\"></a> <a href=\"admin.php?page=menu&section=movedown&id=".$row["id"]."\"><img src=\"/assets/images/down-small.jpg\" alt=\"MOVE DOWN (DECREASE PRIORITY)\"></a></td><td><strong>".$row["text"]."</strong><br>URL: ".$row["url"]."</td></tr>";
		} 
        $content.="</table><br>";			
		
		
		$content.="<h2> FOOTER MENU ENTRIES</h2><strong>(NOTE: ALL MAIN MENU ENTRIES ARE AUTOMATICALLY INCLUDED IN FOOTER MENU!)</strong></h3><a href=\"admin.php?page=menu&section=add&menu=footer\"><strong>ADD MANUALLY ENTRY TO FOOTER MENU</strong></a><br><br>";
		$result=$this->Query("SELECT * FROM `web_mainmenu` WHERE `type`=? order by `priority`",["footer"]);
		$content.="<table width=\"100%\"><tr><td width=\"75\">Action</td><td>URL:</td></tr>";
		foreach($result as $row){
			$content.="<tr><td><a href=\"admin.php?page=menu&section=edit&id=".$row["id"]."\"><img src=\"/assets/images/edit-small.png\" alt=\"EDIT FOOTER MENU TAGS\"></a> <a onclick=\"javascript:return confirm('Are you sure you want to this item from the footer menu ?')\" href=\"admin.php?page=menu&section=delete&id=".$row["id"]."\"><img src=\"/assets/images/delete-small.jpg\" alt=\"REMOVE FROM FOOTER MENU\"></a> <a href=\"admin.php?page=menu&section=moveup&id=".$row["id"]."\"><img src=\"/assets/images/up-small.jpg\" alt=\"MOVE UP (INCREASE PRIORITY)\"></a> <a href=\"admin.php?page=menu&section=movedown&id=".$row["id"]."\"><img src=\"/assets/images/down-small.jpg\" alt=\"MOVE DOWN (DECREASE PRIORITY)\"></a></td><td><strong>".$row["text"]."</strong><br>URL: ".$row["url"]."</td></tr>";
		}
        $this->footer_includes="<script src=\"/assets/scripts/menu.js\"></script>";
		$content.="</table><br>";	
        $page_info = [
            "title" => "Admin Homepage",
            "content" => $content
        ];
        echo $this->Generate_HTML($page_info);
        break;
    }
}
