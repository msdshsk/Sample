<?php

require_once '../src/Shsk/Autoloader.php';
new Shsk\Autoloader();

use Shsk\FileSystem\Directory;

$results = new Directory('results');

if ($results->exists()) {
    $results->remove(true);
}

$samples = new Directory('.');
foreach ($samples->getSearchFileIterator('/^sample_[0-9]+.*\.php$/') as $file) {
    require_once $file->getPathname();
    $func = explode('-', $file->getBasename())[0];
    call_user_func($func);
}
