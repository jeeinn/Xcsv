<?php
/**
 * CsvReader
 * Created by PhpStorm.
 * User: jeeinn
 * Date: 2017/6/14
 * Time: 13:15
 */

// namespace Xcsv;

class CsvReader
{
	
	private $file_handler;
	private $length;
	private $delimiter;
	private $enclosure;
	private $escape;
    private $in_charset;
    private $out_charset;
    private $callback_queue;
    private $row;
    private $start;
    private $end;
    private $is_object;
    private $param;

    public function __construct()
 	{
 		$this->length();
 		$this->delimiter();
 		$this->enclosure();
 		$this->escape();
 		$this->translate();
 		$this->callback_queue = array();
 		$this->start();
 		$this->end();
 		$this->to_object();
 		$this->param = array();
 	}

    /**
     * 加载文件判断
     * @param string $file_name
     * @throws Exception
     */
 	public function load($file_name='')
	{
		if(!file_exists($file_name)){
			$this->_error('Error : file "'. $file_name .'" not exist .');
		}
		$this->file_handler = fopen($file_name,'r');
		if(!$this->file_handler){
			$this->_error('Error : file "'. $file_name .'" open error, please check permission .');
		}
	}

 	public function length($length = 0)
 	{
 		$this->length = $length;
 		return $this;
 	}

 	public function delimiter($delimiter = ',')
 	{
 		$this->delimiter = $delimiter;
 		return $this;
 	}

 	public function enclosure($enclosure = '"')
 	{
 		$this->enclosure = $enclosure;
 		return $this;
 	}

 	public function escape($escape = "\\")
 	{
 		$this->escape = $escape;
 		return $this;
 	}

    /**
     * 设置编码转换
     * @param string $in_charset
     * @param string $out_charset
     * @return $this
     */
	public function translate($in_charset='utf-8',$out_charset='utf-8')
 	{
 		$this->in_charset = (string) $in_charset;
 		$this->out_charset = (string) $out_charset;
 		return $this;
 	}

 	public function to_object($param = false)
 	{
 		$this->is_object = (bool) $param;
 		return $this;
 	}

 	public function add_param($param)
    {
        array_push($this->param,$param);
        return $this;
    }

    /**
     * 添加callback函数名 到 回调队列
     * @param string $fn_name
     * @return $this
     */
 	public function callback($fn_name='')
 	{
        $fn_name = (string) $fn_name;
 		if(is_callable($fn_name)){
 			array_push($this->callback_queue, $fn_name);
 		}
 		return $this;
 	}

    /**
     * 设置读取起始行号
     * @param int $number
     * @return $this
     */
 	public function start($number=1)
 	{
 		$number = (int)$number;
 		if($number < 1) $number=1;
 		$this->start = $number;
 		return $this;
 	}

 	/**
     * 设置结束始行号
     * @param int $number
     * @return $this
     */
 	public function end($number=0)
 	{
 		$number = (int)$number;
 		if($number && $this->start > $number) {
 			$this->_error('Error : your end number should bigger than start number .');
 		}
 		$this->end = $number;
 		return $this;
 	}

	public function exec($fn_name='')
	{

        $fn_name = (string) $fn_name;
 		if(is_callable($fn_name)){
 			array_push($this->callback_queue, $fn_name);
 		}
		
		$current = 0;
		while (!feof($this->file_handler)) {
			// 1.检测是否到达指定结束行号
			if($this->end){
				if($current >= $this->end) break;
			}
			
			// 2.根据设置获取内容
			$this->row = fgetcsv($this->file_handler, $this->length, $this->delimiter, $this->enclosure, $this->escape);
			
			// 3.检测是否到达尾行EOF
			if($this->row === false) break;

			// 4.检测是否到达开始行号
			$current++;
			if($current < $this->start) continue;

			// 5.检测获取结果、并执行其他函数及相应回调
			$this->_check($current);
			$this->_translate();
			$this->_to_object();
			$this->_exec_callback();
		}

		fclose($this->file_handler);
	}

	private function _error($str)
 	{
 		die($str);
 	}

	private function _check($current)
	{
		if(gettype($this->row)!=='array'){
			$this->_error('Error : parse content failed, maybe the format incorrect. Please check the file content, at row:'.$current);
		}
	}

	private function _translate()
	{
		if($this->in_charset === $this->out_charset) return;
		foreach ((array)$this->row as $k => $v) {
			$this->row[$k] = iconv($this->in_charset, $this->out_charset, $v);
		}
	}

	private function _to_object()
	{
		if($this->is_object) {
	  		$this->row = (object) $this->row;
	  	}
	}

	private function _exec_callback()
	{
        array_unshift($this->param,$this->row);
		foreach ((array)$this->callback_queue as $k => $callback) {
            call_user_func_array($callback,$this->param);
		}
		array_shift($this->param);
	}

}