<?php view::header("成绩查询-十九中队") ?>

<body>
    <div class="container abox">
        <form method="get" action="/cx_show">
            <input name="id" placeholder="输入考试ID进入查询"><input type="submit" value="查询">
        </form>
        <hr>
        <?php
        
            if ($mypower >= 2) {
                echo "<a href='/cx/manage'>管理</a>";
            }
?>
        <div class="row">
            <div class="col-sm-12">
                <h3>单次成绩</h3>
                <?php
                $folder = "cx/";   // 文件夹路径
                $handle = DB::scanName($folder);  // 遍历文件夹
                $cnt = 1;
                $thislist=[];
                //$thislist[]=;
                usort($handle, function ($a, $b) {
                    return intval($b) - intval($a);
                });
                foreach ($handle as $k => $v) {
                    $exam = DB::getdata("cx/$v");
                    if ($exam["nd"] > 0) {
                        continue;
                    }
                    $thislist[]=array("title" => $v.". ".$exam['name'], "link" => "/cx/show?id=$v");
                }
                view::List_sys($thislist);
                ?>
            </div>
        </div>

        <hr>
    </div>
</body>
<?php view::foot();?>
