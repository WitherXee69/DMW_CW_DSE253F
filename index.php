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
            <p>Professional Vehicle Service Management</p>
        </div>

    </div>

    <nav aria-label="Main Navigation">

        <a href="index.php" class="active">Dashboard</a>

        <a href="customers.php">
            Customers
        </a>

        <a href="bookings.php">
            Service Bookings
        </a>

    </nav>

</header>


<main>

    <!-- Dashboard Introduction -->

    <section class="hero-section">

        <div class="hero-text">

            <h2>Dashboard</h2>

            <p>
                Welcome to the Vehicle Service Management System.
                Manage customers, service bookings and vehicle
                service operations from one place.
            </p>

            <a href="bookings.php" class="hero-button">
                Create Service Booking
            </a>

        </div>

        <div class="hero-image">

            <img src="images/car-service.jpg"
                 alt="Vehicle being serviced at a service center">

        </div>

    </section>


    <!-- Statistics -->

    <section class="statistics" aria-label="System Statistics">

        <article class="stat-card">

            <div class="stat-icon">
                👥
            </div>

            <h3>Total Customers</h3>

            <p>0</p>

        </article>


        <article class="stat-card">

            <div class="stat-icon">
                🔧
            </div>

            <h3>Active Bookings</h3>

            <p>0</p>

        </article>


        <article class="stat-card">

            <div class="stat-icon">
                💰
            </div>

            <h3>Estimated Revenue</h3>

            <p>Rs. 0.00</p>

        </article>

    </section>


    <!-- Dashboard Main Content -->

    <section class="dashboard-content">


        <!-- Service Overview -->

        <article class="dashboard-panel">

            <div class="panel-header">

                <div>
                    <h2>Service Overview</h2>

                    <p>
                        Number of bookings by service type
                    </p>
                </div>

            </div>


            <div class="service-list">

                <div class="service-item">

                    <span>Oil Change</span>

                    <strong>0</strong>

                </div>


                <div class="service-item">

                    <span>Full Service</span>

                    <strong>0</strong>

                </div>


                <div class="service-item">

                    <span>Brake Service</span>

                    <strong>0</strong>

                </div>


                <div class="service-item">

                    <span>Engine Service</span>

                    <strong>0</strong>

                </div>


                <div class="service-item">

                    <span>AC Service</span>

                    <strong>0</strong>

                </div>

            </div>

        </article>


        <!-- Quick Actions -->

        <article class="dashboard-panel">

            <div class="panel-header">

                <div>

                    <h2>Quick Actions</h2>

                    <p>
                        Frequently used management functions
                    </p>

                </div>

            </div>


            <div class="quick-actions">

                <a href="customers.php"
                   class="dashboard-button">

                    <span class="button-icon">👤</span>

                    <span>
                        <strong>Manage Customers</strong>
                        <small>
                            Register and manage customers
                        </small>
                    </span>

                </a>


                <a href="bookings.php"
                   class="dashboard-button">

                    <span class="button-icon">📅</span>

                    <span>
                        <strong>Manage Bookings</strong>
                        <small>
                            Create and manage service bookings
                        </small>
                    </span>

                </a>

            </div>

        </article>

    </section>


    <!-- Recent Bookings -->

    <section class="dashboard-panel recent-bookings">

        <div class="panel-header">

            <div>

                <h2>Recent Service Bookings</h2>

                <p>
                    Latest service bookings recorded in the system
                </p>

            </div>

            <a href="bookings.php" class="view-all-button">
                View All
            </a>

        </div>


        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>Customer</th>

                        <th>Vehicle</th>

                        <th>Service</th>

                        <th>Date</th>

                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>

                    <tr>

                        <td colspan="5" class="empty-message">
                            No service bookings available.
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </section>


    <!-- Export Data -->

    <section class="export-section">

        <div class="panel-header">

            <div>

                <h2>Export Data</h2>

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

</body>

</html>