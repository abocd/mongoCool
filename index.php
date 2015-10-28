<?php
/**
 * index.php
 * User: Aboc
 * Date: 15-10-21
 * Time: 上午11:04
 */

include_once 'MongoCool.class.php';
$db = new MongoCool('localhost',27017,'local');
//for($i=1;$i<=20000;$i++) {
//	$db->collection( "boy" )->insert( array(
//		'test' => time().$i,
//		'abc'  => "234"
//	) );
//}

//print_r($db->collection("boy")->limit(null,5,2));
////部分更新
//$db->collection("boy")->update(array("test"=>"14453983805"),array('$set'=>array("abc"=>"cool++")));
////更新到只剩abc
//$db->collection("boy")->update(array("test"=>"144539838056"),array("abc"=>"cool++"));


$db->collection( "people" )->insert( array(
	'time' => time(),
	'data'  => "这是测试的数据"
) );
$db->collection( "people" )->insert( array(
	'time' => time(),
	'data'  => array(
		'title'=>"这是标题",
		'body'=>'这是内容',
	)
) );

$db->collection( "people" )->insert( array(
	'_id' => 'custom'.time(),
	'time' => time(),
	'data'  => "这是测试的数据"
) );