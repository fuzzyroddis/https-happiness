<?php
$filename = ($argv[1]) ? $argv[1] : 'scribble.txt';

$handle = fopen($filename, "r");

if ($handle)
{
    $i = 0;
    while (($line = fgets($handle)) !== false)
    {
        if($i > 0)
        {
			list($done, $url, $name, $filename, $host, $from, $to, $comments) = explode("\t",
								str_replace(array("\r", "\n"), '', $line)
							);
			if($done === 'G')
			{
				$good[] = str_replace(".govspace.gov.au", '', $host);
			}
			else if(substr($done, 0, 2) === 'G|')
			{
				$bad[] = str_replace(".govspace.gov.au", '', $host);
			}
        }
        $i++;
    }

    foreach($good as $sub)
    {
    	echo $sub.'|';
    }
    echo "\n";
    foreach($bad as $sub)
    {
    	echo $sub.'|';
    }
}
else
{
    echo "error opening the file.";
}

fclose($handle);
?>