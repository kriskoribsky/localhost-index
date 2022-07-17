<?php

    define('ICONS', [
        'default_file' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><title>default_file</title><path d="M20.414,2H5V30H27V8.586ZM7,28V4H19v6h6V28Z" style="fill:#c5c5c5"/></svg>',
        'default_folder' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><title>default_folder</title><path d="M27.5,5.5H18.2L16.1,9.7H4.4V26.5H29.6V5.5Zm0,4.2H19.3l1.1-2.1h7.1Z" style="fill:#c09553"/></svg>'
    ]);

    define('QUOTES', [
        // Warren Buffet
        'Someone\'s sitting in the shade today because someone planted a tree a long time ago.' => 'Warren Buffett',
        'If you aren\'t willing to own a stock for 10 years, don\'t even think about owning it for 10 minutes.' => 'Warren Buffett',
        'The most important investment you can make is in yourself.' => 'Warren Buffett',
        'I will tell you how to become rich. Close the doors. Be fearful when others are greedy. Be greedy when others are fearful.' => 'Warren Buffett',
        'Never depend on a single income. Make an investment to create a second source.' => 'Warren Buffett',
        'No matter how great the talent or efforts, some things just take time. You can\'t produce a baby in one month by getting nine women pregnant.' => 'Warren Buffett',
        'Don\'t pass up something that\'s attractive today because you think you will find something better tomorrow.' => 'Warren Buffett',
        // Other
        'There is nothing impossible to him who will try.' => 'Alexander the Great',
        'When you have a dream, you\'ve got to grab it and never let go.' => 'Carol Burnett',
        'Be courageous. Challenge orthodoxy. Stand up for what you believe in. When you are in your rocking chair talking to your grandchildren many years from now, be sure you have a good story to tell.' => 'Amal Clooney',
        'Success is not final, failure is not fatal: it is the courage to continue that counts.' => 'Winston Churchill',
        'You are never too old to set another goal or to dream a new dream.' => 'Malala Yousafzai',
        'Where your fear is, there is your task.' => 'Carl Jung',
        'Everything you ever wanted is on the other side of fear.' => 'George Adair',


    ]);

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
        public string $date_edited;
        public int $size;
        public string $icon;

        // CONFIG
        // include sizes for directories (increased overhead)
        private static bool $dir_sizes = true;

        

        public function __construct(string $file_name) {
            $this->name = $file_name;

            $this->date_edited = $this->get_edit_date($file_name);
            $this->size = $this->get_size($file_name, self::$dir_sizes);
            $this->icon = $this->get_icon($file_name);

        }

        public function format_bytes(int $bytes): string {
            return 'pass';
        }

        private function get_edit_date(string $file_name): string {
            // day-month-year hours:minutes
            $time_format = 'd-m-Y G:i';

            return date($time_format, filemtime($file_name));
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



    $dir = getcwd();

    // remove '.' -> current dir & '..' parent dir entries
    $file_names = array_values(array_diff(scandir($dir), ['.', '..']));
    $files = [];

    foreach ($file_names as $name) {
        $files[] = new File($name);
    }

    // Helper::debug_var($files, false, true);

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Page</title>

    <meta name="description" content="Simple index page replacing default server's indexing.">

    <style>
        /* minified normalize.css */
        html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}main{display:block}h1{font-size:2em;margin:.67em 0}hr{box-sizing:content-box;height:0;overflow:visible}pre{font-family:monospace,monospace;font-size:1em}a{background-color:rgba(0,0,0,0)}abbr[title]{border-bottom:none;text-decoration:underline;text-decoration:underline dotted}b,strong{font-weight:bolder}code,kbd,samp{font-family:monospace,monospace;font-size:1em}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative;vertical-align:baseline}sub{bottom:-0.25em}sup{top:-0.5em}img{border-style:none}button,input,optgroup,select,textarea{font-family:inherit;font-size:100%;line-height:1.15;margin:0}button,input{overflow:visible}button,select{text-transform:none}button,[type=button],[type=reset],[type=submit]{-webkit-appearance:button}button::-moz-focus-inner,[type=button]::-moz-focus-inner,[type=reset]::-moz-focus-inner,[type=submit]::-moz-focus-inner{border-style:none;padding:0}button:-moz-focusring,[type=button]:-moz-focusring,[type=reset]:-moz-focusring,[type=submit]:-moz-focusring{outline:1px dotted ButtonText}fieldset{padding:.35em .75em .625em}legend{box-sizing:border-box;color:inherit;display:table;max-width:100%;padding:0;white-space:normal}progress{vertical-align:baseline}textarea{overflow:auto}[type=checkbox],[type=radio]{box-sizing:border-box;padding:0}[type=number]::-webkit-inner-spin-button,[type=number]::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}[type=search]::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}details{display:block}summary{display:list-item}template{display:none}[hidden]{display:none}

        /* main styles */
        html {
            box-sizing: border-box;
        }

        *, *::before, *::after {
            box-sizing: inherit;
        }

        body {
            font-family: "Century Schoolbook", Verdana, Arial, sans-serif;

            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: auto;
        }

        .window {
            max-width: 1280px;
            text-align: center;
        }
        
        .container {
            margin: 0 auto;
        }
    </style>





</head>
<body>

    <div class="container window">

        <header>
            <h1>Projects index page</h1>

            <br>

            <p>
                <code>
                    <?php echo __DIR__; ?>
                </code>
            </p>

        </header>
    
        <main>

            <table class="container files">

                <thead>

                    <tr>
                        <!-- GET requests using href -->
                        <th abbr="File names" title="Sort alphabetically"><a href="?">Name</a></th>
                        <th abbr="Last file edit" title="Sort by time of edit"><a href="?">Last Modified</a></th>
                        <th abbr="Size of file" title="Sort by file size"><a href="?">Size</a></th>
                    </tr>

                    <tr>
                        <th colspan="3"><hr></th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach($files as $file): ?>
                        <tr>
                            <td><a href="<?php echo $file->name; ?>"><?php echo $file->name; ?></a></td>
                            <td><?php echo $file->date_edited; ?></td>
                            <td><?php echo $file->size; ?></td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>

                <tfoot>

                    <tr>
                        <th colspan="3"><hr></th>
                    </tr>

                    <tr>
                        <th title="Open folder in explorer"><a href="">Open in explorer</a></th>
                    </tr>

                </tfoot>


            </table>

            <div class="system-info"></div>

        </main>
    
        <footer>

            <div class="container quote"></div>

        </footer>

    </div>

</body>
</html>