<?php
session_start();
// Remove session check for now
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

require_once "connection.php";

$id = $_GET['id'] ?? null;
$error = '';

// Process deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $sql = "DELETE FROM blogs WHERE ID = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "Blog post deleted successfully";
            header("Location: index.php");
            exit();
        } else {
            $error = "Error deleting record: " . mysqli_error($link);
        }
        mysqli_stmt_close($stmt);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $id) {
    // Fetch blog details for confirmation
    $sql = "SELECT Blog_name FROM blogs WHERE ID = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $blog = mysqli_fetch_assoc($result) ?: [];
        mysqli_stmt_close($stmt);
    }
} else {
    header("Location: index.php");
    exit();
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delete Blog Post</title>
    <link href="assets/vendors/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendors/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <style>
        .confirmation-box { max-width: 600px; margin: 2rem auto; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a href="index.php" class="navbar-brand">
                <img src="assets/img/logo/logo.png" class="img-fluid" alt="logo" width="30">
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbar">
                <i class="bi bi-list"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <div class="navbar-nav ms-auto">
                    <a href="index.php" class="nav-link">Back to Blogs</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="confirmation-box card shadow">
            <div class="card-header bg-danger text-white">
                <h4 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Confirm Deletion</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($blog): ?>
                    <p class="lead">Are you sure you want to delete the blog post titled:</p>
                    <h5 class="mb-4">"<?php echo htmlspecialchars($blog['Blog_name']); ?>"</h5>
                    <p class="text-muted">This action cannot be undone.</p>
                    <form method="post">
                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancel</a>
                            <button type="submit" name="confirm_delete" class="btn btn-danger"><i class="bi bi-trash3"></i> Confirm Delete</button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning">Blog post not found.</div>
                    <a href="index.php" class="btn btn-primary">Back to Blog List</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="assets/vendors/jquery/jquery.min.js"></script>
    <script src="assets/vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>