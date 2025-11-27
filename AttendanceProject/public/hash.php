<?php
echo "Admin: " . password_hash("admin123", PASSWORD_BCRYPT) . "<br>";
echo "Prof: " . password_hash("prof123", PASSWORD_BCRYPT) . "<br>";
echo "Student: " . password_hash("student123", PASSWORD_BCRYPT) . "<br>";

