<?php
$filename = ($argv[1]) ? $argv[1] : 'scribble.txt';

$handle = fopen($filename, "r");

$rule_template = file_get_contents("rule_template.xml");

if($handle)
{
    $i = 0;
    while (($line = fgets($handle)) !== false)
    {
        if($i > 0) //skip header
        {
			list($done, $url, $name, $filename, $host, $from, $to, $comments) = str_getcsv($line, "\t");

			if($done === 'X')
			{
				if($comments)
                    $comments = "<!-- ".str_replace('>', '', $comments)." -->\n";

                //Fix Filename
                if(preg_match('%\s\(.+?\)\s*$%', $name)) //Remove that annoying abbreviation  (but only if there is one)
                	$filename = preg_replace('%([a-z])[A-Z]{2,}$%', '$1', $filename);

                //Fix Name
                $name = preg_replace('%\s?&[a-z0-9];\s?%', '', $name); //remove those pesky html entities
                $name = preg_replace('%\s\(.+?\)\s*$%', '', $name); //Some names are in the format "Agency of Something (AoS)"

                //Fix from rule
                $from = str_replace('(?:www\.)www', '(?:www\.)', $from);
                $from = str_replace('(www\.)www', '(www\.)', $from);

                //No carrot at start, lets fix that
                if($from{0} !== '^')
                	$from = '^'.$from;

                //Forgot to escape dots
                $from = preg_replace('%([^\\\\])\.%', '$1\.', $from);

                //Fix to rule
                $to = str_replace('www.www.', 'www.', $to);

                $host = preg_replace('/^www\./', '', $host);
                
                //Turn into XML
                $xml = str_replace(array('_NAME_', '_HOSTAPEX_', '_HOSTALL_', '_FROM_', '_TO_'),
                				   array($name, $host, '*.'.$host, $from, $to),
                				   $rule_template); //I orignallly used DOM, this is an unsafe way, but gives nicer output.

                $filename = "rules/".preg_replace('/[^a-zA-Z0-9\-\_\.]/', '', $filename).'.xml';

                if(file_exists($filename))
                {
                	$filename .= '_'.uniqid();

                	echo "Already exists, creating: ".$filename."\n";
                }

                file_put_contents(
                    $filename,
                    $comments.
                    $xml
                );
			}
        }
        $i++;
    }
}
else
{
    echo "error opening the file.";
}

fclose($handle);
?>