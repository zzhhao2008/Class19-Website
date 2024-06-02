<?php
$subject = $_GET['subid'];
function getsubjectpm($subjectpm)
{
    unset($subjectpm['total']);
    //排序并保留键值
    foreach ($subjectpm as $k => $v) {
        $sortsubjectpm[] = array("k" => $k, "v" => $v);
    }
    usort($sortsubjectpm, function ($a, $b) {
        return $a['v'] <=> $b['v'];
    });
    return $sortsubjectpm;
}
$folder = "cx/";   // 文件夹路径
$handle = DB::scanName($folder);  // 遍历文件夹
usort($handle, function ($a, $b) {
    return intval($a) - intval($b);
});
$exams = [];
$colors = ["red", "yellow", "blue", "green", "orange"];
$pchange = [];
$names = [];
foreach ($handle as $k => $v) {
    $exam = DB::getdata("cx/$v");
    if (!isset($exam['std'][$subject])) {
        continue;
    }
    $subname = $exam['std'][$subject];
    unset($exam['sco'][$id]['name']);
    $temp = array(
        'id' => $v,
        'name' => $exam['name'],
        "scos" => [],
        "avg" => 0,
        "grades" => ["A" => 0, "B" => 0, "C" => 0, "D" => 0, "E" => 0],
        "gradeAvg" => []
    );
    $total = 0;
    foreach ($exam['sco'] as $k1 => $v1) {
        $tco = $v1[$subject] / $exam['full'][$subject];
        $temp['scos'][$k1] = $tco;
        $total += $tco;
        if ($tco >= 0.9) {
            $temp['grades']['A']++;
        } elseif ($tco >= 0.8) {
            $temp['grades']['B']++;
        } elseif ($tco >= 0.7) {
            $temp['grades']['C']++;
        } elseif ($tco >= 0.6) {
            $temp['grades']['D']++;
        } else {
            $temp['grades']['E']++;
        }
    }
    $temp['avg'] = $total / count($exam['sco']);
    $pchange[] = $temp['avg'];
    $names[] = $temp['name'];
    $exams[] = $temp;
}
view::header("单科成绩分析-$subname");
?>

<body>
    <div class="container main abox">
        <?php
        echo "<h3>$subname 历史成绩分析</h3>";
        $data = [
            "type" => "line",
            "labels" => $names,
            "datas" => [
                [
                    "label" => "得分率变化(%)",
                    "data" => $pchange,
                    "color" => "red"
                ],

            ],
            //"height"=>"400px",
            "width" => "100%",
        ];
        view::chart($data);
        echo "<ul class=\"pagination\">";
        foreach ($exams as $k => $v) {
            echo '<li class="page-item" id="page-changer-'.$k.'" style="overflow:auto"><a class="page-link" href="javascript:page('.$k.')">'.$v['name'].'</a></li>';
        }
        echo "</ul>";
        foreach ($exams as $k => $v) {
            $avgp=round($v['avg']*100,2);
            $last=$exams[$k-1]?:[];
            $grachange=["A"=>"--","B"=>"--", "C"=>"--", "D"=>"--", "E"=>"--"];
            $scochange="--";
            if($last){
                foreach($last['grades'] as $k1=>$v1){
                    $grachange[$k1]=$v['grades'][$k1]-$last['grades'][$k1];
                }
                $scochange=round(($v['avg']-$last['avg'])*100,2);
                if($scochange>0){
                    $scochange="<span class='text-success'>+".$scochange."%</span>";
                }else{
                    $scochange="<span class='text-danger'>".$scochange."%</span>";
                }
            }
            echo <<<HTML
            <div id="GraBar-{$k}" style="">
                <h3>{$v['name']}</h3>
                <h4>平均得分率:{$avgp}% ({$scochange})</h4>
                <h4>等级人数</h4>
                <table class="table table-light">
                    <thead>
                        <tr>
                            <th>等级</th>
                            <th>人数</th>
                            <th>变化</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>A</td>
                            <td>{$v['grades']['A']}</td>
                            <td>{$grachange['A']}</td>
                        </tr>
                        <tr>
                            <td>B</td>
                            <td>{$v['grades']['B']}</td>
                            <td>{$grachange['B']}</td>
                        </tr>
                        <tr>
                            <td>C</td>
                            <td>{$v['grades']['C']}</td>
                            <td>{$grachange['C']}</td>
                        </tr>
                        <tr>
                            <td>D</td>
                            <td>{$v['grades']['D']}</td>
                            <td>{$grachange['D']}</td>
                        </tr>
                        <tr>
                            <td>E</td>
                            <td>{$v['grades']['E']}</td>
                            <td>{$grachange['E']}</td>
                        </tr>
                    </tbody>
                </table>

HTML;
            $data = [
                "type" => "bar",
                "labels" => ["A", "B", "C", "D", "E"],
                "datas" => [
                    [
                        "label" => "等级人数-{$v['name']}",
                        "data" => $exams[$k]['grades'],
                        "color" => "blue"
                    ],
                ],
                "height"=>"300px",
                //"width" => "100%",
                "id" => "GraBar-$k"
            ];
            view::chart($data);
            echo "</div>";
        }
?>
    </div>
</body>
<?php view::foot()?>
<script>
    function page(pageid){
        for(var i=0;i< <?= count($exams)?> ;i++){
            document.getElementById("page-changer-"+i).classList.remove("active");
        }
        document.getElementById("page-changer-"+pageid).classList.add("active");
        for(var i=0;i< <?php echo count($exams);?>;i++){
            document.getElementById("GraBar-"+i).style.display = "none";
        }
        document.getElementById("GraBar-"+pageid).style.display = "block";
        document.getElementById("GraBar-"+pageid).style.display = "block";
    }
    page(0);
</script>