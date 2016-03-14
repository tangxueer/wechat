<?php
define("TOKEN", "txe970731");
$wechatObj = new wechatCallbackapiTest();
if (!isset($_GET['echostr'])) {
    $wechatObj -> responseMsg();
}else{
    $wechatObj -> valid();
}

class wechatCallbackapiTest
{
	
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this -> checkSignature()){
            echo $echoStr;
            exit;
        }
    }
	

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);
            switch ($RX_TYPE)
            {				
                case "event":					
					$resultStr = $this->receiveEvent($postObj);	
					break;	
				case "image":			
					$arr_wall = $this->checkstep_wall($postObj->FromUserName);
					switch($arr_wall['step'])
					{
						case "uploadimage":							
							$contentStr = "您现在可以上墙了，请回复想要上墙的内容。";
							$resultStr = $this->transmitText($postObj,$contentStr,$funcFlag=0);
							
							$this->saveImage($postObj->FromUserName,$postObj->PicUrl);
							
							$this->saveStep_wall($postObj->FromUserName,'onthewall');
							break;
						case "onthewall":
							$contentStr = "发送成功！<a href = \"http://1.378711563.applinzi.com/wall.php/\">进入网页微信墙</a>再次发送请直接回复，若要退出微信墙功能请输入000。";
							$resultStr = $this->transmitText($postObj,$contentStr,$funcFlag=0);
										
							$this->messageImage($postObj->FromUserName,$postObj->PicUrl);										
							break;
					}
					break;
                case "text":
					$arr = $this->checkStep($postObj->FromUserName);
					switch ($arr['step'])
					{
						case 'adv':
							if(trim($postObj->Content) == "000")
							{								
								$contentStr = "您将退出该功能!";
								$resultStr = $this->transmitText($postObj, $contentStr, $funcFlag = 0);
								
								$this->exitStep($postObj->FromUserName);
							}else
							{
								$contentStr = "已收到您的反馈，谢谢支持~";
								$resultStr = $this->transmitText($postObj, $contentStr, $funcFlag = 0);
								
								$this->saveAdvice($postObj->FromUserName,trim($postObj->Content));
							}							
							break;
						case 'wall':																																			
							$arr_wall = $this->checkstep_wall($postObj->FromUserName);							
							switch($arr_wall['step'])
							{
								case "createname":																			
									$contentStr = "请回复图片作为您的头像。";
									$resultStr = $this->transmitText($postObj,$contentStr,$funcFlag=0);
									
									$this->saveNickname($postObj->FromUserName,trim($postObj->Content));
									
									$this->saveStep_wall($postObj->FromUserName,'uploadimage');
									break;			
								case "onthewall":
									if(trim($postObj->Content) != "000")
									{
										$contentStr = "发送成功！<a href = \"http://1.378711563.applinzi.com/wall.php/\">进入网页微信墙</a>再次发送请直接回复，若要退出微信墙功能请输入000。";
										$resultStr = $this->transmitText($postObj,$contentStr,$funcFlag=0);
										
										$this->messageText($postObj->FromUserName,trim($postObj->Content));
									}else
									{
										$contentStr = "您将退出该功能!";
										$resultStr = $this->transmitText($postObj, $contentStr, $funcFlag = 0);
										
										$this->saveStep_wall($postObj->FromUserName,'createname');
										
										$this->exitStep($postObj->FromUserName);
										
										$this->deleteName($postObj->FromUserName);																				
									}									
									break;
							}		
							break;
						case 'sub':
							$resultStr = $this->receiveText($postObj);
							break;
						default:
							break;
					}				
					break;		
				
                default:
                    $resultStr = "";
                    break;
            }
            echo $resultStr;	

        }else {
            echo "";
            exit;
        }
    }
	
