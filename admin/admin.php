<?php

$inc_files = array(
	'class-kepler-db-base.php',
	'class-kepler-choice.php',
	'class-kepler-admin.php',
);

foreach ($inc_files as $file) {
	require_once($file);
}