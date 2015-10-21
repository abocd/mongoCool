<?php
/**
 * index.php
 * User: Aboc
 * Date: 15-10-21
 * Time: 上午11:04
 */

include_once 'mongoCool.class.php';
$db = new mongoCool('localhost',27017,'local');
//for($i=1;$i<=20000;$i++) {
//	$db->collection( "boy" )->insert( array(
//		'test' => time().$i,
//		'abc'  => "234"
//	) );
//}

print_r($db->collection("boy")->limit(null,5,2));
//部分更新
$db->collection("boy")->update(array("test"=>"14453983805"),array('$set'=>array("abc"=>"cool++")));
//更新到只剩abc
$db->collection("boy")->update(array("test"=>"144539838056"),array("abc"=>"cool++"));