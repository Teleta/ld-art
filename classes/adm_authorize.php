<?php
class adm_authorize extends session
{
	var $db;
	var $rights;
	var $login;
	var $password;
	var $uid;

	function adm_authorize(&$db){
		$this->db = $db;
		$this->start();
	}

	function check(){
		$this->_clear_old_sessions();
		if(isset($_SESSION['hashcode'])){
			if(!$uid = $this->_query_for_session($_SESSION['hashcode'])){
				unset($_SESSION['hashcode']);
				$this->reset();
			}else{
				$this->_update($uid);
				return TRUE;
			}
		}elseif(isset($_REQUEST['login']) AND isset($_REQUEST['password'])){
			if($uid = $this->_query_for_user($_REQUEST['login'], $_REQUEST['password'])){
				$this->_make_sess($uid, $_REQUEST['login'], $_REQUEST['password']);
				header("Location: http://".$_SERVER['SERVER_NAME'].(isset($_SERVER['REQUEST_URI'])? $_SERVER['REQUEST_URI'] : '/admin/'));
				exit;
			}
		}
		return FALSE;
	}

	function rights(){
		return $this->rights;
	}

	function uid(){
		return $this->uid;
	}

	function _make_sess($uid, $login, $password){
		$hashcode = md5($password.md5(md5($login).rand(rand(0, 1000), rand(10000, 100000))).microtime(1));
		$q = "INSERT INTO `".PREF."session`
		(`user_id`, `hash`, `sess_id`, `rights`) VALUES ('".$uid."', '".$hashcode."', '".$this->id()."', '".$this->rights."')";
		$this->db->query($q);
		$_SESSION['hashcode'] = $hashcode;
		$_SESSION['rights'] = $this->rights;
	}

	function _query_for_user($login, $password){
		$q = "SELECT `id`, `rights` FROM `".PREF."users`
		WHERE `login`='".$login."' AND `password`='".md5($password)."' LIMIT 1";
		if($this->db->query($q)){
			$u = $this->db->fetch();
			$this->rights = $u['rights'];
			$this->uid = $u['id'];
			return $u['id'];
		}
		return FALSE;
	}

	function _query_for_session($hash){
		$q = "SELECT `user_id`, `rights` FROM `".PREF."session`
		WHERE `hash`='".$hash."' AND `sess_id`='".$this->id()."' LIMIT 1";
		if($this->db->query($q)){
			$uid = $this->db->fetch();
			$this->rights = $uid['rights'];
			$this->uid = $uid['user_id'];
			return $uid['user_id'];
		}
		return FALSE;
	}

	function _update($uid){
		$q = "UPDATE `".PREF."session` SET `time`=NOW()
		WHERE `sess_id`='".$this->id()."' AND `user_id`='".$uid."' LIMIT 1";
		$this->db->query($q);
	}

	function _clear_old_sessions(){
		$q = "DELETE FROM `".PREF."session`
		WHERE (TIMESTAMPDIFF(MINUTE, `time`, NOW()) >= 30)";
		$this->db->query($q);
	}
}
?>
