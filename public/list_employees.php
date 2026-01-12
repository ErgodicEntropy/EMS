<?php
require_once __DIR__ . '/config/db.php';

$result = $mysqli->query("SELECT name, email, status FROM employee");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employees</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

<div class="bg-white shadow-lg rounded-lg p-6 w-full max-w-3xl">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Employees</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="py-2 px-4 border">Name</th>
                    <th class="py-2 px-4 border">Email</th>
                    <th class="py-2 px-4 border">Status</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="py-2 px-4 border"><?= htmlspecialchars($row['name']) ?></td>
                        <td class="py-2 px-4 border"><?= htmlspecialchars($row['email']) ?></td>
                        <td class="py-2 px-4 border"><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
