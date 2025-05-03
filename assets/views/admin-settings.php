<?php
$content = "";
$section = $this->GetVar("section");
switch ($section) {

	case "changeusername": {
			$this->Query("UPDATE `web_settings` SET `value`=? WHERE `key`=? limit 1;", [$_POST["user"], "admin_username"]);
			$content .= "<p class=\"notify\">Admin Username Changed!</p><br>";
			unset($_COOKIE["loggedin"]);
			$this->GenerateLogin();
		}

	case "changesettings": {
			foreach (["name", "slogan", "url", "author"] as $key) {
				$this->Query("UPDATE `web_settings` SET `value`=? WHERE `key`=? limit 1;", [$_POST[$key], $key]);
			}
			$content .= "<p class=\"notify\">settings updated</p><br>";
			$this->GenerateLogin();
		}

	case "changepassword": {
			if (isset($_POST["new1_password"]) && !empty($_POST["new1_password"]) && ($_POST["new1_password"] == $_POST["new2_password"])) {
				$this->Query("UPDATE `web_settings` SET `value`=MD5(?) WHERE `key`=? limit 1;", [$_POST["new1_password"], "admin_password"]);
				$content .= "<p class=\"notify\">Admin Password Changed!</p><br>";
				unset($_COOKIE["loggedin"]);
				$this->GenerateLogin();
			} else {
				$content.="<p class=\"warning\">ERROR: Passwords DO NOT Match! </p>";
			}
		}

	case "changetemplate" : {
		if($section=="changetemplate"){
			$this->Query("UPDATE `web_settings` SET `value`=? WHERE `key`=? limit 1;", [$_POST["template"], "template"]);
			$content .= "<p class=\"notify\">Website Template Changed!</p><br>";
		}
	}	

	default: {
			$content .= '<form name="changesettings" action="admin.php?page=settings&section=changesettings" method="post">
<h2>Website Settings</h2><br>
Website Title/Name: <input type="text" name="name" size="50" maxlength="50" value="' . $this->config["name"] . '" required><br><br>
Website Slogan: <input type="text" name="slogan" size="50" maxlength="50" value="' . $this->config["slogan"] . '" required><br><br>
URL www or non-www: <input type="text" name="url" size="30" maxlength="50" value="' . $this->config["url"] . '" required><br><br>
Website Author: <input type="text" name="author" size="20" maxlength="50" value="' . $this->config["author"] . '" required><br><br>
<input type="hidden" name="page" value="settings"><input type="hidden" name="section" value="changesettings"><input type="submit" value="CLICK TO UPDATE!">
</form>';
			$content .= '<hr/><form name="changesettings" action="admin.php?page=settings&section=changesettings" method="post">
<h2>Change Website Template</h2><br>
Templates: <select name="template" required>';
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
<input type="hidden" name="page" value="settings"><input type="hidden" name="section" value="changetemplate"><input type="submit" value="CLICK TO CHANGE!">
</form>';
			$content .= '<hr/><form name="userprofile" action="admin.php?page=settings&section=changeusername" method="post">
<h2>Change Administrator User Name</h2><br>
Administrator Username: <input type="text" name="user" size="20" maxlength="20" value="' . $this->config["admin_username"] . '"><br><br>
<input type="hidden" name="page" value="settings"><input type="hidden" name="section" value="changeusername" required><input type="submit" value="CLICK TO CHANGE USERNAME!">
</form>';
			$content .= '<hr/><form name="userprofile" action="admin.php?page=settings&section=changepassword" method="post">
<h2>Change Password</h2><br>
NEW PASSWORD: <input type="password" name="new1_password" size="20" maxlength="20" required><br><br>
CONFIRM NEW PASSWORD: <input type="password" name="new2_password" size="20" maxlength="20" required><br><br>
<input type="submit" value="CLICK TO CHANGE PASSWORD!">
</form>';
			$page_info = [
				"title" => "CMS Settings",
				"content" => $content
			];
			echo $this->Generate_HTML($page_info);
			break;
		}
}
