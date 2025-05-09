<?php
$lang["bg"] = [
    "admin"=>"Админ",
    "user"=>"Потребител",
    "user-manager" => "Админ. Управление на потребители",
    "user-add" => "Добавяне на потребител",
    "user-edit" => "Редактиране на потребител",
    "user-delete" => "Премахване на потребител",
    "myprofile"=>"Управление на потребители",
    "changeusername_error"=>"ГРЕШКА: Потребителското име вече съществува! ",
    "changeusername_success"=>"Потребителското име беше променено успешно!",
	"changepassword_success"=>"Паролата на администратора беше променено успешно!",
	"changepassword_error"=>"Грешка: Паролите не съвпадат!",
	"changeinfo_success"=>"Информацията на администратора беше променено успешно!",
	"changeinfo_error"=>"Грешка: Възникна грешка при промяната на информацията на администратора!",
    "createuser_success"=>"Потребителът беше успешно създаден!",
    "createuser_error"=>"Грешка: Вече съществува потребител с това потребителско име!",
    "createuser_error_password"=>"Грешка: Паролите не съвпадат!",
    "createuser_error_general"=>"Грешка: Възникна грешка при създаването на потребител!",
    "changeusertype_success"=>"Потребителският тип беше променен успешно!",
    "deleteuser_error"=>"Грешка: Не може да изтриете собствения си акаунт!",
    "deleteuser_success"=>"Потребителът беше успешно изтрит!"
];
$lang["en"] = [
    "admin"=>"Admin",
    "user"=>"User",
    "user-manager" => "Admin User Management",
    "user-add" => "Add User",
    "user-edit" => "Edit User",
    "user-delete" => "Delete User",
    "myprofile"=>"User Manager",
    "changeusername_error"=>"ERROR: Username already exists! ",
    "changeusername_success"=>"Username Successfully Changed!",
	"changepassword_success"=>"Password Successfully Changed!",
	"changepassword_error"=>"ERROR: Passwords DO NOT Match! ",
	"changeinfo_success"=>"Admin Information Successfully Changed!",
	"changeinfo_error"=>"ERROR: An error occurred while changing the admin information!",
    "createuser_success"=>"User Successfully Created!",
    "createuser_error"=>"ERROR: Username already exists!",
    "createuser_error_password"=>"ERROR: Passwords DO NOT Match!",
    "createuser_error_general"=>"ERROR: An error occurred while creating the user!",
    "changeusertype_success"=>"User Type Successfully Changed!",
    "deleteuser_error"=>"ERROR: You cannot delete your own account!",
    "deleteuser_success"=>"User Successfully Deleted!"
];

