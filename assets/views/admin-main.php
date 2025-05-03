<?php
switch($this->GetVar("section")){
    default: {
        $page_info= [
            "title"=>"Admin Homepage",
            "content"=>"Homepage"
        ];
        echo $this->Generate_HTML($page_info);
        break;
    }
}