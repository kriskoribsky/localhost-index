<?php

// PATHS

// path where index.php file is located
define('ROOT_PATH', getcwd());

// path to file icons
define('ICON_PATH', ROOT_PATH . '\src\view\assets\icons');






// FUNCTIONS

// whether to separate folders and files (sorting will be also separate)
const FOLDERS_FIRST = false;

// include and calculate sizes of folders
const FOLDERS_SIZE = true;

// don't display hidden files, starting with '.' (e.g. '.git')
const IGNORE_HIDDEN = false;

?>