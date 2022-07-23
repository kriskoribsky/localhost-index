<?php
/**
 * Created by Peter Vano
 * Development Environment: PHPStorm
 * Mail: info@pevasoft.com
 * Phone: +421 907 761 771
 */



// local
define('DOMAIN',      'http://localhost/jobin');

// test
// define('DOMAIN',      'https://jobin.pevasoft.com');

// live
// define('DOMAIN',      'https://');



// LOCAL SOURCE URL
define('DOMAIN_SRC',      'http://localhost/jobin');

// SOURCE URL
// define('DOMAIN_SRC',      'https://jobin.pevasoft.com');


// LOCAL DB
// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASS', 'root');
// define('DB_NAME', '');


// TEST DB
define('DB_HOST', 'mariadb105.websupport.sk:3315');
define('DB_USER', 'jobin_test');
define('DB_PASS', 'Xa1qTS/A&|');
define('DB_NAME', 'jobin_test');


// REAL DB
// define('DB_HOST', 'mysql80.websupport.sk:3314');
// define('DB_USER', '');
// define('DB_PASS', '');
// define('DB_NAME', '');


define('TEMPLATES', ['jobin']);
define('PROJECT_NAME', ['JOBin']);
define('PROJECT_TPL_NAME', ['jobin']);


define('SALT_REFERAL_LINKS',    'Gcpnjj71*AY&xvoHL0a4c8K#gl@mu~^M');

define('SALT_FORG_PASS',        'PWC*R4$2xF8H5^1a~fT4&vs4UufG0NpG');

define('SALT_EMAIL_PREINVOICE', 'jRiZCaZmPI3J0EyPsT`ejK*ocK&O6RD~');

define('SALT_INVOICE',          '>LQd4OENE5pFW8u0gIU3~1^rQG>nfs0e');



// email access
// define('EMAIL_HOST','smtp.websupport.sk');
// define('EMAIL_USER','');
// define('EMAIL_PASSWORD','Gy3?!^]6bQ');
// define('EMAIL_PORT', '465');

// email no-reply
define('EMAIL_HOST','smtp.websupport.sk');
define('EMAIL_USER','no-reply@pevasoft.com');
define('EMAIL_PASSWORD','Tz2bP{($*M');
define('EMAIL_PORT', '465');


define('LIVE_MAILS', ['test@pevasoft.dev']);
define('DEBUG_MAILS', ['test@pevasoft.dev']);


// default language
define('DEFAULT_DATETIME_ZONE', 'Europe/Bratislava');


// default language
// define('DEFAULT_LANG', 'sk');


// default currency
define('DEFAULT_CURRENCY', 'EUR');


// debug mode
define('DEBUG', true);


// analytics
define('ANALYTICS', false);


// redirect to HTTPS
define('HTTPS', false);


// order id which defines line between counting DPH
define('DPH_FIRST_ORDER', 999999999);