<?php
    require_once __DIR__ . '\settings.inc.php';
    require_once ROOT_PATH . '\src\controllers\quotes.php';
    require_once ROOT_PATH . '\src\controllers\File.php';
?>

<?php 
    class Helper {

        public static function debug_var(mixed $var, bool $exit = false): void {
            
            echo    '<br>
                    <fieldset style="border: 2px groove red; padding: 0 2rem">
                    <legend style="color: red; font-weight: bold">Debug</legend>
                    <pre style="text-align:left; font-size:12px">';
    
            switch (gettype($var)) {
                case 'array':
                case 'object':
                    echo htmlentities(print_r($var, true));
                    break;
                case 'string':
                    echo 'string(' . strlen($var) . ') "' . htmlentities($var) . '"';
                    break;
                default:
                    var_dump($var);
            }
    
            if ($exit) {
                echo "<br><u>Exit called from:</u><br>\t";
    
                debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
    
                exit('<br><strong>Execution explicitly terminated using <em>' . __METHOD__ . ' </em>method.</strong>');
            }
            echo '</pre></fieldset>';
        }
    }

    // main code
    define('INDEX_DIR', isset($_GET['path']) ? realpath($_GET['path']) : realpath($_SERVER['DOCUMENT_ROOT']));

    // Helper::debug_var(INDEX_DIR);

    // remove '.' -> current dir & '..' parent dir entries
    $filenames = array_values(array_diff(scandir(INDEX_DIR), ['.', '..']));

    $folders = [];
    $files = [];

    foreach ($filenames as $name) {
        $file = File::get_instance(INDEX_DIR . '\\' . $name);
        if (!empty($file)) {
            if (FOLDERS_FIRST) {
                if ($file->is_dir) {
                    $folders[] = $file;
                } else {
                    $files[] = $file;
                }
            } else {
                $files[] = $file;
            }
        }
    }

    // put folders first
    $files = array_merge($folders, $files);

    // total size of current folder
    $folder_size = 0;

    // GET & POST requests
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST' && isset($_POST['open_root']):

            if ((bool) $_POST['open_root'] === true) {
                exec('start ' . __DIR__);
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            }

            break;

        case 'GET' && isset($_GET['C'], $_GET['O']):

            switch ($_GET['C']) {
                case 'N':
                    usort($files, fn(File $file1, File $file2): int => File::sort_files($file1->name, $file2->name,
                        $_GET['O']==='A'));
                    break;
                case 'T':
                    usort($files, fn(File $file1, File $file2): int => File::sort_files($file1->timestamp, $file2->timestamp,
                        $_GET['O']==='A'));
                    break;
                case 'S':
                    usort($files, fn(File $file1, File $file2): int => File::sort_files($file1->size, $file2->size,
                        $_GET['O']==='A'));
                    break;
            }
            break;
    }
?>

<?php include ROOT_PATH . '\src\view\templates\main.php'; ?> 