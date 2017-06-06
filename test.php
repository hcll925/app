<?php
	
	require_once ('./file.php');

	$arr = array(
		'id'=>1,
		'name'=>'hecheng',
		'aa'=>array(1,2,3=>array(1,'23d'),),
	);

	// $result = Response::json(200,'数据传送成功',$arr);
	// var_dump($result);

	$file = new File();

	if($file->cacheData('index_cache',null)) {
		
		echo "success";
	}else{
		echo "false";
	}

