<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();
}

require_once 'classes/Database.php';
require_once 'classes/Post.php';

$error = "";
$success = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $database = new Database();
    $db = $database->getConnection();

    $post = new Post($db);

    $post->title = $_POST['title'];
    $post->content = $_POST['content'];
    $post->user_id = $_SESSION['user_id'];

    if ($post->create()) {
        $success = "Post created successfully.";
    } else {
        $error = "Unable to create post.";
    }
}

include_once 'includes/header.php';
?>

<div class="container">
    <h2>Create New Post</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea class="form-control" id="content" name="content" rows="6" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Post</button>
    </form>
</div>

<?php

include_once 'includes/footer.php';
?>