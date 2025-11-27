USE attendance_db;

-- Utilisateurs
INSERT INTO users(fullname,email,password_hash,role) VALUES
('Admin User','admin@uni.dz','admin123','admin'),
('Prof One','prof@uni.dz','prof123','professor'),
('Ahmed Student','student@uni.dz','student123','student');

-- Cours
INSERT INTO courses(name, code) VALUES
('Advanced Web Programming','AWP-ISIL3');

-- Groupes
INSERT INTO student_groups(name, course_id) VALUES
('Group A', 1),
('Group B', 1);

-- Étudiants
INSERT INTO students(fullname, matricule, group_id) VALUES
('Ahmed Student','ISIL3-001', 1),
('Sara Student','ISIL3-002', 1),
('Yassine Student','ISIL3-003', 1),
('Lina Student','ISIL3-004', 2);

-- Exemple de session
INSERT INTO attendance_sessions(course_id, group_id, date, opened_by, status) VALUES
(1, 1, '2025-11-26', 2, 'open');

-- Exemple d’assiduité
INSERT INTO attendance_records(session_id, student_id, status) VALUES
(1, 1, 'present'),
(1, 2, 'absent'),
(1, 3, 'present');

-- Exemple de justification
INSERT INTO justifications(student_id, course_id, session_id, file_path, status, reason) VALUES
(2, 1, 1, 'just_ISIL3-002.pdf', 'pending', 'Medical appointment');

