# 19中队官网网站
## 开源版本
环境：WAP

推荐配置：

CPU:1.5Ghz 2C4T 64位

内存：4GiB+

硬盘：100GiB可用空间

操作系统：windows Server 2022

Apache 

PHP 7.3.4

### 适合50人左右班级
！ 需要能够使用PHP编程的技术人员支持
## 部署步骤
1.复制全部文件到网站目录下，启动wen服务

2.修改数据：身份证/考号绑定到 /data/class19/data.php 文件中，格式为

```php
<?php return array (
  "姓名"=>md5("身份证/考号"),
  "admin"=>"admin" //必须
);?>
```
3.访问网站，登录管理员账号admin/admin

4.修改相关运营数据

5.荣誉墙/照片墙图片在  /web/comfile 下

6.轮播内容在 /data/sys/scro.php

7.导航栏在 web\script\view\nav.php
