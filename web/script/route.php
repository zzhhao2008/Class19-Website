<?php
Router::any("/", "index");
Router::any("pic", "photowall/index");
Router::any("honour", "honour");

Router::any("cx", "cx/guest/list");
Router::any("cx/show", "cx/guest/show");
Router::any("api/speak","api/goodspeak");
Router::any("support","support");


Router::guest("logup", "user/logup");
Router::guest("profile","user/login");//登录页面

Router::login("profile","user/profile");
Router::login("themeset","user/themeset");
Router::login("change","user/change");


Router::admin("user_manage", "admin/user/manage");
Router::admin("user_edit", "admin/user/edit");
Router::admin("user_cr_rm", "admin/user/crm");

Router::admin("cx_subject","cx/subject/assubject");
Router::admin("cx_manage", "cx/manage/managemain");
Router::admin("cx/manage", "cx/manage/managemain");
Router::admin("addexam", "cx/manage/add");
Router::admin("addexamdone", "cx/manage/adddone");
Router::admin("cx_m", "cx/manage/mdetailnew");
Router::admin("cx/manage/detial", "cx/manage/mdetailnew");
Router::admin("dp","cx/subject/dp");

Router::admin("mpt","/admin/sys/text");

