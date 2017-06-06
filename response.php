<?php
	
	class Response{
		const JSON = 'json';
		/**
		*按综合方式输出通信数据
		*@param integer $code 状态码
		*@param string $message 提示信息
		*@param array $data 数据
		*@param string $type 类型
		*return string
		*/
		public static function show($code,$message='',$data=array(),$type=self::JSON) {
			if(!is_numeric($code)) {
				return '';
			}
			$result = array(
				'code'=> $code,
				'message'=> $message,
				'data'=> $data
			);

			$type = isset($_GET['format']) ? $_GET['format'] : self::JSON;

			if($type == 'json' ) {
				self::json($code,$message,$data);
				exit;
			}elseif ($type == 'xml') {
				self::xmlEncode($code,$message,$data);
				exit;
			}elseif ($type == 'array') {
				var_dump($result);
			}else {
				// TODO
			}
		}


		/**
		*按json方式输出通信数据
		*@param integer $code 状态码
		*@param string $message 提示信息
		*@param array $data 数据
		*return string
		*/
		public static function json($code,$message='',$data=array()) {

			if(!is_numeric($code)){
				return;
			} 

			$result = array(
				'code'    => $code,
				'message' => $message,
				'data'    => $data
			);
			echo json_encode($result);
			exit;
		}

		/**
		*xml拼装字符串的方式输出通信数据
		*/
		public static function xml(){
			header("Content-Type:text/xml");
			$xml= "<?xml version='1.0' encoding='UTF-8'?>";
			$xml.= "<root>";
			$xml.= "<code>200</code>";
			$xml.= "<message>数据返回成功</message>";
			$xml.= "<data>";
			$xml.= "<id>1</id>";
			$xml.= "<name>hecheng</name>";
			$xml.= "</data>";
			$xml.= "</root>";
			echo $xml;
			exit;
		}

		/**
		*	按XML方式输出通信数据
		*	@param $code integer 状态码
		*	@param $message string 提示信息
		*	@param $data array 数据
		*	return array
		*/
		public static function xmlEncode($code,$message='',$data=array()) {

			if(!is_numeric($code)) {
				return;
			}	
			
			$result = array(
				'code' => $code,
				'message' => $message,
				'data'=> $data
			);

			header("Content-Type:text/xml");
			$xml= "<root>";
			$xml.= self::xmlToEncode($result);
			$xml.= "</root>";
			echo $xml;
			exit();
		}
		/**
		*将数组转换为 key 对应节点 value 对应值
		*@param $data array 数据
		*return string
		*/
		public static function xmlToEncode($data) {
			$xml= $attr ='';
			foreach ($data as $key => $value) {
				// xml 节点不能为数字 如果为数字将其拼接
				if(is_numeric($key)) {
					$attr= " id='{$key}'"; // 主要 id 前面空格分开
					$key= "item";
				}
				$xml.= "<{$key}{$attr}>";
				$xml.= is_array($value)?self::xmlToEncode($value):$value; // 递归
				$xml.= "</{$key}>";
			}
			return $xml;
		}
		
	}

	$data = array(
		'id'=>1,
		'name'=>'hecheng',
		'aa'=>array(1,2,3=>array(1,'23d'),),
	);
	// Response::xmlEncode(200,'成功',$data);
	// Response::show(200,'测试成功',$data);