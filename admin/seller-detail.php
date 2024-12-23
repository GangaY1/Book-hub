<?php
$conn = new mysqli('localhost', 'root', '', 'sample'); // Connect to your database

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
                                    <td><img src='uploads/{$row['image_path']}' alt='Product Image' style='width: 100px;'></td>
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
