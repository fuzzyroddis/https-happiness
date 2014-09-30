<?php
//Split generated rules into chunks of X to make pull requests easier on both parties
$chunk_size = (int) $argv[1];
$folder = $argv[2];
$chunk_folder = $argv[3];

$folder =  (substr($folder, -1, 1) === '/') ? substr($folder, 0, -1) : $folder;
	$folder = getcwd().DIRECTORY_SEPARATOR.$folder;
$chunk_folder =  (substr($chunk_folder, -1, 1) === '/') ? substr($chunk_folder, 0, -1) : $chunk_folder;
	$chunk_folder = getcwd().DIRECTORY_SEPARATOR.$chunk_folder;

if(!file_exists($chunk_folder))
{
	mkdir($chunk_folder);
}

if($chunk_size < 1)
{
	die('php split.php 10 generated_rules chunks');
}

$files = scandir($folder);

$i=0;
foreach($files as $file)
{
	if(substr($file, -4, 4) === '.xml') //xml files only
	{
		if(!file_exists($chunk_folder.DIRECTORY_SEPARATOR.floor($i/$chunk_size)))
		{
			mkdir($chunk_folder.DIRECTORY_SEPARATOR.floor($i/$chunk_size));
		}
		copy($folder.DIRECTORY_SEPARATOR.$file,
			$chunk_folder.DIRECTORY_SEPARATOR.floor($i/$chunk_size).DIRECTORY_SEPARATOR.$file
		);
		$i++;
	}
}
?>