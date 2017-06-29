<?php
/**
 * Windows: run as administrator
 * php symlink.php
 */

$cssLink = __DIR__ . '/backend/web/css';
$faviconLink = __DIR__ . '/backend/web/favicon';
$imageLink = __DIR__ . '/backend/web/images';
$backendLink = __DIR__ . '/frontend/web/backend';

/* rm */
if(file_exists($cssLink)){
	unlink($cssLink);
}
if(file_exists($faviconLink)){
	unlink($faviconLink);
}
if(file_exists($imageLink)){
	unlink($imageLink);
}
if(file_exists($backendLink)){
	unlink($backendLink);
}

/* create links */
if (!is_link($cssLink)) {
    symlink(__DIR__ . '/frontend/web/css', $cssLink);
}
if (!is_link($faviconLink)) {
    symlink(__DIR__ . '/frontend/web/favicon', $faviconLink);
}
if (!is_link($imageLink)) {
    symlink(__DIR__ . '/frontend/web/images', $imageLink);
}
if (!is_link($backendLink)) {
    symlink(__DIR__ . '/backend/web', $backendLink);
}
