<?php view::header("编辑用户"); ?>
<?php function back()
{
    global $cuser, $cuid;
    user::saveuserprofie($cuid, $cuser);
    jsjump("/user_manage");
}
if ($_GET['uid']) {
    $cuid = $_GET['uid'];
    $cuser = user::queryUserAdmin($_GET['uid']);
    if (empty($cuser)) {
        alert("ERROR:UID");
        jsjump("/user_manage");
        die();
    }
    if ($_POST['password']) {
        $cuser['about'] = $_POST['about'];
        $cuser['nick'] = $_POST['nick'];
        $cuser['rating'] = $_POST['rating'];
        if ($_POST['password']) {
            $cuser['password'] = md5($_POST['password']);
        }
        back();
    }
}

?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <form method="post">
            <div class="mb-3">
                <label for="nick" class="form-label">昵称</label>
                <input type="text" class="form-control" id="nick" name="nick" value="<?php echo $cuser['nick']; ?>">
            </div>
            <div class="mb-3">
                <label for="about" class="form-label">关于我</label>
                <textarea class="form-control" id="about" name="about"><?php echo $cuser['about']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="rating" class="form-label">评分</label>
                <input type="text" class="form-control" id="rating" name="rating" value="<?php echo $cuser['rating']; ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">密码</label>
                <input type="text" class="form-control" id="password" name="password" value="<?php echo $cuser['password']; ?>">
            </div>
            <button type="submit" class="btn btn-primary">保存更改</button>
        </form>
    </div>
</div>
<?php view::foot(); ?>