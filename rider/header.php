<?php
// This file assumes a session has already been started
if (!isset($_SESSION['rider_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Rider Portal' ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f8f9fa; }
        .navbar { background-color: #343a40; padding: 1rem; display: flex; justify-content: space-between; align-items: center; }
        .navbar-brand { color: #fff; text-decoration: none; font-size: 1.5rem; }
        .navbar-links a { color: #f8f9fa; text-decoration: none; margin-left: 1rem; }
        .navbar-links strong { color: #ffc107; }
        .container { max-width: 1100px; margin: 2rem auto; padding: 2rem; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        h1, h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #dee2e6; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f8f8f8; }
        tr:hover { background-color: #f1f1f1; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .btn { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; color: #fff; }
        .btn-primary { background-color: #007bff; }
        .btn-primary:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="dashboard.php" class="navbar-brand">Rider Portal</a>
        <div class="navbar-links">
            <span>Welcome, <strong><?= htmlspecialchars($_SESSION['rider_name']) ?></strong>!</span>
            <a href="logout.php">Logout</a>
        </div>
    </nav>
    <div class="container">