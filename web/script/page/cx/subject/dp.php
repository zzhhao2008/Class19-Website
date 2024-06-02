<?php
if ($_GET['eid']) {
    $eid = $_GET['eid'];
    $dpment = DB::getdata("dp/$eid");
    $examment = DB::getdata("cx/$eid");
    if (empty($dpment)) {
        foreach ($examment['sco'] as $name => $sco) {
            $dpment[$name] = array("text" => "", "time" => 0, "dpmer" => "");
        }
    }
} else {
    view::header("成绩点评-出错了！");
    view::alert("请选择考试！");
    view::foot();
    exit;
}
if ($_POST['dp']) {
    foreach ($_POST['dp'] as $name => $dp) {
        if ($dp != $dpment[$name]['text']) {
            $dpment[$name]['text'] = $dp;
            $dpment[$name]['time'] = time();
            $dpment[$name]['dpmer'] = user::read()['name'];
        }
    }
    DB::putdata("dp/$eid", $dpment);
    view::alert("点评保存成功！", "info");
}
view::header("成绩点评-{$examment['name']}");
?>

<body>
    <div class="container main abox">
        <a href="/cx_m?id=<?= $eid ?>" class="btn btn-danger">
            < 返回</a>
                <form method="post">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>姓名</th>
                                <th>评价</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dpment as $name => $dp) { ?>
                                <tr>
                                    <td><?= $name ?></td>
                                    <td><textarea name="dp[<?= $name ?>]" class="form-control"><?= $dp['text'] ?></textarea></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2"><input type="submit" value="提交" class="btn btn-success"></td>
                            </tr>
                            <input type="hidden" name="eid" value="<?= $eid ?>">
                            </tbody>
                    </table>
                </form>

    </div>
</body>
<?php view::foot(); ?>