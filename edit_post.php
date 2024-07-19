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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post->id = $_POST['id'];
    $post->title = $_POST['title'];
    $post->content = $_POST['content'];

    if ($post->update()) {
        $success = "Post updated successfully";
    } else {
        $error = "Unable to update post";
    }
}

if (isset($_GET['id'])) {
    $stmt = $post->read($_GET['id']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row || $row['user_id'] != $_SESSION['user_id']) {
        echo "<p>Post not found or you don't have permission to edit it.</p>";
        require_once 'includes/footer.php';
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<h2>Edit Post</h2>
<?php if (isset($success)): ?>
    <p style="color: green;"><?php echo $success; ?></p>
<?php endif; ?>
<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>
<form method="POST">
    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
    <input type="text" name="title" required placeholder="Post Title" value="<?php echo $row['title']; ?>">
    <textarea name="content" required placeholder="Post Content"><?php echo $row['content']; ?></textarea>
    <button type="submit">Update Post</button>
</form>

<?php require_once 'includes/footer.php'; ?>