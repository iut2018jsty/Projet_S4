<?php
require_once File::build_path(array('model', 'ModelDate.php'));

class ControllerDate{
	public static $controller = 'date';

	public static function formatDay($weekdif = 0, $daydif = 0){
		$daydur = 86400;
		// 86 400 = 60*60*24 soit la duree d'un jour
		$weekdur = 604800;
		// 604 800 = 60*60*24*7 soit la durée d'une semaine
		$timestamp = time() + $weekdur * $weekdif + $daydur * $daydif;
		return date('y', $timestamp) . '/'.
			   date('m', $timestamp) . '/'.	
			   date('d', $timestamp);
	}

	public static function getWeek($weekdif = 0){ 
		$weekdur = 604800;
		// 604 800 = 60*60*24*7 soit la durée d'une semaine
		$timestamp = time() + $weekdur * $weekdif;
		// day = numero du jour : 1 à 7
		$day = date('N', $timestamp);
		$return = array();
		for($i = 1; $i <= 5; $i++){
			$return = $return + [$i => self::formatDay($weekdif, $i - $day)];
		}
		return $return;
	}

	public static function getLundi($weekdif = 0){
		$weekdur = 604800;
		// 604 800 = 60*60*24*7 soit la durée d'une semaine
		$timestamp = time() + $weekdur * $weekdif;
		// day = numero du jour : 1 à 7
		$day = date('N', $timestamp);
		return self::formatDay($weekdif, 1-$day);

	}

}
?>
