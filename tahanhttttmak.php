<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['url'])) {
    $url = trim($_POST['url']);
    $filename = basename(parse_url($url, PHP_URL_PATH));

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode === 200 && !empty($content)) {
        if (file_put_contents($filename, $content)) {
            echo "<p>✅ Berhasil disimpan sebagai <b>$filename</b></p>";
        } else {
            echo "<p>❌ Gagal menyimpan file.</p>";
        }
    } else {
        echo "<p>❌ Gagal mengunduh file (HTTP Code: $httpcode)</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Auto Download RAW GitHub</title>
</head>
<body>
    <h2>Masukkan URL RAW GitHub</h2>
    <form method="post">
        <input type="text" name="url" placeholder="https://raw.githubusercontent.com/..." style="width: 400px;" required>
        <button type="submit">Download & Simpan</button>
    </form>
</body>
</html>
