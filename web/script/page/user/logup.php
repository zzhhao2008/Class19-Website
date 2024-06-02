<?php
function emptycheck(){
    return empty($_POST['nickname'])||empty($_POST['id']);
}
$alert="";
if ($_POST['username']) {
    $id = md5($_POST['id']);
    if (user::queryUser($id)) {
        $alert="该用户名已被使用";
    }elseif(emptycheck()){
        $alert="请不要空项";
    }
    elseif(!student::Auth_ID_Name($_POST['id'],$_POST['username'])){
        $alert="身份信息有误";
    }
    else {
        $thiscfg = $emptycfg;
        $thiscfg['nick'] = $_POST['nickname'];
        $thiscfg['name'] = $_POST['username'];
        $passwrd=$_POST['password']?$_POST['password']:$_POST['id'];
        $thiscfg['password'] = md5($passwrd);
        DB::putdata("user/$id", $thiscfg);
        echo user::login($id,$passwrd);
        jsjump("/profile");
    }
    view::alert($alert,"warning");
}
?>
<?php view::header("注册"); ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <img src="/icon.jpg" style="height: 100px;border-radius:5px">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">姓名</label>
                        <input type="text" class="form-control" id="username" name="username" aria-describedby="usernameHelp">
                        <div id="usernameHelp" class="form-text">请输入姓名</div>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">昵称</label>
                        <input type="text" class="form-control" id="nickname" name="nickname" aria-describedby="usernameHelp">
                        <div id="usernameHelp" class="form-text">请输入昵称</div>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">身份证号</label>
                        <input type="text" class="form-control" id="email" name="id" aria-describedby="usernameHelp">
                        <div id="usernameHelp" class="form-text">请输入身份证号</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">密码</label>
                        <input type="password" class="form-control" id="password" name="password" aria-describedby="passwordHelp">
                        <div id="passwordHelp" class="form-text">请输入密码，如果留空则设置为身份证号</div>
                    </div>
                    <div class="mb-3">
                        <?=$alert?>
                    </div>
                    <button type="submit" class="btn btn-primary">注册</button>
                    <a type="button" href="/profile" class="btn btn-default">登录</a>
                </form>
            </div>
            <p>说明：如果你想加密自己的成绩，请设置密码，否则，请不要设置，将自动默认为身份证号</p>
        </div>
    </div>
</div>
<?php view::foot(); ?>