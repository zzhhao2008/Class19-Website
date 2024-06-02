<?php
$exam = DB::getdata("cx/" . $_GET["id"]);
session_start();

$pms = DB::getdata("cx_pms/" . $_GET["id"]);
if (empty($exam)) {
    view::alert("该考试不存在或不可被查询！客服：微信：zsv2022 QQ: 2019666136", "warning", 10000000);
    view::B404();
    exit;
}

function ScoIsSet($sid, $exam)
{
    return isset($exam['sco'][$sid]);
}
$me = user::read()['profile'];
$vip = array("郑志浩", "郑越牧荑");
$hidden = $exam['hidden'];
$gotrep = student::Stu_Auth($_GET['id'], $exam);

$yx = $gotrep["yx"];
$login = $gotrep['login'];
$id = $gotrep['id'];
$mes = $gotrep['mes'];
$iprderurl = $gotrep['iprderurl'];

if (!user::read()['name']) {
    view::alert("现已支持不加密账号，建议您<a href='/profile'>前往注册/登录!</a>", "warning", 8000);
}

if ($mes) {
    view::alert($mes);
}

if (user::is_superuser() && ($_GET["AQname"])) {
    $yx = 1;
    $id = $_GET["AQname"];
    if (!ScoIsSet($id, $exam)) {
        $yx = 0;
        view::alert("学生不存在");
    }
}

if (!user::is_superuser() && $exam['vip'] === 1) {
    view::alert("您没有查看此考试的权限", "danger");
    $yx = 0;
}

if ($yx) { //数据预处理(基本部分)
    $mp = $pms[$id];
    $peoplecnt = count($exam['sco']);
    $gra = array();
    $nj = $exam['nj'] ? $exam['nj'] : array();
    $mysco = $exam['sco'][$id]; //取出我的分数
    $full = $exam['full'];  //取出满分
    unset($mysco['name']);  //删除多余元素
    $std = $exam['std'];    //取出标准名称
    unset($std['id']);
    unset($std['name']); //强制删除导致错位的姓名和身份证号
    $fstd = $std;
    $fstd["total"] = "总分";
    $mysco_p = array(); //我的得分率
    $total = 0; //总分
    $totalfull = 0;
    $dispsco = []; //供给分数面板的集成显示配置
    $sorts = []; //排序后的学科(按分数)
    $sortp = []; //按排名排序
    foreach ($mysco as $k => $v) {
        $v = floatval($v); //强制转换数字
        $mysco[$k] = $v; //保存转换结果
        $thisp = $v / $full[$k] * 100; //计算得分率
        $mysco_p[$k] = $thisp; //记录得分率
        if (!in_array($k, $nj)) { //如果不是不计入总得分的科目
            $total += $v; //计算总分
            $totalfull += $full[$k]; //计算总分满分
        }
        //等级
        if ($thisp >= 90) {
            $gra[$k] = 'A';
        } else if ($thisp >= 80) {
            $gra[$k] = 'B';
        } else if ($thisp >= 70) {
            $gra[$k] = 'C';
        } else if ($thisp >= 60) {
            $gra[$k] = 'D';
        } else $gra[$k] = 'E';
        $dispsco[$k] = array("full" => $full[$k], "sco" => $v, "name" => $std[$k] . ":" . $gra[$k]); //显示的数据
        $sorts[$k] = array("p" => $thisp, "name" => $std[$k]); //排名排序
        $sortp[$k] = array("p" => $mp[$k], "name" => $std[$k]); //得分率排序
    }
    function cmpto($a, $b)
    {
        return $a['p'] > $b['p'];
    }
    usort($sorts, "cmpto");
    usort($sortp, "cmpto"); //排序排名和
    $dispsco[] = array("full" => $totalfull, "sco" => $total, "name" => "总分");
    if ($mp) { //我的排名数据有效
        $mp_p = [];
        $totalp = $mp['total'];
        function pmToPj($pm)
        {
            if ($pm <= 3) return "成绩非常优秀";
            elseif ($pm <= 10) return "成绩优秀";
            elseif ($pm <= 22) return "成绩良";
            elseif ($pm <= 40) return "仍需努力";
            else return "仍需加倍努力";
        }
        $solu = "$id 同学你好，从总分来看，你这次考试 <code>" . pmToPj($totalp)  . "</code>，但也存在一些不足<br>" .
            "从排名看，你最好的学科是 <code>" . $sortp[0]['name'] . "</code>,该科目<code>" . pmToPj($sortp[0]['p']) . "</code><br>" .
            "从排名看，你最差的学科是 <code>" . $sortp[count($sortp) - 1]['name'] . "</code>,该科目<code>" . pmToPj($sortp[count($sortp) - 1]['p']) . "</code><br>" .
            "从成绩看，你最好的学科是 <code>" . $sorts[count($sortp) - 1]['name'] . "</code><br>" .
            "从成绩看，你最差的学科是 <code>" . $sorts[0]['name'] . "</code><br>";
        if ($sortp[count($sortp) - 1]['p'] - $sortp[0]['p'] > 15) {
            $solu = $solu . "你的最好和最坏学科差距过大，可能存在<code>偏科</code>";
        } else if ($sortp[count($sortp) - 1]['p'] - $sortp[0]['p'] > 10) {
            $solu = $solu . "你的最好和最坏学科差距较大";
        }
        $mp_g = $mp;
        unset($mp_g['total']);
        for ($i = 0; $i < count($mp_g); $i++) {
            $mp_p[$i] = ($peoplecnt - $mp[$i] + 1) / $peoplecnt * 100;
        }
        //var_dump($mp_p);
    } else {
        $solu = "暂不提供";
    }
}

