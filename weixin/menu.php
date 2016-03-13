<?php

$appid = "wxd25012bb1da2b4cf";
$appsecret = "d4624c36b6795d1d99dcf0547af5443d";
$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

$output = https_request($url);
$jsoninfo = json_decode($output, true);

$access_token = $jsoninfo["access_token"];


$jsonmenu = '{
      "button":[
      {
            "name":"生活学习",
           "sub_button":[
            {
               "type":"click",
               "name":"天气预报",
               "key":"weather"
            },
			{
               "type":"click",
               "name":"地铁线路图",
               "key":"underground"
            },
            {
               "type":"click",
               "name":"查看课表",
               "key":"timetable"
            },
			{
               "type":"view",
               "name":"教务管理系统",
               "url":"http://www.scut.edu.cn/jw2005/"
            },
            {
                "type":"click",
                "name":"本周作业",
                "key":"homework"
            }]
       },
       {
           "name":"休闲娱乐",
           "sub_button":[
			{
               "type":"click",
               "name":"微信墙",
               "key":"wall"
            },
            {
               "type":"click",
               "name":"nba今日赛程",
               "key":"nba"
            },
			{
               "type":"click",
               "name":"nba明日赛程",
               "key":"nba_tom"
            },
			{
               "type":"click",
               "name":"音乐推荐",
               "key":"music"
            },
            {
               "type":"click",
               "name":"电视剧推荐",
               "key":"drama"
            }]
	   },
	   {
            "name":"关于鄙人",
           "sub_button":[
            {
               "type":"click",
               "name":"意见反馈",
               "key":"advice"
            },
            {
                "type":"click",
                "name":"联系方式",
                "key":"contact"
            }]
       }]
 }';


$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$result = https_request($url, $jsonmenu);
var_dump($result);

function https_request($url,$data = null){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

?>