<?php
/*
	HTTPS Everywhere has utils/trivial-validate.py but it doesn't tell you the failed rule. One day I'll learn Python.
*/
$filename = $argv[1];

if($filename)
{
	$doc = DOMDocument::load($filename);
	$rules = $doc->getElementsByTagName('rule');
	foreach($rules as $rule)
	{
		$from = $rule->getAttribute("from");
		$to = $rule->getAttribute("to");
		if(preg_match($from, $to, $matches) === FALSE)
		{
			echo "FAILED: ".$rule."\n";
		}

	}
}
else
{
	die("Error: no filename passed");
}

?>