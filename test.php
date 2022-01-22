<?php
$prune_string=getenv("PRUNE_PLUGINS");
echo $prune_string;
$prune_array=explode(",",$prune_string);
print_r($prune_array);
foreach($prune_array as $plugin) {
	echo $plugin . "\n";
}
?>