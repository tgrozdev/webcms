<?php
$lang["bg"]=[
	"changeusername"=>"Смяна на потребителското име на администратора",
	"changesettings"=>"Смяна на настройките на сайта",
	"changepassword"=>"Смяна на паролата на администратора",
	"changetemplate"=>"Смяна на шаблона на сайта",
	"changesettings_update"=>"КЛИКНИ ЗА ДА ПРОМЕНИШ!",
	"changetemplate_select"=>"Избери шаблон",
	"changetemplate_update"=>"КЛИКНИ ЗА ДА ПРОМЕНИШ!",
	"changeusername_input"=>"Админ Потребител",
	"changepassword_input"=>"Нова Парола",
	"changeusername_update"=>"КЛИКНИ ЗА ДА ПРОМЕНИШ!",
	"changepassword_update"=>"КЛИКНИ ЗА ДА ПРОМЕНИШ!",
	"changeusername_success"=>"Потребителското име на администратора беше променено успешно!",
	"changesettings_success"=>"Настройките на сайта бяха променени успешно!",
	"changepassword_success"=>"Паролата на администратора беше променено успешно!",
	"changetemplate_success"=>"Шаблона на сайта беше променен успешно!",
	"changepassword_error"=>"Грешка: Паролите не съвпадат!",
	"settings"=>"Настройки",
];
$lang["en"]=[
	"changeusername"=>"Change Administrator Username",
	"changesettings"=>"Change Website Settings",
	"changepassword"=>"Change Administrator Password",
	"changetemplate"=>"Change Website Template",
	"changesettings_update"=>"CLICK TO UPDATE!",
	"changetemplate_select"=>"Select Template",
	"changetemplate_update"=>"CLICK TO UPDATE!",
	"changeusername_input"=>"Administrator Username",
	"changepassword_input"=>"New Password",
	"changeusername_update"=>"CLICK TO CHANGE USERNAME!",
	"changepassword_update"=>"CLICK TO CHANGE PASSWORD!",
	"changeusername_success"=>"Administrator Username Changed!",
	"changesettings_success"=>"Website Settings Updated!",
	"changepassword_success"=>"Administrator Password Changed!",
	"changetemplate_success"=>"Website Template Changed!",
	"changepassword_error"=>"ERROR: Passwords DO NOT Match! ",
	"settings"=>"CMS Settings",
];

$content = "";
$section = $this->GetVar("section");
switch ($section) {

	case "changeusername": {
			$this->Query("UPDATE `web_settings` SET `value`=? WHERE `key`=? limit 1;", [$_POST["user"], "admin_username"]);
			$content .= "<p class=\"notify\">".$lang[$this->config["lang"]]["changeusername_success"]."</p><br>";
			unset($_COOKIE["loggedin"]);
			$this->GenerateLogin();
		}

	case "changesettings": {
			foreach (["name", "slogan", "url", "author"] as $key) {
				$this->Query("UPDATE `web_settings` SET `value`=? WHERE `key`=? limit 1;", [$_POST[$key], $key]);
			}
			$content .= "<p class=\"notify\">".$lang[$this->config["lang"]]["changesettings_success"]."</p><br>";
		}

	case "changepassword": {
			if (isset($_POST["new1_password"]) && !empty($_POST["new1_password"]) && ($_POST["new1_password"] == $_POST["new2_password"])) {
				$this->Query("UPDATE `web_settings` SET `value`=MD5(?) WHERE `key`=? limit 1;", [$_POST["new1_password"], "admin_password"]);
				$content .= "<p class=\"notify\">".$lang[$this->config["lang"]]["changepassword_success"]."</p><br>";
				unset($_COOKIE["loggedin"]);
				$this->GenerateLogin();
			} else {
				$content.="<p class=\"warning\">".$lang[$this->config["lang"]]["changepassword_error"]."</p>";
			}
		}

	case "changetemplate" : {
		if($section=="changetemplate"){
			$this->Query("UPDATE `web_settings` SET `value`=? WHERE `key`=? limit 1;", [$_POST["template"], "template"]);
			$content .= "<p class=\"notify\">".$lang[$this->config["lang"]]["changetemplate_success"]."</p><br>";
		}
	}	

	default: {
			$content .= '<form name="changesettings" action="admin.php?page=settings&section=changesettings" method="post">
<h2>'.$lang[$this->config["lang"]]["changesettings"].'</h2><br>
Website Title/Name: <input type="text" name="name" size="50" maxlength="50" value="' . $this->config["name"] . '" required><br><br>
Website Slogan: <input type="text" name="slogan" size="50" maxlength="50" value="' . $this->config["slogan"] . '" required><br><br>
URL www or non-www: <input type="text" name="url" size="30" maxlength="50" value="' . $this->config["url"] . '" required><br><br>
Website Author: <input type="text" name="author" size="20" maxlength="50" value="' . $this->config["author"] . '" required><br><br>
<input type="hidden" name="page" value="settings"><input type="hidden" name="section" value="changesettings">
<input type="submit" value="'.$lang[$lang[$this->config["lang"]]["changesettings_update"]].'">
</form>';
			$content .= '<hr/><form name="changesettings" action="admin.php?page=settings&section=changesettings" method="post">
<h2>'.$lang[$this->config["lang"]]["changetemplate"].'</h2><br>
'.$lang[$this->config["lang"]]["changetemplate_select"].' <select name="template" required>';
			$first = true;
			$templates=scandir(getcwd()."/assets/templates", 1);
			foreach ($templates as $key => $template){
				if(($template!=".") && ($template!=".."))
					if($first){
						$first = false;
						$content .= "<option selected=\"selected\">$template</option>";
					} else {
						$content .= "<option>$template</option>";
					}
					
			}
			$content .= '</select><br><br>
<input type="hidden" name="page" value="settings">
<input type="hidden" name="section" value="changetemplate">
<input type="submit" value="'.$lang[$this->config["lang"]]["changetemplate_update"].'">
</form>';
$params=explode(":",$_COOKIE["admin_loggedin"]);
			$content .= '<hr/><form name="userprofile" action="admin.php?page=settings&section=changeusername" method="post">
<h2>'.$lang[$this->config["lang"]]["changeusername"].'</h2><br>
'.$lang[$this->config["lang"]]["changeusername_input"].' <input type="text" name="user" size="20" maxlength="20" value="' . $params[0] . '"><br><br>
<input type="hidden" name="page" value="settings">
<input type="hidden" name="section" value="changeusername" required>
<input type="submit" value="'.$lang[$this->config["lang"]]["changeusername_update"].'">
</form>';
			$content .= '<hr/><form name="userprofile" action="admin.php?page=settings&section=changepassword" method="post">
<h2>'.$lang[$this->config["lang"]]["changepassword"].'</h2><br>
'.$lang[$this->config["lang"]]["changepassword_input"].' <input type="password" name="new1_password" size="20" maxlength="20" required><br><br>
'.$lang[$this->config["lang"]]["changepassword_input"].' <input type="password" name="new2_password" size="20" maxlength="20" required><br><br>
<input type="submit" value="'.$lang[$this->config["lang"]]["changepassword_update"].'">
</form>';
			$page_info = [
				"title" => $lang[$this->config["lang"]]["settings"],
				"content" => $content
			];
			echo $this->Generate_HTML($page_info);
			break;
		}
}
