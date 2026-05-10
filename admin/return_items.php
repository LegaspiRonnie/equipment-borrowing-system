<?php
require_once '../config/auth.php';
require_role('admin');
include '../config/db.php';

// 1. Capture search input
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// 2. Build Query with Search Filter
$sql = "
SELECT 
    br.*, 
    u.name AS user_name, 
    e.name AS equipment_name,
    e.image AS equipment_image
FROM borrow_records br
JOIN users u ON br.user_id = u.id
JOIN equipment e ON br.equipment_id = e.id
WHERE br.status = 'pending'";

if (!empty($search)) {
    $sql .= " AND (u.name LIKE '%$search%' OR e.name LIKE '%$search%')";
}

$sql .= " ORDER BY br.id DESC";

$result = $conn->query($sql);
?>

<?php include '../includes/header.html'; ?>

<style>
    :root {
        --primary: #2c3e50;
        --success: #27ae60;
        --danger: #e74c3c;
        --bg-light: #f4f7f6;
    }

    body { font-family: 'Inter', sans-serif; background: var(--bg-light); margin: 0; }
    .main { margin-left: 240px; padding: 40px; }

    /* Search Bar Styling */
    .header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .search-form {
        display: flex;
        gap: 8px;
    }

    .search-input {
        padding: 10px 15px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        width: 280px;
        font-size: 0.9rem;
    }

    .btn-search {
        background: var(--primary);
        color: white;
        padding: 10px 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
    }

    .table-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    table { width: 100%; border-collapse: collapse; }
    th { background: var(--primary); color: white; padding: 15px; text-align: left; font-size: 0.9rem; text-transform: uppercase; }
    td { padding: 15px; border-bottom: 1px solid #eee; color: #444; font-size: 0.95rem; vertical-align: middle; }
    tr:hover { background-color: #fcfcfc; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        border: 2px dashed #cbd5e1;
        margin-top: 20px;
    }

    .btn { padding: 8px 14px; border: none; cursor: pointer; border-radius: 6px; font-weight: 600; text-decoration: none; display: inline-block; font-size: 0.85rem; transition: 0.2s; }
    .btn-approve { background: var(--success); color: white; margin-right: 5px; }
    .btn-delete { background: var(--danger); color: white; }
    .btn:hover { opacity: 0.85; transform: translateY(-1px); }

    img { border-radius: 8px; object-fit: cover; background: #f1f5f9; }
</style>
<?php include '../partials/alert.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main">
    
    <div class="header-flex">
        <h1 style="color: var(--primary); margin: 0;">Pending Return Requests</h1>
        
        <form action="" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" 
                   placeholder="Search user or item..." 
                   value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn-search">Search</button>
            <?php if (!empty($search)): ?>
                <a href="return_requests.php" style="align-self: center; color: #64748b; margin-left: 5px; font-size: 0.85rem;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Equipment</th>
                        <th>Quantity</th>
                        <th>Return Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['user_name']); ?></strong></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <?php
                                $img = !empty($row['equipment_image']) 
                                    ? "../assets/images/" . $row['equipment_image'] 
                                    : "../assets/images/default.png";
                                ?>
                                <img src="<?php echo $img; ?>" width="50" height="50" alt="Equip">
                                <?php echo htmlspecialchars($row['equipment_name']); ?>
                            </div>
                        </td>
                        <td><?php echo $row['quantity'] ?? 1; ?></td>
                        <td>
                            <span style="color: #64748b; font-weight: 500;">
                                <?php echo date('M d, Y', strtotime($row['return_date'])); ?>
                            </span>
                        </td>
                        <td>
                            <a href="../process/approve_return.php?id=<?php echo $row['id']; ?>" 
                               onclick="return confirm('Confirm return for <?php echo addslashes($row['equipment_name']); ?>?')">
                                <button class="btn btn-approve">Approve Return</button>
                            </a>

                            <a href="../process/reject_return.php?id=<?php echo $row['id']; ?>" 
                               onclick="return confirm('Reject this return request?')">
                                <button class="btn btn-delete">Reject</button>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <span class="empty-icon"><?php echo !empty($search) ? '🔍' : '✅'; ?></span>
            <p>
                <?php echo !empty($search) 
                    ? "No returns found matching '" . htmlspecialchars($search) . "'" 
                    : "No pending return requests at the moment."; ?>
            </p>
            <small style="color: #94a3b8;">
                <?php echo !empty($search) ? "Try a different keyword or clear search." : "All borrowed equipment is currently logged."; ?>
            </small>
        </div>
    <?php endif; ?>

</div>

<?php include '../includes/footer.html'; ?>