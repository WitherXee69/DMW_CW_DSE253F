<?php
require_once "../php/db.php";

function clean($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";

    try {
        if ($action === "create") {
            $first_name = trim($_POST["first_name"] ?? "");
            $last_name = trim($_POST["last_name"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $phone = trim($_POST["phone"] ?? "");
            $address = trim($_POST["address"] ?? "");

            if ($first_name === "" || $last_name === "" || $email === "" || $phone === "" || $address === "") {
                throw new Exception("Please fill in all fields.");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Please enter a valid email address.");
            }

            if (mb_strlen($first_name) > 50 || mb_strlen($last_name) > 50 || mb_strlen($email) > 100 || mb_strlen($phone) > 20 || mb_strlen($address) > 255) {
                throw new Exception("One or more fields exceed the allowed length.");
            }

            $sql = "INSERT INTO customers (first_name, last_name, email, phone, address) VALUES (:first_name, :last_name, :email, :phone, :address)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":first_name" => $first_name,
                ":last_name" => $last_name,
                ":email" => $email,
                ":phone" => $phone,
                ":address" => $address,
            ]);

            header("Location: customers.php?status=created");
            exit;
        }

        if ($action === "update") {
            $customer_id = filter_var($_POST["customer_id"] ?? "", FILTER_VALIDATE_INT);
            $first_name = trim($_POST["first_name"] ?? "");
            $last_name = trim($_POST["last_name"] ?? "");
            $email = trim($_POST["email"] ?? "");
            $phone = trim($_POST["phone"] ?? "");
            $address = trim($_POST["address"] ?? "");

            if (!$customer_id || $customer_id < 1) {
                throw new Exception("Invalid customer ID.");
            }

            if ($first_name === "" || $last_name === "" || $email === "" || $phone === "" || $address === "") {
                throw new Exception("Please fill in all fields.");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Please enter a valid email address.");
            }

            if (mb_strlen($first_name) > 50 || mb_strlen($last_name) > 50 || mb_strlen($email) > 100 || mb_strlen($phone) > 20 || mb_strlen($address) > 255) {
                throw new Exception("One or more fields exceed the allowed length.");
            }

            $sql = "UPDATE customers SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone, address = :address WHERE customer_id = :customer_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ":first_name" => $first_name,
                ":last_name" => $last_name,
                ":email" => $email,
                ":phone" => $phone,
                ":address" => $address,
                ":customer_id" => $customer_id,
            ]);

            header("Location: customers.php?status=updated");
            exit;
        }

        if ($action === "delete") {
            $customer_id = filter_var($_POST["customer_id"] ?? "", FILTER_VALIDATE_INT);

            if (!$customer_id || $customer_id < 1) {
                throw new Exception("Invalid customer ID.");
            }

            $sql = "DELETE FROM customers WHERE customer_id = :customer_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([":customer_id" => $customer_id]);

            header("Location: customers.php?status=deleted");
            exit;
        }
    } catch (PDOException $e) {
        $error = "Database operation failed. Please try again.";
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$status = $_GET["status"] ?? "";

if ($status === "created") {
    $message = "Customer added successfully!";
} elseif ($status === "updated") {
    $message = "Customer updated successfully!";
} elseif ($status === "deleted") {
    $message = "Customer deleted successfully!";
}

$search = trim($_GET["search"] ?? "");

$allowedSorts = ["customer_id", "first_name", "last_name", "email", "phone"];
$sort = $_GET["sort"] ?? "customer_id";
if (!in_array($sort, $allowedSorts, true)) {
    $sort = "customer_id";
}

$order = strtoupper($_GET["order"] ?? "DESC");
if (!in_array($order, ["ASC", "DESC"], true)) {
    $order = "DESC";
}

$sql = "SELECT customer_id, first_name, last_name, email, phone, address, created_at FROM customers";
$params = [];

if ($search !== "") {
    $sql .= " WHERE first_name LIKE :search1 OR last_name LIKE :search2 OR email LIKE :search3 OR phone LIKE :search4 OR address LIKE :search5";
    $searchTerm = "%" . $search . "%";
    $params = [
        ":search1" => $searchTerm,
        ":search2" => $searchTerm,
        ":search3" => $searchTerm,
        ":search4" => $searchTerm,
        ":search5" => $searchTerm,
    ];
}

$sql .= " ORDER BY $sort $order";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

$editCustomer = null;
if (isset($_GET["edit"])) {
    $edit_id = filter_var($_GET["edit"], FILTER_VALIDATE_INT);
    if ($edit_id && $edit_id > 0) {
        $stmt = $conn->prepare("SELECT * FROM customers WHERE customer_id = :id");
        $stmt->execute([":id" => $edit_id]);
        $editCustomer = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

function sortLink($column, $currentSort, $currentOrder, $search) {
    $newOrder = ($column === $currentSort && $currentOrder === "ASC") ? "DESC" : "ASC";

    return "customers.php?" . http_build_query([
        "sort" => $column,
        "order" => $newOrder,
        "search" => $search,
    ]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management | Vehicle Service System</title>
    <link rel="stylesheet" href="../css/customers.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header>

    <div class="header-content">

        <img src="../images/img1.png"
             alt="Vehicle Service Management System Logo"
             class="logo">

        <div class="header-text">
            <h1>Vehicle Service Management System</h1>
            <p>Professional Vehicle Service Management</p>
        </div>

    </div>

    <nav aria-label="Main Navigation">

        <a href="../index.php">Dashboard</a>

        <a href="customers.php" class="active">
            Customers
        </a>

        <a href="bookings.php">
            Service Bookings
        </a>

    </nav>

</header>

<main class="container">
    <section class="page-heading">
        <div>
            <span class="eyebrow">CUSTOMER MANAGEMENT</span>
            <h2>Customers</h2>
            <p>Manage customer information and contact details.</p>
        </div>

        <div class="total-badge">
            <span>Total customers</span>
            <strong><?= count($customers) ?></strong>
        </div>
    </section>

    <?php if ($message !== ""): ?>
        <div class="alert success"><?= clean($message) ?></div>
    <?php endif; ?>

    <?php if ($error !== ""): ?>
        <div class="alert error"><?= clean($error) ?></div>
    <?php endif; ?>

    <section class="form-card">
        <div class="section-heading">
            <div>
                <h3><?= $editCustomer ? "Edit Customer" : "Add New Customer" ?></h3>
                <p><?= $editCustomer ? "Update the customer's information below." : "Enter the customer's details below." ?></p>
            </div>

            <?php if ($editCustomer): ?>
                <a href="customers.php" class="btn btn-light">Cancel Edit</a>
            <?php endif; ?>
        </div>

        <form method="POST" action="customers.php" class="customer-form">
            <input type="hidden" name="action" value="<?= $editCustomer ? "update" : "create" ?>">

            <?php if ($editCustomer): ?>
                <input type="hidden" name="customer_id" value="<?= clean($editCustomer["customer_id"]) ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group">
                    <label for="first_name">First Name</label>
                    <input type="text" id="first_name" name="first_name" maxlength="50" placeholder="Enter first name" value="<?= clean($editCustomer["first_name"] ?? "") ?>" required>
                </div>

                <div class="form-group">
                    <label for="last_name">Last Name</label>
                    <input type="text" id="last_name" name="last_name" maxlength="50" placeholder="Enter last name" value="<?= clean($editCustomer["last_name"] ?? "") ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" maxlength="100" placeholder="example@email.com" value="<?= clean($editCustomer["email"] ?? "") ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" maxlength="20" placeholder="Enter phone number" value="<?= clean($editCustomer["phone"] ?? "") ?>" required>
                </div>

                <div class="form-group full-width">
                    <label for="address">Address</label>
                    <textarea id="address" name="address" rows="3" maxlength="255" placeholder="Enter customer address" required><?= clean($editCustomer["address"] ?? "") ?></textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><?= $editCustomer ? "Save Changes" : "Add Customer" ?></button>

                <?php if ($editCustomer): ?>
                    <a href="customers.php" class="btn btn-light">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </section>

    <section class="table-card">
        <div class="section-heading table-heading">
            <div>
                <h3>Customer Directory</h3>
                <p>Search, sort, edit, or remove customer records.</p>
            </div>
        </div>

        <form method="GET" action="customers.php" class="search-form">
            <div class="search-input-wrap">
                <span class="search-icon">&#128269;</span>
                <input type="search" name="search" placeholder="Search by name, email, phone, or address..." value="<?= clean($search) ?>">
            </div>

            <input type="hidden" name="sort" value="<?= clean($sort) ?>">
            <input type="hidden" name="order" value="<?= clean($order) ?>">
            <button type="submit" class="btn btn-primary">Search</button>

            <?php if ($search !== ""): ?>
                <a href="customers.php" class="btn btn-light">Clear</a>
            <?php endif; ?>
        </form>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th><a href="<?= clean(sortLink("customer_id", $sort, $order, $search)) ?>">ID ↕</a></th>
                        <th><a href="<?= clean(sortLink("first_name", $sort, $order, $search)) ?>">First Name ↕</a></th>
                        <th><a href="<?= clean(sortLink("last_name", $sort, $order, $search)) ?>">Last Name ↕</a></th>
                        <th><a href="<?= clean(sortLink("email", $sort, $order, $search)) ?>">Email ↕</a></th>
                        <th><a href="<?= clean(sortLink("phone", $sort, $order, $search)) ?>">Phone ↕</a></th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($customers) > 0): ?>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td><span class="id-badge">#<?= clean($customer["customer_id"]) ?></span></td>
                                <td class="customer-name"><?= clean($customer["first_name"]) ?></td>
                                <td class="customer-name"><?= clean($customer["last_name"]) ?></td>
                                <td><?= clean($customer["email"]) ?></td>
                                <td><?= clean($customer["phone"]) ?></td>
                                <td class="address-cell"><?= clean($customer["address"]) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="customers.php?edit=<?= clean($customer["customer_id"]) ?>" class="btn btn-small btn-edit">Edit</a>

                                        <form method="POST" action="customers.php" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="customer_id" value="<?= clean($customer["customer_id"]) ?>">
                                            <button type="submit" class="btn btn-small btn-delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <div class="empty-icon">&#128269;</div>
                                <strong>No customers found</strong>
                                <p>Try another search or add a new customer.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="table-footer">Showing <?= count($customers) ?> customer(s)</div>
    </section>
</main>
<footer>

    <p>
        2026 Vehicle Service Management System
    </p>

</footer>
</body>
</html>
