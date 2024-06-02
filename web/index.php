<?php
error_reporting(E_ALL^E_NOTICE);
include "./script/lib/main.php";
Router::loadRouteMap();
$p=user::LoginCheck(1);

$safereq=requestDecode();
$mypower=0;
$mypower=$p['power'];
theme::init();
include includePage(Router::GetScriptPath(Router::getUri(),$mypower));
