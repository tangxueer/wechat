<?php
class mysql{
	private $table;
	private $pdo;
	private $rs;
	
	function __construct($table)
	{
		$this->table=$table;
		$this->connect();
	}
	
	function connect()
	{
		try{
			$this->pdo = new PDO("mysql:host=".SAE_MYSQL_HOST_M.";port=".SAE_MYSQL_PORT.";dbname=".SAE_MYSQL_DB, SAE_MYSQL_USER, SAE_MYSQL_PASS);
			$this->pdo -> query("SET NAMES 'UTF8'");
			$this->pdo -> setattribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
			$this->pdo -> setattribute(PDO::ATTR_EMULATE_PREPARES,false);
		}
		catch(Exception $e)
		{
			echo "connect Failed:".$e->getMessage();
		}
	}
	
	/*例如，$name格式为'`id,`user`,`title`,`content`,`lastdate`,`hit`'
	$value格式为"'','叶良臣','记住','我叫叶良臣',NOW(),'1'"*/
	function insert($table,$name,$value)
	{
		$this->pdo -> beginTransaction();
		try{
			$exec_insert = $this->pdo->exec
			("insert into $table ($name) value ($value)");
			$this->pdo -> lastInsertId();			
			$this->pdo -> commit();
		}
		catch(Exception $e)
		{
			$this->pdo -> rollBack();
			echo "insert Failed:".$e->getMessage();
		}
	}
	
	/*例如，$value格式为"`user`='赵日天',`title`='不服',`content`='我赵日天不服'"
	$name格式为'id=41'*/
	function update($table,$value,$name)
	{
		$this->pdo -> beginTransaction();
		try{
			$exec_update = $this->pdo->exec
			("update $table set $value where $name");
			$this->pdo -> commit();
		}
		catch(Exception $e)
		{
			$this->pdo -> rollBack();
			echo "update Failed:".$e->getMessage();
		}
	}
	
	/*例如,$name格式为'`user`="赵日天"'*/	
	function delete($table,$name)
	{
		$this->pdo -> beginTransaction();
		try{
			$exec_delete = $this->pdo->exec
			("delete from $table where $name");
			$this->pdo -> commit();
		}
		catch(Exception $e)
		{
			$this->pdo -> rollBack();
			echo "delete Failed:".$e->getMessage();
		}
	}
	
	/*prepare防注入处理,例如，$value格式为'*'或者'`id`,`user`,`title`'
	$name格式为"`user`=?"或者不写*/
	function prepare_select($value,$table,$name)
	{
		$this->rs=$this->pdo->prepare("select $value from $table where $name");
	}

	/*设置结果集数组类型为关联数组形式*/
	function setFetchMode_assoc()
	{
		$this->rs -> setFetchMode(PDO::FETCH_ASSOC);
	}
	/*设置结果集数组类型为数字索引数组形式*/
	function setFetchMode_num()
	{
		$this->rs -> setFetchMode(PDO::FETCH_NUM);
	}
	/*设置结果集数组类型为以上两者数组形式都显示*/
	function setFetchMode_both()
	{
		$this->rs -> setFetchMode(PDO::FETCH_BOTH);
	}
	
	/*强制结果集数组的列名为小写*/
	function caselower()
	{
		$this->pdo -> setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER);
	}
	/*强制结果集数组的列名为大写*/
	function caseupper()
	{
		$this->pdo -> setAttribute(PDO::ATTR_CASE,PDO::CASE_UPPER);
	}
	/*强制结果集数组的列名为原始方式*/
	function casenatural()
	{
		$this->pdo -> setAttribute(PDO::ATTR_CASE,PDO::CASE_NATURAL);
	}

	/*想要select的键值，例如，当已在select语句中WHERE使用了"`user`=?"
	则$key填写1,表示第一个'?'占位符,$select_name填写"叶良臣"。*/
	function bindParam($key,$select_name)
	{
		$this->rs->bindParam($key,$select_name);
	}
	
	/*执行语句，与prepare连用*/
	function execute()
	{
		$this->rs->execute();
	}
	
	/*获取一条记录*/
	function fetch()
	{
		return $result_arr = $this->rs->fetch();		
	}
	/*获取所有记录*/
	function fetchAll()
	{
		return $result_arr = $this->rs->fetchAll();
	}
	/*取得上一次prepare得到的结果集数组的列数*/
	function rowcount()
	{
		return $this->rs -> rowCount();
	}

	/*错误提示*/
	function error()
	{
		if($this->pdo->errorCode()!='00000')
		{
			return ($this->pdo->errorInfo());
		}
	}
	
}

/*
$p=new mysql('bbs','message');


如果echo insert语句会显示lastinsertid的值
$p->insert('message','`id`,`user`,`title`,`content`,`lastdate`,`hit`',
"'','叶良臣','记住','我叫叶良臣',NOW(),'1'");

$p->update('message',"`user`='赵日天',`title`='不服',`content`='我赵日天不服'",
'id=42');
$p->delete('message','`user`="susan"');


$p->prepare_select('*','message',"`user`=?");
$p->setFetchMode_assoc();
$p->casenatural();
$p->bindParam(1,"叶良臣");
$p->execute();
print_r($p->fetch());
echo $p->rowcount();
print_r($p->error());

*/

?>