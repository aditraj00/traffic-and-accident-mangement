
<?php
// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'traffic_guardian';

// Create connection
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select database
$conn->select_db($database);

// Create users table
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'police', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Users table created successfully<br>";
} else {
    echo "Error creating users table: " . $conn->error . "<br>";
}

// Create incidents table
$sql = "CREATE TABLE IF NOT EXISTS incidents (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT,
    severity ENUM('Low', 'Medium', 'High') NOT NULL,
    status ENUM('Active', 'Resolved', 'Ongoing') NOT NULL,
    reported_by INT(11),
    reported_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    image_path VARCHAR(255),
    FOREIGN KEY (reported_by) REFERENCES users(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Incidents table created successfully<br>";
} else {
    echo "Error creating incidents table: " . $conn->error . "<br>";
}

// Create traffic_reports table
$sql = "CREATE TABLE IF NOT EXISTS traffic_reports (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    area_name VARCHAR(255) NOT NULL,
    congestion_level ENUM('Low', 'Medium', 'High') NOT NULL,
    description TEXT,
    estimated_delay VARCHAR(50),
    time_reported TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Traffic reports table created successfully<br>";
} else {
    echo "Error creating traffic reports table: " . $conn->error . "<br>";
}

// Create feedback table
$sql = "CREATE TABLE IF NOT EXISTS feedback (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    message TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Feedback table created successfully<br>";
} else {
    echo "Error creating feedback table: " . $conn->error . "<br>";
}

// Create violations table
$sql = "CREATE TABLE IF NOT EXISTS violations (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    offender_name VARCHAR(100) NOT NULL,
    license_number VARCHAR(50) NOT NULL,
    violation_type VARCHAR(100) NOT NULL,
    location VARCHAR(255) NOT NULL,
    fine_amount DECIMAL(10,2) NOT NULL,
    date_time TIMESTAMP NOT NULL,
    recorded_by INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (recorded_by) REFERENCES users(id)
)";

if ($conn->query($sql) === TRUE) {
    echo "Violations table created successfully<br>";
} else {
    echo "Error creating violations table: " . $conn->error . "<br>";
}

// Create safety_tips table
$sql = "CREATE TABLE IF NOT EXISTS safety_tips (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Safety tips table created successfully<br>";
} else {
    echo "Error creating safety tips table: " . $conn->error . "<br>";
}

// Create demo users
// Admin user
$admin_username = 'admin';
$admin_email = 'admin@traffic.com';
$admin_password = password_hash('admin123', PASSWORD_DEFAULT);
$admin_role = 'admin';

// Check if admin user already exists
$result = $conn->query("SELECT * FROM users WHERE email = '$admin_email'");
if ($result->num_rows == 0) {
    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$admin_username', '$admin_email', '$admin_password', '$admin_role')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Admin user created successfully<br>";
    } else {
        echo "Error creating admin user: " . $conn->error . "<br>";
    }
} else {
    echo "Admin user already exists<br>";
}

// Police user
$police_username = 'police';
$police_email = 'police@traffic.com';
$police_password = password_hash('password123', PASSWORD_DEFAULT);
$police_role = 'police';

// Check if police user already exists
$result = $conn->query("SELECT * FROM users WHERE email = '$police_email'");
if ($result->num_rows == 0) {
    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$police_username', '$police_email', '$police_password', '$police_role')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Police user created successfully<br>";
    } else {
        echo "Error creating police user: " . $conn->error . "<br>";
    }
} else {
    echo "Police user already exists<br>";
}

// Regular user
$user_username = 'user';
$user_email = 'user@traffic.com';
$user_password = password_hash('password123', PASSWORD_DEFAULT);
$user_role = 'user';

// Check if regular user already exists
$result = $conn->query("SELECT * FROM users WHERE email = '$user_email'");
if ($result->num_rows == 0) {
    $sql = "INSERT INTO users (username, email, password, role) VALUES ('$user_username', '$user_email', '$user_password', '$user_role')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Regular user created successfully<br>";
    } else {
        echo "Error creating regular user: " . $conn->error . "<br>";
    }
} else {
    echo "Regular user already exists<br>";
}

// Add some sample traffic reports
$sample_reports = [
    [
        'area_name' => 'Downtown Main Street',
        'congestion_level' => 'High',
        'description' => 'Heavy traffic due to construction work',
        'estimated_delay' => '20-30 mins'
    ],
    [
        'area_name' => 'Highway 101 North',
        'congestion_level' => 'Medium',
        'description' => 'Moderate traffic flow with occasional slowdowns',
        'estimated_delay' => '10-15 mins'
    ],
    [
        'area_name' => 'Central Avenue',
        'congestion_level' => 'Low',
        'description' => 'Traffic flowing smoothly with no significant delays',
        'estimated_delay' => 'None'
    ]
];

// Check if we already have traffic reports
$result = $conn->query("SELECT * FROM traffic_reports LIMIT 1");
if ($result->num_rows == 0) {
    foreach ($sample_reports as $report) {
        $area_name = $report['area_name'];
        $congestion_level = $report['congestion_level'];
        $description = $report['description'];
        $estimated_delay = $report['estimated_delay'];
        
        $sql = "INSERT INTO traffic_reports (area_name, congestion_level, description, estimated_delay) 
                VALUES ('$area_name', '$congestion_level', '$description', '$estimated_delay')";
        
        if ($conn->query($sql) === TRUE) {
            echo "Sample traffic report created: $area_name<br>";
        } else {
            echo "Error creating sample traffic report: " . $conn->error . "<br>";
        }
    }
} else {
    echo "Traffic reports already exist<br>";
}

echo "<br><strong>Setup completed!</strong> <a href='index.php'>Go to homepage</a>";

// Close connection
$conn->close();
?>
