<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

$result = $conn->query("
    SELECT br.*, e.name, e.image 
    FROM borrow_records br
    JOIN equipment e ON br.equipment_id = e.id
    WHERE br.user_id = $user_id 
    AND (br.status = 'borrowed' OR br.status = 'pending')
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Items</title>

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #f4f6f8;
        }

        .sidebar {
            width: 200px;
            height: 100vh;
            background: #1f2937;
            position: fixed;
            padding-top: 20px;
        }

        .sidebar h2 {
            color: white;
            text-align: center;
        }

        .sidebar a {
            display: block;
            color: white;
            padding: 12px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #374151;
        }

        .main {
            margin-left: 220px;
            padding: 20px;
        }

        h1 {
            margin-bottom: 15px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 18px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
        }

        .card img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 10px;
        }

        .title {
            font-weight: bold;
            margin-top: 10px;
            font-size: 16px;
        }

        .meta {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }

        .status {
            margin-top: 8px;
            font-size: 13px;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
        }

        .borrowed {
            background: #dcfce7;
            color: #166534;
        }

        .pending {
            background: #fef3c7;
            color: #92400e;
        }

        .actions {
            margin-top: 12px;
            display: flex;
            gap: 6px;
        }

        .btn {
            padding: 7px 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-return {
            background: #2563eb;
            color: white;
        }

        .btn-cancel {
            background: #ef4444;
            color: white;
        }

        .btn-disabled {
            background: #9ca3af;
            color: white;
            cursor: not-allowed;
        }

        .empty {
            text-align: center;
            padding: 60px;
            color: #6b7280;
            grid-column: 1 / -1;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<?php
include '../includes/sidebar-user.php';
?>

<!-- MAIN -->
<div class="main">

<h1>My Borrowed Items</h1>

<div class="grid">

<?php if ($result && $result->num_rows > 0): ?>

    <?php while($row = $result->fetch_assoc()): ?>

        <div class="card">

            <img src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>"
                 onerror="this.src='https://via.placeholder.com/300x140'">

            <div class="title">
                <?php echo htmlspecialchars($row['name']); ?>
            </div>

            <div class="meta">
                Quantity: <?php echo (int)$row['quantity']; ?>
            </div>

            <div class="meta">
                Return Date: <?php echo htmlspecialchars($row['return_date']); ?>
            </div>

            <div class="status">
                Status:
                <?php if ($row['status'] == 'borrowed'): ?>
                    <span class="badge borrowed">Borrowed</span>
                <?php else: ?>
                    <span class="badge pending">Pending Return</span>
                <?php endif; ?>
            </div>

            <div class="actions">

                <?php if ($row['status'] == 'borrowed'): ?>

                    <a class="btn btn-return"
                       href="../process/return_request.php?id=<?php echo $row['id']; ?>">
                        Return
                    </a>

                <?php elseif ($row['status'] == 'pending'): ?>

                    <button class="btn btn-disabled" disabled>Pending</button>

                    <a class="btn btn-cancel"
                       href="../process/cancel_return.php?id=<?php echo $row['id']; ?>">
                        Cancel
                    </a>

                <?php endif; ?>

            </div>

        </div>

    <?php endwhile; ?>

<?php else: ?>

    <div class="empty">
        <h3>No borrowed items yet</h3>
        <p>Start by browsing available equipment.</p>

        <a class="btn btn-return" href="borrow.php">Browse Equipment</a>
    </div>

<?php endif; ?>

</div>

</div>

</body>
</html>