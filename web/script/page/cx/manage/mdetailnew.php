<?php
view::header("成绩单管理-详情-2.0");
$eid = $_GET['id'];
$exam = DB::getdata("cx/$eid");
if (empty($exam)) {
    view::alert("该考试不存在");
    view::foot();
    exit;
}
if($_POST['name']){
    $exam['name']=$_POST['name'];
    $exam['bz']=$_POST['bz'];
    $exam['hidden']=$_POST['hidden'];
    $exam['vip']=$_POST['vip'];
    $nnj=[];
    if(is_array($_POST['nj']))
    foreach($_POST['nj'] as $k=>$v){
        if($v){
            $nnj[]=$k;
        }
    }
    $exam['nj']=$nnj;
    DB::putdata("cx/$eid", $exam);
}

$subjectscores = [];  //各科目分数
$subject_score_charts = []; //各科目分数排名键
$std = $exam['std'];   //各科目对应名称
unset($std['name']);
$stdO=$std;
$std['total'] = "总分";
$subject_averges = [];   //各科目平均分
$subject_averge_rates = [];  //各科目平均得分率
$full = $exam['full']; //各科目满分
$nj = $exam['nj']; //不计入总分的科目
$scos = $exam['sco'];
$student = [];//学生列表
$hidden=$exam['hidden'];//隐藏功能



foreach ($full as $subjectid => $score) {
    if (!in_array($subjectid, $nj)) {
        $full['total'] += $score;
    }
}

foreach ($exam['sco'] as $k => $v) {
    $student[] = $k;
    $total = 0;
    foreach ($v as $subjectid => $score) {
        $subjectscores[$subjectid][] = $score;
        if (!in_array($subjectid, $nj)) {
            $total += $score;
        }
    }
    $exam['sco'][$k]['total'] = $total;
    $subjectscores['total'][] = $exam['sco'][$k]['total'];
}
foreach ($std as $subjectid => $subjectname) {
    //将$subjectscores降序排序
    rsort($subjectscores[$subjectid]);
    $cnt = 0;
    $last = 0;
    $sum = 0;
    foreach ($subjectscores[$subjectid] as $v) {
        $sum += $v;
        $cnt++;
        if ($last === $v) {
            continue;
        }
        $last = $v;
        $subject_score_charts[$subjectid][$v] = $cnt;
    }
    $subject_averges[$subjectid] = $sum / $cnt;
    $subject_averge_rates[$subjectid] = $subject_averges[$subjectid] / $full[$subjectid];
}
$pms = [];
$spms = DB::getdata("cx_pms/$eid");
foreach ($exam['sco'] as $k => $v) {
    foreach ($v as $sid => $sco) {
        $pms[$k][$sid] = $subject_score_charts[$sid][$sco];
    }
    /*if ($spms[$k] != $pms[$k]) {
        echo "<div>$k:<pre>" . var_export($pms[$k], 1) . "</pre><pre>" . var_export($spms[$k], 1) . "</pre><pre>" . var_export($exam['sco'][$k], 1) . "</pre></div>";
    };*/
}
if ($pms !== $spms) {
    DB::putdata("cx_pms/$eid", $pms);
    view::message("已更新排名", "success");
} else {
    view::message("排名无变化", "success");
}

?>
<div class="container abox">

    <div>
        <h3><a href="/cx/manage"><?= view::icon("arrow-left-circle-fill") ?></a><?php echo $exam["name"] ?></h3>
        <a href="/dp?eid=<?= $eid ?>" target="_blank" class="btn btn-info">前往点评</a>
    </div>
    <form method="post">
        <input type="hidden" name="eid" value="<?= $eid ?>" />
        <hr>
        Title:<input type="text" class="w-100" name="name" value="<?= $exam["name"] ?>" placeholder="考试名称" /><br /><br />
        <? view::aceeditor($exam['bz'], "text", 0, "bz"); ?>
        <div class="row">
            <div class="col-md-4">
                <h4>权限</h4>
                <blockquote>
                    <?php view::checkbox("vip", 1, $exam['vip'], "不允许查询") ?>
                    <?php view::checkbox("hidden[history]", 1, $hidden['history'], "隐藏历史对比") ?>
                    <?php view::checkbox("hidden[pk]", 1, $hidden['pk'], "隐藏个人PK") ?>
                    <?php view::checkbox("hidden[pm]", 1, $hidden['pm'], "隐藏排名") ?>
                </blockquote>
            </div>
            <div class="col-md-4">
                <h4>不计入总分</h4>
                <blockquote>
                    <?php 
                    foreach($stdO as $k=>$v){
                        view::checkbox("nj[$k]", 1, in_array($k, $nj), $v);
                    }
                    ?>
                </blockquote>
            </div>
            <div class="col-md-4">
                <input type="submit" class="btn btn-danger">
            </div>
        </div>


        <hr>
    </form>
    <div>
        <?php
        $sdata = [
            "type" => "radar",
            "labels" => [],
            "datas" => [
                [
                    "label" => "得分率(.1)",
                    "data" => [],
                    "color" => "red"
                ],
            ],
            "height" => "400px",
            //"width" => "100%",
        ];
        foreach ($std as $subjectid => $subjectname) {
            $sdata['labels'][] = $subjectname;
            $sdata['datas'][0]['data'][] = $subject_averge_rates[$subjectid];
        }
        view::chart($sdata);
        ?>
    </div>
    <div>
        <?php
        $table = [];
        $table[] = array_merge(["#", "满分"], $full);
        $table[] = array_merge(["#", "平均分"], $subject_averges);
        $thead = array_merge(["#", "考生"], $std);
        $t = 0;
        foreach ($student as $v) {
            $t++;
            $table[] = array_merge([$t, "<a href='/cx/show?id=$eid&AQname=$v'>$v</a>"], $exam['sco'][$v],);
        }
        view::table($table, $thead, "mainChart");
        ?>
    </div>