/*数据库操作*/
	
	/*意见反馈功能*/
	public function dbConnect()
    {
		$db = new PDO("mysql:host=".SAE_MYSQL_HOST_M.";port=".SAE_MYSQL_PORT.";dbname=".SAE_MYSQL_DB, SAE_MYSQL_USER, SAE_MYSQL_PASS);
		$db -> query("SET NAMES 'UTF8'");
		return $db;		
    } 


	public function saveAdvice($NAME,$ADV)//把反馈意见存入数据库
	{				
	    $db = $this->dbConnect();
        $sql = "insert into advice (aid,fromusername,advice) values ('','$NAME','$ADV')";
		$db -> query($sql);	
	}
	
	public function subscribe($NAME)//添加关注后记录用户，$step初始值为sub
	{
		$db = $this->dbConnect();
		$sql = "insert into operation (sid,fromusername,step) values ('','$NAME','sub')";
		$db -> query($sql);		
	}
	
	public function saveStep($NAME,$STEP)//记录步骤,$STEP=adv/wall
	{
		$db = $this->dbConnect();
		$sql = "update operation set step = '$STEP' where fromusername='$NAME'";
		$db -> query($sql);
	}	

	public function checkStep($NAME)//检查步骤，是否进入意见反馈功能
	{
		$db = $this->dbConnect();
		$sql = "select * from operation where fromusername = '$NAME' limit 1";
		$res = $db->query($sql);
		$arr = $res->fetch();
		return $arr;
	}
		
	public function exitStep($NAME)//000退出,置step为sub
	{
		$db = $this->dbConnect();
		$sql = "update operation set step = 'sub' where fromusername = '$NAME'";
		$db -> query($sql);		
	}
 
	
	
	/*微信墙功能*/
	public function createwall($NAME)//$step初始值为createname
	{
		$db = $this->dbConnect();
		$sql = "insert into wall (wid,fromusername,date,nickname,picurl,step) values ('','$NAME',NOW(),'','','createname')";
		$db -> query($sql);		
	}		

	public function saveStep_wall($NAME,$STEP)//记录步骤,$STEP=createname/uploadphoto/onthewall
	{
		$db = $this->dbConnect();
		$sql = "update wall set step = '$STEP' where fromusername = '$NAME'";
		$db -> query($sql);
	}
	
	public function checkStep_wall($NAME)//检查步骤
	{
		$db = $this->dbConnect();
		$sql = "select * from wall where fromusername = '$NAME' limit 1";
		$res = $db->query($sql);
		$arr = $res->fetch();
		return $arr;
	}

	public function saveNickname($NAME,$NICKNAME)
	{
		$db = $this->dbConnect();
		$sql = "update wall set nickname = '$NICKNAME' where fromusername = '$NAME'";
		$db -> query($sql);		
	}
	
	public function saveImage($NAME,$IMAGE)
	{
		$db = $this->dbConnect();
		$sql = "update wall set picurl = '$IMAGE' where fromusername = '$NAME'";
		$db -> query($sql);		
	}
	
	public function messageText($NAME,$MESSAGE)
	{
		$db = $this->dbConnect();
		$sql_w = "select * from wall where fromusername = '$NAME' limit 1";
		$res = $db->query($sql_w);
		$arr = $res->fetch();
		$nickname = $arr['nickname'];
		$sql_m = "insert into message (mid,fromusername,date,nickname,text,picurl) values ('','$NAME',NOW(),'$nickname','$MESSAGE','')";
		$db -> query($sql_m);		
	}
	
	public function messageImage($NAME,$MESSAGE)
	{
		$db = $this->dbConnect();
		$sql_w = "select * from wall where fromusername = '$NAME' limit 1";
		$res = $db->query($sql_w);
		$arr = $res->fetch();
		$nickname = $arr['nickname'];
		$sql_m = "insert into message (mid,fromusername,date,nickname,text,picurl) values ('','$NAME',NOW(),'$nickname','','$MESSAGE')";
		$db -> query($sql_m);		
	}
	
	public function deleteName($NAME)
	{
		$db = $this->dbConnect();
		$sql = "delete from wall where fromusername = '$NAME'";
		$db -> query($sql);
	}
	
	public function deleteMessage($NAME)
	{
		$db = $this->dbConnect();
		$sql = "delete from message where fromusername = '$NAME'";
		$db -> query($sql);
	}

		
