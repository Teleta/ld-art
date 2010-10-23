<?php
/**
* easy_Template engine || created for simplifying XTemplate open source project
* see docs for documentation
* @version: 0.1b
* birthday: spring 2007
* last edited 25.09.2007
* @author: Svel Sontz <Svel.Sontz@gmail.com>
* ICQ: 145-288-626
* phone: +7(916)984-3172
* location: Russia, Moscow
*/

class e_Template
{
	var $tpl_dir = '';
	var $tpl_ext = '.tpl';
	var $tpl_file = '';
	var $assigned = array();

	var $file_contents = array(); // raw file content by filenames
	var $file_block = array(); // glued files merged to blocks by block names
	var $parsed = array(); // parsed blocks

	var $tag_start = '{';
	var $tag_end   = '}';
	var $tag_block_start = '<!--';
	var $tag_block_end   = '-->';
	var $word_block_start = 'NEW:';
	var $word_block_end   = 'END:';

	var $iterations = array();

	/**
	* constructor, settings and preloading
	*/
	function e_Template($file, $dir=''){
		$this->tpl_dir = $dir;
		$this->tpl_file = $file;
		$this->_loading();
		$this->_read_file_contents($this->tpl_file);
		$this->_make_block($this->file_contents[$this->tpl_file]);
	}

	/**
	* reading file from disc
	* call of _preparse function
	*/
	function _read_file_contents($file){
		if(array_key_exists($file, $this->file_contents)){
			return $this->file_contents[$file];
		}
		if(!file_exists($this->tpl_dir.$file.$this->tpl_ext)){
			return '';
		}
		if(!is_readable($this->tpl_dir.$file.$this->tpl_ext)){
			return '';
		}
		$h = fopen($this->tpl_dir.$file.$this->tpl_ext, 'r');
		$data = trim(fread($h, filesize($this->tpl_dir.$file.$this->tpl_ext)));
		fclose($h);
		$this->file_contents[$file] = $data;
		$this->_preparse($file);
		return $this->file_contents[$file];
	}

	/**
	* include all files recursively
	* TODO: create blocks from included files
	*/
	function _preparse($file){
		while(preg_match("/".$this->tag_start.'include (.+)?'.$this->tag_end."/", $this->file_contents[$file], $matches)){
			if($file == $matches['1'] OR $matches['1'] == $this->tpl_file){
				$this->file_contents[$file] = preg_replace("/".preg_quote($matches['0'])."/", '', $this->file_contents[$file]);
			}
			$data = $this->_read_file_contents($matches['1']);
			$this->file_contents[$file] = preg_replace("/".preg_quote($matches['0'])."/", $data, $this->file_contents[$file]);
		}
		return TRUE;
	}

	/**
	* creating of structured template data blocks
	*/
	function _make_block($data, $blockname='_BLOCK_', $level='0'){
		if($level > 100){
			return;
		}
		// fixed error in regexp, no more greedy ))
		$str = '/'.$this->tag_block_start.'\s*'.$this->word_block_start.'\s*(.+?)\s*'.$this->tag_block_end.'(.*?)'.$this->tag_block_start.'\s*'.$this->word_block_end.'\s*\1\s*'.$this->tag_block_end.'/ims';
		if(preg_match_all($str, $data, $matches, PREG_SET_ORDER)){
			foreach($matches as $textblock){
				$this->_make_block($textblock['2'], $blockname.".".$textblock['1'], $level+1);
				$data = preg_replace('/'.preg_quote($textblock['0'], '/').'/', "{".$blockname.".".$textblock['1']."}", $data);
			}
		}
		$this->file_block[$blockname] = $data;
		return TRUE;
	}

	/**
	* filling block with assigned variables
	*/
	function parse(){
		$args = func_get_args();
		$num = func_num_args();
		$clear = 1;
		if(!$num){
			$args = array_unshift($args, '_BLOCK_');
		}
		if(($num > 1) AND (end($args) == 1)){
			reset($args);
			$clear = $args[$num-1];
			unset($args[$num-1]);
			foreach($args as $str){
				$this->_parse($str, $clear);
			}
		}elseif($num > 1){
			reset($args);
			foreach($args as $str){
				$this->_parse($str, $clear);
			}
		}else{
			$this->_parse($args['0'], $clear);
		}
	}

	function _parse($fb='_BLOCK_', $clear=1){
		if(empty($fb)){ // fix for setting $clear value
			$fb = '_BLOCK_';
		}
		$fba = explode('.', $fb);
		if($fba['0'] !== '_BLOCK_'){
			$fb = '_BLOCK_.'.$fb;
		}
		if(isset($this->file_block[$fb])){
			$block = $this->file_block[$fb];
		}else{
			return FALSE;
		}
		preg_match_all("/\{([A-Za-z0-9\._]+?)\}/", $block, $matches);
		$matches = $matches['1'];
		foreach($matches as $str){
			$sub = explode('.', $str);
			if($sub['0'] == '_BLOCK_'){ // including blocks
				if(isset($this->parsed[$str])){
					$val = $this->parsed[$str];
					// for previous parsed clearing -> unset subblock
					if($clear){
						unset($this->parsed[$str]);
					}
				}else{ // if subblock is NOT parsed
					$val = '';
				}
				$block = preg_replace("/\{".$str."\}[\r\n\s]*/m", $val, $block);
			}else{ // parsing variables
				$assign = $this->assigned;
				foreach($sub as $v1){
					$assign = ( isset($assign[$v1]) ? $assign[$v1] : '' );
				}
				$block = preg_replace("/\{".$str."\}[\r\n]*/m", $assign, $block); // fixed \s value at the end of {variable}, Svel 02.09.2007
			}
		}
		if(isset($this->parsed[$fb])){
			$this->parsed[$fb] .= $block;
		}else{
			$this->parsed[$fb] = $block;
		}
		return TRUE;
	}

	/**
	* assign value to variable
	*/
	function assign($key, $value='', $reset=1){
		if(is_array($key)){
			unset($value);
			foreach($key as $k => $v){
				if($reset){
					$this->reset($k);
				}
				$this->assigned[$k] = $v;
			}
		}elseif(is_array($value)){
			if($reset){
				$this->reset($key);
			}
			foreach($value as $k => $v){
				// todo: зациклить... возможно
				$this->assigned[$key][$k] = $v;
			}
		}else{
			if($reset){
				$this->reset($key);
			}
			$this->assigned[$key] = $value;
		}
	}

	/**
	* returns block by block.name
	*/
	function out($block='_BLOCK_'){
		$out = ( isset($this->parsed["_BLOCK_.".$block]) ? $this->parsed["_BLOCK_.".$block] : @$this->parsed['_BLOCK_'] );
		return $out;
	}

	/**
	* reload assigned values
	*/
	function reset($k=''){
		if($k == ''){
			$this->assigned = array();
			$this->_loading();
		}else{
			if(isset($this->assigned[$k])){
				unset($this->assigned[$k]);
			}
		}
		return TRUE;
	}

	/**
	* define PHP value, todo: add more variables
	*/
	function _loading(){
		if(isset($_SERVER)){
			$this->assign('PHP', $_SERVER);
		}
		return TRUE;
	}
}
?>