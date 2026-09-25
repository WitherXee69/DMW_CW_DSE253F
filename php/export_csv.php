<?php

require_once "db.php";

$type = $_GET['type'] ?? '';

if ($type === 'customers') {

    $sql = "SELECT
                customer_id,
                first_name,
                last_name,
                email,
                phone,
                address,
                created_at
            FROM customers
            ORDER BY customer_id ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $filename = "customers.csv";

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');

    fputcsv($output, [
        'Customer ID',
        'First Name',
        'Last Name',
        'Email',
        'Phone',
        'Address',
        'Created At'
    ]);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        fputcsv($output, [
            $row['customer_id'],
            $row['first_name'],
            $row['last_name'],
            $row['email'],
            $row['phone'],
            $row['address'],
            $row['created_at']
        ]);
    }

    fclose($output);
    exit;
}


if ($type === 'bookings') {

    $sql = "SELECT
                b.booking_id,
                c.first_name,
                c.last_name,
                b.vehicle_number,
                b.vehicle_type,
                b.service_type,
                b.service_date,
                b.estimated_cost,
                b.status,
                b.created_at
            FROM service_bookings b
            INNER JOIN customers c
                ON b.customer_id = c.customer_id
            ORDER BY b.booking_id ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $filename = "service_bookings.csv";

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');

    fputcsv($output, [
        'Booking ID',
        'Customer Name',
        'Vehicle Number',
        'Vehicle Type',
        'Service Type',
        'Service Date',
        'Estimated Cost',
        'Status',
        'Created At'
    ]);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $customerName =
            $row['first_name'] . ' ' . $row['last_name'];

        fputcsv($output, [
            $row['booking_id'],
            $customerName,
            $row['vehicle_number'],
            $row['vehicle_type'],
            $row['service_type'],
            $row['service_date'],
            $row['estimated_cost'],
            $row['status'],
            $row['created_at']
        ]);
    }

    fclose($output);
    exit;
}


http_response_code(400);

echo "Invalid export type.";

?>