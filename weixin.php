<?php
	//1.��timestamp,nonce,token���ֵ�����
			$timestamp = $_GET['timestamp'];
			$nonce		 = $_GET['nonce'];
			$token		 = 'weixin';
			$signature = $_GET['signature'];
			$array		 = array( $timestamp, $nonce, $token);
			sort($array);
			
	//2.����������������ƴ��֮����sha1����
			$tmpstr = impload('', $array);
			$tmpstr = sha1($tmpstr);
		
	//3.�����ܺ���ַ�����signature���жԱȣ��жϸ������Ƿ�����΢��
			if( $tmpstr == $signature)
			{
				echo $_GET['echostr'];
				exit;
			}