/*数据库结束*/
	
	
    public function receiveText($object)
    {
        $contentStr = "无效信息哟~";
        $resultStr = $this->transmitText($object, $contentStr, $funcFlag=0);
        return $resultStr;
    }
	
	
    public function receiveEvent($object)
    {
		$type = "";
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr = "欢迎关注贴心小助手！";
				$this->subscribe($object->FromUserName);
				break;
            case "unsubscribe":
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "weather":
						$contentStr = $this->forecast();
                        break;
                    case "underground":
                        $contentStr[] = array("Title" =>"广州市地铁线路图", 
                        "Description" =>"点击查看大图", 
                        "PicUrl" =>"http://378711563-picture.stor.sinaapp.com/underground.jpg", 
                        "Url" =>"http://378711563-picture.stor.sinaapp.com/underground.jpg");
                        break;
					case "timetable":
						$contentStr[] = array("Title" =>"信工四班课表", 
                        "Description" =>"点击查看大图", 
                        "PicUrl" =>"http://378711563-picture.stor.sinaapp.com/timetable.png", 
                        "Url" =>"http://378711563-picture.stor.sinaapp.com/timetable.png");
						break;
					case "homework":
						$contentStr = "暂无作业";
						break;
					case "wall":
						$contentStr = "欢迎进入微信墙功能,请回复您所要使用的昵称。";
						$type="wall";
						break;
					case 'nba':
						$contentStr = $this->nba();
						break;
					case 'nba_tom':
						$contentStr = $this->nba_tom();
						break;
					case "music":
						$contentStr = array("Title" => "last of us",
						"Description" => "游戏 美国末日(又名最后生还者) 插曲",
						"MusicUrl" => "http://378711563-music.stor.sinaapp.com/music.mp3",
						"HQMusicUrl" => "http://378711563-music.stor.sinaapp.com/music.mp3");						
						$type="music";				
						break;
					case "drama":
						$contentStr[] = array("Title" =>"BBC电视剧 神探夏洛克", 
                        "Description" =>"大英腐国三集片 主演：Benedict Cumberbatch & Martin Freeman (点击跳转B站链接)", 
                        "PicUrl" =>"http://378711563-picture.stor.sinaapp.com/sherlock.jpg", 
                        "Url" =>"http://www.bilibili.com/video/av722138/");
                        break;
					case "advice":
						$contentStr = "您将进行意见反馈，请输入您的意见！若不进行反馈，输入000退出。";
						$type="advice";
						break;
					case "contact":
						$contentStr = "QQ:378711563 e-mail:378711563@qq.com 任何问题，欢迎叨扰！";
						break;
                }
                break;
        }
        if (is_array($contentStr)){
			if($type == "music")
			{
				$resultStr = $this->transmitMusic($object,$contentStr);
			}else
			{
				$resultStr = $this->transmitNews($object, $contentStr);
			}          
        }else{
			if($type == "advice")
			{
				$this->saveStep($object->FromUserName,'adv');
				$resultStr = $this->transmitText($object, $contentStr);				
			}else
			{
				if($type == "wall")
				{					
					$this->saveStep($object->FromUserName,'wall');
					$this->createwall($object->FromUserName);
					$resultStr = $this->transmitText($object, $contentStr);	
				}else
				{
					$resultStr = $this->transmitText($object, $contentStr);
				}
				
			}
			
            
        }
        return $resultStr;
    }

    public function transmitText($object, $content, $funcFlag = 0)
    {
        $textTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					<FuncFlag>%d</FuncFlag>
					</xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $funcFlag);
        return $resultStr;
    }

    public function transmitNews($object, $arr_item, $funcFlag = 0)
    {
        if(!is_array($arr_item))
            return;

        $itemTpl = "<item>
					<Title><![CDATA[%s]]></Title>
					<Description><![CDATA[%s]]></Description>
					<PicUrl><![CDATA[%s]]></PicUrl>
					<Url><![CDATA[%s]]></Url>
					</item>";
        $item_str = "";
        foreach ($arr_item as $item)
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);

        $newsTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<Content><![CDATA[]]></Content>
					<ArticleCount>%s</ArticleCount>
					<Articles>
					$item_str</Articles>
					<FuncFlag>%s</FuncFlag>
					</xml>";

        $resultStr = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($arr_item), $funcFlag);
        return $resultStr;
    }
	
	public function transmitMusic($object, $item, $funcFlag = 0)
    {
        if(!is_array($item))
            return;

        $itemTpl = "<Title><![CDATA[%s]]></Title>
					<Description><![CDATA[%s]]></Description>
					<MusicUrl><![CDATA[%s]]></MusicUrl>
					<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>";
        $item_str = "";
        $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['MusicUrl'], $item['HQMusicUrl']);

        $newsTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[music]]></MsgType>
					<Music>
					$item_str</Music>
					<FuncFlag>%s</FuncFlag>
					</xml>";

        $resultStr = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), $funcFlag);
        return $resultStr;
    }

	public function forecast()
	{
		function compress_html($string) {  
			$string = str_replace("\r\n", '', $string);   
			$string = str_replace("\n", '', $string);   
			$string = str_replace("\t", '', $string);  
			$pattern = array (  
							"/> *([^ ]*) *</",  
							"/[\s]+/",  
							"/<!--[^!]*-->/",  
							"/\" /",  
							"/ \"/",  
							"'/\*[^*]*\*/'" 
							);  
			$replace = array (  
							">\\1<",  
							" ",  
							"",  
							"\"",  
							"\"",  
							"" 
							);  

		return preg_replace($pattern, $replace, $string);  
		}

		$url = "http://www.weather.com.cn/weather/101280101.shtml";
		$page_content = file_get_contents($url); 
		$page_content = compress_html($page_content);
		preg_match("/\"t clearfix\"(.*?)后天/",$page_content,$result);
		preg_match("/\"hidden_title\"(.*?>)/",$page_content,$today);
		preg_match("/明天(.*?)slid/",$result[1],$tomo);
		preg_match("/后天(.*?)slid/",$page_content,$tdat);

		/*今天*/
		$a = explode(" ",$today[1]);

		//气候
		$weather = $a[2];
		//最高温度
		$b = explode("/",$a[3]);
		$c = explode("°C",$b[1]);	
		$max_tem = $c[0];
		//最低温度
		$min_tem = $b[0];
		

		/*明天*/

		$a1 = explode("<",$tomo[1]);
		//气候
		$b1 = explode(">",$a1[6]);
		$weather1=$b1[1];
		//最高温度
		$d1 = explode(">",$a1[9]);
		$max_tem1 = $d1[1];
		//最低温度
		$e1 = explode(">",$a1[11]);
		$min_tem1 = $e1[1];
		//风速
		$f1 = explode(">",$a1[21]);
		$wind1 = $f1[1];


		/*后天*/
		$a2 = explode("<",$tdat[1]);
		//气候
		$b2 = explode(">",$a2[6]);
		$weather2 = $b2[1];
		//最高温度
		$d2 = explode(">",$a2[9]);
		$max_tem2 = $d2[1];
		//最低温度
		$e2 = explode(">",$a2[11]);
		$min_tem2 = $e2[1];
		//风速
		$f2 = explode(">",$a2[21]);
		$wind2 = $f2[1];
		
		if($weather == "晴"){$picurl="http://378711563-picture.stor.sinaapp.com/qing.jpg";}else
		if($weather == "多云"){$picurl="http://378711563-picture.stor.sinaapp.com/duoyun.jpg";}else
		if($weather == "阴"){$picurl="http://378711563-picture.stor.sinaapp.com/yin.jpg";}else
		if($weather == "暴风"||$weather=="台风"){$picurl="http://378711563-picture.stor.sinaapp.com/baofentaifen.jpg";}else
		if($weather == "暴雨"){$picurl="http://378711563-picture.stor.sinaapp.com/baoyu.jpg";}else
		if($weather == "冰雹"){$picurl="http://378711563-picture.stor.sinaapp.com/bingbao.jpg";}else
		if($weather == "雷阵雨"){$picurl="http://378711563-picture.stor.sinaapp.com/leizhenyu.jpg";}else
		if($weather == "雾"||$weather=="霾"){$picurl="http://378711563-picture.stor.sinaapp.com/wumai.jpg";}else
		if($weather == "小雪"||$weather=="中雪"||$weather=="大雪"){$picurl="http://378711563-picture.stor.sinaapp.com/xiaoxuedaxuezhongxue.jpg";}else
		if($weather == "雨夹雪"){$picurl="http://378711563-picture.stor.sinaapp.com/yujiaxue.jpg";}else
		if($weather == "小雨"||$weather=="中雨"||$weather=="大雨"||$weather=="阵雨"){$picurl="http://378711563-picture.stor.sinaapp.com/xiaoyuzhongyudayuzhenyu.jpg";}
		
		if($weather1 == "晴"){$picurl1="http://378711563-picture.stor.sinaapp.com/qing.jpg";}else
		if($weather1 == "多云"){$picurl1="http://378711563-picture.stor.sinaapp.com/duoyun.jpg";}else
		if($weather1 == "阴"){$picurl1="http://378711563-picture.stor.sinaapp.com/yin.jpg";}else
		if($weather1 == "暴风"||$weather=="台风"){$picurl1="http://378711563-picture.stor.sinaapp.com/baofentaifen.jpg";}else
		if($weather1 == "暴雨"){$picurl1="http://378711563-picture.stor.sinaapp.com/baoyu.jpg";}else
		if($weather1 == "冰雹"){$picurl1="http://378711563-picture.stor.sinaapp.com/bingbao.jpg";}else
		if($weather1 == "雷阵雨"){$picurl1="http://378711563-picture.stor.sinaapp.com/leizhenyu.jpg";}else
		if($weather1 == "雾"||$weather1=="霾"){$picurl1="http://378711563-picture.stor.sinaapp.com/wumai.jpg";}else
		if($weather1 == "小雪"||$weather1=="中雪"||$weather1=="大雪"){$picurl1="http://378711563-picture.stor.sinaapp.com/xiaoxuedaxuezhongxue.jpg";}else
		if($weather1 == "雨夹雪"){$picurl="http://378711563-picture.stor.sinaapp.com/yujiaxue.jpg";}else
		if($weather1 == "小雨"||$weather1=="中雨"||$weather1=="大雨"||$weather1=="阵雨"){$picurl1="http://378711563-picture.stor.sinaapp.com/xiaoyuzhongyudayuzhenyu.jpg";}
		
		if($weather2 == "晴"){$picurl2="http://378711563-picture.stor.sinaapp.com/qing.jpg";}else
		if($weather2 == "多云"){$picurl2="http://378711563-picture.stor.sinaapp.com/duoyun.jpg";}else
		if($weather2 == "阴"){$picurl2="http://378711563-picture.stor.sinaapp.com/yin.jpg";}else
		if($weather2 == "暴风"||$weather2=="台风"){$picurl2="http://378711563-picture.stor.sinaapp.com/baofentaifen.jpg";}else
		if($weather2 == "暴雨"){$picurl2="http://378711563-picture.stor.sinaapp.com/baoyu.jpg";}else
		if($weather2 == "冰雹"){$picurl2="http://378711563-picture.stor.sinaapp.com/bingbao.jpg";}else
		if($weather2 == "雷阵雨"){$picurl2="http://378711563-picture.stor.sinaapp.com/leizhenyu.jpg";}else
		if($weather2 == "雾"||$weather2=="霾"){$picurl2="http://378711563-picture.stor.sinaapp.com/wumai.jpg";}else
		if($weather2 == "小雪"||$weather2=="中雪"||$weather2=="大雪"){$picurl2="http://378711563-picture.stor.sinaapp.com/xiaoxuedaxuezhongxue.jpg";}else
		if($weather2 == "雨夹雪"){$picurl2="http://378711563-picture.stor.sinaapp.com/yujiaxue.jpg";}else
		if($weather2 == "小雨"||$weather2=="中雨"||$weather2=="大雨"||$weather2=="阵雨"){$picurl2="http://378711563-picture.stor.sinaapp.com/xiaoyuzhongyudayuzhenyu.jpg";}
		
		
		$con[] = array("Title" =>"广州天气预报");
		$con[] = array("Title" =>"今天 天气:".$weather." 温度:".$max_tem."-".$min_tem."℃",
		"PicUrl" =>$picurl);
		$con[] = array("Title" =>"明天 天气:".$weather1." 温度:".$max_tem1."-".$min_tem1." 风速:".$wind1,
		"PicUrl" =>$picurl1);
		$con[] = array("Title" =>"后天 天气:".$weather2." 温度:".$max_tem2."-".$min_tem2." 风速:".$wind2,
		"PicUrl" =>$picurl2);             

		return $con;

	}
		
	public function nba()
	{
		function compress_html($string) {  
			$string = str_replace("\r\n", '', $string);   
			$string = str_replace("\n", '', $string);   
			$string = str_replace("\t", '', $string);  
			$pattern = array (  
							"/> *([^ ]*) *</",  
							"/[\s]+/",  
							"/<!--[^!]*-->/",  
							"/\" /",  
							"/ \"/",  
							"'/\*[^*]*\*/'" 
							);  
			$replace = array (  
							">\\1<",  
							" ",  
							"",  
							"\"",  
							"\"",  
							"" 
							);  

		return preg_replace($pattern, $replace, $string);  
		}
		
		$d = intval(date('W',strtotime(date("Y-m-d"))));//计算今日是本年的第几周
		$url = "http://nba.sports.163.com/schedule/2016-{$d}.html";//注意：url里的2016-{$d}随周次而变化
		$page_content = file_get_contents($url); 
		$page_content = compress_html($page_content);
		preg_match("/\"in-con2 clearfix\"(.*?)\"blank20\"/",$page_content,$result);	
		$day=explode("blank6",$result[1]);		
		
		for($i = 1;$i <= 7;$i++)
		{
			//日期
			$k = 2*$i-1;
			$date = explode(">",$day[$k]);			
			$date = explode("<",$date[3]);
			
			$date = $date[0];	
			
			$one = explode("nolbo",$day[2*$i]);
			
			$max = count($one);
			
			$now = date("Y年m月d日");
			$week = date("w");		
			if($week == 0){$week = "日";}else
			if($week == 1){$week = "一";}else
			if($week == 2){$week = "二";}else
			if($week == 3){$week = "三";}else
			if($week == 4){$week = "四";}else
			if($week == 5){$week = "五";}else
			if($week == 6){$week = "六";}
			$now = $now." 星期".$week;

			if($max <= 10)
			{
				for($j = 1;$j <= $max-1;$j++)
				{													
					$con = explode("<",$one[$j]);
					
					//开球时间		
					$time = explode(">",$con[0]);
					$time = $time[1];

					//客场球队
					$team1 = explode(">",$con[4]);	
					$team1 = $team1[1];

					//比分(对于未完成的比赛，比分暂用VS表示)
					$score = explode(">",$con[7]);	
					$score = $score[1];

					//主场球队
					$team2 = explode(">",$con[12]);	
					$team2 = $team2[1];

					if($now == $date)
					{
						$res[0] = array("Title" => $now."赛程");
						$res[$j] = array("Title" => $time."  客场: ".$team1.$score."主场: ".$team2);
					}							
				}
			}else
			{
				for($j = 1;$j <= 9;$j++)
				{													
					$con = explode("<",$one[$j]);
					
					//开球时间		
					$time = explode(">",$con[0]);
					$time = $time[1];

					//客场球队
					$team1 = explode(">",$con[4]);	
					$team1 = $team1[1];

					//比分(对于未完成的比赛，比分暂用VS表示)
					$score = explode(">",$con[7]);	
					$score = $score[1];

					//主场球队
					$team2 = explode(">",$con[12]);	
					$team2 = $team2[1];

					if($now == $date)
					{
						$res[0] = array("Title" => $now."赛程(当天赛程超过十场，省略部分赛程)");
						$res[$j] = array("Title" => $time."  客场: ".$team1.$score."主场: ".$team2);
					}							
				}
			}
		}
		
		return $res;		
	}
	
	public function nba_tom()
	{
		function compress_html($string) 
		{  
			$string = str_replace("\r\n", '', $string);   
			$string = str_replace("\n", '', $string);   
			$string = str_replace("\t", '', $string);  
			$pattern = array (  
							"/> *([^ ]*) *</",  
							"/[\s]+/",  
							"/<!--[^!]*-->/",  
							"/\" /",  
							"/ \"/",  
							"'/\*[^*]*\*/'" 
							);  
			$replace = array (  
							">\\1<",  
							" ",  
							"",  
							"\"",  
							"\"",  
							"" 
							);  

		return preg_replace($pattern, $replace, $string);  
		}
		$d = intval(date('W',strtotime(date("Y-m-d",strtotime("+1 day")))));//计算明日是本年的第几周
		$url = "http://nba.sports.163.com/schedule/2016-{$d}.html";//注意：url里的2016-8随周次而变化，记得随时刷新
		$page_content = file_get_contents($url); 
		$page_content = compress_html($page_content);
		preg_match("/\"in-con2 clearfix\"(.*?)\"blank20\"/",$page_content,$result);	
		$day = explode("blank6",$result[1]);		
		
		for($i = 1;$i <= 7;$i++)
		{
			//日期
			$k = 2*$i-1;
			$date = explode(">",$day[$k]);			
			$date = explode("<",$date[3]);
			
			$date = $date[0];	
			
			$one = explode("nolbo",$day[2*$i]);
			
			$max = count($one);
			
			$tom = date("Y年m月d日",strtotime("+1 day"));
			
			$week = date("w");		
			if($week == 0){$week = "一";}else
			if($week == 1){$week = "二";}else
			if($week == 2){$week = "三";}else
			if($week == 3){$week = "四";}else
			if($week == 4){$week = "五";}else
			if($week == 5){$week = "六";}else
			if($week == 6){$week = "日";}
			$tom = $tom." 星期".$week;
			
			if($max <= 10)
			{
				for($j = 1;$j <= $max-1;$j++)
				{													
					$con = explode("<",$one[$j]);
					
					//开球时间		
					$time = explode(">",$con[0]);
					$time = $time[1];

					//客场球队
					$team1 = explode(">",$con[4]);	
					$team1 = $team1[1];

					//比分(对于未完成的比赛，比分暂用VS表示)
					$score = explode(">",$con[7]);	
					$score = $score[1];

					//主场球队
					$team2 = explode(">",$con[12]);	
					$team2 = $team2[1];

					if($tom == $date)
					{
						$res[0] = array("Title" => $tom."赛程");
						$res[$j] = array("Title" => $time." 客场 ".$team1.$score." 主场 ".$team2);
					}							
				}
			}else
			{
				for($j = 1;$j <= 9;$j++)
				{													
					$con = explode("<",$one[$j]);
					
					//开球时间		
					$time = explode(">",$con[0]);
					$time = $time[1];

					//客场球队
					$team1 = explode(">",$con[4]);	
					$team1 = $team1[1];

					//比分(对于未完成的比赛，比分暂用VS表示)
					$score = explode(">",$con[7]);	
					$score = $score[1];

					//主场球队
					$team2 = explode(">",$con[12]);	
					$team2 = $team2[1];

					if($tom == $date)
					{
						$res[0] = array("Title" => $tom."赛程(当天赛程超过十场，省略部分赛程)");
						$res[$j] = array("Title" => $time." 客场 ".$team1.$score." 主场 ".$team2);
					}							
				}
			}
		}

		return $res;
	}
}
?>