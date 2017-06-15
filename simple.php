<?php

require('./Xcsv/CsvReader.class.php');

header("Content-type:text/html;charset=utf-8");

$reader = new CsvReader();
$reader->load('./test.csv');
 $reader->start(2);

// $reader->end(4);
// $reader->translate('gb2312','utf-8');
// $reader->to_object(true);
// $reader->callback('echo');
// $reader->exec('print_r');

function test($data,$hello){
    var_dump($data);
    var_dump($hello);
}

$hello = 'hello world!';
$reader->add_param($hello)->exec('test');