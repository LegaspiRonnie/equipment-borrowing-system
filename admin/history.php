<?php
session_start();
include '../config/db.php';

// 1. Pagination Settings
$limit = 10; // Items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 2. Search Logic
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where_clause = "";
if (!empty($search)) {
    $where_clause = " WHERE u.name LIKE '%$search%' 
                      OR e.name LIKE '%$search%' 
                      OR h.action LIKE '%$search%' 
                      OR h.details LIKE '%$search%' ";
}

// 3. Get Total Records for Pagination
$total_query = "SELECT COUNT(*) AS total FROM history_logs h 
                LEFT JOIN users u ON h.user_id = u.id 
                LEFT JOIN equipment e ON h.equipment_id = e.id 
                $where_clause";
$total_result = $conn->query($total_query);
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// 4. Get Paginated & Filtered Results
$query = "
    SELECT h.*, u.name AS user_name, e.name AS equipment_name
    FROM history_logs h
    LEFT JOIN users u ON h.user_id = u.id
    LEFT JOIN equipment e ON h.equipment_id = e.id
    $where_clause
    ORDER BY h.created_at DESC
    LIMIT $limit OFFSET $offset
";
$result = $conn->query($query);
?>

<?php include '../includes/header.html'; ?>
<?php include '../includes/sidebar.php'; ?>

<style>
    :root { --primary: #2c3e50; --bg: #f4f7f6; }
    body { font-family: 'Inter', sans-serif; background: var(--bg); }
    .main { margin-left: 240px; padding: 40px; }
    
    .header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    
    /* Search Bar */
    .search-form { display: flex; gap: 8px; }
    .search-input { padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px; width: 250px; }
    .btn-search { background: var(--primary); color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; }

    /* Table */
    .table-card { background: white; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th { background: var(--primary); color: white; padding: 12px; text-align: left; font-size: 0.85rem; }
    td { padding: 12px; border-bottom: 1px solid #eee; font-size: 0.9rem; }
    
    /* Action Badges */
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; }
    .bg-request { background: #e3f2fd; color: #1976d2; }
    .bg-approved { background: #e8f5e9; color: #2e7d32; }
    .bg-rejected { background: #ffebee; color: #c62828; }
    .bg-returned { background: #f3e5f5; color: #7b1fa2; }

    /* Pagination Styling */
    .pagination { margin-top: 20px; display: flex; justify-content: center; gap: 5px; }
    .pagination a { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: var(--primary); border-radius: 4px; background: white; transition: 0.2s; }
    .pagination a.active { background: var(--primary); color: white; border-color: var(--primary); }
    .pagination a:hover:not(.active) { background: #eee; }
</style>

<div class="main">
    <div class="header-flex">
        <h1>History Logs</h1>
        
        <form action="" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" placeholder="Search logs..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn-search">Search</button>
            <?php if(!empty($search)): ?>
                <a href="history.php" style="font-size: 0.8rem; color: #666; align-self: center;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Action</th>
                    <th>Item</th>
                    <th>Details</th>
                    <th>Date & Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): 
                        $action_class = "bg-" . strtolower($row['action']);
                    ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['user_name'] ?? 'System'); ?></strong></td>
                        <td><span class="badge <?php echo $action_class; ?>"><?php echo $row['action']; ?></span></td>
                        <td><?php echo htmlspecialchars($row['equipment_name'] ?? 'N/A'); ?></td>
                        <td><small><?php echo htmlspecialchars($row['details']); ?></small></td>
                        <td style="color: #666;"><?php echo date("M d, Y | h:i A", strtotime($row['created_at'])); ?></td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align: center; padding: 30px;">No records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($total_pages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" 
               class="<?php echo ($page == $i) ? 'active' : ''; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>
    <?php endif; ?>

</div>

<?php include '../includes/footer.html'; ?>