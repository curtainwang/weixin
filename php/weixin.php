<?php
	//1.将timestamp,nonce,token按字典排序
			$timestamp = $_GET['timestamp'];
			$nonce		 = $_GET['nonce'];
			$token		 = 'weixin';
			$signature = $_GET['signature'];
			$array		 = array( $timestamp, $nonce, $token);
			sort($array);
			
	//2.将排序后的三个参数拼接之后用sha1加密
			$tmpstr = impload('', $array);
			$tmpstr = sha1($tmpstr);
		
	//3.将加密后的字符串与signature进行对比，判断该请求是否来自微信
			if( $tmpstr == $signature)
			{
				echo $_GET['echostr'];
				exit;
			}