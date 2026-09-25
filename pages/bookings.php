<?php

require_once "../php/db.php";

function clean($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}

$message = "";
$error = "";

$servicePrices = [
    "Oil Change" => 5000,
    "Full Service" => 15000,
    "Brake Service" => 8000,
    "Engine Service" => 25000,
    "AC Service" => 10000
];

$vehicleTypes = [
    "Car",
    "Van",
    "SUV",
    "Motorcycle",
    "Three Wheeler"
];

$serviceTypes = array_keys($servicePrices);

$statuses = [
    "Pending",
    "Confirmed",
    "Completed",
    "Cancelled"
];


/* =========================================
   CREATE / UPDATE / DELETE
   ========================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $action = $_POST["action"] ?? "";

    try {

        /* =========================================
           CREATE BOOKING
           ========================================= */

        if ($action === "create") {

            $customer_id = filter_var($_POST["customer_id"] ?? "", FILTER_VALIDATE_INT);
            $vehicle_number = trim($_POST["vehicle_number"] ?? "");
            $vehicle_type = trim($_POST["vehicle_type"] ?? "");
            $service_type = trim($_POST["service_type"] ?? "");
            $service_date = trim($_POST["service_date"] ?? "");

            if (!$customer_id || $customer_id < 1) {
                throw new Exception("Please select a valid customer.");
            }

            if ($vehicle_number === "" || $vehicle_type === "" || $service_type === "" || $service_date === "") {
                throw new Exception("Please fill in all booking fields.");
            }

            if (!in_array($vehicle_type, $vehicleTypes, true)) {
                throw new Exception("Invalid vehicle type.");
            }

            if (!array_key_exists($service_type, $servicePrices)) {
                throw new Exception("Invalid service type.");
            }

            $date = DateTime::createFromFormat("Y-m-d", $service_date);

            if (!$date || $date->format("Y-m-d") !== $service_date) {
                throw new Exception("Please enter a valid service date.");
}

            if (strlen($vehicle_number) > 20) {
                throw new Exception("Vehicle number cannot exceed 20 characters.");
            }

            /* Check that customer exists */

            $stmt = $conn->prepare(
                "SELECT customer_id FROM customers WHERE customer_id = :customer_id"
            );

            $stmt->execute([
                ":customer_id" => $customer_id
            ]);

            if (!$stmt->fetch()) {
                throw new Exception("Selected customer does not exist.");
            }

            /* Calculate cost on server */

            $estimated_cost = $servicePrices[$service_type];

            $sql = "INSERT INTO service_bookings
                    (customer_id, vehicle_number, vehicle_type, service_type, service_date, estimated_cost, status)
                    VALUES
                    (:customer_id, :vehicle_number, :vehicle_type, :service_type, :service_date, :estimated_cost, :status)";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ":customer_id" => $customer_id,
                ":vehicle_number" => $vehicle_number,
                ":vehicle_type" => $vehicle_type,
                ":service_type" => $service_type,
                ":service_date" => $service_date,
                ":estimated_cost" => $estimated_cost,
                ":status" => "Pending"
            ]);

            header("Location: bookings.php?status=created");
            exit;
        }


        /* =========================================
           UPDATE BOOKING
           ========================================= */

        if ($action === "update") {

            $booking_id = filter_var($_POST["booking_id"] ?? "", FILTER_VALIDATE_INT);
            $customer_id = filter_var($_POST["customer_id"] ?? "", FILTER_VALIDATE_INT);
            $vehicle_number = trim($_POST["vehicle_number"] ?? "");
            $vehicle_type = trim($_POST["vehicle_type"] ?? "");
            $service_type = trim($_POST["service_type"] ?? "");
            $service_date = trim($_POST["service_date"] ?? "");
            $status = trim($_POST["status"] ?? "");

            if (!$booking_id || $booking_id < 1) {
                throw new Exception("Invalid booking ID.");
            }

            if (!$customer_id || $customer_id < 1) {
                throw new Exception("Please select a valid customer.");
            }

            if ($vehicle_number === "" || $vehicle_type === "" || $service_type === "" || $service_date === "" || $status === "") {
                throw new Exception("Please fill in all booking fields.");
            }

            if (!in_array($vehicle_type, $vehicleTypes, true)) {
                throw new Exception("Invalid vehicle type.");
            }

            if (!array_key_exists($service_type, $servicePrices)) {
                throw new Exception("Invalid service type.");
            }

            if (!in_array($status, $statuses, true)) {
                throw new Exception("Invalid booking status.");
            }

            $date = DateTime::createFromFormat("Y-m-d", $service_date);

            if (!$date || $date->format("Y-m-d") !== $service_date) {
                throw new Exception("Please enter a valid service date.");
}

            if (strlen($vehicle_number) > 20) {
                throw new Exception("Vehicle number cannot exceed 20 characters.");
            }

            /* Check customer */

            $stmt = $conn->prepare(
                "SELECT customer_id FROM customers WHERE customer_id = :customer_id"
            );

            $stmt->execute([
                ":customer_id" => $customer_id
            ]);

            if (!$stmt->fetch()) {
                throw new Exception("Selected customer does not exist.");
            }

            /* Check booking */

            $stmt = $conn->prepare(
                "SELECT booking_id FROM service_bookings WHERE booking_id = :booking_id"
            );

            $stmt->execute([
                ":booking_id" => $booking_id
            ]);

            if (!$stmt->fetch()) {
                throw new Exception("Booking does not exist.");
            }

            /* Calculate cost again on server */

            $estimated_cost = $servicePrices[$service_type];

            $sql = "UPDATE service_bookings
                    SET customer_id = :customer_id,
                        vehicle_number = :vehicle_number,
                        vehicle_type = :vehicle_type,
                        service_type = :service_type,
                        service_date = :service_date,
                        estimated_cost = :estimated_cost,
                        status = :status
                    WHERE booking_id = :booking_id";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ":customer_id" => $customer_id,
                ":vehicle_number" => $vehicle_number,
                ":vehicle_type" => $vehicle_type,
                ":service_type" => $service_type,
                ":service_date" => $service_date,
                ":estimated_cost" => $estimated_cost,
                ":status" => $status,
                ":booking_id" => $booking_id
            ]);

            header("Location: bookings.php?status=updated");
            exit;
        }


        /* =========================================
           DELETE BOOKING
           ========================================= */

        if ($action === "delete") {

            $booking_id = filter_var($_POST["booking_id"] ?? "", FILTER_VALIDATE_INT);

            if (!$booking_id || $booking_id < 1) {
                throw new Exception("Invalid booking ID.");
            }

            $sql = "DELETE FROM service_bookings
                    WHERE booking_id = :booking_id";

            $stmt = $conn->prepare($sql);

            $stmt->execute([
                ":booking_id" => $booking_id
            ]);

            if ($stmt->rowCount() === 0) {
                throw new Exception("Booking does not exist.");
            }

            header("Location: bookings.php?status=deleted");
            exit;
        }

    } catch (PDOException $e) {

        $error = "Database operation failed. Please try again.";

    } catch (Exception $e) {

        $error = $e->getMessage();
    }
}


