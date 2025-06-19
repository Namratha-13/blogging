<?php
require_once "connection.php";

$blog_name = $blog_content = $blog_slug = "";
$blog_name_err = $blog_content_err = $blog_slug_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    if (empty(trim($_POST["blog_name"]))) {
        $blog_name_err = "Please enter a blog name";
    } else {
        $blog_name = trim($_POST["blog_name"]);
    }

    if (empty(trim($_POST["blog_content"]))) {
        $blog_content_err = "Please enter blog content";
    } else {
        $blog_content = trim($_POST["blog_content"]);
    }

    if (empty(trim($_POST["blog_slug"]))) {
        $blog_slug_err = "Please enter a blog slug";
    } else {
        $blog_slug = trim($_POST["blog_slug"]);
    }

    // Insert if no errors
    if (empty($blog_name_err) && empty($blog_content_err) && empty($blog_slug_err)) {
        $sql = "INSERT INTO blogs (Blog_name, Blog_content, Blog_slug) VALUES (?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sss", $blog_name, $blog_content, $blog_slug);
            if (mysqli_stmt_execute($stmt)) {
                header("Location: index.php?create=success");
                exit();
            } else {
                $error = "Error creating record: " . mysqli_error($link);
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create New Blog</title>
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
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Create New Blog</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_GET['create']) && $_GET['create'] == 'success'): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Blog created successfully!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Blog Title</label>
                                <input type="text" class="form-control <?php echo (!empty($blog_name_err)) ? 'is-invalid' : ''; ?>" 
                                       name="blog_name" value="<?php echo htmlspecialchars($blog_name); ?>" required>
                                <?php if (!empty($blog_name_err)): ?>
                                    <div class="invalid-feedback"><?php echo $blog_name_err; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Blog Slug</label>
                                <input type="text" class="form-control <?php echo (!empty($blog_slug_err)) ? 'is-invalid' : ''; ?>" 
                                       name="blog_slug" value="<?php echo htmlspecialchars($blog_slug); ?>" required>
                                <?php if (!empty($blog_slug_err)): ?>
                                    <div class="invalid-feedback"><?php echo $blog_slug_err; ?></div>
                                <?php endif; ?>
                                <small class="text-muted">URL-friendly version (e.g., my-awesome-blog)</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Blog Content</label>
                                <textarea id="editor" class="form-control <?php echo (!empty($blog_content_err)) ? 'is-invalid' : ''; ?>" 
                                          name="blog_content"><?php echo htmlspecialchars($blog_content); ?></textarea>
                                <?php if (!empty($blog_content_err)): ?>
                                    <div class="invalid-feedback"><?php echo $blog_content_err; ?></div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> Save Blog
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
        document.querySelector('input[name="blog_name"]').addEventListener('input', function() {
            const slugInput = document.querySelector('input[name="blog_slug"]');
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