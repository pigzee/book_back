<?php
$topmenu['book'] = '';
/*第一个值为菜单名字;会用默认的lang获取,也可以直接写名字。
  第二个值为参数GET[′action′]、_GET['operation']和$_GET['do'] 用”_”格开,这个在写管理文件时要用,
  比如这个对应的网址就是 admin.php?action=test&operation=main&do=setting
  每一个数组就是一个菜单按钮。
*/
$menu['book'] = array(
    array('menu_college_manage','collegemanage'),
    array('menu_teacher_manage','teachermanage'),
    array('menu_video_manage','videomanage'),
);
?>