if ($yx) { //数据预处理（等级科目、历史对比、PK）
    //等级start
    /*
    $xzk = DB::getdata("student/xzk");
    $xzk = $xzk['sco'][$id];
    $zzgrade = "E";
    $zzsco = $mysco[5];
    $zzfull = $full[5];
    if ($zzsco > $zzfull * 0.9) $zzgrade = "A";
    elseif ($zzsco > $zzfull * 0.8) $zzgrade = "B";
    elseif ($zzsco > $zzfull * 0.7) $zzgrade = "C";
    elseif ($zzsco > $zzfull * 0.6) $zzgrade = "D";
    $xzk[] = $zzgrade;
    $xzkgradecnt = ["A" => 0, "B" => 0, "C" => 0, "D" => 0, "E" => 0];
    foreach ($xzk as $k => $v) {
        $xzkgradecnt[$v]++;
    }*/
    //等级end

    //历史start
    $histid = $_GET['id'] - 1;
    $histsco = DB::getdata("cx/" . $histid);
    if ($_POST['hisid'] || ScoIsSet($id, $histsco)) {
        $hist = 1;
        $histid = $_POST['hisid'] ? $_POST['hisid'] : $histid;
        $histsco = DB::getdata("cx/" . $histid);
        $histpms = DB::getdata("cx_pms/" . $histid);
        $histtit = $histsco['name'];
        if (empty($histsco) || $histid === $_GET['id']) {
            view::alert("抱歉，您选择的考试不存在或不可被查询！客服：微信：zsv2022 QQ: 2019666136", "warning", 5000);
            $hist = 0;
        } elseif (!ScoIsSet($id, $histsco)) {
            view::alert("抱歉，您选择的考试中没有您的成绩！客服：微信：zsv2022 QQ: 2019666136", "warning", 5000);
            $hist = 0;
        }
        if ($hist) {
            $histpms = $histpms[$id];
            $histpcnt = count($histsco['sco']);
            $histsco = $histsco['sco'][$id];
            $histjtb =  "持平";
            if ($histpms["total"] > $mp['total']) {
                $histjtb = "进步";
            } elseif ($histpms["total"] < $mp['total']) {
                $histjtb = "退步";
            }
            $tfstd = [];
            $thistpms_p = [];
            $tpms_p = [];
            foreach ($mp as $k => $v) {
                if (!isset($histpms[$k])) {
                    continue;
                }
                $tfstd[] = $fstd[$k];
                $histpms_p[$k] = round(($histpcnt - $histpms[$k] + 1) / $histpcnt * 100, 2);
                $ch_p[] = $mp_p[$k] - $histpms_p[$k];
                $thistpms_p[] = $histpms_p[$k];
                if ($k === "total") {
                    $tpms_p[] = round(($peoplecnt - $totalp + 1) / $peoplecnt * 100, 2);
                } else
                    $tpms_p[] = $mp_p[$k];
            }
            $histchart = [
                "id" => "hist",
                "type" => "bar",
                "labels" => $tfstd,
                "datas" => [
                    [
                        "label" => "历史得分率(%)",
                        "data" => $thistpms_p,
                        "color" => "red"
                    ],
                    [
                        "label" => "本次得分率(%)",
                        "data" => $tpms_p,
                        "color" => "blue"
                    ],
                ],
                "width" => "100%"
            ];
        }
    }
    //历史end

    //PK开始

    if ($_POST['PK']) {
        if ($hidden['pk']) {
            alert("抱歉,您暂时无法使用PK功能", "warning");
            $pk = 0;
        } else {
            $pk = 1;
            $pkman = $_POST['PK'];
            $pkpms = $pms[$pkman];
            if ($id === $pkman) {
                alert("抱歉,您不能和您自己PK", "warning");
                $pk = 0;
            }
            if ($pkman === "郑志浩") {
                view::alert("抱歉，您的PK请求被郑志浩同学拒绝");
                $pk = 0;
            }
            if (ScoIsSet($pkman, $exam) && $pkpms) {
                $pksco = $exam['sco'][$pkman];

                $pkres = "平手";
                if ($pkpms['total'] > $mp['total']) {
                    $pkres = "你赢了";
                } elseif ($pkpms['total'] < $mp['total']) {
                    $pkres = "你输了";
                }
                for ($i = 0; $i < count($mp_g); $i++) {
                    $v = $pkpms[$i];
                    $pkpms_p[$i] = ($peoplecnt - $v + 1) / $peoplecnt * 100;
                }

                $pkchart = [
                    "id" => "pk",
                    "type" => "bar",
                    "labels" => $std,
                    "datas" => [
                        [
                            "label" => "我的得分率(%)",
                            "data" => $mp_p,
                            "color" => "red"
                        ],
                        [
                            "label" => "TA的得分率(%)",
                            "data" => $pkpms_p,
                            "color" => "blue"
                        ],
                    ],
                    "width" => "100%",
                    "borderWidth" => "1"
                ];
            } else {
                view::alert("抱歉,您选择的PK对象不存在或不可被查询！客服：微信：zsv2022 QQ: 2019666136", "warning", 5000);
                $pk = 0;
            }
        }
    }

    //PK结束
}

