<?php
session_start(); 

require_once 'includes/header.php';
require_once 'classes/Database.php';
require_once 'classes/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user->login($username, $password)) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="username" required placeholder="Username">
        <input type="password" name="password" required placeholder="Password">
        <button type="submit">Login</button>
    </form>
</body>
</html>

<?php require_once 'includes/footer.php'; ?>
