<?php

/**
 * mongoCool.class.php
 * User: Aboc  QQ:9986584
 * Date: 15-10-21
 * Time: 上午11:04
 */
class mongoCool {

	/*
	 *
	 * @return MongoClient|null
	 */
	public $mo = null;

	/**
	 *
	 * 数据库
	 *
	 * @var null
	 */
	public $db = null;

	/**
	 * 集合
	 * @var null
	 */
	public $collection = null;

	public $error = '';

	/**
	 * 初始化
	 *
	 * @param $host
	 * @param $port
	 * @param $dbname
	 */
	function __construct($host,$port,$dbname){
		if($host==''){
			$host = 'localhost';
		}
		if($port==''){
			$port = '27017';
		}
		$this->_connect($host,$port)->_db($dbname);
	}

	/**
	 * @param $host
	 * @param $port
	 *
	 * @return MongoClient|null
	 */
	private function _connect($host,$port){
		$this->mo = new MongoClient("mongodb://{$host}:{$port}");
		return $this;
	}

	/**
	 * @param $dbname
	 *
	 * @return mongoCool
	 */
	private function _db($dbname){
		$this->db = $this->mo->selectDb($dbname);
		return $this;
	}

	/**
	 * @param $collection
	 * @param $dbname
	 *
	 * @return mongoCool
	 */
	function collection($collection,$dbname=''){
		if($dbname=="") {
			$this->collection = $this->db->selectCollection( $collection );
		}else{
			$this->collection = $this->mo->selectCollection($dbname,$collection);
		}
		return $this;
	}

	/**
	 * @param $data
	 *
	 * @return mixed
	 */
	function insert($data){
		if(empty($data)){
			$this->error = '值不能为空';
			return false;
		}
		return $this->collection->insert($data);
	}

	/**
	 * 获取单条数据
	 *
	 * @param null $where
	 *
	 * @return mixed
	 */
	function get($where=null){
		if(!$this->_check_where($where)){
			$this->error = '条件设置有问题！';
			return false;
		}
		if($where == null){
			$result = $this->collection->findOne();
		}else{
			$result = $this->collection->findOne($where);
		}
		return $result;
	}

	function find($where=null){
		if(!$this->_check_where($where)){
			$this->error = '条件设置有问题！';
			return false;
		}
		if($where == null){
			$result = $this->collection->find();
		}else{
			$result = $this->collection->find($where);
		}
		$data = array();
		foreach($result as $k => $v){
			$data[$k] = $v;
		}
		return $data;
	}

	function limit($where=null,$num=5,$page=1){
		if(!$this->_check_where($where)){
			$this->error = '条件设置有问题！';
			return false;
		}
		if($page<1){
			$page = 1;
		}
		if($where == null){
			$result = $this->collection->find()->limit($num)->skip(($page-1)*$num);
		}else{
			$result = $this->collection->find($where)->limit($num)->skip(($page-1)*$num);
		}
		$data = array();
		foreach($result as $k => $v){
			$data[$k] = $v;
		}
		return $data;
	}

	function update($where=null,$data=array()){
		if(!$this->_check_where($where)){
			$this->error = '条件设置有问题！';
			return false;
		}
		if(empty($data)){
			$this->error = '值不能为空';
			return false;
		}
		if($where == null){//故意的
			$result = $this->collection->update(null,$data);
		} else {
			$result = $this->collection->update(null,$data);
		}
		return $result;
	}

	function delete($where=null){
		if(!$this->_check_where($where)){
			$this->error = '条件设置有问题！';
			return false;
		}
		if($where==null){
			$result = $this->collection->remove();
		} else {
			$result = $this->collection->remove($where);
		}
		return $result;
	}

	/**
	 * 检查条件
	 *
	 * @param $where
	 *
	 * @return bool
	 */
	private function _check_where($where){
		if($where != null && !is_array($where)){
			return false;
		}
		return true;
	}

}