<?php
// Display a simple greeting to confirm execution
echo "<h1>✅ PHP is working successfully!</h1>";

// Display the current PHP version running on your server
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";

// Display the current server time
echo "<p><strong>Server Time:</strong> " . date('Y-m-d H:i:s') . "</p>";

echo "<p><strong>Server Software:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
?>
