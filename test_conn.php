<?php
$c = new mysqli('localhost', 'root', 'root');
echo $c->connect_error ? 'FAIL: ' . $c->connect_error : 'OK';
