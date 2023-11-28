<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
<?php
$username="";
$password="";
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
}
if (!empty($username) && !empty($password)) {
    if ($username=="admin" && $password=="admin123") {
        header("Location: admin.php");
    } else {
        echo "<br><h2>Invalid username or password. Please try again.<h2>";
    }
}
?>

</body>
</html>