$tags["[{MESSAGE}]"]="";
$content="";
$section = $this->GetVar("section");
switch ($section) {
    case "createuser" : {
        $result = $this->Query("SELECT * FROM `web_users` WHERE `username`=? limit 1;", [$_POST["user"]]);
        if(count($result) == 0){
            if($_POST["new1_password"] == $_POST["new2_password"]){
                $res=   $this->Query("INSERT INTO `web_users` (`type`, `username`, `password`, `first_name`, `last_name`, `email`, `homepage`, `job_industry`,`enabled`) VALUES (?, ?, MD5(?), ?, ?, ?, ?, ?, ?);", [$_POST["type"], $_POST["user"], $_POST["new1_password"], $_POST["first_name"], $_POST["last_name"], $_POST["email"], $_POST["homepage"], $_POST["job_industry"],1]);
                if($res){
                    $tags["[{MESSAGE}]"]="<p class=\"notify\">".$lang[$this->config["lang"]]["createuser_success"]."</p><br>";
                } else {
                    $tags["[{MESSAGE}]"]="<p class=\"warning\">".$lang[$this->config["lang"]]["createuser_error_general"]."</p>";
                }
            } else {
                $tags["[{MESSAGE}]"]="<p class=\"warning\">".$lang[$this->config["lang"]]["createuser_error_password"]."</p>";
            }
        } else {
            $tags["[{MESSAGE}]"]="<p class=\"warning\">".$lang[$this->config["lang"]]["createuser_error"]."</p>";
        }
        $tags["[{TITLE}]"] = $lang[$this->config["lang"]]["user-add"];
        $tags["[{MAINCONTENT}]"] = $tags["[{MESSAGE}]"];
        echo $this->LoadHTML($tags, "users-new");
        break;
    }
    case "add" : {
        $tags["[{TITLE}]"] = $lang[$this->config["lang"]]["user-add"];
        echo $this->LoadHTML($tags, "users-new");
        break;
    }

    case "changeusertype": {		
        $this->Query("UPDATE `web_users` SET `type`=? WHERE `id`=? limit 1;", [$_POST["type"], $_POST["id"]]);
        $tags["[{MESSAGE}]"] = "<p class=\"notify\">".$lang[$this->config["lang"]]["changeusertype_success"]."</p><br>";           
    }

	case "changeusername": {		
        if($section=="changepassword"){
            $result = $this->Query("SELECT * FROM `web_users` WHERE `username`=? limit 1;", [$_POST["user"]]);
            if(count($result) == 0){
                $this->Query("UPDATE `web_users` SET `username`=? WHERE `id`=? limit 1;", [$_POST["user"], $_POST["id"]]);
                $tags["[{MESSAGE}]"] = "<p class=\"notify\">".$lang[$this->config["lang"]]["changeusername_success"]."</p><br>";            
            } else {
                $tags["[{MESSAGE}]"]="<p class=\"warning\">".$lang[$this->config["lang"]]["changeusername_error"]."</p>";
            }		
        }
    }

	case "changepassword": {
        if($section=="changepassword"){
			if (isset($_POST["new1_password"]) && !empty($_POST["new1_password"]) && ($_POST["new1_password"] == $_POST["new2_password"])) {				
				$this->Query("UPDATE `web_users` SET `password`=MD5(?) WHERE `id`=? limit 1;", [$_POST["new1_password"], $_POST["id"]]);
				$tags["[{MESSAGE}]"] = "<p class=\"notify\">".$lang[$this->config["lang"]]["changepassword_success"]."</p><br>";
			} else {
				$tags["[{MESSAGE}]"]="<p class=\"warning\">".$lang[$this->config["lang"]]["changepassword_error"]."</p>";
			}
		}
    }
    case "changeinfo": {
        if($section=="changeinfo"){
            $result=$this->Query("UPDATE `web_users` SET `first_name`=?, `last_name`=?, `email`=?, `homepage`=?, `job_industry`=? WHERE `id`=? limit 1;",
            [$_POST["first_name"], $_POST["last_name"], $_POST["email"], $_POST["homepage"], $_POST["job_industry"], $_POST["id"]]);
            if($result){
                $tags["[{MESSAGE}]"] = "<p class=\"notify\">".$lang[$this->config["lang"]]["changeinfo_success"]."</p><br>";
                $this->user["first_name"]=$_POST["first_name"];
                $this->user["last_name"]=$_POST["last_name"];
                $this->user["email"]=$_POST["email"];
                $this->user["homepage"]=$_POST["homepage"];
                $this->user["job_industry"]=$_POST["job_industry"];
            } else {
                $tags["[{MESSAGE}]"] = "<p class=\"warning\">".$lang[$this->config["lang"]]["changeinfo_error"]."</p>";
            }
        }
    }

	case "edit": {	
            $result=$this->Query("SELECT * FROM `web_users` WHERE `id`=? limit 1;", [$this->GetVar("id")]);
            $row=current($result);
            $tags["[{ID}]"]=$row["id"];
			$tags["[{USERNAME}]"]=$row["username"];
            $tags["[{FIRST-NAME}]"]=$row["first_name"];
            $tags["[{LAST-NAME}]"]=$row["last_name"];
            $tags["[{EMAIL}]"]=$row["email"];
            $tags["[{HOMEPAGE}]"]=$row["homepage"];
            $tags["[{JOB-INDUSTRY}]"]=$row["job_industry"];

            if($row["type"]=="admin"){
                $tags["[{TYPES}]"]="<option value=\"admin\" selected=\"selected\">".$lang[$this->config["lang"]]["admin"]."</option><option value=\"user\">".$lang[$this->config["lang"]]["user"]."</option>";
            } else {
                $tags["[{TYPES}]"]="<option value=\"admin\">".$lang[$this->config["lang"]]["admin"]."</option><option value=\"user\" selected=\"selected\">".$lang[$this->config["lang"]]["user"]."</option>";
            }

            $tags["[{TITLE}]"]=$lang[$this->config["lang"]]["myprofile"];			
			echo $this->LoadHTML($tags, "users");
			break;
		}


    case "deleteuser": {
        if($this->user["id"]==$this->GetVar("id")){
            $content = "<p class=\"warning\">".$lang[$this->config["lang"]]["deleteuser_error"]."</p><br>";
        } else {
            $this->Query("DELETE FROM `web_users` WHERE `id`=? limit 1;", [$this->GetVar("id")]);
            $content = "<p class=\"notify\">".$lang[$this->config["lang"]]["deleteuser_success"]."</p><br>";
        }        
    }

    default: {
		$result=$this->Query("SELECT * FROM `web_users` ORDER BY `type` ASC",[]);
        $content.="<h2>".$lang[$this->config["lang"]]["user-manager"]."</h2><a href=\"admin.php?page=users&section=add\"><strong>".$lang[$this->config["lang"]]["user-add"]."</strong></a><br><br>";
        $content.="<ul>";
        foreach($result as $row){
            $content.="<li><a href=\"admin.php?page=users&section=edit&id=".$row["id"]."\" alt=\"".$lang[$this->config["lang"]]["user-edit"]."\"><strong>".strtoupper($row["type"])."</strong> ".$row["username"]."</a> : ".$row["first_name"]." ".$row["last_name"]." <a href=\"mailto:".$row["email"]."\">  ".$row["email"]."</a></li>";
        }
        $content.="</ul>";
        $tags["[{TITLE}]"] = $lang[$this->config["lang"]]["user-manager"];
        $tags["[{MAINCONTENT}]"] = $content;
        echo $this->LoadHTML($tags,"users");
    }
}