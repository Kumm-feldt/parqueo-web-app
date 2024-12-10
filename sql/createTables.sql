

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
