<?php
if ($_POST['id']) {
    theme::changemy($_POST['id']);
    theme::changeid($_POST['eid']);
    jsjump("/profile");
}
view::header("主题设置");
?>
<form action="" method="post">
    <label for="sel1" class="form-label">选择主题：</label>
    <select class="form-select" id="sel1" name="id">
        <option value="light">亮色（默认）</option>
        <option value="dark">夜晚</option>
        <option value="blue">蓝</option>
        <option value="dai">黛</option>
        <option value="pink">猛男粉</option>
        <option value="kun">坤色</option>
    </select>
    编辑器主题：<input name="eid" value="tomorrow">
    <input  class="btn btn-primary" value="提交" type="submit">
</form>

<?php view::foot() ?>