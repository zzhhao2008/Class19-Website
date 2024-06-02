<?php
$mytext=DB::getdata("sys/mainpagetext");
if($_POST['face']){
    DB::putdata("sys/mainpagetext",$_POST['face']);
}
view::header("主页内容设置");
?>
<form method="post" class="row" id="tableA">
    <div class="col-sm-8 problembox">
        <!--题面编辑-->
        <div>
            <h3>编辑</h3>
            <?php view::aceeditor($mytext,"html",0,"face");?>
        </div>
    </div>
    <div class="col-sm-4  problemsubbox">

        <div>
            <input class="btn btn-primary" type="submit" value="保存">
            <button class="btn btn-danger" type="button" onclick="reflush()">重置</button>
        </div>
    </div>
</form>

<?php
view::foot(); ?>
<style>
    .problemFace {
        min-width: 100%;
        border: none;
        min-height: 500px;
        max-width: 100%;
    }
</style>
<script>
    function reflush() {
        res = prompt("确定重置更改请输入：yes")
        if (res == "yes") {
            document.getElementById('tableA').reset();
        }
    }
    document.addEventListener("keydown", function(e) {
        //可以判断是不是mac，如果是mac,ctrl变为花键
        //event.preventDefault() 方法阻止元素发生默认的行为。
        if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
            e.preventDefault();
            //document.getElementById("alertbox").innerHTML = "Ctrl+S保存成功！";
            document.getElementById("tableA").submit();
        }
    }, false);
</script>