# Xcsv
php csv tools with read

一个php写的csv管理小工具，现在仅包含有读取功能……

#Use Case
使用说明

参见 `simple.php`

可以自定义函数来将数据写入数据库：
```
function insert($data, $pdo){
    $res = $pdo->exec("insert into table (title,value) values('{$data[0]}','{$data[1]}');");
    if(!$res){
        var_dump($pdo->errorInfo());
    }
}
```

#method
方法

`load`:载入文件

`length`：设置读取长度

`delimiter`：设置分隔字符

`enclosure`：设置围绕字符

`escape`：设置转义字符

`start`：设置读取开始行号

`end`：设置读取结束行号

`translate`：设置编码

`to_object`：是否输出为object类型

`add_param`：添加传递参数（确保回调函数可处理多个参数）

`callback`：添加回调函数（多个回调函数将依次执行）

`exec`：执行（每次只返回读取的当前行传给callback，默认类型为数组）

#To do
1. 添加写csv文件功能