</div>


<div>
</div>

<?php
view::foot();
?>
<script>
    window.onload = function() {
        var Table = document.getElementById("mainChart");
        var HeadTD = Table.getElementsByTagName("thead")[0];
        var ContTD = Table.getElementsByTagName("tbody")[0];
        var HeadList = HeadTD.getElementsByTagName("th");
        var ContTrList = ContTD.getElementsByTagName("tr");
        var sortArray = new Array();
        var newNode;
        for (var i = 0; i < HeadList.length; i++) {
            HeadList[i].index = i;
            HeadList[i].onclick = function() {
                if (this.innerHTML !== "考生" && this.innerHTML !== "#") {
                    newNode = "";
                    for (var j = 0; j < ContTrList.length; j++) {
                        sortArray[j] = new Array();
                        sortArray[j][0] = ContTrList[j].getElementsByTagName("td")[this.index].innerText;
                        //转换为数字
                        sortArray[j][0] = parseFloat(sortArray[j][0].replace(/[^0-9.]/g, ""));
                        sortArray[j][1] = j;
                    }

                    sortArray.sort(sortNumber);

                    templist = ContTrList;
                    //templist[sortArray[1][1]].getElementsByTagName("td")[this.index].style.backgroundColor = "#ff0";
                    cnt = 0;
                    last = 0;
                    lcnt = 0;
                    for (var x = 0; x < ContTrList.length; x++) {
                        if (x == 0 || templist[sortArray[x][1]].getElementsByTagName("td")[0].innerHTML == '#') {
                            ;
                        } else {
                            cnt++;
                            if (last == sortArray[x][0]) {
                                templist[sortArray[x][1]].getElementsByTagName("td")[0].innerHTML = lcnt;
                            } else {
                                lcnt = cnt;
                                templist[sortArray[x][1]].getElementsByTagName("td")[0].innerHTML = cnt;
                                last = sortArray[x][0];
                            }
                        }
                        var k = 200 / sortArray.length;
                        templist[sortArray[1][1]].getElementsByTagName("td")[this.index].style.background = "#aaf";
                        if (x > 1 && x < 10) {
                            templist[sortArray[x][1]].getElementsByTagName("td")[this.index].style.backgroundColor =
                                "rgb(" + (x * 19) + "," + 200 + "," + 0 + ")";
                        } else if (x >= 10 && x < sortArray.length * 2 / 3) {
                            templist[sortArray[x][1]].getElementsByTagName("td")[this.index].style.backgroundColor =
                                "rgb(" + 200 + "," + (200 - (x - sortArray.length / 3) * k) + "," + 0 + ")";
                        } else if (x > 1) {
                            templist[sortArray[x][1]].getElementsByTagName("td")[this.index].style.backgroundColor =
                                "rgb(" + 200 + "," + (200 - (x - sortArray.length / 3) * k) + "," + 0 + ")";
                        }
                        newNode += "<tr>" + templist[sortArray[x][1]].innerHTML + "</tr>";
                    }

                    ContTD.innerHTML = newNode;
                }
            }
            HeadList[i].click();
        }

    }

    function sortNumber(b, a) {
        //将ab转为数字
        a = parseFloat(a);
        b = parseFloat(b);
        if (a > b) {
            return 1
        } else if (a < b) {
            return -1
        } else {
            return 0
        }
    }
</script>