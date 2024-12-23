<?php
$conn = new mysqli('localhost', 'root', '', 'sample'); // Connect to your database

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the uploads directory exists
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// Initialize messages
$success = '';
$error = '';

// Handle product submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $image = $_FILES['image'];

    if ($image['error'] == 0) {
        $imagePath = 'uploads/' . basename($image['name']);

        if (move_uploaded_file($image['tmp_name'], $imagePath)) {
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, discount, image_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdss", $name, $description, $price, $discount, $imagePath);

            if ($stmt->execute()) {
                $success = "Product added successfully!";
                // Redirect to avoid form resubmission
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $error = "Failed to add product to the database.";
            }

            $stmt->close();
        } else {
            $error = "Failed to upload the image. Please check folder permissions.";
        }
    } else {
        $error = "Please upload a valid image.";
    }
}

// Fetch existing products
$productsResult = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            border-radius: 10px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
        .btn-primary {
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Seller Dashboard</h1>

        <!-- Product Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Add Product</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price (RS.)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="discount" class="form-label">Discount (%)</label>
                        <input type="number" step="0.01" class="form-control" id="discount" name="discount" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>

        <!-- Product List -->
        <div class="card">
            <div class="card-header">
                <h3>Products</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price (RS.)</th>
                            <th>Discount (%)</th>
                            <th>Final Price (RS.)</th>
                            <th>Added On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($productsResult->num_rows > 0) {
                            while ($row = $productsResult->fetch_assoc()) {
                                $finalPrice = $row['price'] - ($row['price'] * $row['discount'] / 100);
                                echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td><img src='{$row['image_path']}' alt='Product Image' style='width: 100px;'></td>
                                    <td>{$row['name']}</td>
                                    <td>{$row['description']}</td>
                                    <td>RS.{$row['price']}</td>
                                    <td>{$row['discount']}%</td>
                                    <td>RS." . number_format($finalPrice, 2) . "</td>
                                    <td>{$row['created_at']}</td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>No products found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
