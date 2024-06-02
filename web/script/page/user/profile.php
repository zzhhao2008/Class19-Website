<?php
if ($_GET['logout']) {
    setcookie("login_name_class19", "", time() - 3600 * 48, "/");
    setcookie("tempLogin", "", time() - 3600 * 48, "/");
    jsjump("?");
}
view::header("我的账户");
$userconfig = user::read()['profile'];
?>
<p>
<h2>Hi,<?= user::queryUserNick(user::read()['name'],1,1) ?>!</h2>
</p>
<a href="?logout=1" class="btn btn-danger"><?= view::icon('box-arrow-left') ?>退出登录</a>
<a href="/change" class="btn btn-primary"><?= view::icon('pencil-square') ?>个人设置</a>
<a href="/themeset" class="btn btn-primary"><?= view::icon('pencil-square') ?>主题设置</a>
<ul class="list-group">
    <li class="list-group-item list-group-item-info">Rating:<?= $userconfig['rating'] ?></li>
    <li class="list-group-item list-group-item-secondary">Name:<?= $userconfig['name'] ?></li>
    <li class="list-group-item list-group-item-dark">
        <h5>个人介绍</h5><?= $userconfig['about'] ?>
    </li>
    
    <li class="list-group-item list-group-item-light">
        <h5>动态</h5>
        <div>
            <?php
            usort($userconfig['dt'], function ($a, $b) {
                //按时间从大到小
                return $b['time'] - $a['time'];
            });
            foreach ($userconfig['dt'] as $v) {
                $date = date("Y-m-d H:i:s", $v['time']);
                echo <<<HTML
<div class="card">
    <div class="card-body">
        <h5 class="card-title">{$date}</h5>
        <p class="card-text">{$v['art']}</p>
    </div>
</div>
HTML;
            } ?>
        </div>
    </li>
</ul>
<?php view::foot(); ?>