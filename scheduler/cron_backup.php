<?php
include_once ('cronConfig.php');

function returnBackupSet($tables = '*'){

	global $db;

	// set the text to display when the variable is an array or not
	if(is_array($tables)){
		$tableText = implode(", ",$tables);
	}else{
		$tableText = $tables;
	}

	// set the section header
	$data = "\n/*---------------------------------------------------------------".
		"\n  SQL DUMP ".date("d.m.Y H:i")." ".
		"\n  TABLES: {$tableText}".
		"\n  ---------------------------------------------------------------*/\n";

	// if no tables are defined get all tables
	if($tables == '*'){
		$tables = array();

		$getTables = $db->prepare("SHOW TABLES");
		$getTables->execute();
		$getTables = $getTables->fetchAll(PDO::FETCH_COLUMN);

		echo '<pre>';
		var_dump($getTables);
		echo '</pre>';exit;

		$tables = is_array($getTables) ? $getTables : explode(',',$getTables);

	}else{
		$tables = is_array($tables) ? $tables : explode(',',$tables);
	}

	// start the dump process
	foreach($tables as $table){

		// set the section header
		$data.= "\n/*---------------------------------------------------------------".
			"\n  TABLE: `{$table}`".
			"\n  ---------------------------------------------------------------*/\n";
		$data.= "DROP TABLE IF EXISTS `{$table}`;\n";

		$getTable = $db->prepare("SHOW CREATE TABLE `{$table}`");;
		$getTable->execute();
		$getTable = $getTable->fetch(PDO::FETCH_ASSOC);

		$data.= $getTable["Create Table"].";\n";

		$getTableData = $db->prepare("SELECT * FROM `{$table}`");
		$getTableData->execute();
		$rowCount = $getTableData->rowCount();

		if($rowCount > 0){
			$values = Array(); $z = 0;
			for($i = 0; $i < $rowCount; $i++){
				$items = $getTableData->fetch(PDO::FETCH_NUM);
				$values[$z]="(";
				for($j = 0; $j < count($items); $j++){
					if (isset($items[$j])) { $values[$z].= "'".addslashes($items[$j])."'"; } else { $values[$z].= "NULL"; }
					if ($j < (count($items)-1)){ $values[$z].= ","; }
				}
				$values[$z].= ")"; $z++;
			}
			$data.= "INSERT INTO `{$table}` VALUES ";
			$data.= "  ".implode(',', $values).";\n";
		}
	}

	return $data;
}

// provide an array or keep empty if the full database needs to be dumped into a sql
file_put_contents('../includes/backups/'.$dbname.'_'.date("dmY").'.sql', returnBackupSet(array('gebruikers','gebruikers_item','gebruikers_tmhm','gebruikers_badges','gebruikers_badges','pokemon_speler')));