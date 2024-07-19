<?php
require_once 'includes/header.php';
require_once 'classes/Database.php';
require_once 'classes/Post.php';

$database = new Database();
$db = $database->getConnection();
$post = new Post($db);

if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword'];
    $stmt = $post->search($keyword);
} else {
    header("Location: index.php");
    exit();
}
?>

<h2>Search Results for "<?php echo htmlspecialchars($keyword); ?>"</h2>
<?php if ($stmt->rowCount() > 0): ?>
    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
        <article class="post">
            <h3><a href="view_post.php?id=<?php echo $row['id']; ?>"><?php echo $row['title']; ?></a></h3>
            <p>By <?php echo $row['username']; ?> on <?php echo $row['created_at']; ?></p>
            <p><?php echo substr($row['content'], 0, 200) . '...'; ?></p>
        </article>
    <?php endwhile; ?>
<?php else: ?>
    <p>No results found.</p>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>