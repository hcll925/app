<?php

require_once('./response.php');
require_once('./db.php');
require_once('./file.php');

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$pageSize = isset($_GET['pagesize']) ? $_GET['pagesize'] :3;

// 判断分页参数
if(!is_numeric($page)||!is_numeric($pageSize)) {
	return Response::show(401,'参数不合法');
}

$offset = ($page-1)*$pageSize;

$sql = "select * from item limit ".$offset.",".$pageSize;

$cache = new File();
$rows = array(); // 存储数据

// 如果缓存不存在
if(!$rows = $cache->cacheData('index_cache'. $page.'-'. $pageSize)) {
	// 从数据库提取数据
	// 数据库连接错误 异常处理
	try{
		$connect = DB::getInstance() -> connect();
	}catch(Exception $e) {
		return Response::show(403,'数据库连接失败');
	}
	$result = mysql_query($sql,$connect);

	while($row = mysql_fetch_assoc($result)) {  // mysql_fetch_array 
		$rows[] = $row;
	}

	// 数据提取成功 写入缓存
	if($rows) {
		$cache->cacheData('index_cache'.$page.'-'.$pageSize,$rows,10);
	}
}


if($rows) {
	return Response::show(200,'数据获取成功',$rows);
}else{
	return Response::show(400,'数据获取失败',$rows);
}
