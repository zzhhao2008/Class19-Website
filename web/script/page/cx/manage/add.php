<?php

view::header("成绩管理-添加");
if (!$_POST['addtext']) echo "?你在做什么，请先提交数据再进行确认！", exit();
$itid = DB::countName("cx") + 1;
$data = array();
$lines = explode("\r\n", $_POST['addtext']);
$t = explode("	", $lines[0]);
$std = array();
$std['name'] = $t[0];
for ($i = 0; $i < count($t) - 1; $i++) {
    $std[$i] = $t[$i + 1];
}
$sco = array();
$full = array();
for ($i = 1; $i < count($lines) - 1; $i++) {
    $thisline = explode("	", $lines[$i]);
    for($j=0;$j<count($thisline)-1;$j++){
        $ttt[$j]=floatval($thisline[$j+1]);
    }
    if ($thisline[0] === 'full') {
        $data['full'] = $ttt;
        //print_r($ttt);
    } else {
        $sco[$thisline[0]] = $ttt;
    }
}

$data['std'] = $std;
$data['sco'] = $sco;//var_dump($data);
//print_r($data);
?>

<body>
    <div class="container abox">
        <h2>预览总表：</h2>
        <table border="1">
            <tr>
                <?php
                foreach ($data["std"] as $k => $v) {
                    echo "<th>$v</th>";
                }
                ?>
            </tr>
            <tr>
                <th>满分</th>
                <?php
                foreach ($data["full"] as $k => $v) {
                    echo "<th>$v</th>";
                }
                ?>
            </tr>

            <?php
            foreach ($data['sco'] as $k => $v) {
                echo "<tr><td>$k</td>";
                foreach ($v as $kk => $vv) {
                    if ($kk === 'name') continue;
                    echo "<td>$vv</td>";
                }
                echo "</tr>";
            }
            ?>
        </table>
        <form method="post" action="/addexamdone">
            <h2>JSON配置总览</h2>
            <textarea readonly name="cfg"><?= json_encode($data, 1) ?></textarea>
            考试名称：<input type="text" name="name" value="<?= date("Y-m-d H") ?>"><br>
            不计入总分的科目ID：<input type="text" name="nj" value="5"><br>
            备注:<textarea name="bz"></textarea>
            <input type="submit" class="btn btn-danger" value="我已确定解析准确无误！">
        </form>
    </div>
</body>
<style>
    .hiddenbox {
        display: none;
    }

    textarea {
        max-width: 100%;
        min-width: 100%;
        min-height: 100px;
    }
</style>
<?php view::foot() ?>