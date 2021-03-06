<?php

$config = Config::inst();

define('DMS_DIR', basename(__DIR__));

if (!file_exists(BASE_PATH . DIRECTORY_SEPARATOR . DMS_DIR)) {
    user_error('DMS directory named incorrectly. Please install the DMS module into a folder named: ' . DMS_DIR);
}

CMSMenu::remove_menu_item('DMSDocumentAddController');

ShortcodeParser::get('default')->register(
    $config->get('DMS', 'shortcode_handler_key'),
    array('DMSShortcodeHandler', 'handle')
);

if ($config->get('DMSDocument_versions', 'enable_versions')) {
    //using the same db relations for the versioned documents, as for the actual documents
    $config->update('DMSDocument_versions', 'db', $config->get('DMSDocument', 'db'));
}

// add dmsassets folder to file system sync exclusion
if (strpos($config->get('DMS', 'folder_name'), 'assets/') === 0) {
    $folderName = substr($config->get('DMS', 'folder_name'), 7);
    $config->update('Filesystem', 'sync_blacklisted_patterns', array("/^" . $folderName . "$/i",));
}
