<?php
require_once "config.php";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: registration.php');
    exit;
}

$pdo = getConnection();
$sql = "SELECT id, full_name, username, email, role FROM users WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch();

if (!$user) {
    header('Location: registration.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Registration Successful</title>
</head>

<body>

    <h1 style="color: green;">Registration successful!</h1>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <td><?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <th>Full Name</th>
            <td><?= htmlspecialchars($user['full_name'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <th>Username</th>
            <td><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
        </tr>
    </table>

    <p><a href="registration.php">Register another user</a></p>

</body>

</html>