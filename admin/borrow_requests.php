<?php
require_once '../config/auth.php';
require_role('admin');
include '../config/db.php';

// 1. Capture search term
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// 2. Build Query with Search Filter
$sql = "
SELECT 
    br.*, 
    u.name AS user_name, 
    e.name AS equipment_name,
    e.image AS equipment_image
FROM borrow_requests br
JOIN users u ON br.user_id = u.id
JOIN equipment e ON br.equipment_id = e.id
WHERE br.status = 'pending'";

if (!empty($search)) {
    $sql .= " AND (u.name LIKE '%$search%' OR e.name LIKE '%$search%')";
}

$sql .= " ORDER BY br.request_date DESC";

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

    body { font-family: 'Inter', sans-serif; background: var(--bg-light); }
    .main { margin-left: 240px; padding: 40px; }

    /* Search Bar Styling */
    .search-wrapper {
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .search-form {
        display: flex;
        gap: 10px;
    }

    .search-input {
        padding: 10px 15px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        width: 300px;
        outline: none;
        transition: border-color 0.2s;
    }

    .search-input:focus { border-color: var(--primary); }

    .btn-search {
        background: var(--primary);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
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

    th {
        background: var(--primary);
        color: white;
        padding: 15px;
        text-align: left;
        font-size: 0.9rem;
        text-transform: uppercase;
    }

    td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        color: #444;
        font-size: 0.95rem;
    }

    tr:hover { background-color: #fcfcfc; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        border: 2px dashed #cbd5e1;
    }

    .btn {
        padding: 8px 14px;
        border: none;
        cursor: pointer;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .btn-approve { background: var(--success); color: white; margin-right: 5px; }
    .btn-reject { background: var(--danger); color: white; }
    .btn:hover { opacity: 0.9; transform: translateY(-1px); }

    img { border-radius: 8px; object-fit: cover; background: #f1f5f9; }
</style>
<?php include '../partials/alert.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main">
    <div class="search-wrapper">
        <h1 style="color: var(--primary); margin: 0;">Pending Borrow Requests</h1>
        
        <form action="" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" 
                   placeholder="Search user or equipment..." 
                   value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn-search">Search</button>
            <?php if (!empty($search)): ?>
                <a href="borrow_requests.php" style="align-self: center; color: #64748b; font-size: 0.9rem;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Equipment</th>
                        <th>Quantity</th>
                        <th>Purpose</th>
                        <th>Timeline</th>
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
                                <img src="<?php echo $img; ?>" width="50" height="50">
                                <?php echo htmlspecialchars($row['equipment_name']); ?>
                            </div>
                        </td>
                        <td><?php echo $row['quantity'] ?? 1; ?></td>
                        <td><small><?php echo htmlspecialchars($row['purpose']); ?></small></td>
                        <td>
                            <div style="font-size: 0.8rem;">
                                <b>Requested:</b> <?php echo date('M d', strtotime($row['request_date'])); ?><br>
                                <b>Return:</b> <?php echo date('M d', strtotime($row['return_date'])); ?>
                            </div>
                        </td>
                        <td>
                            <a href="../process/approve_request.php?id=<?php echo $row['id']; ?>" 
                               class="btn btn-approve" 
                               onclick="return confirm('Approve request for <?php echo addslashes($row['equipment_name']); ?>?')">
                               Approve
                            </a>

                            <a href="../process/reject_request.php?id=<?php echo $row['id']; ?>" 
                               class="btn btn-reject" 
                               onclick="return confirm('Reject this request?')">
                               Reject
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div style="font-size: 3rem; margin-bottom: 15px;">🔍</div>
            <p><?php echo !empty($search) ? "No results found for '$search'" : "No pending borrow requests found."; ?></p>
            <span style="color: #94a3b8; font-size: 0.9rem;">Check back later or try a different search.</span>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.html'; ?>