<? php
		
		public function index(){
					//1.��timestamp,nonce,token���ֵ�����
					$timestamp = $_GET['timestamp'];
					$nonce		 = $_GET['nonce'];
					$token		 = 'weixin';
					$signature = $_GET['signature'];
					$echostr   = $_GET['echostr'];
					$array		 = array( $timestamp, $nonce, $token);
					sort($array);
					
					//2.����������������ƴ��֮����sha1����
					$tmpstr = impload('', $array);
					$tmpstr = sha1($tmpstr);
				
					//3.�����ܺ���ַ�����signature���жԱȣ��жϸ������Ƿ�����΢��
					if( $tmpstr == $signature && $echostr)
					{
						//��һ�ν���weixin api�ӿڵ�ʱ��,΢��server�ᷢ��echostr��������sever��֮��㲻����
						echo $echostr;
						exit;
					}else{
						$this->reponseMsg();
					}
		}
		
		
		
		public function reponseMsg(){
			//1.��ȡ��΢�����͹����ĵ�post���ݣ�xml��ʽ��
			$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
			
			//2.������Ϣ���ͣ������ûظ����ͺ�����
		  $postObj = simplexml_load_string( $postArr );
		 					//���յ�����xml��ʽ 
							// <xml>
							// <ToUserName><![CDATA[toUser]]></ToUserName>
							// <FromUserName><![CDATA[fromUser]]></FromUserName> 
							// <CreateTime>1348831860</CreateTime>
							// <MsgType><![CDATA[text]]></MsgType>
							// <Content><![CDATA[this is a test]]></Content>
							// <MsgId>1234567890123456</MsgId>
							// </xml>
 		  
						  //$postObj->ToUserName = '';
						  //$postObj->FromUserName = '';
						  //$postObj->CreateTime = '';
						  //$postObj->MsgType = '';
						  //$postObj->Event = '';
		  
		  //3.�жϸ����ݰ��Ƿ��Ƕ��ĵ��¼�����
		  if ( strtolower( $postObj->MsgType ) == 'event') {
		  	//����ǹ�עsubscribe�¼�
		  	if( strtolower( $postObj->Event ) == 'subscribe'){
		  		//�ظ��û���Ϣ
		  		$toUser = $postObj->FromUserName;
		  		$fromUser = $postObj->ToUserName;
		  		$time = time();
		  		$msgType = 'text';
		  		$content = '��ӭ��ע���ǵ�΢�Ź����˺�';
		  		$template = "
								  		<xml>
											<ToUserName><![CDATA[%s]]></ToUserName>
											<FromUserName><![CDATA[%s]]></FromUserName>
											<CreateTime>%s</CreateTime>
											<MsgType><![CDATA[%s]]></MsgType>
											<Content><![CDATA[%s]]></Content>
											</xml>";
					$info = sprintf($template, $toUser, $fromUser, $time, $msgType, $content);
					echo $info;

								//���͵�����xml��ʽ
								//<xml>
								//<ToUserName><![CDATA[toUser]]></ToUserName>
								//<FromUserName><![CDATA[fromUser]]></FromUserName>
								//<CreateTime>12345678</CreateTime>
								//<MsgType><![CDATA[text]]></MsgType>
								//<Content><![CDATA[���]]></Content>
								//</xml>
					

		  	}
		  }
		  
		}
		
		//ǿ���curl����
		function http_curl(){
			//1.��ʼ��curl
			$ch = curl_init();
			$url = 'http://www.imooc.com';
			//2.����curl�Ĳ���
			curl_setopt( $ch, CURLOPT_URL, $url);
			//3.����
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);			
			//4.�ɼ�
			$output = curl_exec($ch);
			//5.�ر�
			curl_close($ch);
			var_dump( $output ); //��ӡ
		}
		
		//���access tokenֵ
		function getAccessToken(){
			//1.����url��ַ
			$appid = 'skaldflksa';
			$appsecret = 'slfasdkfjsldfssdkfdfd';
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
			//2.��ʼ��
			$ch = curl_init();
			//3.���ò���
			curl_setopt( $ch, CURLOPT_URL, $url);			
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);		
			//4.���ýӿ�
		  $res = curl_exec($ch);
		  //5.�ر�curl
		  curl_close($ch);
		  
		  if(curl_errno($ch)){
		  	var_dump(curl_eror($ch));
		  }
		  
		  //json_decode��jsonת��Ϊ���飬 json_encode������ת��Ϊjson
		  $arr = json_decode($res, true);
		  var_dump($arr);
		}
		
		//
		function getWeixinServerIp(){
			$accesstoken = "";//�����ֵ�������getAccessToken������õ�access tokenֵ
			$url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=".$accesstoken;
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url);			
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);		
		  $res = curl_exec($ch);
		  curl_close($ch);
		  
		  if(curl_errno($ch)){
		  	var_dump(curl_eror($ch));
		  }
		  
		  //json_decode��jsonת��Ϊ���飬 json_encode������ת��Ϊjson
		  $arr = json_decode($res, true);
		  echo "<pre>";
		  var_dump($arr);
		  echo "</pre>";
		}