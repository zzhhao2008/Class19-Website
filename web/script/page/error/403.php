<?php
view::header("403 Error");
echo "<h1>I'am Sorry,But The Page ".Router::getUri()." has been blocked!</h1>";
?>
<hr>
<p>AwA 你不应该来这里</p>

<?php view::foot();