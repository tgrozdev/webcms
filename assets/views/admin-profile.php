<?php
$lang["bg"]=[
    "myprofile"=>"Моят Профил",
    "changeusername_error"=>"ГРЕШКА: Потребителското име вече съществува! ",
    "changeusername_success"=>"Потребителското име беше променено успешно!",
	"changepassword_success"=>"Паролата на администратора беше променено успешно!",
	"changepassword_error"=>"Грешка: Паролите не съвпадат!",
	"changeinfo_success"=>"Информацията на администратора беше променено успешно!",
	"changeinfo_error"=>"Грешка: Възникна грешка при промяната на информацията на администратора!"
];
$lang["en"]=[
    "myprofile"=>"My Profile",
    "changeusername_error"=>"ERROR: Username already exists! ",
    "changeusername_success"=>"Username Successfully Changed!",
	"changepassword_success"=>"Password Successfully Changed!",
	"changepassword_error"=>"ERROR: Passwords DO NOT Match! ",
	"changeinfo_success"=>"Admin Information Successfully Changed!",
	"changeinfo_error"=>"ERROR: An error occurred while changing the admin information!"
];

$tags["[{MESSAGE}]"]="";
$section = $this->GetVar("section");
switch ($section) {

	case "changeusername": {		
        $result = $this->Query("SELECT * FROM `web_users` WHERE `username`=? limit 1;", [$_POST["user"]]);
        if($result->num_rows == 0){
            $this->Query("UPDATE `web_users` SET `username`=? WHERE `id`=? limit 1;", [$_POST["user"], $this->user["id"]]);
            $tags["[{MESSAGE}]"] = "<p class=\"notify\">".$lang[$this->config["lang"]]["changeusername_success"]."</p><br>";
            setcookie("loggedin","",time()-3600,"/");
            $this->GenerateLogin();
        } else {
            $tags["[{MESSAGE}]"]="<p class=\"warning\">".$lang[$this->config["lang"]]["changeusername_error"]."</p>";
        }		
    }

	case "changepassword": {
        if($section=="changepassword"){
			if (isset($_POST["new1_password"]) && !empty($_POST["new1_password"]) && ($_POST["new1_password"] == $_POST["new2_password"])) {				
				$this->Query("UPDATE `web_users` SET `password`=MD5(?) WHERE `id`=? limit 1;", [$_POST["new1_password"], $this->user["id"]]);
				$tags["[{MESSAGE}]"] = "<p class=\"notify\">".$lang[$this->config["lang"]]["changepassword_success"]."</p><br>";
				setcookie("loggedin","",time()-3600,"/");
				$this->GenerateLogin();
			} else {
				$tags["[{MESSAGE}]"]="<p class=\"warning\">".$lang[$this->config["lang"]]["changepassword_error"]."</p>";
			}
		}
    }
    case "changeinfo": {
        if($section=="changeinfo"){
            $result=$this->Query("UPDATE `web_users` SET `first_name`=?, `last_name`=?, `email`=?, `homepage`=?, `job_industry`=? WHERE `id`=? limit 1;",
            [$_POST["first_name"], $_POST["last_name"], $_POST["email"], $_POST["homepage"], $_POST["job_industry"], $this->user["id"]]);
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

	default: {			
			$tags["[{MY-USERNAME}]"]=$this->user["username"];
            $tags["[{FIRST-NAME}]"]=$this->user["first_name"];
            $tags["[{LAST-NAME}]"]=$this->user["last_name"];
            $tags["[{EMAIL}]"]=$this->user["email"];
            $tags["[{HOMEPAGE}]"]=$this->user["homepage"];
            $tags["[{JOB-INDUSTRY}]"]=$this->user["job_industry"];

            $tags["[{TITLE}]"]=$lang[$this->config["lang"]]["myprofile"];			
			echo $this->LoadHTML($tags, "profile");
			break;
		}
}
