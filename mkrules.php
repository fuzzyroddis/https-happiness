<?php
#Good ol' PHP
/*
	([^/:@\.]+\.)? should be used instead of .* in `from`
	Need to escape before putting in XML.
	This is crude attempt to get the job done.
*/
function process_line($line)
{
	//Might as well have fun with this! Some call it job security.
	preg_match("%^(.+)			#name
				\s\|\s
				#pattern
				(
					(\((?:\?\:)?[^/:@\.]+\.\))? #eg. (www\.)
					(.+?)		#host
				)
				\s->\s(.+)		#rewrite
				(?: //(.+))?	#comment
				%x",
				$line, $matches);
	list(, $NAME, $PATTERN, $wild, $HOST, $REWRITE) = $matches;

	$rule = "<ruleset name="{$NAME}">\r\n  <target host="{$HOST}" />";

	if($wild)
	{
		$rule += "\r\n <target host=\"*.{$HOST}\" />";
	}

	if(substr($PATTERN, -1, 1) === '/')
		$PATTERN = substr($PATTERN, 0, -1);

	if(substr($REWRITE, -1, 1) === '/')
		$REWRITE = substr($REWRITE, 0, -1);

	$rule += "\r\n  <rule from=\"^http://{$PATTERN}/\" to=\"{$REWRITE}/\"/>\r\n</ruleset>";

	$filename = preg_replace("/[^a-zA-Z0-9\-\_\.]/", '', $filename);

	file_put_contents($filename, 'rules/'.$rule);
}


$handle = fopen("rules.txt", "r");

if ($handle)
{
    while (($line = fgets($handle)) !== false)
    {
        process_line($line);
    }
}
else
{
    echo "error opening the file.";
}

fclose($handle);
?>