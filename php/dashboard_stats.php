
<?php

require_once "db.php";

header('Content-Type: application/json; charset=utf-8');

try {

    
    $customerQuery = $conn->query(
        "SELECT COUNT(*) AS total_customers
         FROM customers"
    );

    $customerData = $customerQuery->fetch(PDO::FETCH_ASSOC);

    $totalCustomers = (int) $customerData['total_customers'];


   
    $activeQuery = $conn->query(
        "SELECT COUNT(*) AS active_bookings
         FROM service_bookings
         WHERE status IN ('Pending', 'Confirmed')"
    );

    $activeData = $activeQuery->fetch(PDO::FETCH_ASSOC);

    $activeBookings = (int) $activeData['active_bookings'];

    // revenue 
   
    $revenueQuery = $conn->query(
        "SELECT COALESCE(SUM(estimated_cost), 0) AS estimated_revenue
         FROM service_bookings
         WHERE status != 'Cancelled'"
    );

    $revenueData = $revenueQuery->fetch(PDO::FETCH_ASSOC);

    $estimatedRevenue = (float) $revenueData['estimated_revenue'];

    //count bookings by service type
   
    $serviceQuery = $conn->query(
        "SELECT service_type, COUNT(*) AS total
         FROM service_bookings
         GROUP BY service_type"
    );

    $serviceOverview = [];

    while ($row = $serviceQuery->fetch(PDO::FETCH_ASSOC)) {

        $serviceOverview[$row['service_type']] = (int) $row['total'];
    }


    /*recent bookings
    get the last 5 bookings 
    */
    
    $recentQuery = $conn->query(
        "SELECT
            b.booking_id,
            CONCAT(c.first_name, ' ', c.last_name) AS customer_name,
            b.vehicle_number,
            b.service_type,
            b.service_date,
            b.status
         FROM service_bookings b
         INNER JOIN customers c
             ON b.customer_id = c.customer_id
         ORDER BY b.booking_id DESC
         LIMIT 5"
    );

    $recentBookings = [];

    while ($row = $recentQuery->fetch(PDO::FETCH_ASSOC)) {

        $recentBookings[] = [
            'booking_id' => (int) $row['booking_id'],
            'customer_name' => $row['customer_name'],
            'vehicle_number' => $row['vehicle_number'],
            'service_type' => $row['service_type'],
            'service_date' => $row['service_date'],
            'status' => $row['status']
        ];
    }

    //send  the dashboard data

    echo json_encode([
        'success' => true,

        'statistics' => [
            'total_customers' => $totalCustomers,
            'active_bookings' => $activeBookings,
            'estimated_revenue' => $estimatedRevenue
        ],

        'service_overview' => $serviceOverview,

        'recent_bookings' => $recentBookings
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Unable to load dashboard statistics.'
    ]);
}
?>

