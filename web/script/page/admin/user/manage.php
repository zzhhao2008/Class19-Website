<?php
$users = DB::scanName("user");
function getstau($user)
{
    if ($user['ban'] === 1) {
        return "已封禁";
    }
    if ($user['unlink'] === 1) {
        return "已删除";
    }
    if ($user['power'] > 1) {
        return "管理员";
    }
    if ($user['power'] === 1) {
        return "正常";
    }
    return "未知";
}
function getcolor($user)
{
    switch (getstau($user)) {
        case "已封禁":
            return " table-danger";
        case "管理员":
            return " table-success";
        case "未知":
            return " table-warning";
    }
}
view::header("用户列表"); ?>
<table class="table table-hover">
    <thead>
        <tr class="table-info">
            <th>ID</th>
            <th>昵称</th>
            <th>状态</th>
            <th>编辑</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $k) {
            $v=user::queryUserAdmin($k);
            if (getstau($v) === '已删除' || user::read()['name'] === $k) continue;
        ?>
            <tr class="<?= getcolor($v) ?>">
                <td><?= $v['name'] ?></td>
                <td><?= $v['nick'] ?></td>
                <td><?= getstau($v) ?>-<?=getDate_ToNow($v['llt'])?></td>
                <td>
                    <a href="/user_cr_rm?m=rm&uid=<?= $k ?>">删除</a>
                    <a href="/user_edit?uid=<?= $k ?>">编辑</a>
                    <a href="/user_cr_rm?m=ca&uid=<?= $k ?>">设为/取消管理员</a>
                    <a href="/user_cr_rm?m=ban&uid=<?= $k ?>">Ban/Unban</a>
                </td>
            </tr>
        <?php         } ?>
    </tbody>
</table>
<?php view::foot(); ?>