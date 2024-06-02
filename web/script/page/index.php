<?php view::header(); ?>

<style>
    .pagefault {
        background: rgba(255, 255,255,0.5);
        border: 1px solid gainsboro;
        padding: 10px;
        border-radius: 3px;
        font-family: "STFS";
        font-size: 18px;
    }
    #welcometxt{
        text-align: center;
    }
</style>

<body>
    <div class="container">
        <?php
        echosi(); ?>
        <div style="text-align: center;font-size:95px;font-family:'STFS'" id="time"></div>
        <h1 id="welcometxt"></h1>
        <?=DB::getdata('sys/mainpagetext')?>
    </div>
</body>

<?php view::foot(); ?>
<script>
    function gettime() {
        var nowtime = new Date();
        var hours = nowtime.getHours().toString().padStart(2, "0");
        var minutes = nowtime.getMinutes().toString().padStart(2, "0");
        var seconds = nowtime.getSeconds().toString().padStart(2, "0");
        var milliseconds = nowtime.getMilliseconds().toString().padStart(3, "0");
        document.getElementById("time").innerHTML = hours + ":" + minutes + ":" + seconds //+ "<span class='ms'>." + milliseconds+"</span>";
        /*document.getElementById("date").innerHTML = nowtime.getFullYear() + "年" +
            (nowtime.getMonth() + 1) + "月" +
            +nowtime.getDate() + "日";*/
    }
    gettime()
    sh = setInterval(function() {
        gettime()
    }, 1000);
    fetch("api/speak")
        .then(res => res.json())
        .then(data =>
            document.getElementById("welcometxt").innerHTML = data
        )
        .catch(err => console.log(err));
</script>
<style>
    h1 {
        font-weight: 300;
    }

    .clock {
        position: absolute;
        height: 100px;
        line-height: 100px;
        top: calc(50% - 60px);
        text-align: center;
        width: 100%;
        font-weight: 300;
    }

    #date {
        position: absolute;
        height: 50px;
        line-height: 50px;
        top: calc(50% + 25px);
        text-align: center;
        width: 100%;
        font-weight: 300;
    }
</style>
