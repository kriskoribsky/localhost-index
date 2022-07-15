<?php

    class Helper {



        public static function debugVar(mixed $var, bool $exit = false): void {
            echo '<hr><pre style="color: red;">';

            switch (gettype($var)) {
                case "array":
                case "object":
                    print_r($var);
                    break;
                default:
                    var_dump($var);
            }

            if ($exit) {
                $backtrace = debug_backtrace();
                
                echo '<br><em>Exit called from:</em><br>';

                // loop through & output all stack frames in backtrace
                for ($i = 0; $i < count($backtrace); $i++) {
                    $trace = $backtrace[$i];

                    // output string formatting
                    echo ($i + 1) . '.) ' . $trace['file'] . ' (line ' . $trace['line'] . ') ';
                    echo (array_key_exists('class', $trace)) ? $trace['class'] . ' -> ' : '';
                    echo (array_key_exists('function', $trace)) ? $trace['function'] . '(' : '';
                    echo (array_key_exists('args', $trace)) ? implode(", ", $trace['args']) . ')' : ')';  
                }
                exit('<br><strong>Execution explicitly terminated in <em>' . __METHOD__ . '</em></strong>');
            }

            echo '</pre>';

        }
    }


    $dir = getcwd();
    $files = scandir($dir);

    // remove '.' -> current dir & '..' parent dir entries
    $files = array_diff($files, ['.', '..']);

    // echo '<pre>';
    // print_r($files);
    // echo '</pre>';

    class File {
        public string $name;
        public string $date_edited;
        public int $size;
        public $icon;

        public function __construct() {

        }
    }

    exit("Exited on 'exit' statement in code.");


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Index Page</title>

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
            font-family: Verdana, Arial, sans-serif;
        }

        .window {
            max-width: 1280px;
            border: 1px solid green;
        }
        
        .container {
            margin: 0 auto;
        }





    </style>





</head>
<body>

    <div class="window container">

        <header></header>
    
        <main>

            <table class="files">

                <?php foreach($files as $file): ?>
                    <tr>
                        <td><a href="<?php echo $file; ?>"><?php echo $file; ?></a></td>
                    </tr>
                <?php endforeach; ?>


            </table>

            <div class="system-info"></div>

        </main>
    
        <footer>

            <div class="container quote"></div>

        </footer>

    </div>

</body>
</html>