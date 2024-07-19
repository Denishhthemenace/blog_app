<?php
session_start();
require_once 'includes/header.php';
require_once 'classes/Database.php';
require_once __DIR__ . '/classes/Post.php';

$database = new Database();
$db = $database->getConnection();
$post = new Post($db);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 5;
$from_record_num = ($records_per_page * $page) - $records_per_page;

$stmt = $post->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

$total_rows = $post->count();
$total_pages = ceil($total_rows / $records_per_page);
?>

<div class="container">
    <h2>Recent Blog Posts</h2>

    <?php if(isset($_SESSION['user_id'])): ?>
        <div class="user-actions">
            <a href="new_post.php" class="btn btn-primary">Create New Post</a>
            <a href="export.php" class="btn btn-secondary">Export Posts to Excel</a>
        </div>
    <?php endif; ?>

    <?php if($num > 0): ?>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <article class="post">
                <h3><a href="view_post.php?id=<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h3>
                <p class="post-meta">By <?php echo htmlspecialchars($row['username']); ?> on <?php echo date('F j, Y', strtotime($row['created_at'])); ?></p>
                <div class="post-excerpt">
                    <?php 
                    $excerpt = strip_tags($row['content']);
                    echo substr($excerpt, 0, 200) . (strlen($excerpt) > 200 ? '...' : '');
                    ?>
                </div>
                <a href="view_post.php?id=<?php echo $row['id']; ?>" class="read-more">Read More</a>
            </article>
        <?php endwhile; ?>

        <div class="pagination">
            <?php if($page > 1): ?>
                <a href="index.php?page=<?php echo $page - 1; ?>" class="btn btn-secondary">&laquo; Previous</a>
            <?php endif; ?>
            
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <?php if($i == $page): ?>
                    <span class="current-page"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if($page < $total_pages): ?>  
                <a href="index.php?page=<?php echo $page + 1; ?>" class="btn btn-secondary">Next &raquo;</a>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <p>No posts found.</p>
    <?php endif; ?>

    <?php if(!isset($_SESSION['user_id'])): ?>
        <div class="cta-section">
            <h3>Join our blogging community!</h3>
            <p>Sign up to create your own posts and interact with other bloggers.</p>
            <a href="signup.php" class="btn btn-primary">Sign Up Now</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>