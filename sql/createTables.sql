

use parqueo;
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    company_name VARCHAR(255),
    phone_number VARCHAR(20),
    password VARCHAR(255) NOT NULL,  -- Make sure to hash the passwords before storing them
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE user_login (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),  -- To store the IP address of the user logging in
    FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE excel_files (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    created_date DATE,
    created_time TIME,
    username VARCHAR(255),
    file_data LONGBLOB,
    file_name VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id)
);
-- Create the `log_out` table with `user_id`
CREATE TABLE log_out (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,  -- References the user who logged out the vehicle
    vehicle_type VARCHAR(20),
    ticket VARCHAR(20),
    time_in DATETIME,
    time_out DATETIME,
    charge INT(11),
    person VARCHAR(255),
    park_type VARCHAR(50),
    placa TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Ensures that this log-out is associated with a specific user
);

CREATE TABLE total_log_out (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,  -- References the user who logged out the vehicle
    vehicle_type VARCHAR(20),
    ticket VARCHAR(20),
    time_in DATETIME,
    time_out DATETIME,
    charge INT(11),
    person VARCHAR(255),
    park_type VARCHAR(50),
    placa TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id)  -- Ensures that this log-out is associated with a specific user
);


-- other tables
-- Table: workers
CREATE TABLE workers (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    worker_name VARCHAR(255)
);

-- Table: vehicle_events
CREATE TABLE vehicle_events (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    vehicle_type VARCHAR(50),
    event VARCHAR(50)
);

-- Table: password_reset_temp
CREATE TABLE password_reset_temp (
    email VARCHAR(250),
    key VARCHAR(250),
    expDate DATETIME
);

-- Table: logos
CREATE TABLE logos (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    logo_path VARCHAR(255)
);

-- Table: log_in
CREATE TABLE log_in (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    vehicle_type VARCHAR(20),
    ticket VARCHAR(20),
    time_in DATETIME,
    user_id INT(11)
);

-- Table: forward_emails
CREATE TABLE forward_emails (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    email VARCHAR(255)
);
