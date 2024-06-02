<?php
view::header("404 Error");
echo "<h1>I'am Sorry,But The Page ".Router::getUri()." is not Exists!</h1>";
?>
<hr>
<p>QwQ~ 你的网页走丢了</p>

<?php view::foot();