<?php

view::header("成绩管理");
if ($_POST['rmid']) {
    $id = $_POST['rmid'];
    if (DB::rmdata("cx/$id")) {
        alert("删除成功！");
        jsjump("/cx_manage");
    } else {
        alert("删除失败！");
        jsjump("/cx_manage");
    }
}
?>

<body>
    <div class="container abox">
        <form method="get" action="/cx_subject">
            <input name="subid" placeholder="输入科目ID进入分析"><input type="submit" value="查询">
        </form>
        <button id="add-button" class="btn btn-info">添加成绩</button>
        <div id="add" class="hiddenbox">
            <form method="post" action="/addexam">
                <textarea name="addtext"></textarea>
                <input type="submit" value="确定">
            </form>
            <pre>
说明：第一行为STD数据（若不对姓名进行说明请空开两列），第一列为姓名，其余为分数
使用Excel中的分隔符‘	’，直接黏贴EXCEL数据即可
full是定义该科满分（必须！）
例如：
姓名	语文
王五	11
张三	123
full	114514
            </pre>
        </div>
        <button id="edit-button" class="btn btn-default">删除成绩</button>
        <div id="edit" class="hiddenbox">
            <form method="post">
                <input type="number" name="rmid" placeholder="考试ID">
                <button class="btn btn-danger" type="submit">确定</button>
            </form>
        </div><br>
        <?php
        echo "<hr>";
        $thislist = [];
        $files = array();
        $handle = DB::scanName("cx");  // 遍历文件夹
        usort($handle, function ($a, $b) {
            return intval($b) - intval($a);
        });
        $cnt = 1;
        foreach ($handle as $k => $v) {
            $exam = DB::getdata("cx/$v");
            if (isset($exam['nd']) && $exam["nd"] > 0) {
                continue;
            }
            $v = str_replace(".php", "", $v);
            $thislist[] = array("title" => "$v" . $exam['name'], "link" => "/cx/manage/detial?id=$v");
        }
        view::List_sys($thislist);
        ?>
    </div>
</body>
<script>
    var ab = document.getElementById("add-button");
    var eb = document.getElementById("edit-button");
    var abox = document.getElementById("add");
    var ebox = document.getElementById("edit");
    var now = "";
    ab.addEventListener("click", function() {
        if (abox.style.display == "none" || abox.style.display == "") {
            abox.style.display = "block";
        } else {
            abox.style.display = "none";
        }
    });
    eb.addEventListener("click", function() {
        if (ebox.style.display == "none" || ebox.style.display == "") {
            ebox.style.display = "block";
        } else {
            ebox.style.display = "none";
        }
    });
</script>
<style>
    .hiddenbox {
        display: none;
    }

    textarea {
        max-width: 100%;
        min-width: 100%;
        min-height: 300px;
    }
</style>
<?php view::foot() ?>