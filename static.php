<?php
$name = str_replace('..', '', filter_input(INPUT_GET, '_item', FILTER_SANITIZE_STRING));

// abre o arquivo em modo binário
$path = 'app/static/' . $name;
if (file_exists($path)) {
    $name = $path;
}
else {
    $path = 'app_default/static/' . $name;
    if (file_exists($path)) {
        $name = $path;
    }
    else {
        header('HTTP/1.0 404 Not Found');
        exit;
    }
}
$fp = fopen($name, 'rb');

//http://www.sitepoint.com/web-foundations/mime-types-complete-list/

$ext = strtolower(substr($name, strrpos($name, ".")+1));

$mimeTypes = array(
    'css'  => 'text/css',
    #fonts
    'woff' => 'text/css',
    'ttf' => 'text/css',
    'eot' => 'text/css',
    #images
    'gif' => 'image/gif',
    'jpg' => 'image/jpeg',
    'png' => 'image/png',
    #others
    'js' => 'text/javascript',
    'pdf' => 'application/pdf'
);

if (isset($mimeTypes[$ext])) {
    $contentType = $mimeTypes[$ext];
}
else {
    //TODO required extension=php_fileinfo.dll
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $contentType = finfo_file($finfo, $name);
}

// envia os headers
header("Content-Type: " . $contentType);
header("Content-Length: ".filesize($name));

//TODO cache

// manda a imagem e para o script
fpassthru($fp);
exit;