<?php
$filename = ($argv[1]) ? $argv[1] : 'scribble.txt';

$handle = fopen($filename, "r");

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

                $xml = new DOMDocument();

                $rule_set = $xml->createElement("ruleset");
                $rule_set->setAttribute("name", $name);

                //Fix from rule
                $from = str_replace('(?:www\.)www', '(?:www\.)', $from);
                $from = str_replace('(www\.)www', '(www\.)', $from);

                //Fix to rule
                $to = str_replace('www.www.', 'www.', $to);

                $host = preg_replace('/^www\./', '', $host);
                
                //Targets
                $targetApex = $xml->createElement("target");
                    $targetApex->setAttribute("host", $host);
                $rule_set->appendChild($targetApex);

                $targetAll = $xml->createElement("target");
                    $targetAll->setAttribute("host", '*.'.$host);
                $rule_set->appendChild($targetAll);
                //Rule
                $rule = $xml->createElement("rule");
                    $rule->setAttribute("from", $from);
                    $rule->setAttribute("to", $to);
                $rule_set->appendChild($rule);

                //Add
                $xml->appendChild($rule_set);

                file_put_contents(
                    "rules/".preg_replace('/[^a-zA-Z0-9\-\_\.]/', '', $filename).'.xml',
                    $comments.
                    $xml->saveXML()
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