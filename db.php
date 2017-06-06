<?php

// 单例模式连接数据库
// 单例模式指 这个类只能有一个实例对象
/**
*	单例模式三大原则
*	1.构造函数需要标记为非 public（防止外部使用 new 操作符创建对象），单例类不能在其他类中实例化，只能被其自身实例化;
*	2.拥有一个保存类的实例的静态成员变量 $_instance(类的实例用变量的方式保存);
*	3.拥有一个访问这个实例的公共的静态方法（入口）;
*/

class DB {
	static private $_instance; // 保存类的变量
	static private $_connectSource; // 连接数据库资源
	private $_dbConfig = array(
		'host' => '127.0.0.1',
		'user' => 'root',
		'pwd'  => 'root',
		'database' => 'test',
	);

	private function __construct() {
	}
	// 实例化类分方法
	static public function getInstance() {
		if(!(self::$_instance instanceof self)){  // instanceof 检测类是否被实例化
			self::$_instance = new self();  // 实例化本身 存放至 $_instance 变量
		}	
		return self::$_instance;
	}

	public function connect() {
		if(!self::$_connectSource) {
			// 连接数据库
			self::$_connectSource = mysql_connect($this->_dbConfig['host'],$this->_dbConfig['user'],$this->_dbConfig['pwd']);

			if(!self::$_connectSource) {
				throw new Exception("mysql connect error".mysql_error());
			}

			// 选择数据库
			mysql_select_db($this->_dbConfig['database'],self::$_connectSource);
			// 设置字符集
			mysql_query("set names UTF8",self::$_connectSource);
		}
		
		// 返回一个连接资源
		return self::$_connectSource;
	}
}
