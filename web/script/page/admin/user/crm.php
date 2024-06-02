<?php
function back()
{
    global $cuser, $cuid;
    user::saveuserprofie($cuid, $cuser);
    jsjump("/user_manage");
}
if ($_GET['uid']) {
    $cuser = user::queryUserAdmin($_GET['uid']);
    $cuid = $_GET['uid'];
}
if (empty($cuser)) {
    alert("ERROR:UID");
    jsjump("/user_manage");
}
switch ($_GET['m']) {
    case "rm":
        $cuser['unlink'] = 1;
        back();
        break;
    case "ban":
        if ($cuser['ban'] === 1) $cuser['ban'] = 0;
        else $cuser['ban'] = 1;
        back();
        break;
    case "ca":
        if ($cuser['power'] === 2) $cuser['power'] = 1;
        else $cuser['power'] = 2;
        back();
        break;
}
