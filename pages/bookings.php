<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="description"
          content="Manage vehicle service bookings">

    <title>Service Bookings - Vehicle Service Management System</title>

    <link rel="stylesheet" href="../css/style.css">

    <style>

        /* =========================================
           PAGE HEADER
           ========================================= */

        .booking-page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .booking-page-heading .eyebrow {
            display: inline-block;
            color: #2563eb;
            font-size: 11px;
            letter-spacing: 1.5px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .booking-page-heading h2 {
            font-size: 32px;
            margin-bottom: 6px;
            color: #1f2937;
        }

        .booking-page-heading p {
            color: #555;
        }

        .booking-total-badge {
            background-color: white;
            padding: 22px 24px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            min-width: 170px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .booking-total-badge span {
            font-size: 12px;
            color: #777;
        }

        .booking-total-badge strong {
            font-size: 28px;
            color: #1f2937;
            line-height: 1.4;
        }

        /* =========================================
           BOOKING FORM
           ========================================= */

        .booking-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-weight: bold;
            color: #374151;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            background-color: white;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #2563eb;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-actions {
            grid-column: 1 / -1;

            display: flex;
            gap: 10px;

            margin-top: 5px;
        }

        .form-button {
            border: none;
            cursor: pointer;

            padding: 11px 20px;

            border-radius: 6px;

            font-size: 14px;
        }

        .submit-button {
            background-color: #2563eb;
            color: white;
        }

        .submit-button:hover {
            background-color: #1d4ed8;
        }

        .reset-button {
            background-color: #f3f4f6;
            color: #1f2937;
        }

        .reset-button:hover {
            background-color: #e5e7eb;
        }


        /* =========================================
           BOOKING SEARCH
           ========================================= */

        .booking-search {
            margin-bottom: 20px;
        }

        .booking-search label {
            display: block;

            font-weight: bold;

            color: #374151;

            font-size: 14px;

            margin-bottom: 6px;
        }

        .booking-search input {
            width: 100%;

            padding: 11px 12px;

            border: 1px solid #d1d5db;

            border-radius: 6px;

            font-size: 14px;
        }

        .booking-search input:focus {
            outline: none;

            border-color: #2563eb;
        }


        /* =========================================
           RESPONSIVE BOOKING FORM
           ========================================= */

        @media (max-width: 700px) {

            .booking-form {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: auto;
            }

            .form-actions {
                grid-column: auto;
            }

        }

    </style>

</head>


<body>


<header>

    <div class="header-content">

        <img src="images/img1.png"
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

    <section class="booking-page-heading">
        <div>
            <span class="eyebrow">BOOKING MANAGEMENT</span>
            <h2>Service Bookings</h2>
            <p>Manage vehicle service bookings and appointments.</p>
        </div>

        <div class="booking-total-badge">
            <span>Total bookings</span>
            <strong>0</strong>
        </div>
    </section>

    <!-- =========================================
         CREATE BOOKING
         ========================================= -->

    <section class="dashboard-panel">

        <div class="panel-header">

            <div>

                <h2>Create Service Booking</h2>

                <p>
                    Register a new vehicle service booking.
                </p>

            </div>

        </div>


        <form method="POST" action="" class="booking-form">


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

                    <option value="1">
                        John Perera
                    </option>

                    <option value="2">
                        Amal Silva
                    </option>

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
                       placeholder="Example: CAB-1234"
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

                    <option value="Car">
                        Car
                    </option>

                    <option value="Van">
                        Van
                    </option>

                    <option value="SUV">
                        SUV
                    </option>

                    <option value="Motorcycle">
                        Motorcycle
                    </option>

                    <option value="Three Wheeler">
                        Three Wheeler
                    </option>

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

                    <option value="Oil Change">
                        Oil Change
                    </option>

                    <option value="Full Service">
                        Full Service
                    </option>

                    <option value="Brake Service">
                        Brake Service
                    </option>

                    <option value="Engine Service">
                        Engine Service
                    </option>

                    <option value="AC Service">
                        AC Service
                    </option>

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
                       value="0"
                       readonly>

            </div>


            <!-- Buttons -->

            <div class="form-actions">

                <button type="submit"
                        class="form-button submit-button">

                    Add Booking

                </button>


                <button type="reset"
                        class="form-button reset-button"
                        onclick="resetCost()">

                    Reset

                </button>

            </div>


        </form>

    </section>


    <!-- =========================================
         BOOKINGS LIST
         ========================================= -->

    <section class="dashboard-panel recent-bookings">

        <div class="panel-header">

            <div>

                <h2>Service Bookings</h2>

                <p>
                    View and manage vehicle service bookings.
                </p>

            </div>

        </div>


        <!-- Search -->

        <div class="booking-search">

            <label for="search">
                Search Bookings
            </label>

            <input type="search"
                   id="search"
                   name="search"
                   placeholder="Search by customer, vehicle or service...">

        </div>


        <!-- Table -->

        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>Booking ID</th>

                        <th>Customer</th>

                        <th>Vehicle</th>

                        <th>Service</th>

                        <th>Date</th>

                        <th>Cost</th>

                        <th>Status</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    <tr>

                        <td colspan="8"
                            class="empty-message">

                            No service bookings available.

                        </td>

                    </tr>

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


</script>


</body>

</html>