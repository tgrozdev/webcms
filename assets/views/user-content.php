<?php

$lang["bg"]=[
    "title"=>"Администратор на Съдържание",
    "content_manager"=>"Админ Панел за Съдържание",
    "error_resizing"=>"ГРЕШКА: Грешка при оптимизиране на изображението!",
    "error_title"=>"ГРЕШКА: Трябва да има заглавие! Друг вариант няма!",
    "error_content"=>"ГРЕШКА: Трябва да има съдържание! Друг вариант няма!",
    "error_found"=>"ГРЕШКА: Oткрити са Грепки! Моля, коригирайте ги и опитайте отново!",
    "content_added"=>"Съдържанието беше успешно добавено!",
    "content_not_added"=>"Съдържанието не беше успешно добавено!",
    "content_updated"=>"Съдържанието беше успешно обновено!",
    "content_not_updated"=>"Съдържанието не беше успешно обновено!",
    "back_to_content"=>"Върни се към Админ Панела за Съдържание",
    "back_to_main"=>"Върни се към Главния Админ Панел",
    "blog"=>"Блог",
    "news"=>"Новини",
    "page"=>"Страница",
    "add_more_content"=>"Добави още съдържание"
];
$lang["en"]=[
    "title"=>"Content Admin",
    "content_manager"=>"Content Admin",
    "error_resizing"=>"ERROR: Image resizing error!",
    "error_title"=>"ERROR: MUST HAVE TITLE!! NO OTHER OPTION!",
    "error_content"=>"ERROR: MUST HAVE CONTENT OR CUSTOM URL! NOT RECOMMENDED TO PLACE EMPTY PAGES!",
    "error_found"=>"ERROR: Errors found! Please correct them and try again!",
    "content_added"=>"Content added successfully!",
    "content_not_added"=>"Content not added!",
    "content_updated"=>"Content updated successfully!",
    "content_not_updated"=>"Content not updated!",
    "back_to_content"=>"Back to the Content Administration Panel",
    "back_to_main"=>"Back to the Main Administration Panel",
    "blog"=>"Blog",
    "news"=>"News",
    "page"=>"Page",
    "add_more_content"=>"Add more content"
];

