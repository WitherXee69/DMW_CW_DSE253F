<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="description"
          content="Vehicle Service Management System dashboard">

    <title>Vehicle Service Management System</title>

    <link rel="stylesheet" href="css/style.css">

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

        <a href="index.php" class="active">
            Dashboard
        </a>

        <a href="pages/customers.php">
            Customers
        </a>

        <a href="pages/bookings.php">
            Service Bookings
        </a>

    </nav>

</header>


<main>


    <!-- =========================================
         DASHBOARD INTRODUCTION
         ========================================= -->

    <section class="hero-section">

        <div class="hero-text">

            <h2>
                Dashboard
            </h2>

            <p>
                Welcome to the Vehicle Service Management System.
                Manage customers, service bookings and vehicle
                service operations from one place.
            </p>

            <a href="pages/bookings.php"
               class="hero-button">

                Create Service Booking

            </a>

        </div>


        <div class="hero-image">

            <img src="images/img1.png"
                 alt="Vehicle Service Management System">

        </div>

    </section>


    <!-- =========================================
         STATISTICS
         ========================================= -->

    <section class="statistics"
             aria-label="System Statistics">


        <article class="stat-card">

            <div class="stat-icon">
                👥
            </div>

            <h3>
                Total Customers
            </h3>

            <p id="totalCustomers">
                0
            </p>

        </article>


        <article class="stat-card">

            <div class="stat-icon">
                🔧
            </div>

            <h3>
                Active Bookings
            </h3>

            <p id="activeBookings">
                0
            </p>

        </article>


        <article class="stat-card">

            <div class="stat-icon">
                💰
            </div>

            <h3>
                Estimated Revenue
            </h3>

            <p id="estimatedRevenue">
                Rs. 0.00
            </p>

        </article>


    </section>


    <!-- =========================================
         DASHBOARD MAIN CONTENT
         ========================================= -->

    <section class="dashboard-content">


        <!-- =========================================
             SERVICE OVERVIEW
             ========================================= -->

        <article class="dashboard-panel">

            <div class="panel-header">

                <div>

                    <h2>
                        Service Overview
                    </h2>

                    <p>
                        Number of bookings by service type
                    </p>

                </div>

            </div>


            <div class="service-list">


                <div class="service-item">

                    <span>
                        Oil Change
                    </span>

                    <strong id="oilChangeCount">
                        0
                    </strong>

                </div>


                <div class="service-item">

                    <span>
                        Full Service
                    </span>

                    <strong id="fullServiceCount">
                        0
                    </strong>

                </div>


                <div class="service-item">

                    <span>
                        Brake Service
                    </span>

                    <strong id="brakeServiceCount">
                        0
                    </strong>

                </div>


                <div class="service-item">

                    <span>
                        Engine Service
                    </span>

                    <strong id="engineServiceCount">
                        0
                    </strong>

                </div>


                <div class="service-item">

                    <span>
                        AC Service
                    </span>

                    <strong id="acServiceCount">
                        0
                    </strong>

                </div>


            </div>

        </article>


        <!-- =========================================
             QUICK ACTIONS
             ========================================= -->

        <article class="dashboard-panel">

            <div class="panel-header">

                <div>

                    <h2>
                        Quick Actions
                    </h2>

                    <p>
                        Frequently used management functions
                    </p>

                </div>

            </div>


            <div class="quick-actions">


                <a href="pages/customers.php"
                   class="dashboard-button">

                    <span class="button-icon">
                        👤
                    </span>

                    <span>

                        <strong>
                            Manage Customers
                        </strong>

                        <small>
                            Register and manage customers
                        </small>

                    </span>

                </a>


                <a href="pages/bookings.php"
                   class="dashboard-button">

                    <span class="button-icon">
                        📅
                    </span>

                    <span>

                        <strong>
                            Manage Bookings
                        </strong>

                        <small>
                            Create and manage service bookings
                        </small>

                    </span>

                </a>


            </div>

        </article>


    </section>


    <!-- =========================================
         RECENT BOOKINGS
         ========================================= -->

    <section class="dashboard-panel recent-bookings">


        <div class="panel-header">

            <div>

                <h2>
                    Recent Service Bookings
                </h2>

                <p>
                    Latest service bookings recorded in the system
                </p>

            </div>


            <a href="pages/bookings.php"
               class="view-all-button">

                View All

            </a>

        </div>


        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>
                            Customer
                        </th>

                        <th>
                            Vehicle
                        </th>

                        <th>
                            Service
                        </th>

                        <th>
                            Date
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody id="recentBookingsTable">

                    <tr>

                        <td colspan="5"
                            class="empty-message">

                            Loading recent bookings...

                        </td>

                    </tr>

                </tbody>


            </table>

        </div>


    </section>


    <!-- =========================================
         EXPORT DATA
         ========================================= -->

    <section class="export-section">


        <div class="panel-header">

            <div>

                <h2>
                    Export Data
                </h2>

                <p>
                    Download system records as CSV files.
                </p>

            </div>

        </div>


        <div class="export-buttons">


            <a href="php/export_csv.php?type=customers"
               class="export-button">

                Export Customers CSV

            </a>


            <a href="php/export_csv.php?type=bookings"
               class="export-button">

                Export Bookings CSV

            </a>


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
   LOAD DASHBOARD DATA
   ========================================= */

