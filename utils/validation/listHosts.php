<?php
//Lists the hostnames affected in a chunk
$chunk_folder = $argv[1];

$chunk_folder =  (substr($chunk_folder, -1, 1) === '/') ? substr($chunk_folder, 0, -1) : $chunk_folder;
	$chunk_folder = getcwd().DIRECTORY_SEPARATOR.$chunk_folder;

if(!file_exists($chunk_folder))
{
	die('php listHosts.php chunk_folder');
}

$files = scandir($chunk_folder);

foreach($files as $file)
{
	if(substr($file, -4, 4) === '.xml') //xml files only
	{
		preg_match('%to="https://(?:www\.)?(.+)/" />%', file_get_contents($chunk_folder.DIRECTORY_SEPARATOR.$file), $matches);
		echo $matches[1]."\n";
	}
}
?>