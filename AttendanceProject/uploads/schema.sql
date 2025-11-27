-- Créer la base si elle n'existe pas
CREATE DATABASE IF NOT EXISTS attendance_db;
USE attendance_db;

-- Table des utilisateurs (admin, professeurs, étudiants)
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(100) NOT NULL,
  email VARCHAR(120) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('student','professor','admin') NOT NULL
);

-- Table des cours
CREATE TABLE courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  code VARCHAR(50) UNIQUE NOT NULL
);

-- Table des groupes (renommée en student_groups pour éviter le mot réservé GROUPS)
CREATE TABLE student_groups (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  course_id INT NOT NULL,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- Table des étudiants
CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(120) NOT NULL,
  matricule VARCHAR(50) UNIQUE NOT NULL,
  group_id INT,
  FOREIGN KEY (group_id) REFERENCES student_groups(id) ON DELETE SET NULL
);

-- Table des sessions d’assiduité
CREATE TABLE attendance_sessions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT NOT NULL,
  group_id INT NOT NULL,
  date DATE NOT NULL,
  opened_by INT NOT NULL,
  status ENUM('open','closed') DEFAULT 'open',
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
  FOREIGN KEY (group_id) REFERENCES student_groups(id) ON DELETE CASCADE,
  FOREIGN KEY (opened_by) REFERENCES users(id) ON DELETE SET NULL,
  UNIQUE (course_id, group_id, date)
);

-- Table des enregistrements d’assiduité
CREATE TABLE attendance_records (
  id INT AUTO_INCREMENT PRIMARY KEY,
  session_id INT NOT NULL,
  student_id INT NOT NULL,
  status ENUM('present','absent') NOT NULL,
  note VARCHAR(255),
  FOREIGN KEY (session_id) REFERENCES attendance_sessions(id) ON DELETE CASCADE,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  UNIQUE (session_id, student_id)
);

-- Table des justifications d’absence
CREATE TABLE justifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  course_id INT NOT NULL,
  session_id INT NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  status ENUM('pending','approved','rejected') DEFAULT 'pending',
  reason VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
  FOREIGN KEY (session_id) REFERENCES attendance_sessions(id) ON DELETE CASCADE
);
