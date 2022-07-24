<?php

function get_random_quote(array|null $categories = null): array {

    $quotes = json_decode(file_get_contents('/Programovanie/web/stranky/localhost-index/src/view/assets/quotes.json'), true);
    
    if ($categories) {
        $quotes = array_intersect_key($quotes, array_flip($categories));
    }

    $category = array_rand($quotes);
    $quote = array_rand($quotes[$category]);

    return ['category' => $category, 'quote' => $quote, 'author' => $quotes[$category][$quote]];
}

?>