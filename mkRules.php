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
			list($done, $url, $name, $filename, $host, $from, $to, $comments) = explode("\t",
								str_replace(array("\r", "\n"), '', $line)
							);
			if($done === 'X')
			{
				if($comments)
                    $comments = "<!-- ".str_replace('>', '', $comments)." -->\n";

                //Fix from rule
                $from = str_replace('(?:www\.)www', '(?:www\.)', $from);
                $from = str_replace('(www\.)www', '(www\.)', $from);

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