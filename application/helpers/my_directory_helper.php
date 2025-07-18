<?php

function mkdir_if_not_exist($path, $mode = 0777, $recursive = TRUE)
{
	if (!is_dir($path))
	{
		// Reference: http://stackoverflow.com/questions/3997641/why-cant-php-create-a-directory-with-777-permissions
		$oldmask = umask(0);
		mkdir($path, $mode, $recursive);
		umask($oldmask);
	}
}

function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir."/".$object))
                    rrmdir($dir."/".$object);
                else
                    unlink($dir."/".$object);
            }
        }
        rmdir($dir);
    }
}