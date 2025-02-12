<?php
session_start();

// HardCOD-CRED 
$db_host = "localhost";
$db_user = "root";
$db_pass = "password";
$db_name = "vulnerable_db";
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// S-Q-L INJ
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $_SESSION['user'] = $username; // Insecure session handling (CWE-311)
        echo "<script>alert('Login successful!');</script>";
    } else {
        echo "<script>alert('Invalid credentials');</script>";
    }
}

// X-S-S-U-COMMENT
if (isset($_POST['comment'])) {
    $comment = $_POST['comment'];
    echo "<p>User Comment: $comment</p>"; // No sanitization
}

// CommanD INJ
if (isset($_GET['ping'])) {
    $ip = $_GET['ping'];
    echo "<pre>" . shell_exec("ping -c 4 $ip") . "</pre>"; // No input validation
}

// UnSEC-FI-UP
if (isset($_FILES['file'])) {
    $upload_dir = "uploads/";
    $file_path = $upload_dir . $_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], $file_path);
    echo "<p>File uploaded to: $file_path</p>";
}

// Ins-F-INC 
if (isset($_GET['page'])) {
    include($_GET['page']); // Direct file inclusion
}

// S-S-R-F
if (isset($_GET['fetch'])) {
    $url = $_GET['fetch'];
    echo "<p>Fetching data from: $url</p>";
    echo "<pre>" . file_get_contents($url) . "</pre>"; // Fetches content from any URL
}

// - D.TRAV
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    echo "<pre>" . file_get_contents("uploads/" . $file) . "</pre>"; // No input validation
}

// C-s-r-f - Fake money transfer
if (isset($_POST['transfer'])) {
    echo "<p>Transferred $" . $_POST['amount'] . " to attacker account!</p>"; // No CSRF protection
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vulnerable PHP App</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username"><br>
        <input type="password" name="password" placeholder="Password"><br>
        <button type="submit" name="login">Login</button>
    </form>

    <h2>Comment Section</h2>
    <form method="POST">
        <textarea name="comment"></textarea><br>
        <button type="submit">Post Comment</button>
    </form>

    <h2>Ping a Server</h2>
    <form method="GET">
        <input type="text" name="ping" placeholder="Enter IP">
        <button type="submit">Ping</button>
    </form>

    <h2>File Upload</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="file"><br>
        <button type="submit">Upload</button>
    </form>

    <h2>Transfer Money</h2>
    <form method="POST">
        <input type="number" name="amount" placeholder="Amount">
        <button type="submit" name="transfer">Transfer</button>
    </form>

    <h2>File Inc</h2>
    <form method="GET">
        <input type="text" name="page" placeholder="Enter file name">
        <button type="submit">Include</button>
    </form>

    <h2>Fetch URL (ss-r-f)</h2>
    <form method="GET">
        <input type="text" name="fetch" placeholder="Enter URL">
        <button type="submit">Fetch</button>
    </form>

    <h2>Read File (DTRav)</h2>
    <form method="GET">
        <input type="text" name="file" placeholder="Enter filename">
        <button type="submit">Read</button>
    </form>
</body>
</html>
