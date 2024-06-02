<?php
if($_POST['username']){
    $id=student::getIdByName($_POST['username']);
    if(user::login($id,$_POST['password'])){
        jsjump("?");
        exit;
    }else{
        alert("错误");
    }
}
?>
<?php view::header("登录"); ?>

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
                        <label for="password" class="form-label">密码</label>
                        <input type="password" class="form-control" id="password" name="password" aria-describedby="passwordHelp">
                        <div id="passwordHelp" class="form-text">请输入密码</div>
                    </div>
                    <button type="submit" class="btn btn-primary">登录</button>
                    <a type="button" href="/logup" class="btn btn-default">注册</a>
                </form>
            </div>
        </div>
    </div>
</div>
    <?php view::foot(); ?>