<?php
$lang["bg"]=[
    "title"=>"Потребителски Панел",
];
$lang["en"]=[
    "title"=>"User Dashboard",
];
switch($this->GetVar("section")){
    default: {
        $tags["[{TITLE}]"]=$lang[$this->config["lang"]]["title"];        
        echo $this->LoadHTML($tags,"dashboard");
    }
}