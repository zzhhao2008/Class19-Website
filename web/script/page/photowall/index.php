<?php
view::header("照片墙-十九中队");
?>
<style>
    .mainbox img {
        width: 100%;
        max-height: 450px;
        display: inline;
        max-width: 350px;
        max-height: 400px;
        object-fit: cover;
        margin: 0;
    }

    .mainbox {
        text-align: center;
    }
</style>

<body>
    <div class="container">
        
        <div class='mainbox'>
            <?php
            echo "<h3>图片墙</h3>";
            echo "<div class='pbin'>";
            $folder = "comfile/photos/";   // 文件夹路径
            $files = scandir($folder);  // 遍历文件夹
            $cnt = count($files);
            $min = 0;
            if ($cnt >= 5 && !$_GET['all']) {
                $min = $cnt - 5;
            }
            $c = 1;
            for ($i = $cnt - 1; $i >= $min; $i--) {
                $v = $files[$i];
                if ($v === "." || $v == "..") continue;
                echo "<img src='" . $folder . $v . "'>";
            }echo "</div>";
            if ($min !== 0) {
                echo "<a href='?all=1'>查看全部</a>";
            }
            
            ?>
            
        </div>
    </div>
</body>
<?php view::foot()?>
