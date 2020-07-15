<?php
$currenturl=$_SERVER['REQUEST_URI'];
if(strpos($currenturl, DOMAINE)!==NULL) $currenturl=substr($currenturl,strlen(DOMAINE));
if(strrpos($currenturl, "?")) $currenturl=substr($currenturl, 0,strrpos($currenturl, "?")-strlen($currenturl));
if(strrpos($currenturl, "/")==strlen($currenturl)-1) $currenturl=substr($currenturl,0,strlen($currenturl)-1);
function check($path=""){
    global $currenturl;
    if($currenturl===$path){
        return true;
    }else{
        return false;
    }
}
function has($path=""){
    global $currenturl;
    if(strpos($currenturl."/", $path."/")===0){
        return true;
    }else{
            return false;
    }
    }