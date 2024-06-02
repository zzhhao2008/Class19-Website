<?php
$GLOBALS['username'] = $_COOKIE['login_name_class19'];
$GLOBALS['userprofile'] = array();
$emptycfg = array(
    'nick' => '',
    'password' => '',
    'llt' => time(),
    'power' => 1,
    'rating' => 1500,
    'email' => '',
    'about' => '',
    'dt' => array()
);
class user
{
    /**
     * 获取（已登录）用户的信息
     * @return array('name'=>"<USERID>","profile"=>"<USERPROFILE>")
     */
    public static function read()
    {
        return array("name" => $GLOBALS['username'], "profile" => $GLOBALS['userprofile']);
    }
    /**
     * 查询用户信息
     * @input username=><USERID>
     * @return userprofile
     */
    public static function queryUser($username)
    {
        $uprofile = DB::getdata("user/$username");
        if (empty($uprofile) || $uprofile['unlink'] === 1 || $uprofile['ban'] === 1) {
            return 0;
        }
        return $uprofile;
    }
    /**
     * 查询用户昵称
     * @input username=><USERID>,html=><是否需要使用HTML格式展示>，link=><是否需要使用链接格式展示>
     * @return string=>用户昵称
     */
    public static function queryUserNick($username, $html = 0, $link = 0)
    {
        $uprofile = DB::getdata("user/$username");
        if (empty($uprofile) || $uprofile['unlink'] === 1 || $uprofile['ban'] === 1) {
            return "UNK";
        }
        if ($html == 1) {
            if ($link == 1) {
                return "<a target='_blank' href='/visituser?uid=$username'>" . "<span class='text-" . user::NickColor($uprofile) . "'>" . $uprofile['nick'] . "</span>" . "</a>";
            }
            return  "<span class='text-" . user::NickColor($uprofile) . "'>" . $uprofile['nick'] . "</span>";
        }
        return $uprofile['nick'];
    }
    /**
     * 获取用户昵称的颜色，对应Bootstrap组件颜色，输入值为用户配置文件
     * 一般不单独使用
     */
    public static function NickColor($user)
    {
        /*
        Admin:danger
        User:primary
        R<1000 muted
        R>2500 info
        Baned warning
        VIP/leader/doctor success
        */
        if ($user['power'] >= 2) {
            return "danger";
        }
        if ($user['doctor'] == 1) {
            return "success";
        }
        if ($user['rating'] > 2500) {
            return "info";
        }
        if ($user['rating'] < 1000) {
            return "muted";
        }
        if ($user['ban'] == 1) {
            return "warning";
        }
        return "primary";
    }
    /**
     * 强制获取用户信息
     */
    public static function queryUserAdmin($username)
    {
        $uprofile = DB::getdata("user/$username");
        return $uprofile;
    }
    /**
     * 登录状态检查
     * init=>是否需要初始化
     * @return array 用户配置文件
     */
    public static function LoginCheck($init = 0)
    {
        $name = user::read()['name'];
        $thisup = user::queryUser($name);
        if ($_COOKIE['login_pas_class19'] === md5($thisup['llt'] . $thisup['password'])) {
            if ($init === 1) $GLOBALS['userprofile'] = $thisup;
            return $thisup;
        } else {
            return 0;
        }
    }

    /**
     * 登录函数
     * @return bool 是否登录成功
     */
    public static function login($name, $pas)
    {
        $cfg = user::queryUser($name);
        if ($cfg === 0) {
            return 0;
        }
        if ($cfg['password'] === md5($pas)) {
            $GLOBALS['userprofile'] = $cfg;
            $GLOBALS['userprofile']['llt'] = time();
            $pass = md5($GLOBALS['userprofile']['llt'] . $GLOBALS['userprofile']['password']);
            setcookie("login_name_class19", $name, time() + 3600 * 48, "/");
            setcookie("login_pas_class19", $pass, time() + 3600 * 48,"/");
            $GLOBALS['username'] = $name;
            return user::saveuserchange(1);
        } else {
            return 0;
        }
    }
    /**
     * 修改用户的配置文件 $savenow=>是否立即保存
     */
    public static function change($key, $value, $savenow = 0): bool
    {
        $GLOBALS['userprofile'][$key] = $value;
        return $savenow ? user::saveuserchange() : 1;
    }
    public static function change_intval($key, $value, $savenow = 0): bool
    {
        $GLOBALS['userprofile'][$key] += $value;
        return $savenow ? user::saveuserchange() : 1;
    }
    /**
     * 给用户配置文件的某个键值添加一个新元素
     */
    public static function change_Add($key, $value, $savenow = 0): bool
    {
        $GLOBALS['userprofile'][$key][] = $value;
        return $savenow ? user::saveuserchange() : 1;
    }
    /**
     * 保存对用户的更改
     * 一般配合change()使用
     * @return int 0=>失败 1=>成功
     */
    public static function saveuserchange($logining = 0)
    {
        if (user::LoginCheck() || $logining) {
            DB::putdata("user/" . user::read()['name'], user::read()['profile']);
            return 1;
        }
        return 0;
    }
    /**
     * 读取用户的配置文件，忘记有啥用了
     */
    public static function getMy($key)
    {
        $res = $GLOBALS['userprofile'][$key];
        return $res;
    }
    public static function saveuserprofie($userid, $cfg)
    {
        $y = DB::getdata("user/$userid");
        if (empty($y) || !is_array($cfg)) return 0;
        else return DB::putdata("user/$userid", $cfg);
    }
    /**
     * 获取当前用户等级
     */
    public static function is_superuser()
    {
        return user::read()['profile']['power'] > 1;
    }
    public static function is_superuserO($id)
    {
        return self::queryUser($id)['power'] > 1;
    }
}

