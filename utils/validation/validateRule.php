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
		if(preg_match('%www\.\)[^?]%', $from)) //http://(?:www\.)example\.com
		{
			echo "FAILED (missing question mark): ".$doc->saveXML($rule)."\n";
		}
		if(strpos($from, "\.)\.") !== false) //http://(?:www\.)\.example\.com
		{
			echo "FAILED (extra dot): ".$doc->saveXML($rule)."\n";
		}
		if(preg_match("%".$from."%", $to, $matches) === FALSE) //I don't feel confortable with addslashes, I worry it might fix errors
		{
			echo "FAILED: (bad regex)".$doc->saveXML($rule)."\n";
		}

	}
}
else
{
	die("Error: no filename passed");
}

?>