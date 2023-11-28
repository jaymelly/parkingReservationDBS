<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
<!---------------------------------------------------- Admin Login -------------------------------------------------------------------->
    <h2>Enter as Admin</h2>

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
// Checks if values are there
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
}
// Checks if username and password are correct
if (!empty($username) && !empty($password)) {
    if ($username=="admin" && $password=="admin123") {
        header("Location: admin.php");
    } else {
        echo "<br>Invalid username or password. Please try again.<br>";
    }
}
?>

<!---------------------------------------------------- User Login -------------------------------------------------------------------->
<br><br><br><br>
<h2>Enter as User</h2>
<a href="UserInterface.php"><button>Go To User Page</button> 
</body>
</html>
