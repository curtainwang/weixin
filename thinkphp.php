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
		
		
		
		public function reponseMsg()
		{
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
		