$tags["[{MESSAGE}]"] = "";
$tags["[{MAXPOSTSIZE}]"] = $this->getMaximumFileUploadSize();
switch ($this->GetVar("section")) {
    case "upload": {                    
        $filename = $this->AJAXUpload();
        //optimize image?        
        $res=$this->ResizePhoto("uploads/".$filename, "uploads/".$filename, 800);
        if(!$res){ $this->ReturnResponse(false, [], $lang[$this->config["lang"]]["error_resizing"]); }            

        $page = $this->GetVar("pageid");
        if($page == "0"){ $page = $this->GetNextID("web_pages"); }        
        $query="INSERT INTO `web_uploads` (`page`,`filename`) VALUES (?,?);";
        $this->Query($query,[$page,$filename]);
        
        $this->ReturnResponse(true,["fullpath"=>"/uploads/".$filename,"filename"=>$filename]);
        break;
    }
    case "post": {

            $error = false;
            $author = !empty($_POST["author"]) ? $_POST["author"] : "Anonymous";
            //--------------------------------------------------------------------

            if (empty($_POST["title"])) {
                // MUST HAVE TITLE!! NO OTHER OPTION!
                $error = true;
                $tags["[{MESSAGE}]"] .= "<br>".$lang[$this->config["lang"]]["error_title"]."<br>";
            } else {
                $title = strlen($_POST["title"]) > 180 ? substr($_POST["title"], 0, 180) : $_POST["title"];
                $url = $this->SafeURL($title).".html";
            }

            //--------------------------------------------------------------------

            if (empty($_POST["content"]) && empty($_POST["custom_url"])) {
                $error = true;
                $tags["[{MESSAGE}]"].= "<br>".$lang[$this->config["lang"]]["error_content"]."<br>";
            } else {
                $content = $_POST["content"];
            }

            //--------------------------------------------------------------------
            if ($error) {
                $tags["[{MESSAGE}]"] .= "<br>".$lang[$this->config["lang"]]["error_found"]."<br>";
            } else {
                $source = empty($_POST["source"]) ? NULL : $_POST["source"];
                $parameters = empty($_POST["parameters"]) ? NULL : $_POST["parameters"];
                $custom = !empty($source) && !empty($parameters) ? 1 : 0;

                $comments = isset($_POST["comments"]) ? 1 : 0;

                // THIS IS DONE CORRECTLY AND WE HAVE TO SAVE EVERYTING TO DATABASE!
                if ($_POST["id"] == "0") {
                    $result=$this->Query("INSERT INTO `web_pages` (`creator`,`type`,`url`,`title`,`content`,`date`,`author`,`comments`,`custom`,`parameters`,`source`) VALUES
                        (?,?,?,?,?,?,?,?,?,?,?);", [ $this->user["id"],$_POST["type"], $url, $title, $content, $_POST["date"], $author, $comments, $custom, $parameters, $source]);
                    if($result){
                        $tags["[{MESSAGE}]"] .= "<br>".$lang[$this->config["lang"]]["content_added"]."<br><br>";
                    } else {
                        $tags["[{MESSAGE}]"] .= "<br>".$lang[$this->config["lang"]]["content_not_added"]."<br><br>";
                    }                        
                } else {
                    $tags["[{MESSAGE}]"] .= "UPDATE CONTENT PAGE ID " . $_POST["id"] . "<br>";
                    $result=$this->Query(
                        "UPDATE `web_pages` SET `type`=?,`url`=?,`title`=?,`content`=?,`date`=?,`author`=?,`comments`=?,`custom`=?,`parameters`=?,`source`=? WHERE `id`=? AND `creator`=?",
                        [$_POST["type"], $url, $title, $content, $_POST["date"], $author, $comments, $custom, $parameters, $source, $_POST["id"],$this->user["id"]]
                    );
                    if($result){
                        $tags["[{MESSAGE}]"] .= "<br>".$lang[$this->config["lang"]]["content_updated"]."<br><br>";
                    } else {
                        $tags["[{MESSAGE}]"] .= "<br>".$lang[$this->config["lang"]]["content_not_updated"]."<br><br>";
                    }
                }

                $tags["[{MESSAGE}]"] .= "<br><br><a href=\"/admin.php?page=content\">".($lang[$this->config["lang"]]["back_to_content"])."</a><br>";
                $tags["[{MESSAGE}]"] .= "<br><a href=\"/admin.php\">".($lang[$this->config["lang"]]["back_to_main"])."</a><br>";
            }
            $tags["[{TITLE}]"] = $lang[$this->config["lang"]]["content_manager"];
            $tags["[{MAINCONTENT}]"] = $tags["[{MESSAGE}]"];
            unset($tags["[{MESSAGE}]"]);
            echo $this->LoadHTML($tags);
            break;
        }

        //====================================================================================
        // EDIT NEWS RELEASE BELOW
        //====================================================================================
    case "edit": {
            $result = $this->Query("select * from `web_pages` where `id`=? AND `creator`=? limit 1", [$this->GetVar("id"),$this->user["id"]]);
            $page = current($result);

            $tags["[{ID}]"] = $page["id"];
            $tags["[{URL}]"] = $page["url"];
            $tags["[{CONTENT-TITLE}]"] = $page["title"];
            $tags["[{CONTENT}]"] = $page["content"];
            $tags["[{AUTHOR}]"] = $page["author"];
            
            $tags["[{DATE}]"] = $page["date"];
            $tags["[{URL}]"] = $page["url"];
            $tags["[{CUSTOM}]"] = $page["custom"] ?? "";
            $tags["[{SOURCE}]"] = $page["source"] ?? "";            
            $tags["[{PARAMETERS}]"] = $page["parameters"] ?? "";

            if($page["type"] == "blog"){
                $tags["[{TYPES}]"] = "<option value='blog' selected=\"selected\">".($lang[$this->config["lang"]]["blog"])."</option><option value='news'>".($lang[$this->config["lang"]]["news"])."</option><option value='page'>".($lang[$this->config["lang"]]["page"])."</option>";
            } else if($page["type"] == "news"){
                $tags["[{TYPES}]"] = "<option value='blog'>".($lang[$this->config["lang"]]["blog"])."</option><option value='news' selected=\"selected\">".($lang[$this->config["lang"]]["news"])."</option><option value='page'>".($lang[$this->config["lang"]]["page"])."</option>";
            } else {
                $tags["[{TYPES}]"] = "<option value='blog'>".($lang[$this->config["lang"]]["blog"])."</option><option value='news'>".($lang[$this->config["lang"]]["news"])."</option><option value='page' selected=\"selected\">".($lang[$this->config["lang"]]["page"])."</option>";
            }

            $result = $this->Query("SELECT * FROM `web_uploads` WHERE `page`=?;", [$page["id"]]);
            $tags["[{IMAGES}]"] = "";
            foreach($result as $row){ 
                $tags["[{IMAGES}]"] .= "<img class=\"img-fluid\" style=\"max-width: 400px;\" src=\"/uploads/".$row["filename"]."\" ><br/>URL: /uploads/".$row["filename"]."<br/>";
            }

            $tags["[{SECTION}]"]="post";

            $this->head_includes .= '<link href="/assets/styles/style.css" rel="stylesheet"><script src="/assets/scripts/wyzz.js"></script>';
            $this->footer_includes .= '<script src="/assets/scripts/content.js"></script>';
            $tags["[{TITLE}]"] = $lang[$this->config["lang"]]["content_manager"];            
            echo $this->LoadHTML($tags,"content");
            break;
        }

        //====================================================================================
        // ADD NEW NEWS RELEASE BELOW
        //====================================================================================

    case "new": {
            $tags["[{ID}]"] = "0";
            $tags["[{CONTENT-TITLE}]"] = "";
            $tags["[{CONTENT}]"] = "";
            $tags["[{AUTHOR}]"] = $this->user["first_name"] . " " . $this->user["last_name"];
            $tags["[{DATE}]"] = date("Y-m-d");
            $tags["[{URL}]"] = "";
            $tags["[{SOURCE}]"] = "";
            $tags["[{PARAMETERS}]"] = "";
            $tags["[{IMAGES}]"] = "";

            $tags["[{TYPES}]"] = "<option value='blog'>".($lang[$this->config["lang"]]["blog"])."</option><option value='news'>".($lang[$this->config["lang"]]["news"])."</option><option value='page' selected=\"selected\">".($lang[$this->config["lang"]]["page"])."</option>";

            $tags["[{SECTION}]"]="post";
            $this->head_includes .= '<link href="/assets/styles/style.css" rel="stylesheet"><script src="/assets/scripts/wyzz.js"></script>';
            $this->footer_includes .= '<script src="/assets/scripts/content.js"></script>';
            $tags["[{TITLE}]"] = $lang[$this->config["lang"]]["content_manager"];
            
            echo $this->LoadHTML($tags,"content");
            break;
        }
        case "delete": {            
            $result=$this->Query("DELETE FROM `web_pages` WHERE `id`=? AND `creator`=? limit 1",[$this->GetVar("id"),$this->user["id"]  ]);
            //$this->ReturnResponse($result);
            //break;
        }

    default: {
            $content = "<a href=\"/admin.php?page=content&section=new\">".($lang[$this->config["lang"]]["add_more_content"])."</a><br><br>";
            $content .= '<table width="100%"><tr><td>action</td><td>URL</td></tr>';
            $result = $this->Query("select * from `web_pages` where `creator`=? and `moved` is NULL order by `type` desc, `date` desc",[$this->user["id"]]);
            foreach ($result as $row) {                
                    $content .= "<tr><td><a onclick=\"javascript:return confirm('Are you sure you want to delete this page ?')\" href=\"/admin.php?page=content&section=delete&id=" . $row["id"] . "\"><img src=\"/assets/images/delete-small.jpg\" alt=\"DELETE PAGE\"></a></td><td><a href=\"/admin.php?page=content&section=edit&id=" . $row["id"] . "\">" . strtoupper($row["type"]) . " - " . $row["title"] . "</a><br>Author : " . $row["author"] . " | URL: /".strtolower($row["type"]) . "/" . $row["url"] . " </td></tr>";
            }
            $content .= "</table>";
            $tags["[{TITLE}]"] = $lang[$this->config["lang"]]["content_manager"];
            $tags["[{MAINCONTENT}]"] = $content;
            echo $this->LoadHTML($tags);
            break;
        }
}
