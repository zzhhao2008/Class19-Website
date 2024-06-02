
<?php

class student
{
    static public function getStulist()
    {
        return DB::getdata("student/g2022c19");
    }
    static public function getIdByName($name)
    {
        $list = DB::getdata("student/name2id");
        return $list[$name];
    }
    static public function getNamelist(){
        $list = DB::getdata("student/name2id");
        $name=array();
        foreach ($list as $key => $value) {
            $name[]=$key;
        }
        return $name;
    }
    static public function stuQuery($id)
    {
        $list = self::getStulist();
        return $list[$id] ? $list[$id] : $list[self::getIdByName($id)];
    }
    static public function Auth_ID_Name($id, $name)
    {
        return self::getIdByName($name) === md5($id);
    }
    static public function Stu_Auth($eid, $exam)
    {
        $id = $_POST['name'];
        
        if (user::read()['name']||user::login(student::getIdByName($id),$_POST['ID'])) { //已登录
            $me = user::read()['profile'];
            $id = $me['name'];
            if (isset($exam['sco'][$id]) || $exam['AnyWay'] === '1') {
                $yx = 1;
            }
            $login = 1;
        } else {
            $login = 0;
        }
        
        if (!$_POST['ID'] || $yx);
        else if (user::queryUser(md5($_POST['ID']))) { //检测账号是否绑定
            $mes = "该身份证已绑定账号，请登录后再试！";
            student::Auth_ID_Name($_POST["ID"], $_POST["name"]);
        } else if (
            student::Auth_ID_Name($_POST["ID"], $_POST["name"])
            &&
            (isset($exam['sco'][$_POST['name']]) || $exam['AnyWay'] === '1')
        ) { //底层校验
            $yx = 1;
            $id = $_POST['name'];
        } else {
            $mes = "信息有误，请重新输入";
        }
        return array("mes" => $mes,"login" => $login, "yx" => $yx, "id" => $id);
    }
}
