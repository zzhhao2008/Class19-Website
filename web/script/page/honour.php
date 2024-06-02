<?php
view::header("荣誉墙-十九中队");
?>
<style>
    .cccc img{
        width: 100%;
        max-height: 450px;
    }
    .cccc{
        text-align: center;
    }
</style>
<body>
    <div class="container cccc">
        <?php
        echo "<h3>初四年级</h3>";
        $folder = "comfile/honours/g4/";   // 文件夹路径
        $files = scandir($folder);  // 遍历文件夹
        $cnt=count($files);
        $min=0;
        if($cnt>=12&&!$_GET['all']){
            $min=$cnt-12;
        }
        $c=1;
        for($i=$cnt-1;$i>=$min;$i--){
            $v=$files[$i];
            if($v==="."||$v=="..") continue;
            echo "<img src='" . $folder . $v . "'>" . ($c++) . "." . $v . "<br>";
        }
        if($min!==0){
            echo "<a href='?all=1'>查看全部</a>";
        }
        echo "<h3>初三年级</h3>";
        $folder = "comfile/honours/g3/";   // 文件夹路径
        $files = scandir($folder);  // 遍历文件夹
        $cnt=count($files);
        $min=0;
        if($cnt>=12&&!$_GET['all']){
            $min=$cnt-12;
        }
        $c=1;
        for($i=$cnt-1;$i>=$min;$i--){
            $v=$files[$i];
            if($v==="."||$v=="..") continue;
            echo "<img src='" . $folder . $v . "'>" . ($c++) . "." . $v . "<br>";
        }
        if($min!==0){
            echo "<a href='?all=1'>查看全部</a>";
        }
        
        echo "<h3>初一、初二年级</h3>";
        $folder = "comfile/honours/yuan/";   // 文件夹路径
        $files = scandir($folder);  // 遍历文件夹
        $cnt=count($files);
        $min=0;
        if($cnt>=2&&!$_GET['all']){
            $min=$cnt-2;
        }
        $c=1;
        for($i=$cnt-1;$i>=$min;$i--){
            $v=$files[$i];
            if($v==="."||$v=="..") continue;
            echo "<img src='" . $folder . $v . "'>" . ($c++) . "." . $v . "<br>";
        }
        if($min!==0){
            echo "<a href='?all=1'>查看全部</a>";
        }
        ?>
    </div>
</body>
<?php view::foot() ?>