<?php
class session
{
	var $session_id;

	function session(){
		$this->start();
	}

	function start(){
		$this->session_id = session_id();
		if($this->id() == ''){
			session_start();
			$this->session_id = session_id();
		}
	}

	function reset(){
		session_regenerate_id(0);
		$this->session_id = session_id();
	}

	function id(){
		return $this->session_id;
	}
}
?>
