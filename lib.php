<?php

function block_public_files_pluginfile($course, $birecord, $context, $filearea, $args, $forcedownload, array $options = array()) {

	require_login();

	if ($filearea !== 'files') {
	    send_file_not_found();
	}

	$fs = get_file_storage();
	$filename = array_pop($args);
	$filepath = $args ? '/'.implode('/', $args).'/' : '/';

	if (!$file = $fs->get_file($context->id, 'block_public_files', 'files', 0, $filepath, $filename) or $file->is_directory()) {
		send_file_not_found();
	}

	\core\session\manager::write_close();
	send_stored_file($file, null, 0, $forcedownload, $options);

}