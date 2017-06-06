<?php

require_once('./response.php');
require_once('./db.php');

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] :3;

// 判断分页参数
if(!is_numeric($page)||!is_numeric($pageSize)) {
	return Response::show(401,'参数不合法');
}

$offset = ($page-1)*$pageSize;

$sql = "select * from item limit ".$offset.",".$pageSize;

// 数据库连接错误 异常处理
try{
	$connect = DB::getInstance() -> connect();
}catch(Exception $e) {
	return Response::show(403,'数据库连接失败');
}

$result = mysql_query($sql,$connect);

$rows = array();
while($row = mysql_fetch_assoc($result)) {
	$rows[] = $row;
}

if($rows) {
	return Response::show(200,'数据获取成功',$rows);
}else{
	return Response::show(400,'数据获取失败',$rows);
}
