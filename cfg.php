<?php

define("ROOT", str_replace("\\", "/", dirname(__FILE__)));
define("SITE", 1);
define("PREF", "ld_");

require_once(ROOT."/classes/mysql.php");
require_once(ROOT."/classes/session.php");
require_once(ROOT."/classes/e_Template.php");

require_once(ROOT . "/" . "sql_cfg.php");

?>