/* =========================================
   STATUS MESSAGES
   ========================================= */

$status = $_GET["status"] ?? "";

if ($status === "created") {

    $message = "Booking added successfully!";

} elseif ($status === "updated") {

    $message = "Booking updated successfully!";

} elseif ($status === "deleted") {

    $message = "Booking deleted successfully!";
}


/* =========================================
   SEARCH AND SORT
   ========================================= */

$search = trim($_GET["search"] ?? "");

$allowedSorts = [
    "booking_id",
    "customer",
    "vehicle_number",
    "vehicle_type",
    "service_type",
    "service_date",
    "estimated_cost",
    "status"
];

$sort = $_GET["sort"] ?? "booking_id";

if (!in_array($sort, $allowedSorts, true)) {
    $sort = "booking_id";
}

$order = strtoupper($_GET["order"] ?? "DESC");

if (!in_array($order, ["ASC", "DESC"], true)) {
    $order = "DESC";
}


/* =========================================
   GET BOOKINGS
   ========================================= */

$sql = "SELECT
            sb.booking_id,
            sb.customer_id,
            sb.vehicle_number,
            sb.vehicle_type,
            sb.service_type,
            sb.service_date,
            sb.estimated_cost,
            sb.status,
            sb.created_at,
            c.first_name,
            c.last_name
        FROM service_bookings sb
        INNER JOIN customers c
            ON sb.customer_id = c.customer_id";

$params = [];

if ($search !== "") {

    $sql .= " WHERE
                c.first_name LIKE :search1
                OR c.last_name LIKE :search2
                OR sb.vehicle_number LIKE :search3
                OR sb.vehicle_type LIKE :search4
                OR sb.service_type LIKE :search5
                OR sb.status LIKE :search6";

    $searchTerm = "%" . $search . "%";

    $params = [
        ":search1" => $searchTerm,
        ":search2" => $searchTerm,
        ":search3" => $searchTerm,
        ":search4" => $searchTerm,
        ":search5" => $searchTerm,
        ":search6" => $searchTerm
    ];
}


