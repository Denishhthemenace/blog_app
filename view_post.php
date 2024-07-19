<?php
require_once 'includes/header.php';
require_once 'classes/Database.php';
require_once 'classes/Post.php';

$database = new Database();
$db = $database->getConnection();
$post = new Post($db);

if (isset($_GET['id'])) {
    $stmt = $post->read($_GET['id']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        echo "<p>Post not found.</p>";
        require_once 'includes/footer.php';
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<article class="post" id="exportContent">
    <h2><?php echo htmlspecialchars($row['title']); ?></h2>
    <p>By <?php echo htmlspecialchars($row['username']); ?> on <?php echo htmlspecialchars($row['created_at']); ?></p>
    <div class="post-content">
        <?php echo nl2br(htmlspecialchars($row['content'])); ?>
    </div>
</article>

<script>
function Export2Word(element, filename = ''){
    var preHtml = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'><head><meta charset='utf-8'><title>Export HTML To Doc</title></head><body>";
    var postHtml = "</body></html>";
    var html = preHtml + document.getElementById(element).innerHTML + postHtml;

    var blob = new Blob(['\ufeff', html], {
        type: 'application/msword'
    });
    
    var url = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(html);
    
    filename = filename ? filename + '.doc' : 'document.doc';
    
    var downloadLink = document.createElement("a");

    document.body.appendChild(downloadLink);
    
    if (navigator.msSaveOrOpenBlob) {
        navigator.msSaveOrOpenBlob(blob, filename);
    } else {
        downloadLink.href = url;
        downloadLink.download = filename;
        downloadLink.click();
    }
    
    document.body.removeChild(downloadLink);
}
</script>

<button onclick="Export2Word('exportContent', 'word-content');">Export as Word file</button>

<?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']): ?>
    <a href="edit_post.php?id=<?php echo htmlspecialchars($row['id']); ?>">Edit Post</a>
    <a href="delete_post.php?id=<?php echo htmlspecialchars($row['id']); ?>" onclick="return confirm('Are you sure you want to delete this post?');">Delete Post</a>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
