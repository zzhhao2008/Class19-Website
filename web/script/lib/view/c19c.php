<?php
function echosi()
{
    $list = DB::getdata("sys/scro");
    if ($list) {
        echo '<div id="demo" class="carousel slide" data-bs-ride="carousel">
	<!-- 轮播（Carousel）指标 -->
	<div class="carousel-indicators">
        <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>';
        for ($i = 1; $i < count($list); $i++) {
            echo '<button type="button" data-bs-target="#demo" data-bs-slide-to="' . $i . '"></button>';
        }

        echo '</div>
	<!-- 轮播（Carousel）项目 -->
	<div class="carousel-inner">
		<div class="item carousel-item active">
			<a href="' . $list[0]["a"] . '"><img src="' . $list[0]["img"] . '" alt="First slide"></a>
		</div>';
        for ($i = 1; $i < count($list); $i++) {
            echo '
		<div class="carousel-item item">
		<a href="' . $list[0]["a"] . '"><img src="' . $list[$i]["img"] . '" alt="Slide"></a>
		</div>';
        }
        echo '</div>
	<!-- 轮播（Carousel）导航 -->
	<button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>';
    }
}
