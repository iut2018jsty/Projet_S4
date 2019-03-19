<?php
	require File::build_path(array('view', 'header.php'));
	
	require File::build_path(array("view", self::$controller, "$view.php"));

	require File::build_path(array('view', 'footer.php'));
?>