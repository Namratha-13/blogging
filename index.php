<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .action-buttons { white-space: nowrap; }
        .table-responsive { overflow-x: auto; }
        .card { border-radius: 10px; }
        .navbar-brand img { margin-right: 10px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <img src="assets/img/logo/logo.png" class="img-fluid" alt="logo" width="30">
                <span class="ms-2">Blog Management</span>
            </a>
            <div class="d-flex">
                <a href="create.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Add Blog
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <?php if (isset($_GET['update']) && $_GET['update'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Blog updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['delete']) && $_GET['delete'] == 'success'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Blog deleted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th width="25%">Title</th>
                        <th width="20%">Slug</th>
                        <th width="20%">Added On</th>
                        <th width="15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once "connection.php";
                    
                    $sql = "SELECT ID, Blog_name, Blog_slug, Blog_added_on FROM blogs ORDER BY ID DESC";
                    $result = mysqli_query($link, $sql);
                    
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['ID']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Blog_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Blog_slug']) . "</td>";
                            echo "<td>" . date("M d, Y H:i", strtotime($row['Blog_added_on'])) . "</td>";
                            echo "<td class='action-buttons'>";
                            echo "<a href='edit.php?id=" . $row['ID'] . "' class='btn btn-sm btn-outline-primary me-1' title='Edit'>";
                            echo "<i class='bi bi-pencil-square'></i>";
                            echo "</a>";
                            echo "<a href='delete.php?id=" . $row['ID'] . "' class='btn btn-sm btn-outline-danger' title='Delete' 
                                  onclick='return confirm(\"Are you sure you want to delete this blog?\")'>";
                            echo "<i class='bi bi-trash3'></i>";
                            echo "</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-4'>No blogs found. <a href='create.php' class='btn btn-sm btn-primary'>Create your first blog</a></td></tr>";
                    }
                    
                    mysqli_close($link);
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>