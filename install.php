<?php
error_reporting(E_ERROR | E_PARSE);
//пресетваме агнлийски език ако няма предварително зададен
$language=$_POST["language"] ?? "en";
//преводите на различните стрингове спрямо избраният език
$lang["bg"]=[
    "language"=>"Български",
    "installer"=>"Уеб CMS Инсталатор",
    "already_installed"=>"Изглежда че софтуерът вече е инсталиран!",
    "mysql_enter_hostname"=>"Моля попълнете полето за База Данни Сървъра !",
    "mysql_enter_user" => "Моля попълнете полето за БД Потребител (потребителя трябва да има всички права) !",
    "mysql_enter_password" => "Моля попълнете полето за Парола на БД Потребител !",
    "mysql_enter_database" => "Моля попълнете полето за Име на База Данни!",
    "config_enter_url" => "Моля въведете адреса на сайта (пример: www.mywebsite.com) !",
    "config_enter_title" => "Моля въведете Име/Заглавие на сайта!",
    "config_enter_slogan" => "Моля въведете лозунг на сайта (пример: Ние сме Най-Добрите!) !",
    "config_enter_author" => "Моля въведете Името на Автор на сайта! (вашето?)",
    "config_enter_copyrights" => "Моля въведете текст за Запазени Права (показва се във Футъра на сайта)!",
    "config_enter_username" => "Моля въведете желаното от вас потребителско име за Администратор на сайта!",
    "config_enter_password" => "Моля въведете желаното от вас Парола за потребителя!",
    "config_enter_email" => "Моля въведете валиден Администраторски Е-Майл!",
    "mysql_label1" => "Настройки на връзката за База Данни",
    "config_label1" => "Основни Настройки на Сайта",
    "config_db_server" => "БД Сървър",
    "config_db_username" => "БД Потребилте",
    "config_db_password" => "БД Парола",
    "config_db_name" => "Име на База Данни",
    "config_url" => "Уебсайт Адрес",
    "config_title" => "Име/Заглавие на Сайта",
    "config_slogan" => "Лозунг на Сайта",
    "config_author" => "Автор",
    "config_copyrights" => "Текст за Запазени Права",
    "config_template" => "Изберете Дизайн/Шаблон",
    "config_label2" => "Администраторски Потребител",
    "config_admin_username" => "Админ Потребител",
    "config_admin_password" => "Админ Парола",
    "config_admin_email" => "Админ Контакт Емайл",
    "install_label2" => "Продължете Инсталацията към Стъпка 2",
    "installer_step2_success"=>"Настройки на Сайта бяха успешно записани в Базата с Данни!",    
    "mysql_cannot_connect"=>"Не успяхме да се свържем към БД Сървъра! Грешка",
    "mysql_cannot_access"=>"Не успяхме да влезем в Базата с Данни.",
    "mysql_success"=>"Успешно се свързахме към Базата с Данни!",
    "installer_step2"=>"Уеб CMS Инсталатор: Стъпка 2",
    "config_cannot_write"=>"Уеб Сървърните настойки не позволяват писане на файлове! Трябва да създадете и запишете конфигурационния файл ръчно! Моля създайте файл <strong>webconf.php</strong> в главната папка на инсталацията и сложете в него следното съдържание:",
    "config_success"=>"Уеб Конфигурационния Файл беше успешно генериран!",
];
$lang["en"]=[
    "language"=>"English",
    "installer"=>"CMS Installer Script",
    "already_installed"=>"Website CMS seems to be installed already!",
    "mysql_enter_hostname"=>"Please enter the MySQL Server Host !",
    "mysql_enter_user" => "Please enter the MySQL User (user must have all rights) !",
    "mysql_enter_password" => "Please enter the Password for this MySQL User !",
    "mysql_enter_database" => "Please enter the MySQL Database Name!",
    "config_enter_url" => "Please enter the Website URL (example: www.mywebsite.com) !",
    "config_enter_title" => "Please enter Website Name/Title!",
    "config_enter_slogan" => "Please enter Website Slogan! (example: We are The Best in)",
    "config_enter_author" => "Please enter Website Author! (yours?)",
    "config_enter_copyrights" => "Please enter Website Copyrights! (displayed in web footer)",
    "config_enter_username" => "Please enter Administrator Username!",
    "config_enter_password" => "Please enter Administrator Password!",
    "config_enter_email" => "Administrator Email Address is invalid !",
    "mysql_label1" => "MySQL Server Settings",
    "config_label1" => "Basic Website Configuration",
    "config_db_server" => "DB Server",
    "config_db_username" => "DB Username",
    "config_db_password" => "DB Password",
    "config_db_name" => "Database Name",
    "config_url" => "Website URL",
    "config_title" => "Website Name/Title",
    "config_slogan" => "Website Slogan",
    "config_author" => "Author",
    "config_copyrights" => "Copyrights",
    "config_template" => "Choose Website Template",
    "config_label2" => "Administrator Login",
    "config_admin_username" => "Admin Username",
    "config_admin_password" => "Admin Password",
    "config_admin_email" => "Admin Contact Email",
    "install_label2" => "Continue Installation to Step 2",    
    "installer_step2_success"=>"Website Settings were successfully populated in Database!",    
    "mysql_cannot_connect"=>"Cannot connect to the database server because",
    "mysql_cannot_access"=>"Cannot use/access this database name",
    "mysql_success"=>"Successfully Connected to Database!",
    "installer_step2"=>"Website CMS Installation: Step 2",
    "config_cannot_write"=>"Server Settings show that we can not create the configuration file, and you will have to create it manually. Please create file in the main installation folder called <strong>webconf.php</strong> and copy/paste the following code:",
    "config_success"=>"Website CMS Configuration File was generated successfully",
];
?>
<!DOCTYPE html><html>
<head>
<title><?php echo $lang[$language]["installer"]; ?></title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
<?php
if(file_exists(getcwd()."/webconf.php")){
	print("<h1>".$lang[$language]["already_installed"]."</h1>");
} else {
	switch($_POST["installer"]) {
        case "step1" : {
			// step 1
			?>
			<SCRIPT LANGUAGE="JavaScript">
<!-- Begin
function Verify() //verify if the requested fields is filled
{
    if (window.document.forms[0].sql_server.value == ""){
        alert("<?php echo $lang[$language]["mysql_enter_hostname"]; ?>");
        window.document.forms[0].sql_server.focus();
        return false;
    }

    if (window.document.forms[0].sql_user.value == ""){
        alert("<?php echo $lang[$language]["mysql_enter_user"]; ?>");
        window.document.forms[0].sql_user.focus();
        return false;
    }

    if (window.document.forms[0].sql_password.value == ""){
        alert("<?php echo $lang[$language]["mysql_enter_password"]; ?>");
        window.document.forms[0].sql_password.focus();
        return false;
    }

    if (window.document.forms[0].sql_database.value == ""){
        alert("<?php echo $lang[$language]["mysql_enter_database"]; ?>");
        window.document.forms[0].sql_database.focus();
        return false;
    }

    if (window.document.forms[0].website_url.value == ""){
        alert("<?php echo $lang[$language]["config_enter_url"]; ?>");
        window.document.forms[0].website_url.focus();
        return false;
    }
	
    if (window.document.forms[0].website_name.value == ""){
        alert("<?php echo $lang[$language]["config_enter_title"]; ?>");
        window.document.forms[0].website_name.focus();
        return false;
    }


    if (window.document.forms[0].website_slogan.value == ""){
        alert("<?php echo $lang[$language]["config_enter_slogan"]; ?>");
        window.document.forms[0].website_slogan.focus();
        return false;
    }
    
    if (window.document.forms[0].website_author.value == ""){
        alert("<?php echo $lang[$language]["config_enter_author"]; ?>");
        window.document.forms[0].website_author.focus();
        return false;
    }

    if (window.document.forms[0].website_copyrights.value == ""){
        alert("<?php echo $lang[$language]["config_enter_copyrights"]; ?>");
        window.document.forms[0].website_copyrights.focus();
        return false;
    }
    
    if (window.document.forms[0].admin_username.value == ""){
        alert("<?php echo $lang[$language]["config_enter_username"]; ?>");
        window.document.forms[0].admin_username.focus();
        return false;
    }

    if (window.document.forms[0].admin_password.value == ""){
        alert("<?php echo $lang[$language]["config_enter_password"]; ?>");
        window.document.forms[0].admin_password.focus();
        return false;
    }
    
    var regex = /^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
    if(!regex.test(window.document.forms[0].admin_email.value)){
        alert("<?php echo $lang[$language]["config_enter_email"]; ?>");
        window.document.forms[0].admin_email.focus();
        return false;
    }
    
    return true;
}
//  End -->
</script>
<div class="container">
    <div class="row">
        <div class="col-12 col-md-12 col-xxl-8">
            <h1>Инсталация: Стъпка 1</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6">
        <form name="step1" action="install.php" method="post" onSubmit="return Verify()">
            <h2><?php echo $lang[$language]["mysql_label1"]; ?></h2>
            <?php echo $lang[$language]["config_db_server"]; ?>: <input id="sql_server" type="text" name="sql_server" value="<?php if(!empty($sql_server)) print($sql_server); else print("localhost"); ?>" /><br />
            <?php echo $lang[$language]["config_db_username"]; ?>: <input id="sql_user" type="text" name="sql_user" value="<?php if(!empty($sql_user)) print($sql_user); ?>" /><br />
            <?php echo $lang[$language]["config_db_password"]; ?>: <input id="sql_password" type="text" name="sql_password" value="<?php if(!empty($sql_password)) print($sql_password); ?>" /><br />
            <?php echo $lang[$language]["config_db_name"]; ?>: <input id="sql_database" type="text" name="sql_database" value="<?php if(!empty($sql_database)) print($sql_database); ?>"/><br />
            <hr />
            <h2><?php echo $lang[$language]["config_label1"]; ?></h2>
            <?php echo $lang[$language]["config_url"]; ?>: <input id="website_url" type="text" name="website_url" value="<?php print($_SERVER["HTTP_HOST"]); ?>" /><br />
            <?php echo $lang[$language]["config_title"]; ?>: <input id="website_name" type="text" name="website_name" value="<?php if(!empty($website_name)) print($website_name); ?>" /><br />
            <?php echo $lang[$language]["config_slogan"]; ?>: <input id="website_slogan" type="text" name="website_slogan" value="<?php if(!empty($website_slogan)) print($website_slogan); ?>" /><br />
            <?php echo $lang[$language]["config_author"]; ?>: <input id="website_author" type="text" name="website_author" value="<?php if(!empty($website_author)) print($website_author); ?>" /><br />
            <?php echo $lang[$language]["config_copyrights"]; ?>: <input id="website_copyrights" type="text" name="website_copyrights" value="<?php if(!empty($website_copyrights)) print($website_copyrights); ?>" /><br /><br />
            <?php echo $lang[$language]["config_template"]; ?>: <select name="website_template">
            <?php
			$templates=scandir(getcwd()."/assets/templates", 1);
			foreach ($templates as $key => $template){
				if(($template!=".") && ($template!=".."))
					print("<option>$template</option>");
			}
			?>            
            </select>			
            <hr />
            <h2><?php echo $lang[$language]["config_label2"]; ?></h2>       
            <?php echo $lang[$language]["config_admin_username"]; ?>: <input id="admin_username" type="text" name="admin_username" value="<?php if(!empty($admin_username)) print($admin_username); ?>" /><br />
            <?php echo $lang[$language]["config_admin_password"]; ?>: <input id="admin_password" type="text" name="admin_password" value="<?php if(!empty($admin_password)) print($admin_password); ?>" /><br />
            <?php echo $lang[$language]["config_admin_email"]; ?>: <input id="admin_email" type="text" name="admin_email" value="<?php if(!empty($admin_email)) print($admin_email); ?>" /><br />
            <hr />
			<input type="hidden" name="installer" value="step2" />
            <input type="hidden" name="language" value="<?php echo $language; ?>" />
            <input type="submit" value="<?php echo $lang[$language]["install_label2"]; ?>" />
        </form>
        </div>
    </div>    
</div>    
            <?php
			break;
		}

		case "step2" : {
			?>
			<div class="container">
    <div class="row">
        <div class="col-12 col-md-12 col-xxl-8">     
			<?php	
            mb_internal_encoding('UTF-8');
            mb_regex_encoding('UTF-8');
            mysqli_report(MYSQLI_REPORT_STRICT);
            try {
                $sql = new mysqli($_POST["sql_server"],$_POST["sql_user"],$_POST["sql_password"],$_POST["sql_database"]);
                $sql->set_charset("utf8");
            } catch (Exception $e) {
                //опа, нещо гръмна и не се вързахме.
                echo '<p style="color:red;">'.$lang[$language]["mysql_cannot_connect"].' '.$sql->error.'</p>';
            }
            if ($sql->connect_errno) {
                echo '<p style="color:red;">'.$lang[$language]["mysql_cannot_connect"].' '.$sql->error.'</p><br />';
			} else {
					print("<h1>".$lang[$language]["installer_step2"]."</h1>");
					
					$config='?php
$this->config=array(
"mysql_host"=>"'.$_POST["sql_server"].'", // Please enter MYSQL Host Server
"mysql_username"=>"'.$_POST["sql_user"].'", // Please enter MYSQL account Username
"mysql_password"=>"'.$_POST["sql_password"].'", // Please enter MYSQL account Password
"mysql_db"=>"'.$_POST["sql_database"].'" // Please enter MYSQL database name which will be used for this website
);
?';
					
					print($lang[$language]["mysql_success"]."!<br />");					
					if (!$file=fopen(getcwd()."/webconf.php",'w')) {
						print("<p>".$lang[$language]["config_cannot_write"]."</p>");
						print("<textarea rows=30 cols=5><".$config."></textarea><br />");
					} else {
						fwrite($file,"<".$config.">");
						fclose($file);
						print("<p>".$lang[$language]["config_success"]."</p>");						
					}
					$sql->execute_query("CREATE TABLE `web_settings`(
                        `key` varchar(50) NOT NULL default '',
                        `value` varchar(100) NOT NULL default '',
                        PRIMARY KEY  (`key`))
                        ENGINE=InnoDB
                        DEFAULT CHARSET=4
                        COMMENT='PLEASE DO NOT EDIT IF YOU DONT KNOW WHAT YOU ARE DOING!';",[]
                    );
					
					$sql->execute_query("INSERT INTO `web_settings` (`key`,`value`) VALUES
                        ('url', ?),
                        ('name', ?),
                        ('slogan', ?),
                        ('author', ?),
                        ('copyright', ?),
                        ('template', ?);",
                            [
                                $_POST["website_url"],
                                $_POST["website_name"],
                                $_POST["website_slogan"],
                                $_POST["website_author"],
                                $_POST["website_copyrights"],
                                $_POST["website_template"],
                            ]
                    );
					
                    $sql->execute_query("CREATE TABLE `web_users` (
                    `id` int NOT NULL AUTO_INCREMENT,
                    `admin` tinyint(1) DEFAULT '0',
                    `username` varchar(20) NOT NULL DEFAULT '',
                    `password` varchar(40) NOT NULL DEFAULT '',
                    `email` varchar(80) NOT NULL DEFAULT '',
                    `first_name` varchar(20) NOT NULL DEFAULT '',
                    `last_name` varchar(20) NOT NULL DEFAULT '',
                    `job_industry` varchar(50) NOT NULL DEFAULT '',
                    `homepage` varchar(80) NOT NULL DEFAULT '',
                    `blogaccess` tinyint(1) DEFAULT '0',
                    `newsaccess` tinyint(1) DEFAULT '0',
                    `diraccess` tinyint(1) DEFAULT '1',
                    `enabled` tinyint(1) DEFAULT '0',
                     PRIMARY KEY  (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");                        
                    $sql->execute_query("INSERT INTO `web_users` (`admin`,`username`,`password`,`email`,`first_name`,`last_name`,`enabled`) VALUES
                    (1,?,?,?,?,?,1);",
                        [
                            $_POST["admin_username"],
                            $_POST["admin_password"],
                            $_POST["admin_email"],
                            'Website',
                            'Administrator',
                        ]
                    );

					$sql->execute_query("CREATE TABLE `web_mainmenu` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `type` varchar(20) default NULL,
                        `text` varchar(50) NOT NULL default '', 
                        `title` varchar(50) NOT NULL default '', 
                        `url` varchar(250) NOT NULL default '', 
                        `priority` int(11) default '9999',
                        `page_id` int(11) default '0', 
                        PRIMARY KEY  (`id`))
                        ENGINE=InnoDB
                        DEFAULT CHARSET=utf8mb4;",
                        []
                    );
                    if($language == 'en'){
                        $sql->execute_query("INSERT INTO `web_mainmenu` (`id`,`type`,`text`,`title`,`url`,`priority`,`page_id`) VALUES
                            (1, 'admin', 'MENU', 'MENU ADMINISTRATION (MAIN, QUICK, FOOTER)', '/admin.php?page=menu&section=main', 100, 0),
                            (2, 'admin', 'PAGES', 'WEB PAGES ADMINISTRATION', '/admin.php?page=content&section=main', 200, 0),
                            (3, 'admin', 'NEWS', 'NEWS ADMINISTRATION', '/admin.php?module=news&section=main', 300, 0),
                            (4, 'admin', 'BLOG', 'BLOG ADMINISTRATION', '/admin.php?module=blog&section=main', 400, 0);",
                            []
                        );
                    } else {
                        $sql->execute_query("INSERT INTO `web_mainmenu` (`id`,`type`,`text`,`title`,`url`,`priority`,`page_id`) VALUES
                            (1, 'admin', 'Меню', 'Администратор на Менюта', '/admin.php?page=menu&section=main', 100, 0),
                            (2, 'admin', 'Страници', 'Администратор на Страниците', '/admin.php?page=content&section=main', 200, 0),
                            (3, 'admin', 'Новини', 'Администратор на Новините', '/admin.php?module=news&section=main', 300, 0),
                            (4, 'admin', 'Блог', 'Администратор на Блога', '/admin.php?module=blog&section=main', 400, 0);",
                            []
                        );
                    }    
					
					$sql->execute_query("CREATE TABLE `web_pages`(
                        `id` int(11) NOT NULL auto_increment,
                        `url` varchar(50) NOT NULL default '',
                        `title` varchar(150) NOT NULL default '',
                        `content` text NOT NULL,
                        `author` varchar(50) default NULL,
                        `custom` tinyint(1) default '0',
                        `parameters` varchar(255) default NULL,
                        `source` varchar(200) default NULL,
                        `moved` varchar(250) default NULL,
                        PRIMARY KEY  (`id`))
                        ENGINE=InnoDB 
                        DEFAULT CHARSET=utf8mb4;",
                        []
                    );					

                    $pages = [];
                    $pages["index.html"]=[
                        1,"index.html",
                        $language=="en" ? "Home Page" : "Начална страница",
                        $language=="en" ? "<h1>Home Page</h1><p>This is the home page of the website.</p>" : "<h1>Начална страница</h1><p>Това е началната страница на сайта.</p>",
                        "Auto Generated",0,'','',NULL
                    ];

                    $pages["contacts.html"]=[
                        2,"contacts.html",
                        $language=="en" ? "Contact Us" : "Свържете се с нас",
                        $language=="en" ? "<h1>Contact Us</h1>\r\n<ul><li>Name: </li><li>Phone: </li><li>Email: </li></ul>" : "<h1>Свържете се с нас</h1>\r\n<ul><li>Име: </li><li>Телефон: </li><li>Имейл: </li></ul>",
                        "Auto Generated",0,'','',NULL
                    ];

                    $pages["sitemap.html"]=[
                        3,"sitemap.html",
                        $language=="en" ? "Website Sitemap" : "Карта на сайта",
                        $language=="en" ? "<h1>Sitemap</h1><p>This is the sitemap of the website.</p>" : "<h1>Карта на сайта</h1><p>Това е карта на сайта.</p>",
                        "Auto Generated",1,'','sitemap.php',NULL
                    ];

                    
                    $sql->execute_query("INSERT INTO `web_pages` (`id`,`url`,`title`,`content`,`author`,`custom`,`parameters`,`source`,`moved`) VALUES
                    (?,?,?,?,?,?,?,?,?),
                    (?,?,?,?,?,?,?,?,?),
                    (?,?,?,?,?,?,?,?,?)",array_merge($pages["index.html"],$pages["contacts.html"],$pages["sitemap.html"]));


                    $sql->execute_query("CREATE TABLE `web_news` (
                    `id` int(11) NOT NULL auto_increment,
                    `url` varchar(200) NOT NULL default '',
                    `title` varchar(200) NOT NULL default '',
                    `content` text NOT NULL, 
                    `author` varchar(50) NOT NULL default '',
                    `date` datetime default NULL,
                    `comments` tinyint(1) default '0',
                    `frontpage` tinyint(1) default '1',
                    PRIMARY KEY  (`id`),
                    FULLTEXT KEY `title` (`title`,`content`))
                    ENGINE=InnoDB
                    DEFAULT CHARSET=utf8mb4;",
                    []);

                    $sql->execute_query("CREATE TABLE `web_blog` (
                    `id` int(11) NOT NULL auto_increment,
                    `url` varchar(200) NOT NULL default '',
                    `title` varchar(200) NOT NULL default '',
                    `content` text NOT NULL,
                    `author` varchar(50) NOT NULL default '',
                    `date` datetime default NULL,
                    `comments` tinyint(1) default '0',
                    `enabled` tinyint(1) default '0',
                    `frontpage` tinyint(1) default '1',
                    PRIMARY KEY  (`id`))
                    ENGINE=InnoDB 
                    DEFAULT CHARSET=utf8mb4;",
                    []);					

					print("<p>".$lang[$language]["installer_step2_success"]."</p>");										
					
                    $website_url=isset($_SERVER['HTTPS']) ? 'https'.'://'.$_SERVER['SERVER_NAME'] : 'http'.'://'.$_SERVER['SERVER_NAME'];
                    $admin_email=$_POST["admin_email"];
                    $admin_username=$_POST["admin_username"];
                    $admin_password=$_POST["admin_password"];

                    if($language == 'en'){
					$message="Website CMS was successfully installed on $website_url<br />
                    <br />Administrator URL: <a href=\"$website_url/admin.php\">$website_url/admin.php</a>
                    <br />Administrator Username: $admin_username
                    <br />Administrator Password: $admin_password
                    <br /><br />If you have any problems please contact our custommer support at support@seowebsitecms.com<br />";
					$subject = "Website CMS was successfully installed on $website_url";
                    print("<br /><h1>Website CMS installed Successfully!</h1><p>You can now login to the site administration panel and start adding content here: <a href=\"$website_url/admin.php\">$website_url/admin.php</a> with the admin login you provided on the previous page</p>");
					print("<p>Thank you for chosing Website CMS</p>");

                    } else {
                        $message="Уеб CMS беше успешно инсталиран на $website_url<br />
                        <br />Администраторският URL: <a href=\"$website_url/admin.php\">$website_url/admin.php</a>
                        <br />Администраторско име: $admin_username
                        <br />Администраторски парола: $admin_password
                        <br /><br />Ако имате някакви проблеми, моля свържете се с нашият техническа поддръжка на support@seowebsitecms.com<br />";
                        $subject = "Уеб CMS беше успешно инсталиран на $website_url";
                        print("<br /><h1>Уеб CMS беше успешно инсталиран на $website_url</h1><p>Можете сега да влезете в администраторския панел и да започнете да добавяте съдържание тук: <a href=\"$website_url/admin.php\">$website_url/admin.php</a> с администраторските данни, които сте предоставили на предишната страница</p>");
                        print("<p>Благодарим ви че избрахте Уеб CMS</p>");
                    }
                    $to  = $admin_email;
					$headers = 'From: system@'.$_SERVER["HOST_NAME"]."\r\n";
					$headers .= 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					mail($to, $subject, $message, $headers);										
					
					break;				
			}	
			print("</div></div></div>");
		}
	
        default : {
            ?>
<div class="container">
    <div class="row">
        <div class="col-12 col-md-12 col-xxl-8">
            <h1>Please Select Your Language:</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-6">
            <form name="step1" action="install.php" method="post">
                Choose Language: <select name="language">
                    <?php
                    $first=false;
                    foreach($lang as $key => $val){
                        if($first){
                            print("<option value=\"".$key."\" selected=\"selected\">".$val["language"]."</option>");
                        } else {
                            print("<option value=\"".$key."\">".$val["language"]."</option>");
                        }
                    }    
                    ?>
                </select><br/><br/>    
                <input type="hidden" name="installer" value="step1" />
                <input type="submit" value="Proceed to Step 1" />
            </form>
        </div>
    </div>
</div>            
            <?php
        }
	}
}
?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</body>    
</html>
