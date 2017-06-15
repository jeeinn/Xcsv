<?php

require('./Xcsv/CsvReader.class.php');

header("Content-type:text/html;charset=utf-8");

// 建表语句
$create_table = "
CREATE TABLE IF NOT EXISTS `goods` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `goods_no` BIGINT(15) NULL COMMENT '商品id',
  `name` VARCHAR(200) NOT NULL DEFAULT '' COMMENT '商品名',
  `main_pic` VARCHAR(300) NULL DEFAULT '' COMMENT '商品主图',
  `detail_page` VARCHAR(300) NULL DEFAULT '' COMMENT '商品详情页',
  `first_cate` VARCHAR(100) NULL DEFAULT '' COMMENT '主分类',
  `tbk_link` VARCHAR(300) NULL DEFAULT '' COMMENT '淘宝客链接',
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT '价格',
  `month_sale_num` INT NULL DEFAULT 0 COMMENT '月销量',
  `rebate_rate` DECIMAL(10,2) NULL DEFAULT 0 COMMENT '返佣百分比',
  `commision` DECIMAL(10,2) NULL DEFAULT 0 COMMENT '佣金',
  `seller_name` VARCHAR(45) NULL DEFAULT '' COMMENT '卖家名',
  `seller_id` INT(11) UNSIGNED NULL COMMENT '卖家旺旺ID',
  `shop_name` VARCHAR(100) NULL DEFAULT '' COMMENT '店铺名',
  `platform_type` CHAR(10) NULL DEFAULT '' COMMENT '平台类型',
  `coupon_id` VARCHAR(32) NULL DEFAULT '' COMMENT '优惠券ID',
  `coupon_num` INT NULL COMMENT '优惠券总数量',
  `coupon_remain_num` INT NULL COMMENT '优惠券剩余数量',
  `coupon_info` VARCHAR(45) NULL DEFAULT '' COMMENT '优惠券信息',
  `coupon_start_time` DATETIME NULL COMMENT '优惠券开始信息',
  `coupon_stop_time` DATETIME NULL COMMENT '优惠券到期时间',
  `coupon_link` VARCHAR(300) NULL DEFAULT '' COMMENT '优惠券链接',
  `uland_link` VARCHAR(300) NULL DEFAULT '' COMMENT '优惠券落地页',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = '淘宝商品表';
)";



// 连接数据库执行
$pdo = new PDO('mysql:host=localhost;dbname=test', 'root', 'root');
$pdo->query("SET NAMES utf8");
$pdo->query($create_table);
//die('ok');

function insert($data,$pdo){
	$insert = "INSERT INTO `goods` (
        `goods_no`,`name`,`main_pic`,`detail_page`,
        `first_cate`,`tbk_link`,`price`,`month_sale_num`,
        `rebate_rate`,`commision`,`seller_name`,`seller_id`,
        `shop_name`,`platform_type`,`coupon_id`,`coupon_num`,
        `coupon_remain_num`,`coupon_info`,`coupon_start_time`,
        `coupon_stop_time`,`coupon_link`,`uland_link`)
		VALUES(
		'{$data[0]}','{$data[1]}','{$data[2]}','{$data[3]}',
		'{$data[4]}','{$data[5]}','{$data[6]}','{$data[7]}',
		'{$data[8]}','{$data[9]}','{$data[10]}','{$data[11]}',
		'{$data[12]}','{$data[13]}','{$data[14]}','{$data[15]}',
		'{$data[16]}','{$data[17]}','{$data[18]}','{$data[19]}',
		'{$data[20]}','{$data[21]}');";

	echo $insert;
	$res = $pdo->exec($insert);
	if(!$res){
        var_dump($pdo->errorInfo());
    }
}

$reader = new CsvReader();
$reader->load('./test.csv');
 $reader->start(2);
// $reader->end(6);
// $reader->translate('gb2312','utf-8');
// $reader->to_object(true);
// $reader->callback('var_dump');
// $reader->exec('print_r');

//function test($data,$test){
//    print_r($data);
//    var_dump($test);
//}
$reader->translate('gb2312','utf-8')->add_param($pdo)->exec('insert');