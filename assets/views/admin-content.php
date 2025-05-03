<?php
$content = "";
switch ($this->GetVar("section")) {
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

            if (empty($_POST["content"]) && empty($_POST["custom_url"])) {
                $error = true;
                $content .= "<br>ERROR: PLEASE DEFINE CONTENT CUSTOM URL OR TYPE CONTENT BELOW!! ITS NOT RECOMENDED TO PLACE EMPTY PAGES!<br>";
            } else {
                $add_content = $_POST["content"];
            }

            //--------------------------------------------------------------------
            if ($error) {
                $content .= "<br>ERRORS FOUND: PLEASE CORRECT THEM AND CLICK SAVE AGAIN<br>";
            } else {
                $source = !empty($_POST["source"]) ?: NULL;
                // THIS IS DONE CORRECTLY AND WE HAVE TO SAVE EVERYTING TO DATABASE!
                if ($_POST["id"] == "0") {
                    $this->Query("INSERT INTO `web_pages` (`source`, `url`,`title`,`content`,`author`) VALUES
                        (?,?,?,?,?);", [ $source, $url, $title, $add_content, $author]);
                    $content .= "<br>CONTENT PAGE ADDED!<br><br>";
                } else {
                    $content .= "UPDATE CONTENT PAGE ID " . $_POST["id"] . "<br>";
                    $this->Query(
                        "UPDATE `web_pages` SET `source`=?,`author`=?,`url`=?,`content`=? WHERE `id`=?",
                        [$source, $author, $url, $add_content, $_POST["id"]]
                    );
                    $content .= "<br>CONTENT PAGE UPDATED!<br><br>";
                }

                $content .= "<br><br><a href=\"/admin.php?page=content\">BACK TO THE CONTENT ADMINISTRATION PANEL</a><br>";
                $content .= "<br><a href=\"/admin.php\">BACK TO THE MAIN ADMINISTRATION PANEL</a><br>";
            }
            $page_info = [
                "title" => "Admin Homepage",
                "content" => $content
            ];
            echo $this->Generate_HTML($page_info);
            break;
        }

        //====================================================================================
        // EDIT NEWS RELEASE BELOW
        //====================================================================================
    case "edit": {
            $result = $this->Query("select * from `web_pages` where `id`=? limit 1", [$_GET["id"]]);
            $page = current($result);

            $tags = [];
            $tags["[{ID}]"] = $page["id"];
            $tags["[{URL}]"] = $page["url"];
            $tags["[{TITLE}]"] = $page["title"];
            $tags["[{CONTENT}]"] = $page["content"];
            $tags["[{AUTHOR}]"] = $page["author"];
            $tags["[{SOURCE}]"] = $page["source"] ?? "";

            $content .= $this->LoadPage("assets/html/admin-content-edit.html", $tags);
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
            $tags["[{CONTENT}]"] = "";
            $tags["[{AUTHOR}]"] = "";
            $tags["[{URL}]"] = "";
            $tags["[{SOURCE}]"] = "";

            $content .= $this->LoadPage("assets/html/admin-content-edit.html", $tags);
            $this->head_includes .= '<link href="/assets/styles/style.css" rel="stylesheet"><script src="/assets/scripts/wyzz.js"></script>';
            $page_info = [
                "title" => "Content Editor :: CMS Admin",
                "content" => $content
            ];
            echo $this->Generate_HTML($page_info);
            break;
        }

        //====================================================================================
        // DELETE NEWS RELEASE BELOW
        //====================================================================================
    case "delete": {
            $this->Query("DELETE FROM `web_pages` WHERE `id`=? limit 1",[$_GET["id"]]);
            $content.="<p class=\"warning\">PAGE DELETED<p>";
        }
    default: {
            $content .= "<a href=\"/admin.php?page=content&section=addnew\">CLICK HERE TO ADD MORE CONTENT</a><br><br>";
            $content .= '<table width="100%"><tr><td>action</td><td>URL</td></tr>';
            $result = $this->Query("select `id`,`url`,`title`,`author`,`moved` from `web_pages`");
            foreach ($result as $row) {
                if (!is_null($row["moved"])) {
                    // do nothing
                } else {
                    $content .= "<tr><td><a href=\"/admin.php?page=content&section=edit&id=" . $row["id"] . "\"><img src=\"/assets/images/edit-small.png\" alt=\"EDIT PAGE\"></a> <a onclick=\"javascript:return confirm('Are you sure you want to delete this page ?')\" href=\"/admin.php?page=content&section=delete&id=" . $row["id"] . "\"><img src=\"/assets/images/delete-small.jpg\" alt=\"DELETE PAGE\"></a></td><td><a href=\"/" . $row["url"] . "\">" . $row["title"] . "</a><br>Author : " . $row["author"] . " | URL: /" . $row["url"] . " </td></tr>";
                }
            }
            $content .= "</table>";
            $page_info = [
                "title" => "Admin Homepage",
                "content" => $content
            ];
            echo $this->Generate_HTML($page_info);
            break;
        }
}
