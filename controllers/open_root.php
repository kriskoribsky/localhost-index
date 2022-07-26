<?php
chdir(realpath($_SERVER['DOCUMENT_ROOT'] . $_GET['p']));
exec('start .');