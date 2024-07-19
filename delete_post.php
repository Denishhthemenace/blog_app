<?php
require_once 'includes/header.php';
require_once 'classes/Database.php';
require_once 'classes/Post.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
$post = new Post($db);

if (isset($_GET['id'])) {
    $stmt = $post->read($_GET['id']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row || $row['user_id'] != $_SESSION['user_id']) {
        echo "<p>Post not found or you don't have permission to delete it.</p>";
        require_once 'includes/footer.php';
        exit();
    }

    $post->id = $_GET['id'];
    if ($post->delete()) {
        echo "<p>Post deleted successfully.</p>";
        echo "<a href='index.php'>Return to Home</a>";
    } else {
        echo "<p>Unable to delete post.</p>";
    }
} else {
    header("Location: index.php");
    exit();
}

require_once 'includes/footer.php';