<?php
namespace Lib;

class Curl
{
	private $curl;
	public $agent = 'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.120 Safari/537.36';
	public function __construct()
	{
		$this->curl = curl_init(); // 启动一个CURL会话
	}

	public function get($url){ // 模拟获取内容函数
		curl_setopt($this->curl, CURLOPT_URL, $url); // 要访问的地址
		curl_setopt($this->curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
		curl_setopt($this->curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
		curl_setopt($this->curl, CURLOPT_HTTPGET, 1); // 发送一个常规的Post请求
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
		curl_setopt($this->curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
		$tmpInfo = curl_exec($this->curl); // 执行操作
		if (curl_errno($this->curl)) {
			echo 'Error'.curl_error($this->curl);
		}
		// curl_close($this->curl); // 关闭CURL会话
		return $tmpInfo; // 返回数据
	}

	public function post($url,$data){ // 模拟提交数据函数
		curl_setopt($this->curl, CURLOPT_URL, $url); // 要访问的地址
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent); // 模拟用户使用的浏览器
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
		curl_setopt($this->curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
		curl_setopt($this->curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
		curl_setopt($this->curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false); //取消https 验证
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false); //取消https 验证
		$tmpInfo = curl_exec($this->curl); // 执行操作
		if (curl_errno($this->curl)) {
			echo 'Error'.curl_error($this->curl);
		}
		// curl_close($this->curl); // 关键CURL会话
		return $tmpInfo; // 返回数据
	}
    public function https($url,$data){ // 模拟提交数据函数
		curl_setopt($this->curl, CURLOPT_URL, $url); // 要访问的地址
		curl_setopt($this->curl, CURLOPT_USERAGENT, $this->agent); // 模拟用户使用的浏览器
		curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
		curl_setopt($this->curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
		curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($this->curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
		curl_setopt($this->curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
		curl_setopt($this->curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
		$tmpInfo = curl_exec($this->curl); // 执行操作
		if (curl_errno($this->curl)) {
			echo 'Error'.curl_error($this->curl);
		}
		// curl_close($this->curl); // 关键CURL会话
		return $tmpInfo; // 返回数据
	}
	public function __destruct(){
		curl_close($this->curl);
	}


	static function getCurl() {
		return new self;
	}

}