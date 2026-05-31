<?php
require_once __DIR__ . '/functions.php';

if (!isLoggedIn()) {
    flash('error', 'Silakan login terlebih dahulu.');
    redirect('login.php');
}
