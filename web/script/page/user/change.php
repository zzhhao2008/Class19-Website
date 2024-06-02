<?php
if ($_POST['nick']) {
    user::change("about", $_POST['about']);
    user::change("nick", $_POST['nick']);
    if($_POST['password']){
        user::change("password",md5($_POST['password']));
    }
    user::saveuserchange();
    jsjump("/profile");
}
?>
<?php view::header("修改用户信息"); ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <img src="/icon.jpg" style="height: 100px;border-radius:5px">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">昵称</label>
                        <input type="text" class="form-control" id="username" name="nick" value="<?= user::read()['profile']['nick'] ?>" aria-describedby="usernameHelp">
                        <div id="usernameHelp" class="form-text">请输入昵称</div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">密码</label>
                        <input type="password" class="form-control" id="password" name="password" aria-describedby="passwordHelp">
                        <div id="passwordHelp" class="form-text">请输入密码,不填则不修改</div>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea" name="about"><?= user::read()['profile']['about'] ?></textarea>
                        <label for="floatingTextarea">个人介绍</label>
                        <div id="passwordHelp" class="form-text">输入关于我的一切</div>
                    </div>
                    <button type="submit" class="btn btn-primary">确定</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php view::foot(); ?>