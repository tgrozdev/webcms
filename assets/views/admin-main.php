<?php
$lang["bg"]=[   
    "title"=>"Админ Панел",
];
$lang["en"]=[
    "title"=>"Admin Dashboard",
];
switch($this->GetVar("section")){
    default: {
        $tags["[{TITLE}]"]=$lang[$this->config["lang"]]["title"];        
        echo $this->LoadHTML($tags,"dashboard");
    }
}