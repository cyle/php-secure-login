<?php

// generate a random key from /dev/random
function get_key($bit_length = 128, $use_urandom = true){
	if ($use_urandom) {
		$command = '/dev/urandom';
	} else {
		$command = '/dev/random';
	}
	$fp = @fopen($command,'rb');
	if ($fp !== FALSE) {
		$key = substr(base64_encode(@fread($fp,($bit_length + 7) / 8)), 0, (($bit_length + 5) / 6)  - 2);
		@fclose($fp);
		$key = str_replace(array('+', '/'), array('0', 'X'), $key);
		return $key;
	}
	return null;
}


$time_start = microtime(true); 

echo '<pre>';
echo '/dev/urandom keys:'."\n";
for ($i = 0; $i < 10; $i++) {
	echo get_key(256)."\n";
	//echo $i."\n";
}

$time_between = microtime(true);

echo 'that took '.($time_between - $time_start).' seconds'."\n";

echo "\n";

echo '/dev/random keys:'."\n";
for ($i = 0; $i < 10; $i++) {
	echo get_key(256, false)."\n";
	//echo $i."\n";
}

$time_end = microtime(true);

echo 'that took '.($time_end - $time_between).' seconds'."\n";

echo "\n";

echo 'all of it took '.($time_end - $time_start).' seconds total'."\n";

echo '</pre>';

?>