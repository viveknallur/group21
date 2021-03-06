<?php

/*
 * swap.php
 *
 * hotswaps the LB_ALGO into haproxy, and gracefully reloads
 */

// true if the script is being accessed from the html interface
$post = false;

if (isset($_POST["LB_ALGO"])) {
	// fetch new algorithm name from html form
	$new_algo = $_POST["LB_ALGO"];
	$post = true;
} else {
	// fetch command line argument of the new algorithm name
	if (!isset($argv[1]) || isset($argv[2])) {
		echo "Usage: ".$argv[0]." [LB_ALGO]\n";
		exit(-1);
	}
	$new_algo = $argv[1];
}

// some constants that vary based on file location
define("CONFIG_FILE", "/etc/haproxy/haproxy.cfg");
define("ALGO_FILE", "algorithms.txt");
define("RELOAD_FILE", "graceful_reload.sh");

$config_file = explode("\n", file_get_contents(CONFIG_FILE));
$algo_file = explode("\n", trim(file_get_contents(ALGO_FILE)));

// make sure that the algorithm we're switching to is actually valid
$valid = false;
foreach ($algo_file as $algorithm) {
	if (strpos($new_algo, $algorithm) !== false) {
		$valid = true;
		break;
	}
}
if ($valid == false) {
	if ($post)
		echo "error";
	else
		echo "Unrecognised LB_ALGO: ".$new_algo."\n";
	exit(-1);
}

// and array to contain a list of all ports that haproxy is currently bound to
$ports = array();

// make the changes
foreach ($config_file as $key => $line) {
	// search for LB_ALGO
	if (preg_match("#balance#i", $line)) {
		// it has now found a line related to LB_ALGO. if it finds an existing algorithm 
		// name there, it's going to replace it with the new algorithm.
		foreach ($algo_file as $algorithm) {
			if (($pos = strpos($line, $algorithm)) !== false) {
				if (strcmp($algorithm, $new_algo) == 0) {
					if ($post)
						echo "ignore";
					else
						echo "Algorithm already in use: ".$algorithm.". Aborting\n";
					exit(-1);
				}
				$config_file[$key] = substr_replace($config_file[$key], $new_algo, $pos, strlen($algorithm));
			}
		}		
	}
	// search for bound port numbers
	else if (preg_match("#\blisten\b#", $line)) {
		if (($pos = strpos($line, ":")) !== false) {
			$port = explode(" ", substr($line, $pos+1), 2);
			array_push($ports, $port[0]);
		}	
	}
}

// write the changes to the config file
$f = fopen(CONFIG_FILE, "w");
fwrite($f, implode("\n", $config_file));
fclose($f);

// build a string shell command before executing to keep overhead as low as possible
$shell_reloader = "";
$undrop = "";

// begin closing all bound ports using iptables so as to avoid packet loss
shell_exec("sudo /var/www/files/swap_files/graceful_reload.sh");

// return algorithm to ajax call
if ($post)
	echo $new_algo;

?>
