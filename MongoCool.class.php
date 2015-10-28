<?php

/**
 * mongoCool.class.php
 * User: Aboc  QQ:9986584
 * Date: 15-10-21
 * Time: 上午11:04
 */
class MongoCool {

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
	 * 连接数据库
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
	 * 选择数据库
	 *
	 * @param $dbname
	 *
	 * @return mongoCool
	 */
	private function _db($dbname){
		$this->db = $this->mo->selectDb($dbname);
		return $this;
	}

	/**
	 * 选择集合/表
	 *
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
	 * 创建集合
	 *
	 * @param       $name
	 * @param array $options
	 *
	 * @return mixed
	 */
	function create($name,$options=array()){
		$this->mo->createCollection($name,$options);
		return $this;
	}

	/**
	 * 插入数据
	 *
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
	 * @param $where
	 * @param $sort
	 *
	 * @return mixed
	 */
	function get($where='',$sort=''){
		if(!$this->_check_where($where)){
			$this->error = '条件设置有问题！';
			return false;
		}
		if($sort != ''){
			if ( $where == '' ) {
				$result = $this->collection->findOne()->sort($sort);
			} else {
				$result = $this->collection->findOne( $where )->sort($sort);
			}
		} else {
			if ( $where == '' ) {
				$result = $this->collection->findOne();
			} else {
				$result = $this->collection->findOne( $where );
			}
		}
		return $result;
	}

	/**
	 * 获取多条数据/不分页
	 *
	 * @param $where
	 * @param $sort
	 *
	 * @return array|bool
	 */
	function find($where='',$sort=''){
		if(!$this->_check_where($where)){
			$this->error = '条件设置有问题！';
			return false;
		}
		if($sort != ''){
			if ( $where == '' ) {
				$result = $this->collection->find()->sort($sort);
			} else {
				$result = $this->collection->find( $where )->sort($sort);
			}
		} else {
			if ( $where == '' ) {
				$result = $this->collection->find();
			} else {
				$result = $this->collection->find( $where );
			}
		}
		$data = array();
		foreach($result as $k => $v){
			$data[$k] = $v;
		}
		return $data;
	}

	/**
	 * 获取多条数据，简单分页
	 *
	 * @param $where
	 * @param int  $num
	 * @param int  $page
	 * @param string $sort
	 *
	 * @return array|bool
	 */
	function limit($where='',$num=5,$page=1,$sort=''){
		if(!$this->_check_where($where)){
			$this->error = '条件设置有问题！';
			return false;
		}
		if($page<1){
			$page = 1;
		}
		if($sort!=''){
			if ( $where == '' ) {
				$result = $this->collection->find()->limit( $num )->skip( ( $page - 1 ) * $num )->sort($sort);
			} else {
				$result = $this->collection->find( $where )->limit( $num )->skip( ( $page - 1 ) * $num )->sort($sort);
			}
		} else {
			if ( $where == '' ) {
				$result = $this->collection->find()->limit( $num )->skip( ( $page - 1 ) * $num );
			} else {
				$result = $this->collection->find( $where )->limit( $num )->skip( ( $page - 1 ) * $num );
			}
		}
		$data = array();
		foreach($result as $k => $v){
			$data[$k] = $v;
		}
		return $data;
	}

	/**
	 * 获取记录数
	 *
	 * @param string $where
	 *
	 * @return bool
	 */
	function count($where=''){
		if(!$this->_check_where($where)){
			$this->error = '条件设置有问题！';
			return false;
		}
		if($where == ''){
			$result = $this->collection->count();
		} else {
			$result = $this->collection->find($where)->count();
		}
		return $result;
	}

	/**
	 * 更新数据
	 * @param  $where
	 * @param array $data
	 *
	 * @return bool
	 */
	function update($where='',$data=array()){
		if(!$this->_check_where($where)){
			$this->error = '条件设置有问题！';
			return false;
		}
		if(empty($data)){
			$this->error = '值不能为空';
			return false;
		}
		if($where == ''){//故意的
			$result = $this->collection->update(null,$data);
		} else {
			$result = $this->collection->update($where,$data);
		}
		return $result;
	}

	/**
	 * 删除数据
	 *
	 * @param $where
	 *
	 * @return bool
	 */
	function delete($where=''){
		if(!$this->_check_where($where)){
			$this->error = '条件设置有问题！';
			return false;
		}
		if($where==''){
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
		if($where != '' && !is_array($where)){
			return false;
		}
		return true;
	}

}