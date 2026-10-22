-- ============================================
-- USERS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS users (
                                     id INT PRIMARY KEY AUTO_INCREMENT,
                                     name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'account_manager', 'specialist', 'client') NOT NULL DEFAULT 'client',
    avatar VARCHAR(255),
    is_active BOOLEAN DEFAULT 1,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (email),
    INDEX (role),
    INDEX (is_active)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CLIENTS TABLE (Boardovi)
-- ============================================
CREATE TABLE IF NOT EXISTS clients (
                                       id INT PRIMARY KEY AUTO_INCREMENT,
                                       name VARCHAR(255) NOT NULL,
    description TEXT,
    created_by INT NOT NULL,
    status ENUM('active', 'archived') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX (created_by),
    INDEX (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- CLIENT TEAM MEMBERS
-- ============================================
CREATE TABLE IF NOT EXISTS client_team_members (
                                                   id INT PRIMARY KEY AUTO_INCREMENT,
                                                   client_id INT NOT NULL,
                                                   user_id INT NOT NULL,
                                                   added_by INT NOT NULL,
                                                   added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                                   UNIQUE KEY (client_id, user_id),
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (added_by) REFERENCES users(id),
    INDEX (client_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TASKS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS tasks (
                                     id INT PRIMARY KEY AUTO_INCREMENT,
                                     client_id INT NOT NULL,
                                     title VARCHAR(255) NOT NULL,
    description TEXT,
    assigned_to INT,
    created_by INT NOT NULL,
    status ENUM('todo', 'in_progress', 'in_review', 'done') DEFAULT 'todo',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    due_date DATE,
    progress INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id),
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX (client_id, status),
    INDEX (assigned_to),
    INDEX (created_by)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- TASK COMMENTS
-- ============================================
CREATE TABLE IF NOT EXISTS task_comments (
                                             id INT PRIMARY KEY AUTO_INCREMENT,
                                             task_id INT NOT NULL,
                                             user_id INT NOT NULL,
                                             comment TEXT NOT NULL,
                                             created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                             updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                             FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX (task_id),
    INDEX (user_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ATTACHMENTS
-- ============================================
CREATE TABLE IF NOT EXISTS attachments (
                                           id INT PRIMARY KEY AUTO_INCREMENT,
                                           task_id INT,
                                           comment_id INT,
                                           file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_size INT,
    uploaded_by INT NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (comment_id) REFERENCES task_comments(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id),
    INDEX (task_id),
    INDEX (comment_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- ACTIVITY LOG
-- ============================================
CREATE TABLE IF NOT EXISTS activity_log (
                                            id INT PRIMARY KEY AUTO_INCREMENT,
                                            user_id INT NOT NULL,
                                            client_id INT,
                                            task_id INT,
                                            action VARCHAR(100) NOT NULL,
    changes JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (client_id) REFERENCES clients(id),
    FOREIGN KEY (task_id) REFERENCES tasks(id),
    INDEX (user_id),
    INDEX (client_id),
    INDEX (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- DEMO DATA
-- ============================================
-- Super Admin
INSERT INTO users (name, email, password_hash, role, is_active) VALUES (
                                                                           'Demo Admin',
                                                                           'admin@example.com',
                                                                           '$argon2id$v=19$m=65536,t=4,p=1$ZFNIMkRvQ2lnZjd0bXhsSQ$cf1lgoVNK0W9TrJ1dDVewQT2K9dLUWHvW9hyc/4Bl/I',
                                                                           'super_admin',
                                                                           1
                                                                       );

-- Account Manager
INSERT INTO users (name, email, password_hash, role, is_active) VALUES (
                                                                           'Demo Manager',
                                                                           'manager@example.com',
                                                                           '$argon2id$v=19$m=65536,t=4,p=1$ZFNIMkRvQ2lnZjd0bXhsSQ$cf1lgoVNK0W9TrJ1dDVewQT2K9dLUWHvW9hyc/4Bl/I',
                                                                           'account_manager',
                                                                           1
                                                                       );