function loadDashboardData() {

    fetch("php/dashboard_stats.php")

        .then(function(response) {

            if (!response.ok) {
                throw new Error("Failed to load dashboard data.");
            }

            return response.json();

        })

        .then(function(data) {

            if (!data.success) {
                throw new Error(data.message);
            }


            /* =========================================
               STATISTICS
               ========================================= */

            document.getElementById("totalCustomers").textContent =
                data.statistics.total_customers;


            document.getElementById("activeBookings").textContent =
                data.statistics.active_bookings;


            document.getElementById("estimatedRevenue").textContent =
                "Rs. " + Number(data.statistics.estimated_revenue).toLocaleString(
                    "en-LK",
                    {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }
                );


            /* =========================================
               SERVICE OVERVIEW
               ========================================= */

            document.getElementById("oilChangeCount").textContent =
                data.service_overview["Oil Change"] || 0;


            document.getElementById("fullServiceCount").textContent =
                data.service_overview["Full Service"] || 0;


            document.getElementById("brakeServiceCount").textContent =
                data.service_overview["Brake Service"] || 0;


            document.getElementById("engineServiceCount").textContent =
                data.service_overview["Engine Service"] || 0;


            document.getElementById("acServiceCount").textContent =
                data.service_overview["AC Service"] || 0;


            /* =========================================
               RECENT BOOKINGS
               ========================================= */

            var tableBody =
                document.getElementById("recentBookingsTable");


            tableBody.innerHTML = "";


            if (data.recent_bookings.length === 0) {

                var emptyRow = document.createElement("tr");

                var emptyCell = document.createElement("td");

                emptyCell.colSpan = 5;

                emptyCell.className = "empty-message";

                emptyCell.textContent = "No service bookings found.";

                emptyRow.appendChild(emptyCell);

                tableBody.appendChild(emptyRow);

                return;
            }


            data.recent_bookings.forEach(function(booking) {

                var row = document.createElement("tr");


                var customerCell = document.createElement("td");

                customerCell.textContent =
                    booking.customer_name;


                var vehicleCell = document.createElement("td");

                vehicleCell.textContent =
                    booking.vehicle_number;


                var serviceCell = document.createElement("td");

                serviceCell.textContent =
                    booking.service_type;


                var dateCell = document.createElement("td");

                dateCell.textContent =
                    booking.service_date;


                var statusCell = document.createElement("td");


                var statusBadge = document.createElement("span");

                statusBadge.className =
                    "status-badge status-" +
                    booking.status.toLowerCase();


                statusBadge.textContent =
                    booking.status;


                statusCell.appendChild(statusBadge);


                row.appendChild(customerCell);

                row.appendChild(vehicleCell);

                row.appendChild(serviceCell);

                row.appendChild(dateCell);

                row.appendChild(statusCell);


                tableBody.appendChild(row);

            });

        })

        .catch(function(error) {

            console.error(error);


            document.getElementById("totalCustomers").textContent =
                "N/A";


            document.getElementById("activeBookings").textContent =
                "N/A";


            document.getElementById("estimatedRevenue").textContent =
                "N/A";


            document.getElementById("recentBookingsTable").innerHTML =
                '<tr>' +
                    '<td colspan="5" class="empty-message">' +
                        'Unable to load dashboard data.' +
                    '</td>' +
                '</tr>';

        });

}


/* =========================================
   LOAD DATA WHEN PAGE OPENS
   ========================================= */

document.addEventListener("DOMContentLoaded", function() {

    loadDashboardData();

});

</script>


</body>

</html>