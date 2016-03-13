<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>微信墙</title>
<style>
p
{
    font-family: Microsoft YaHei; 
    font-size: 30px;
}

body
{
    background-color: #B4EEB4;
}
           
    
#msgBox div 
{
	padding: 19px;
	margin-bottom: 20px;
	background: #F0FFF0;
	border: 2px solid #9bcd9b;
        
    font-family:黑体;
    font-size:30px;
    color:#66CD00;
}
    
    
</style>
</head>
<body>

<p>
<img height=60 width=60 src="http://378711563-picture.stor.sinaapp.com/logo.png" />
微信墙
</p>   
<div id="msgBox">
<?php
$db = new PDO("mysql:host=".SAE_MYSQL_HOST_M.";port=".SAE_MYSQL_PORT.";dbname=".SAE_MYSQL_DB, SAE_MYSQL_USER, SAE_MYSQL_PASS);
$db -> query("SET NAMES 'UTF8'");
    
$wxQuery="select * from message order by mid desc";
$wxResult=$db->query($wxQuery);

while ($wxRow=$wxResult->fetch())
{
    $lastID or $lastID = $wxRow[0];
    $content = $wxRow[3];
	$picture = $wxRow[4];
	$fromusername = $wxRow[1];
    
	$wxQuery_w="select * from wall where fromusername='{$fromusername}'";
	$wxResult_w=$db->query($wxQuery_w);
	$wxRow_w=$wxResult_w->fetch();
    
	$nickname = $wxRow_w[3];
    $picurl = $wxRow_w[4];
    $picurl=substr_replace($picurl,"logo",14,3);
	$content = $nickname." : ".$content;
?>
<div>
    <img height=100 width=100 src="<?php echo $picurl;?>" />

<?php 
	if(!empty($picture))
	{
        $picture=substr_replace($picture,"logo",14,3);
        echo $content;
        
?>
	<img height=90 width=90 src="<?php echo $picture;?>"/>
<?php
    }else{          
		echo $content; 
    }
?>		
</div>
<?php
}
$lastID = (int)$lastID;
?>               

<script src="jquery.js"></script>
<script>
var lastID = <?php echo $lastID; ?>;
function getMessages() {
       $.ajax({
        url: "message.php?lastID=" + lastID + "&v=" + (new Date()/1),
        dataType: "json",
             error: function(){  
   
        },  
        success: function(data){           
				$.each(data,function(i,n){
                    	var str1 = n.split("@");
						var str2 = str1[0].split("!");
                    	
						if(str2[1]=="")
						{
							message = '<div>' + '<img height=100 width=100 src=' + str1[1] + '/>' + str2[0] + '</div>';
						}else
						{
							message = '<div>' + '<img height=100 width=100 src=' + str1[1] + '/>' + str2[0] + '<img height=90 width=90 src=' + str2[1] + '/>' + '</div>';
						}
                       $(message).prependTo('#msgBox').hide().slideDown('slow');
                       	lastID = i;
				});


        }
    });	
	window.setTimeout(getMessages, 1500);
}
getMessages();
</script>
</div>
</body>
</html>