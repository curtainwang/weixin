<? php
		
		public function index(){
					//1.将timestamp,nonce,token按字典排序
					$timestamp = $_GET['timestamp'];
					$nonce		 = $_GET['nonce'];
					$token		 = 'weixin';
					$signature = $_GET['signature'];
					$echostr   = $_GET['echostr'];
					$array		 = array( $timestamp, $nonce, $token);
					sort($array);
					
					//2.将排序后的三个参数拼接之后用sha1加密
					$tmpstr = impload('', $array);
					$tmpstr = sha1($tmpstr);
				
					//3.将加密后的字符串与signature进行对比，判断该请求是否来自微信
					if( $tmpstr == $signature && $echostr)
					{
						//第一次接入weixin api接口的时候,微信server会发送echostr给第三方sever，之后便不会了
						echo $echostr;
						exit;
					}else{
						$this->reponseMsg();
					}
		}
		
		
		
		public function reponseMsg()
		{
			//1.获取到微信推送过来的的post数据（xml格式）
			$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
			
			//2.处理消息类型，并设置回复类型和内容
		  $postObj = simplexml_load_string( $postArr );
		 					//接收的数据xml格式 
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
		  
		  //3.判断该数据包是否是订阅的事件推送
		  if ( strtolower( $postObj->MsgType ) == 'event') {
		  	//如果是关注subscribe事件
		  	if( strtolower( $postObj->Event ) == 'subscribe'){
		  		//回复用户消息
		  		$toUser = $postObj->FromUserName;
		  		$fromUser = $postObj->ToUserName;
		  		$time = time();
		  		$msgType = 'text';
		  		$content = '欢迎关注我们的微信公众账号';
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

								//发送的数据xml格式
								//<xml>
								//<ToUserName><![CDATA[toUser]]></ToUserName>
								//<FromUserName><![CDATA[fromUser]]></FromUserName>
								//<CreateTime>12345678</CreateTime>
								//<MsgType><![CDATA[text]]></MsgType>
								//<Content><![CDATA[你好]]></Content>
								//</xml>
					

		  	}
		  }
		  
		}
		