if ($yx) { //点评
    $dpment = DB::getdata("dp/" . $_GET["id"]);
    $mydp = $dpment[$id];
}


$show_id = $id;

view::header("成绩查询-" . $exam['name']);
?>

<body>
    <div class="container main abox">
        <h3><a href="/cx"><?= view::icon("arrow-left-circle-fill") ?></a><?php echo $exam["name"] ?></h3>

        <?= $mypower >= 1 && $yx ? "已根据您的登录账号自动匹配" :
            "你好" ?>
        <hr>
        <h4>备注：</h4>
        <pre><?php echo $exam["bz"] ?></pre>
        <hr>
        <?php
        if (!$yx) {
            view::IDandNameForm();
        }
        ?>
        <span class="btn-warning"><?= $mes ?></span>
        <hr>
        <div>
            <?php
            if ($yx === 1) {
                echo "<h3>", $show_id, "</h3>";

                view::SubjectSco($dispsco) ?>
                <?php
                if (!empty($nj)) {
                    echo "<p class='text-warning'>不计入总分的科目：";
                    foreach ($nj as $k => $v) {
                        echo $std[$v] . ",";
                    }
                    echo "</p>";
                }
                ?>

                <div><!--教师点评-->
                    <h2>教师点评</h2>
                    <?php
                    if ($mydp['time'] > 0) {
                        echo "<p>" . user::queryUserNick($mydp['dpmer'], 1, 0) . " 于 " . getDate_ToNow($mydp['time']) . "</p>";
                        echo "<pre>" . htmlspecialchars($mydp['text']) . "</pre>";
                    } else {
                        echo "<p>暂无点评</p>";
                    }
                    ?>
                </div>
                <div><!--综合报告-->
                    <h2>综合报告</h2>
                    <div class="row">
                        <div class="col-sm-6">
                            <?php
                            $data = [
                                "type" => "radar",
                                "labels" => $std,
                                "datas" => [
                                    [
                                        "label" => "得分率(%)",
                                        "data" => $mysco_p,
                                        "color" => "red"
                                    ],
                                    [
                                        "label" => "修正得分率(%)",
                                        "data" => $mp_p,
                                        "color" => "blue"
                                    ],
                                ],
                                //"height"=>"400px",
                                "width" => "100%",
                            ];
                            view::chart($data);
                            //view::lader($mysco_p,$std) 
                            ?>
                        </div>
                        <div class="col-sm-6" style="font-size: 16px;">
                            <?= $solu ?>
                        </div>
                    </div>
                </div>
                <div><!--等级科目分析
                    <h2>等级科目分析</h2>
                    <?php
                    /*if ($xzk === []) echo "您未参加小中考";
                    elseif (!isset($mysco[5])) echo "本次考试未包含政治成绩";
                    else {
                    ?>
                        <div>
                            <h4>回顾</h4>
                            <?php
                            view::table([$xzk], array("历史", "地理", "生物", "信息", "政治"));
                            view::table([[$xzkgradecnt['A'], $xzkgradecnt['B'], $xzkgradecnt['C'], $xzkgradecnt['D'], $xzkgradecnt['E']]], array("A", "B", "C", "D", "E"));
                            if ($xzkgradecnt['E'] >= 1 || $xzkgradecnt['D'] >= 2) {
                                $type = "danger";
                                $text = "未通过";
                            } elseif ($xzkgradecnt['D'] >= 1) {
                                $text = "可能有困难";
                                $type = "warning";
                            } else {
                                $text = "问题不大";
                                $type = "success";
                            }
                            echo "<div class='alert alert-$type' role='alert'>$text</div>"
                            ?>
                        </div>
                        <?php }*/ ?>
                </div>-->
                <hr>
                <?php if (!$hidden['history']) { ?>
                    <div><!--历史对比-->
                        <h2>历史对比</h2>
                        <form method="post">
                            <input type="number" name="hisid" placeholder="要对比的考试ID" value="<?= $histid ?>">
                            <input type="submit" value="确定">
                        </form>
                        <?php if ($hist) { ?>
                            <div>
                                <h4>对比结果-与<?= $histtit ?></h4>
                                <div>
                                    <h4><code><?= $histjtb ?></code></h4>
                                    <?php view::chart($histchart) ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <hr>
                <?php
                }
                if (!$hidden['pk']) {
                ?>
                    <h2>PK</h2>
                    <form method="post">
                        <input type="text" name="PK" placeholder="要PK的考生姓名">
                        <input type="submit" value="确定">
                    </form>
                    <?php if ($pk) { ?>
                        <div>
                            <h4>PK结果-与<?= $pkman ?></h4>
                            <div>
                                <h4><code><?= $pkres ?></code></h4>
                                <?php view::chart($pkchart) ?>
                            </div>
                        </div>
                    <?php } ?>
                    <hr>
                <?php
                }
                if (!$hidden['pm']) {
                ?>
                    <h2>班级内排名数据</h2>
                <?php
                    $tfstd = [];
                    $tmp = [];
                    foreach ($fstd as $k => $v) {
                        $tfstd[] = $v;
                        $tmp[] = $mp[$k];
                    }
                    view::table([$tmp], $tfstd);
                } ?>
                <h4>传统表格</h4>
                <div>
                    <?php
                    $tabledata = [];
                    $lcnt = 0;
                    foreach ($mysco as $k => $v) {
                        $tabledata[$lcnt] = array($std[$k], $v, $gra[$k], $full[$k], round($mysco_p[$k], 2) . "%");
                        $lcnt++;
                    }
                    $tabledata[] = array("总分", $total, "-", $totalfull, round($total / $totalfull * 100, 2) . "%");
                    view::table($tabledata, array("科目", "分数", "等级", "满分", "得分率"));
                    ?></div>
            <?php }
            ?>
        </div>
    </div>
</body>
<?php view::foot(); ?>