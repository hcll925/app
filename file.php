<?php
	
class File {
	private $_dir;
	const EXT = '.txt';
	public function __construct() {
		$this->_dir = dirname(__FILE__).'/file/'; // 获取文件当前目录
	}

	/**
	*	处理缓存数据
	*	@param string $key 缓存文件名
	*	@param string $value 缓存数据
	*	@param string $path 路径
	*/
	public function cacheData($key,$value='',$cacheTime=0) {
		$filename = $this->_dir . $key . self::EXT;

		// 当值不为空的时候  写入缓存
		if($value !== '') {   // 将value值写入缓存
			if(is_null($value)) { // 当 value 值为 NULL 的时候  删除缓存
				return @unlink($filename);
			}
			$dir = dirname($filename);
			if(!is_dir($dir)) {		// 如果文件夹不存在
				mkdir($dir,0777);
			}

			$cacheTime = sprintf('%011d',$cacheTime); // 设置 11 为数 不够补 0
			// file_put_contents  向文件写入数据  数据只能为 string
			return file_put_contents($filename, $cacheTime . json_encode($value));
		}

		// 当不传 value 值得时候 读取缓存
		if(!is_file($filename)) { // 文件不存在
			return FALSE;
		}
		$contents = file_get_contents($filename);
		$cacheTime = (int)substr($contents,0,11);	
		$value = substr($contents,11);
		// 设置缓存失效时间 当缓存时间+文件时间 < 当前时间 说明缓存失效 删除缓存文件
		// 缓存时间为 0 的时候 为永久生效
		if($cacheTime != 0 && ($cacheTime + filemtime($filename) < time())) { // filemtime  文件上次修改时间 返回时间戳
			unlink($filename);
			return FALSE;
		}
		// json_decode 不传入 true 参数的时候 返回对象 否则返回 字符串
		return json_decode($value,true);
	}
}