/*
 * The sort column comes from a whitelist above,
 * so it is safe to place into the SQL query.
 */

$sortColumns = [
    "booking_id" => "sb.booking_id",
    "customer" => "c.first_name",
    "vehicle_number" => "sb.vehicle_number",
    "vehicle_type" => "sb.vehicle_type",
    "service_type" => "sb.service_type",
    "service_date" => "sb.service_date",
    "estimated_cost" => "sb.estimated_cost",
    "status" => "sb.status"
];

$sql .= " ORDER BY " . $sortColumns[$sort] . " " . $order;

$stmt = $conn->prepare($sql);

$stmt->execute($params);

$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* =========================================
   TOTAL BOOKINGS
   ========================================= */

$stmt = $conn->query(
    "SELECT COUNT(*) FROM service_bookings"
);

$totalBookings = $stmt->fetchColumn();


/* =========================================
   GET CUSTOMERS FOR FORM
   ========================================= */

$stmt = $conn->query(
    "SELECT customer_id, first_name, last_name
     FROM customers
     ORDER BY first_name, last_name"
);

$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);


/* =========================================
   GET BOOKING FOR EDIT
   ========================================= */

$editBooking = null;

if (isset($_GET["edit"])) {

    $edit_id = filter_var($_GET["edit"], FILTER_VALIDATE_INT);

    if ($edit_id && $edit_id > 0) {

        $stmt = $conn->prepare(
            "SELECT *
             FROM service_bookings
             WHERE booking_id = :booking_id"
        );

        $stmt->execute([
            ":booking_id" => $edit_id
        ]);

        $editBooking = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}


/* =========================================
   SORT LINK
   ========================================= */

function sortLink($column, $currentSort, $currentOrder, $search) {

    $newOrder = (
        $column === $currentSort &&
        $currentOrder === "ASC"
    ) ? "DESC" : "ASC";

    return "bookings.php?" . http_build_query([
        "sort" => $column,
        "order" => $newOrder,
        "search" => $search
    ]);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Manage vehicle service bookings">
    <title>Service Bookings - Vehicle Service Management System</title>

    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/bookings.css">
</head>


<body>
<header>

    <div class="header-content">

        <img src="../images/img1.png"
             alt="Vehicle Service Management System Logo"
             class="logo">

        <div class="header-text">

            <h1>Vehicle Service Management System</h1>

            <p>
                Professional Vehicle Service Management
            </p>

        </div>

    </div>


    <nav aria-label="Main Navigation">

        <a href="../index.php">
            Dashboard
        </a>

        <a href="customers.php">
            Customers
        </a>

        <a href="bookings.php" class="active">
            Service Bookings
        </a>

    </nav>

</header>


<main>


    <!-- =========================================
         PAGE HEADING
         ========================================= -->

    <section class="booking-page-heading">

        <div>

            <span class="eyebrow">
                BOOKING MANAGEMENT
            </span>

            <h2>
                Service Bookings
            </h2>

            <p>
                Manage vehicle service bookings and appointments.
            </p>

        </div>


        <div class="booking-total-badge">

            <span>
                Total bookings
            </span>

            <strong>
                <?= clean($totalBookings) ?>
            </strong>

        </div>

    </section>


    <!-- =========================================
         ALERTS
         ========================================= -->

    <?php if ($message !== ""): ?>

        <div class="alert success">
            <?= clean($message) ?>
        </div>

    <?php endif; ?>


    <?php if ($error !== ""): ?>

        <div class="alert error">
            <?= clean($error) ?>
        </div>

    <?php endif; ?>


    <!-- =========================================
         CREATE / UPDATE BOOKING
         ========================================= -->

    <section class="dashboard-panel">

        <div class="panel-header">

            <div>

                <h2>
                    <?= $editBooking ? "Edit Service Booking" : "Create Service Booking" ?>
                </h2>

                <p>
                    <?= $editBooking
                        ? "Update the booking information below."
                        : "Register a new vehicle service booking."
                    ?>
                </p>

            </div>

        </div>


        <form method="POST"
              action="bookings.php"
              class="booking-form">


            <input type="hidden"
                   name="action"
                   value="<?= $editBooking ? "update" : "create" ?>">


            <?php if ($editBooking): ?>

                <input type="hidden"
                       name="booking_id"
                       value="<?= clean($editBooking["booking_id"]) ?>">

            <?php endif; ?>


            <!-- Customer -->

            <div class="form-group">

                <label for="customer_id">
                    Customer
                </label>

                <select id="customer_id"
                        name="customer_id"
                        required>

                    <option value="">
                        Select Customer
                    </option>

                    <?php foreach ($customers as $customer): ?>

                        <option value="<?= clean($customer["customer_id"]) ?>"
                            <?= ($editBooking && $editBooking["customer_id"] == $customer["customer_id"]) ? "selected" : "" ?>>

                            <?= clean($customer["first_name"] . " " . $customer["last_name"]) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- Vehicle Number -->

            <div class="form-group">

                <label for="vehicle_number">
                    Vehicle Number
                </label>

                <input type="text"
                       id="vehicle_number"
                       name="vehicle_number"
                       maxlength="20"
                       placeholder="Example: CAB-1234"
                       value="<?= clean($editBooking["vehicle_number"] ?? "") ?>"
                       required>

            </div>


            <!-- Vehicle Type -->

            <div class="form-group">

                <label for="vehicle_type">
                    Vehicle Type
                </label>

                <select id="vehicle_type"
                        name="vehicle_type"
                        required>

                    <option value="">
                        Select Vehicle Type
                    </option>

                    <?php foreach ($vehicleTypes as $vehicleType): ?>

                        <option value="<?= clean($vehicleType) ?>"
                            <?= ($editBooking && $editBooking["vehicle_type"] === $vehicleType) ? "selected" : "" ?>>

                            <?= clean($vehicleType) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- Service Type -->

            <div class="form-group">

                <label for="service_type">
                    Service Type
                </label>

                <select id="service_type"
                        name="service_type"
                        required
                        onchange="calculateCost()">

                    <option value="">
                        Select Service
                    </option>

                    <?php foreach ($servicePrices as $service => $price): ?>

                        <option value="<?= clean($service) ?>"
                            <?= ($editBooking && $editBooking["service_type"] === $service) ? "selected" : "" ?>>

                            <?= clean($service) ?> - Rs. <?= number_format($price, 2) ?>

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>


            <!-- Service Date -->

            <div class="form-group">

                <label for="service_date">
                    Service Date
                </label>

                <input type="date"
                       id="service_date"
                       name="service_date"
                       value="<?= clean($editBooking["service_date"] ?? "") ?>"
                       required>

            </div>


            <!-- Estimated Cost -->

            <div class="form-group">

                <label for="estimated_cost">
                    Estimated Cost (Rs.)
                </label>

                <input type="number"
                       id="estimated_cost"
                       name="estimated_cost"
                       value="<?= clean($editBooking["estimated_cost"] ?? "0") ?>"
                       readonly>

            </div>


            <?php if ($editBooking): ?>

                <!-- Status -->

                <div class="form-group">

                    <label for="status">
                        Status
                    </label>

                    <select id="status"
                            name="status"
                            required>

                        <?php foreach ($statuses as $bookingStatus): ?>

                            <option value="<?= clean($bookingStatus) ?>"
                                <?= $editBooking["status"] === $bookingStatus ? "selected" : "" ?>>

                                <?= clean($bookingStatus) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            <?php endif; ?>


            <!-- Buttons -->

            <div class="form-actions">

                <button type="submit"
                        class="form-button submit-button">

                    <?= $editBooking ? "Save Changes" : "Add Booking" ?>

                </button>


                <?php if ($editBooking): ?>

                    <a href="bookings.php"
                       class="form-button reset-button">

                        Cancel Edit

                    </a>

                <?php else: ?>

                    <button type="reset"
                            class="form-button reset-button"
                            onclick="resetCost()">

                        Reset

                    </button>

                <?php endif; ?>

            </div>


        </form>

    </section>


    <!-- =========================================
         BOOKINGS LIST
         ========================================= -->

    <section class="dashboard-panel recent-bookings">

        <div class="panel-header">

            <div>

                <h2>
                    Service Bookings
                </h2>

                <p>
                    Search, sort, edit, or remove service bookings.
                </p>

            </div>

        </div>


        <!-- Search -->

        <form method="GET"
              action="bookings.php"
              class="booking-search">

            <input type="search"
                   name="search"
                   placeholder="Search by customer, vehicle or service..."
                   value="<?= clean($search) ?>">

            <input type="hidden"
                   name="sort"
                   value="<?= clean($sort) ?>">

            <input type="hidden"
                   name="order"
                   value="<?= clean($order) ?>">

            <button type="submit"
                    class="search-button">

                Search

            </button>


            <?php if ($search !== ""): ?>

                <a href="bookings.php"
                   class="clear-button">

                    Clear

                </a>

            <?php endif; ?>

        </form>


        <!-- Table -->

        <div class="table-container">

            <table class="booking-table">

                <thead>

                    <tr>

                        <th>
                            <a href="<?= clean(sortLink("booking_id", $sort, $order, $search)) ?>">
                                ID ↕
                            </a>
                        </th>

                        <th>
                            <a href="<?= clean(sortLink("customer", $sort, $order, $search)) ?>">
                                Customer ↕
                            </a>
                        </th>

                        <th>
                            <a href="<?= clean(sortLink("vehicle_number", $sort, $order, $search)) ?>">
                                Vehicle ↕
                            </a>
                        </th>

                        <th>
                            <a href="<?= clean(sortLink("vehicle_type", $sort, $order, $search)) ?>">
                                Type ↕
                            </a>
                        </th>

                        <th>
                            <a href="<?= clean(sortLink("service_type", $sort, $order, $search)) ?>">
                                Service ↕
                            </a>
                        </th>

                        <th>
                            <a href="<?= clean(sortLink("service_date", $sort, $order, $search)) ?>">
                                Date ↕
                            </a>
                        </th>

                        <th>
                            <a href="<?= clean(sortLink("estimated_cost", $sort, $order, $search)) ?>">
                                Cost ↕
                            </a>
                        </th>

                        <th>
                            <a href="<?= clean(sortLink("status", $sort, $order, $search)) ?>">
                                Status ↕
                            </a>
                        </th>

                        <th>
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (count($bookings) > 0): ?>

                        <?php foreach ($bookings as $booking): ?>

                            <tr>

                                <td>
                                    <span class="id-badge">
                                        #<?= clean($booking["booking_id"]) ?>
                                    </span>
                                </td>


                                <td>
                                    <?= clean($booking["first_name"] . " " . $booking["last_name"]) ?>
                                </td>


                                <td>
                                    <?= clean($booking["vehicle_number"]) ?>
                                </td>


                                <td>
                                    <?= clean($booking["vehicle_type"]) ?>
                                </td>


                                <td>
                                    <?= clean($booking["service_type"]) ?>
                                </td>


                                <td>
                                    <?= clean($booking["service_date"]) ?>
                                </td>


                                <td>
                                    Rs. <?= number_format($booking["estimated_cost"], 2) ?>
                                </td>


                                <td>

                                    <?php
                                    $statusClass = strtolower($booking["status"]);
                                    ?>

                                    <span class="status-badge status-<?= clean($statusClass) ?>">

                                        <?= clean($booking["status"]) ?>

                                    </span>

                                </td>


                                <td>

                                    <div class="action-buttons">

                                        <a href="bookings.php?edit=<?= clean($booking["booking_id"]) ?>"
                                           class="action-button edit-button">

                                            Edit

                                        </a>


                                        <form method="POST"
                                              action="bookings.php"
                                              class="delete-form"
                                              onsubmit="return confirm('Are you sure you want to delete this booking?');">

                                            <input type="hidden"
                                                   name="action"
                                                   value="delete">

                                            <input type="hidden"
                                                   name="booking_id"
                                                   value="<?= clean($booking["booking_id"]) ?>">

                                            <button type="submit"
                                                    class="action-button delete-button">

                                                Delete

                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="9"
                                class="empty-message">

                                No service bookings found.

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </section>
</main>


<footer>

    <p>
        2026 Vehicle Service Management System
    </p>

</footer>


<script>


/* =========================================
   SERVICE COST CALCULATION
   ========================================= */

function calculateCost() {

    var serviceType = document.getElementById("service_type").value;

    var cost = 0;


    if (serviceType == "Oil Change") {

        cost = 5000;

    } else if (serviceType == "Full Service") {

        cost = 15000;

    } else if (serviceType == "Brake Service") {

        cost = 8000;

    } else if (serviceType == "Engine Service") {

        cost = 25000;

    } else if (serviceType == "AC Service") {

        cost = 10000;

    }


    document.getElementById("estimated_cost").value = cost;

}


/* =========================================
   RESET COST
   ========================================= */

function resetCost() {

    document.getElementById("estimated_cost").value = 0;

}


/* =========================================
   SET INITIAL COST WHEN EDITING
   ========================================= */

window.addEventListener("load", function() {

    calculateCost();

});


</script>


</body>

</html>