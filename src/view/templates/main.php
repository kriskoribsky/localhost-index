<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Page</title>

    <meta name="description" content="Simple index page replacing default server's indexing.">

    <link rel="stylesheet" type="text/css" href="localhost-index/src/view/css/normalize.min.css?<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="localhost-index/src/view/css/main.css?<?php echo time(); ?>">

    <script defer src="localhost-index/src/view/js/main.js?<?php echo time(); ?>"></script>

</head>

<body>

    <div class="container window text-center secondary-text-clr">

        <header>

            <h1>Projects index page</h1>

            <br>

            <p>
                <code>
                    <?php echo INDEX_DIR; ?>
                </code>
            </p>

        </header>
    
        <main>

            <table class="container primary-text-clr" width="100%">

                <thead>

                    <tr class="sort-rows">
                        <!-- GET requests using href & sort order logic -->
                        <th abbr="File names" title="Sort alphabetically">
                            <a href="?C=N&O=<?php echo (isset($_GET['C'], $_GET['O']) && $_GET['C'] === 'N' && $_GET['O'] === 'A') ? 'D' : 'A'; ?>">Name</a>
                        </th>

                        <th abbr="Last file edit" title="Sort by time of edit">
                            <a href="?C=T&O=<?php echo (isset($_GET['C'], $_GET['O']) && $_GET['C'] === 'T' && $_GET['O'] === 'A') ? 'D' : 'A'; ?>">Last Modified</a>
                        </th>

                        <th class="text-right" abbr="Size of file" title="Sort by file size">
                            <a href="?C=S&O=<?php echo (isset($_GET['C'], $_GET['O']) && $_GET['C'] === 'S' && $_GET['O'] === 'A') ? 'D' : 'A'; ?>">Size</a>
                        </th>
                    </tr>

                    <tr>
                        <th colspan="5"><hr></th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach($files as $file): ?>
                        <tr class="file-rows">
                            <td class="files text-left">
                                <a href="<?php echo $file->basename; ?>">
                                    <img class=svg src="<?php echo $file->icon; ?>" alt="<?php echo $file->is_dir ? '[D]' : '[F]' ?>">
                                    <?php echo $file->basename; ?>
                                </a>
                            </td>
                            <td class="text-left"><?php echo File::format_date($file->timestamp); ?></td>

                            <?php $formatted_bytes = File::format_bytes($file->size); $folder_size += $file->size ?>

                            <td class="text-right"><?php echo $formatted_bytes['value']; ?></td>
                            <td class="text-right"><?php echo $formatted_bytes['prefix']; ?></td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>

                <tfoot>

                    <tr>
                        <th colspan="5"><hr></th>
                    </tr>

                    <tr>
                        <td class="text-left" colspan="2">
                            <?php echo apache_get_version(); ?>
                            <a hidden href="#">View more</a>
                        </td>
                        <td class="text-right"><?php echo implode(' ', File::format_bytes($folder_size)); ?></td>
                        <td class="text-right" title="Open folder in explorer">
                            
                            <form name="open-explorer-form" action="/localhost-index/src/controllers/open_in_explorer.php" method="post">
                                
                                <input type="hidden" name="open_root" value="true">
                                <a id="open-explorer" href="#">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48" viewbox="0 0 48 48">
                                        <path d="M7 40q-1.15 0-2.075-.925Q4 38.15 4 37V11q0-1.15.925-2.075Q5.85 8 7 8h12.8q.6 0 1.175.25.575.25.975.65l2.1 2.1H41q1.15 0 2.075.925Q44 12.85 44 14H22.75l-3-3H7v26l4.5-17.75q.25-1 1.1-1.625.85-.625 1.85-.625H43.1q1.45 0 2.4 1.15t.55 2.6l-4.4 16.95q-.3 1.2-1.1 1.75T38.5 40Zm3.15-3h28.6l4.2-17h-28.6Zm0 0 4.2-17-4.2 17ZM7 17v-6 6Z"/>
                                    </svg>
                                </a>
                        
                            </form>
                        </td>
                       
                    </tr>
                </tfoot>
            </table>
        </main>
    
        <footer>

            <blockquote class="container">
                        
                    <?php $q = get_random_quote(); ?>

                    <p class="quote"><?php echo $q['quote'] ?></p>
                    <p class="author text-right"><strong><?php echo $q['author']; ?></strong></p>

            </blockquote>

        </footer>

    </div>

</body>

</html>