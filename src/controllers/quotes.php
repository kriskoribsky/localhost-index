<?php

$quotes = json_decode(file_get_contents('/Programovanie/web/stranky/localhost-index/src/view/assets/quotes.json'), true);

Helper::debug_var($quotes);

?>