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
		
		
		
		public function reponseMsg(){
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
		
		//强大的curl工具
		function http_curl(){
			//1.初始化curl
			$ch = curl_init();
			$url = 'http://www.imooc.com';
			//2.设置curl的参数
			curl_setopt( $ch, CURLOPT_URL, $url);
			//3.返回
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);			
			//4.采集
			$output = curl_exec($ch);
			//5.关闭
			curl_close($ch);
			var_dump( $output ); //打印
		}
		
		//获得access token值
		function getAccessToken(){
			//1.请求url地址
			$appid = 'skaldflksa';
			$appsecret = 'slfasdkfjsldfssdkfdfd';
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
			//2.初始化
			$ch = curl_init();
			//3.设置参数
			curl_setopt( $ch, CURLOPT_URL, $url);			
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);		
			//4.调用接口
		  $res = curl_exec($ch);
		  //5.关闭curl
		  curl_close($ch);
		  
		  if(curl_errno($ch)){
		  	var_dump(curl_eror($ch));
		  }
		  
		  //json_decode把json转化为数组， json_encode把数组转化为json
		  $arr = json_decode($res, true);
		  var_dump($arr);
		}
		
		//
		function getWeixinServerIp(){
			$accesstoken = "";//里面的值是上面的getAccessToken函数获得的access token值
			$url = "https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token=".$accesstoken;
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url);			
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);		
		  $res = curl_exec($ch);
		  curl_close($ch);
		  
		  if(curl_errno($ch)){
		  	var_dump(curl_eror($ch));
		  }
		  
		  //json_decode把json转化为数组， json_encode把数组转化为json
		  $arr = json_decode($res, true);
		  echo "<pre>";
		  var_dump($arr);
		  echo "</pre>";
		}