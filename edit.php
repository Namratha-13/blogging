<?php
require_once "connection.php";

$id = $title = $content = $slug = '';
$error = '';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $slug = trim($_POST['slug'] ?? '');

    if (empty($title)) {
        $error = "Title is required";
    } elseif (empty($slug)) {
        $error = "Slug is required";
    } else {
        $sql = "UPDATE blogs SET Blog_name = ?, Blog_content = ?, Blog_slug = ? WHERE ID = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssi", $title, $content, $slug, $id);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: index.php?update=success");
                exit();
            } else {
                $error = "Error updating record: " . mysqli_error($link);
            }
            mysqli_stmt_close($stmt);
        }
    }
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM blogs WHERE ID = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if ($row = mysqli_fetch_assoc($result)) {
                $title = $row['Blog_name'] ?? '';
                $content = $row['Blog_content'] ?? '';
                $slug = $row['Blog_slug'] ?? '';
            } else {
                header("Location: index.php");
                exit();
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        header("Location: index.php");
        exit();
    }
}
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Blog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <style>
        .error { color: #dc3545; }
        .ck-editor__editable { min-height: 300px; }
        .card { border-radius: 10px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <img src="assets/img/logo/logo.png" class="img-fluid" alt="logo" width="30">
                <span class="ms-2">Blog Management</span>
            </a>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Blog Post</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" class="form-control" name="title" 
                                       value="<?php echo htmlspecialchars($title); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input type="text" class="form-control" name="slug" 
                                       value="<?php echo htmlspecialchars($slug); ?>" required>
                                <small class="text-muted">URL-friendly version (e.g., my-awesome-blog)</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <textarea id="editor" name="content"><?php echo htmlspecialchars($content); ?></textarea>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Back to Blogs
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize CKEditor
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|',
                        'blockQuote', 'insertTable', 'undo', 'redo', '|',
                        'fontFamily', 'fontSize', 'fontColor', 'fontBackgroundColor'
                    ],
                    shouldNotGroupWhenFull: true
                }
            })
            .catch(error => {
                console.error(error);
            });

        // Auto-generate slug from title
        document.querySelector('input[name="title"]').addEventListener('input', function() {
            const slugInput = document.querySelector('input[name="slug"]');
            if (!slugInput.value) {
                slugInput.value = this.value
                    .toLowerCase()
                    .replace(/[^\w\s]/g, '')
                    .replace(/\s+/g, '-');
            }
        });
    </script>
</body>
</html>