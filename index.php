<?php

    class Helper {



        public static function debugVar(mixed $var, bool $exit = false): void {
            
            echo    '<br>
                    <fieldset style="border: 2px groove red; padding: 0 2rem">
                    <legend style="color: red; font-weight: bold">Debug</legend>
                    <pre style="text-align:left">';

            switch (gettype($var)) {
                case "array":
                case "object":
                    print_r($var);
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
        public $icon;

        

        public function __construct($fileName) {
            $this->name = $fileName;


        }


        
        private function getEditDate() {

        }

        private function getSize() {

        }



    }



    $dir = getcwd();
    $files = scandir($dir);

    // remove '.' -> current dir & '..' parent dir entries
    $files = array_values(array_diff($files, ['.', '..']));



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

                <tbody>

                    <?php foreach($files as $file): ?>
                        <tr>
                            <td><a href="<?php echo $file; ?>"><?php echo $file; ?></a></td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>


            </table>

            <div class="system-info"></div>

        </main>
    
        <footer>

            <div class="container quote"></div>

        </footer>

    </div>

</body>
</html>