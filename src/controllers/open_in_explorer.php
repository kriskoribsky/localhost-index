<?php 

    echo $_SERVER['REQUEST_METHOD'];
    // check for GET request and redirect back to index.php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['open_root']) && $_POST['open_root'] === true) {
        exec('start ' . INDEX_DIR);  
    } 

    // echo $_SERVER['DOCUMENT_ROOT'];
    header('Location: /localhost-index/index.php', response_code:301);
    exit;

?>