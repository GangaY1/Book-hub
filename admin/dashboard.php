<?php
$conn = new mysqli('localhost', 'root', '', 'sample'); // Connect to your database

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching total customer count
$customerQuery = "SELECT COUNT(*) as total_customers FROM customers";
$customerResult = $conn->query($customerQuery);
$totalCustomers = $customerResult->fetch_assoc()['total_customers'];

// Fetching total seller count
$sellerQuery = "SELECT COUNT(*) as total_sellers FROM sellers";
$sellerResult = $conn->query($sellerQuery);
$totalSellers = $sellerResult->fetch_assoc()['total_sellers'];

// Fetching customer records
$customerRecords = $conn->query("SELECT * FROM customers");

// Fetching seller records
$sellerRecords = $conn->query("SELECT * FROM sellers");

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
    <style>
        /* Navbar styles */
        .navbar {
            background-color: #007bff;
        }
        .navbar a {
            color: #fff;
        }
        
        /* Card styles */
        .card {
            border-radius: 10px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }

        /* Table styles */
        .table thead {
            background-color: #f1f1f1;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Button styles */
        .btn-primary {
            background-color: #007bff;
        }

        /* Sidebar styles */
        .sidebar {
            background-color: #343a40;
            height: 100vh;
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            transition: all 0.3s ease; /* Adding smooth transition effect */
        }

        .sidebar a:hover {
            background-color: #007bff; /* Change background color on hover */
            transform: scale(1.05); /* Slightly scale the element on hover */
            padding-left: 10px; /* Optional: add a little padding for effect */
        }

        .sidebar a:active {
            transform: scale(1.1); /* Slightly enlarge when clicked */
        }

        /* Main content styles */
        .w-100 {
            width: calc(100% - 250px);
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- SIDEBAR -->
        <nav class="sidebar p-3 flex-column">
            <a href="#" class="text-decoration-none text-white mb-4 fs-4 d-flex align-items-center">
                <i class="bx bxs-smile fs-3 me-2"></i> <span>AdminHub</span>
            </a>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white active">
                        <i class="bx bxs-dashboard"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="seller-detail.php" class="nav-link text-white">
                        <i class="bx bxs-message-dots"></i> Seller Details
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="feedback.php" class="nav-link text-white">
                        <i class="bx bxs-message-dots"></i> Feedback
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="settings.php" class="nav-link text-white">
                        <i class="bx bxs-cog"></i> Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-white">
                        <i class="bx bxs-log-out-circle"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- MAIN CONTENT -->
        <div class="w-100">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light px-4 shadow-sm">
                <a class="navbar-brand text-white" href="#">AdminHub</a>
                <div class="collapse navbar-collapse">
                    <form class="d-flex ms-auto">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-light" type="submit">Search</button>
                    </form>
                </div>
            </nav>

            <!-- Dashboard Content -->
            <div class="container mt-4">
                <h1 class="mb-4">Admin Dashboard</h1>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Total Customers</h5>
                            </div>
                            <div class="card-body text-center">
                                <h2 class="display-4 mb-0"><?php echo $totalCustomers; ?></h2>
                                <p class="text-muted mt-2">Total Customers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Total Sellers</h5>
                            </div>
                            <div class="card-body text-center">
                                <h2 class="display-4 mb-0"><?php echo $totalSellers; ?></h2>
                                <p class="text-muted mt-2">Total Sellers</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customers Table -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Customers</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($customerRecords->num_rows > 0) {
                                    while ($row = $customerRecords->fetch_assoc()) {
                                        echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['name']}</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['phone']}</td>
                                            <td>{$row['address']}</td>
                                            <td>{$row['created_at']}</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No records found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Sellers Table -->
                <div class="card">
                    <div class="card-header">
                        <h3>Sellers</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Shop Name</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($sellerRecords->num_rows > 0) {
                                    while ($row = $sellerRecords->fetch_assoc()) {
                                        echo "<tr>
                                            <td>{$row['id']}</td>
                                            <td>{$row['name']}</td>
                                            <td>{$row['email']}</td>
                                            <td>{$row['shop_name']}</td>
                                            <td>{$row['phone']}</td>
                                            <td>{$row['address']}</td>
                                            <td>{$row['status']}</td>
                                            <td>{$row['created_at']}</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center'>No records found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
