<?php
session_start();  // Start the session to access session variables

// Check if the customer_id exists in the session
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'sample'); // Connect to your database

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching customer data using the customer_id from session
$customerId = $_SESSION['customer_id'];  // Get customer ID from session

// Fetch customer details
$customerQuery = "SELECT * FROM customers WHERE id = $customerId";
$customerResult = $conn->query($customerQuery);

// Check if the customer exists in the database
if ($customerResult->num_rows > 0) {
    $customerData = $customerResult->fetch_assoc();
} else {
    die("Customer not found.");
}

// Fetching customer order history
$orderQuery = "SELECT * FROM orders WHERE customer_id = $customerId";
$orderResult = $conn->query($orderQuery);

// Fetching feedback given by the customer
$feedbackQuery = "SELECT * FROM feedback WHERE customer_id = $customerId";
$feedbackResult = $conn->query($feedbackQuery);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Customer Dashboard</title>
    <style>
        /* Sidebar styles */
        .sidebar {
            background-color: #343a40;
            height: 100vh;
            padding-top: 20px;
        }

        .sidebar a {
            color: white;
            transition: all 0.3s ease;
        }

        .sidebar a:hover {
            background-color: #007bff;
            transform: scale(1.05);
            padding-left: 10px;
        }

        .sidebar a:active {
            transform: scale(1.1);
        }

        /* Main content styles */
        .w-100 {
            width: calc(100% - 250px);
        }

        .card {
            border-radius: 10px;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }

        .table thead {
            background-color: #f1f1f1;
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
    <div class="d-flex">
        <!-- SIDEBAR -->
        <nav class="sidebar p-3 flex-column">
            <a href="#" class="text-decoration-none text-white mb-4 fs-4 d-flex align-items-center">
                <i class="bx bxs-smile fs-3 me-2"></i> <span>CustomerHub</span>
            </a>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="customer_dashboard.php" class="nav-link text-white active">
                        <i class="bx bxs-dashboard"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="order_history.php" class="nav-link text-white">
                        <i class="bx bxs-box"></i> Order History
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
                <a class="navbar-brand text-white" href="#">CustomerHub</a>
                <div class="collapse navbar-collapse">
                    <form class="d-flex ms-auto">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-light" type="submit">Search</button>
                    </form>
                </div>
            </nav>

            <!-- Dashboard Content -->
            <div class="container mt-4">
                <h1 class="mb-4">Welcome, <?php echo $customerData['name']; ?>!</h1>

                <!-- Customer Info Card -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Your Information</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Email:</strong> <?php echo $customerData['email']; ?></li>
                                    <li class="list-group-item"><strong>Phone:</strong> <?php echo $customerData['phone']; ?></li>
                                    <li class="list-group-item"><strong>Address:</strong> <?php echo $customerData['address']; ?></li>
                                    <li class="list-group-item"><strong>Joined:</strong> <?php echo $customerData['created_at']; ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order History Table -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Your Order History</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Ordered On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($orderResult->num_rows > 0) {
                                    while ($row = $orderResult->fetch_assoc()) {
                                        echo "<tr>
                                            <td>{$row['order_id']}</td>
                                            <td>{$row['product_name']}</td>
                                            <td>{$row['quantity']}</td>
                                            <td>{$row['price']}</td>
                                            <td>{$row['status']}</td>
                                            <td>{$row['ordered_on']}</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center'>No orders found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Feedback Section -->
                <div class="card">
                    <div class="card-header">
                        <h3>Your Feedback</h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Feedback ID</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Feedback Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($feedbackResult->num_rows > 0) {
                                    while ($row = $feedbackResult->fetch_assoc()) {
                                        echo "<tr>
                                            <td>{$row['feedback_id']}</td>
                                            <td>{$row['message']}</td>
                                            <td>{$row['status']}</td>
                                            <td>{$row['created_at']}</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center'>No feedback provided.</td></tr>";
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
