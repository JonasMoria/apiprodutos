<?php

// API params
putenv('ENVIRONMENT=LOCALHOST');

// Database Params
$dbHost = '';
$dbName = '';
$dbUser = '';
$dbPass = '';

putenv('DB_HOST=' . $dbHost);
putenv('DB_NAME=' . $dbName);
putenv('DB_USER=' . $dbUser);
putenv('DB_PASS=' . $dbPass);