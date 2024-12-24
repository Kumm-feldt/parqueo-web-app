
-- Table: events v.1.1
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- Assuming every event must have a user
    event VARCHAR(255) NOT NULL, -- Assuming event name cannot be null
    price DECIMAL(10, 2) NOT NULL, -- Using DECIMAL for prices for precision
    FOREIGN KEY (user_id) REFERENCES users(id) -- Assuming you have a `users` table
);

-- Table: events_fixed v.1.2
CREATE TABLE fixed_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- Assuming every event must have a user
    hora INT DEFAULT 0,
    evento INT DEFAULT 0,
    dia_y_noche INT DEFAULT 0,
    anulado INT DEFAULT 0,
    perdido INT DEFAULT 0,
   
    FOREIGN KEY (user_id) REFERENCES users(id) -- Assuming you have a `users` table
);
-- Table: vehicles

CREATE TABLE vehicles(
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
vehicle VARCHAR(255) NOT NULL,
price INT
)
    
-- Table: log_out
CREATE TABLE log_out (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    vehicle_type VARCHAR(20),
    ticket VARCHAR(20),
    time_in DATETIME,
    time_out DATETIME,
    charge INT(11),
    person VARCHAR(255),
    park_type VARCHAR(50),
    placa TEXT
);

-- Table: users
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255),
    company_name VARCHAR(255),
    phone_number VARCHAR(20),
    password VARCHAR(255),
    created_at TIMESTAMP,
    authorized_user VARCHAR(255),
    authorized_user_password VARCHAR(255)
);

-- Table: user_login
CREATE TABLE user_login (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    login_time TIMESTAMP,
    ip_address VARCHAR(45)
);

-- Table: excel_files
CREATE TABLE excel_files (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    created_date DATE,
    created_time TIME,
    username VARCHAR(255),
    file_data LONGBLOB,
    file_name VARCHAR(255)
);

-- Table: total_log_out
CREATE TABLE total_log_out (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11),
    vehicle_type VARCHAR(20),
    ticket VARCHAR(20),
    time_in DATETIME,
    time_out DATETIME,
    charge INT(11),
    person VARCHAR(255),
    park_type VARCHAR(50),
    placa TEXT
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
