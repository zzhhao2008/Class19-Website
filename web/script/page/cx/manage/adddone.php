<?php

view::header("成绩管理-添加完成");
if ((!$_POST['cfg'] || !$_POST['name'])) {
    if (!$_GET['a']) {
        alert("请勿空项！单击确定按键返回上一步");
        echo "<script>history.go(-1)</script>";
        exit;
    }
    $itid = DB::countName("cx");
} else {;
    $itid = DB::countName("cx") + 1;
    $cfg = json_decode($_POST['cfg'], 1);
    $cfg['name'] = $_POST['name'];
    $cfg['nj'] = explode(",",$_POST['nj']) ;
    $cfg['time'] = time();
    $cfg['bz'] = $_POST['bz'];
    //var_dump($cfg);
    //var_dump($_POST['cfg']);
    echo DB::putdata("cx/$itid", $cfg);
    jsjump("/addexamdone?a=done");
}
?>

<body>
    <div class="container">
        <h1>恭喜你，添加成功！<a href="/cx/manage/detial?id=<?=$itid?>">立即查看</a></h1>
        <script>
            setTimeout(() => {
                location.href="cx/manage/detial?id=<?=$itid?>";
            }, 3000);
        </script>
    </div>
</body>
<?php view::foot() ?>