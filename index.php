<?php

    require_once '/Programovanie/web/stranky/localhost-index/src/controllers/quotes.php';


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

    class File {
        public string $name;
        public int $timestamp;
        public int $size;
        public string $icon;

        // CONFIG
        // include sizes for directories (increased overhead)
        private static bool $dir_sizes = true;

        

        public function __construct(string $file_name) {
            $this->name = $file_name;

            $this->timestamp = $this->get_timestamp($file_name);
            $this->size = $this->get_size($file_name, self::$dir_sizes);
            $this->icon = $this->get_icon($file_name);

        }

        public static function format_bytes(int $bytes, bool $base_2 = false): array {
            $prefixes = [
                ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'],
                ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
            ];
            
            $prefix = $base_2 ? $prefixes[0] : $prefixes[1]; 
            $base = $base_2 ? 1024 : 1000;
            
            // handle division by 0 & logarithm of 0 cases 
            if ($bytes === 0) {
                return ['value' => 0, 'prefix' => $prefix[0]];
            }

            $exponent = floor(log($bytes, $base));

            return ['value' => round($bytes / pow($base, $exponent), 2), 'prefix' => $prefix[$exponent]];
        }

        public static function format_date(int $timestamp): string {
            // day-month-year hours:minutes
            $time_format = 'd-m-Y G:i';

            return date($time_format, $timestamp);
        }

        // used for built-in usort function
        public static function sort_files(int|string $input1, int|string $input2, bool $ascending): int {
            $type1 = gettype($input1);
            $type2 = gettype($input2);

            assert($type1 === $type2, 'Sorting according to distinct data types.');

            if ($type1 === 'integer') {
                return $ascending ? $input1 <=> $input2 : $input2 <=> $input1;

            } elseif ($type1 === 'string') {
                return $ascending ? strcmp($input1, $input2) : strcmp($input2, $input1);

            } else {
                throw new TypeError('Sorting according to incorrect data types.');
            }

        }

        private function get_timestamp(string $file_name): int {

            return filemtime($file_name);
        }

        private function get_size(string $file_name, bool $dir_sizes): int {

            // $file_name = rtrim((str_replace('\\', '/', $file_name)), '/');
            $file_name = realpath($file_name);

            // OS specific checks (faster) || recursive directory traverse (slower)
            if ($dir_sizes && is_dir($file_name)) {
                $os = strtolower(substr(PHP_OS, 0, 3));

                // Windows Host (WIN32, WINNT, Windows)
                if ($os === 'win' && extension_loaded('com_dotnet')) {
                    $obj = new COM('scripting.filesystemobject');

                    if (is_object($obj)) {
                        $ref = $obj->getfolder($file_name);
                        $size = $ref->size;
                        $obj = null;
                        return $size;
                    }
                }
                // Unix Host (Linux, Mac OS)
                if ($os !== 'win') {
                    $io = popen('/usr/bin/du -sb ' . $file_name, 'r');

                    if ($io) {
                        $size = intval(fgets($io, 80));
                        pclose($io);
                        return $size;
                    }
                }
                // if system calls did't work, use slower recursive directory traverse method
                $size = 0;
                $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($file_name, FilesystemIterator::SKIP_DOTS));

                foreach ($files as $file) {
                    $size += $file->getSize();
                }
                return $size;

            } else {
                return filesize($file_name);
            }
        }

        private function get_icon(string $file_name): string {
          
            $ext = pathinfo($file_name, PATHINFO_EXTENSION);

            // folder
            if ($ext === '') {
                $folder_name = pathinfo($file_name, PATHINFO_FILENAME);
                $q = 'folder_type_' . $folder_name;
                return (array_key_exists($q, ICONS)) ? $q : ICONS['default_folder'];

            // file
            } else {
                $q = 'file_type_' . $ext;
                return (array_key_exists($q, ICONS)) ? $q : ICONS['default_file'];
            }
        }
    }
    
    // main
    $dir = getcwd();

    // remove '.' -> current dir & '..' parent dir entries
    $file_names = array_values(array_diff(scandir($dir), ['.', '..']));
    $files = [];

    foreach ($file_names as $name) {
        $files[] = new File($name);
    }

    // get random quote
    $random_quote = array_rand(QUOTES);

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