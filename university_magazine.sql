-- Create the 'users' table to store user information
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'coordinator', 'student') NOT NULL
);

-- Create the 'faculties' table to store information about university faculties
CREATE TABLE faculties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Create the 'marketing_coordinators' table to associate marketing coordinators with faculties
CREATE TABLE marketing_coordinators (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE,
    faculty_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (faculty_id) REFERENCES faculties(id)
);

-- Create the 'articles' table to store article submissions
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_url VARCHAR(255),
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_published BOOLEAN DEFAULT FALSE,
    is_final BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create the 'article_comments' table to store comments on articles
CREATE TABLE article_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT,
    user_id INT,
    comment TEXT NOT NULL,
    comment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create a table to store closure dates for each academic year
CREATE TABLE closure_dates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    academic_year VARCHAR(20) NOT NULL,
    closure_date DATE NOT NULL
);

-- Create a table to track user agreement to terms and conditions
CREATE TABLE user_agreements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    agreed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create a table to store selected articles for publication
CREATE TABLE selected_articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT,
    marketing_coordinator_id INT,
    selected_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id),
    FOREIGN KEY (marketing_coordinator_id) REFERENCES marketing_coordinators(id)
);

-- Create a table to store statistical data
CREATE TABLE statistics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    faculty_id INT,
    academic_year VARCHAR(20),
    num_contributions INT,
    FOREIGN KEY (faculty_id) REFERENCES faculties(id)
);