<?php
$content = "";
switch ($this->GetVar("section")) {
        //====================================================================================
        // PROCESS THE INPUT BELOW
        //====================================================================================

    case "postnew": {
            $error = false;
            $author = !empty($_POST["author"]) ? $_POST["author"] : "Anonymous";
            //--------------------------------------------------------------------

            if (empty($_POST["title"])) {
                // MUST HAVE TITLE!! NO OTHER OPTION!
                $error = true;
                $content .= "<br>ERROR: MUST HAVE TITLE!! NO OTHER OPTION!<br>";
            } else {
                $title = strlen($_POST["title"]) > 180 ? substr($_POST["title"], 0, 180) : $_POST["title"];
                $url = empty($_POST["url"]) ? $this->SafeURL($title) : $_POST["url"];
                $title = $this->EncodeQuotes($title);
            }

            //--------------------------------------------------------------------

            if (empty($_POST["short_content"]) && empty($_POST["long_content"])) {
                $error = true;
                $content .= "<br>ERROR: PLEASE ENTER SHORT AND LONG CONTENT OF NEWS ARTICLE! ITS NOT RECOMENDED TO PLACE EMPTY PAGES!<br>";
            } else {
                $short_content = $_POST["short_content"];
                $long_content = $_POST["long_content"];
            }

            if ($error) {
                $content .= "<br>ERRORS FOUND: PLEASE CORRECT THEM AND CLICK SAVE AGAIN<br>";
            } else {
                // THIS IS DONE CORRECTLY AND WE HAVE TO SAVE EVERYTING TO DATABASE!
                if ($_POST["id"] == "0") {
                    $this->Query("INSERT INTO `web_blog` (`url`,`title`,`content_short`,`content_long`,`author`,`date`) VALUES
                        (?,?,?,?,?,?);", [$url, $title, $short_content, $long_content, $author, $_POST["date"]]);
                    $content .= "<p class=\"notify\">BLOG ENTRY ADDED!</p>";
                } else {
                    $content .= "UPDATE CONTENT NEWS ID " . $_POST["id"] . "<br>";
                    $this->Query(
                        "UPDATE `web_blog` SET `author`=?,`url`=?,`content_short`=?,`content_long`=?,`date`=? WHERE `id`=?",
                        [$author, $url, $short_content, $long_content, $_POST["date"], $_POST["id"]]
                    );
                    $content .= "<p class=\"notify\">BLOG ENTRY UPDATED!</p>";
                }

                $content .= "<br><br><a href=\"/admin.php?page=blog\">BACK TO THE BLOG ADMINISTRATION PANEL</a><br>";
                $content .= "<br><a href=\"/admin.php\">BACK TO THE MAIN ADMINISTRATION PANEL</a><br>";
            }
            $page_info = [
                "title" => "BLOG MANAGER : WEB ADMIN",
                "content" => $content
            ];
            echo $this->Generate_HTML($page_info);
            break;
        }

        //====================================================================================
        // EDIT BLOG POSTS BELOW
        //====================================================================================
    case "edit": {
            $result = $this->Query("select * from `web_blog` where `id`=? limit 1", [$_GET["id"]]);
            $page = current($result);

            $tags = [];
            $tags["[{ID}]"] = $page["id"];
            $tags["[{URL}]"] = $page["url"];
            $tags["[{TITLE}]"] = $page["title"];
            $tags["[{SHORT_CONTENT}]"] = $page["content_short"];
            $tags["[{LONG_CONTENT}]"] = $page["content_long"];
            $tags["[{AUTHOR}]"] = $page["author"];
            $tags["[{DATE}]"] = $page["date"];

            $content .= $this->LoadPage("assets/html/admin-blog-edit.html", $tags);
            $this->head_includes .= '<link href="/assets/styles/style.css" rel="stylesheet"><script src="/assets/scripts/wyzz.js"></script>';
            $page_info = [
                "title" => "Content Editor :: CMS Admin",
                "content" => $content
            ];
            echo $this->Generate_HTML($page_info);
            break;
        }

        //====================================================================================
        // ADD NEW NEWS RELEASE BELOW
        //====================================================================================

    case "addnew": {
            $tags = [];
            $tags["[{ID}]"] = "0";
            $tags["[{TITLE}]"] = "";
            $tags["[{SHORT_CONTENT}]"] = "";
            $tags["[{LONG_CONTENT}]"] = "";
            $tags["[{AUTHOR}]"] = "";
            $tags["[{URL}]"] = "";            
            $tags["[{DATE}]"] = date("Y-m-d");

            $content .= $this->LoadPage("assets/html/admin-blog-edit.html", $tags);
            $this->head_includes .= '<link href="/assets/styles/style.css" rel="stylesheet"><script src="/assets/scripts/wyzz.js"></script>';
            $page_info = [
                "title" => "BLOG Editor :: CMS Admin",
                "content" => $content
            ];
            echo $this->Generate_HTML($page_info);
            break;
        }

        //====================================================================================
        // DELETE NEWS RELEASE BELOW
        //====================================================================================
    case "delete": {
            $this->Query("DELETE FROM `web_blog` WHERE `id`=? limit 1",[$_GET["id"]]);
            $content.="<p class=\"warning\">PAGE DELETED!</p>";
        }
    default: {
            $content .= "<a href=\"/admin.php?page=blog&section=addnew\">CLICK HERE TO ADD BLOG POST</a><br><br>";
            $content .= '<table width="100%"><tr><td>action</td><td>URL</td></tr>';
            $result = $this->Query("select `id`,`url`,`title`,`author`,`date`,`frontpage` from `web_blog`");
            foreach ($result as $row) {
                $content .= "<tr><td><a href=\"/admin.php?page=blog&section=addfrontpage&id=" . $row["id"] . "\"><img src=\"/assets/images/add-small.jpg\" alt=\"Add to Front Page\"></a> <a href=\"/admin.php?page=blog&section=edit&id=" . $row["id"] . "\"><img src=\"/assets/images/edit-small.png\" alt=\"EDIT PAGE\"></a> <a onclick=\"javascript:return confirm('Are you sure you want to delete this page ?')\" href=\"/admin.php?page=blog&section=delete&id=" . $row["id"] . "\"><img src=\"/assets/images/delete-small.jpg\" alt=\"DELETE PAGE\"></a></td><td><a href=\"/news/" . $row["url"] . "\">" . $row["title"] . "</a><br>Date : " . $row["date"] . " | Author : " . $row["author"] . " | URL: /blog/" . $row["url"] . " </td></tr>";
            }
            $content .= "</table>";
            $page_info = [
                "title" => "Admin BLOG Manager",
                "content" => $content
            ];
            echo $this->Generate_HTML($page_info);
            break;
        }
}
