#!/bin/bash
cd "c:\Users\lutta\Downloads\streamline\streamline (6)\streamline"
php -r "
\$mysqli = new mysqli('127.0.0.1', 'root', '', 'streamline');
if (\$mysqli->connect_error) {
    die('Connection failed: ' . \$mysqli->connect_error);
}
\$result = \$mysqli->query('DESCRIBE admin_contracts');
echo \"admin_contracts columns:\n\";
while (\$row = \$result->fetch_assoc()) {
    echo \$row['Field'] . \" - \" . \$row['Type'] . \"\n\";
}
\$mysqli->close();
"
