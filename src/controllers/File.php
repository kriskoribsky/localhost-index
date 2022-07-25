<?php 

class File {
    public string $path;
    public string $basename;
    public string $filename;
    public string $extension;

    public bool $is_dir;
    public bool $is_hidden;

    public int $timestamp;
    public int $size;
    public string $icon;

    // CONFIG
    // private static bool $folders_first = FOLDERS_FIRST;
    // private static bool $dir_sizes = FOLDERS_SIZE;
    // private static bool $ignore_hidden = IGNORE_HIDDEN;

    // private constructor because of hidden file filtering
    private function __construct(string $path) {
        // example file: 'README.md'
        // $this->basename = README.md
        // $this->filename = README
        // $this->extension = md
        $this->path = $path;
        $this->basename = pathinfo($this->path, PATHINFO_BASENAME);
        $this->filename = pathinfo($this->path, PATHINFO_FILENAME);
        $this->extension = pathinfo($this->path, PATHINFO_EXTENSION);

        $this->is_dir = empty($this->extension);
        $this->is_hidden = empty($this->filename);

        $this->timestamp = $this->get_timestamp($this->path);
        $this->size = $this->get_size($this->path, FOLDERS_SIZE);
        $this->icon = $this->assign_icon($this->is_dir, $this->filename, $this->extension);

    }

    public static function get_instance(string $path): File|null {
        $ins = new File($path);

        if (!(IGNORE_HIDDEN === true && $ins->is_hidden === IGNORE_HIDDEN)) {
            return $ins;
        } else {
            return null;
        }
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

    private function get_timestamp(string $path): int {

        return filemtime($path);
    }

    private function get_size(string $path, bool $dir_sizes): int {

        // $path = rtrim((str_replace('\\', '/', $path)), '/');
        $path = realpath($path);

        // OS specific checks (faster) || recursive directory traverse (slower)
        if ($dir_sizes && is_dir($path)) {
            $os = strtolower(substr(PHP_OS, 0, 3));

            // Windows Host (WIN32, WINNT, Windows)
            if ($os === 'win' && extension_loaded('com_dotnet')) {
                $obj = new COM('scripting.filesystemobject');

                if (is_object($obj)) {
                    $ref = $obj->getfolder($path);
                    $size = $ref->size;
                    $obj = null;
                    return $size;
                }
            }
            // Unix Host (Linux, Mac OS)
            if ($os !== 'win') {
                $io = popen('/usr/bin/du -sb ' . $path, 'r');

                if ($io) {
                    $size = intval(fgets($io, 80));
                    pclose($io);
                    return $size;
                }
            }
            // if system calls did't work, use slower recursive directory traverse method
            $size = 0;
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS));

            foreach ($files as $file) {
                $size += $file->getSize();
            }
            return $size;

        } else {
            return filesize($path);
        }
    }

    private function assign_icon(bool $is_dir, string $filename, string $extension): string {
        $path = 'localhost-index/src/view/assets/icons/';

        if ($is_dir) {
            switch ($filename) {
                default:
                    return $path . 'default_folder.svg';
            }

        } else {
            switch ($extension) {
                default:
                    return $path . 'default_file.svg';
            }
        }
    }
}
?>