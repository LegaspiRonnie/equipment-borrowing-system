<?php
session_start();
include '../config/db.php';

// 1. Pagination Settings
$limit = 10; // Items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 2. Handle Search Logic
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where_clause = "";
if (!empty($search)) {
    $where_clause = " WHERE name LIKE '%$search%' OR category LIKE '%$search%'";
}

// 3. Get Total Records for Calculation
$total_result = $conn->query("SELECT COUNT(*) AS total FROM equipment $where_clause");
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// 4. Build Paginated Query
$query = "SELECT * FROM equipment $where_clause ORDER BY name ASC LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
?>

<?php include '../includes/header.html'; ?>

<style>
    :root {
        --primary: #2c3e50;
        --accent: #3498db;
        --bg: #f4f7f6;
    }

    body { font-family: 'Inter', sans-serif; background: var(--bg); }
    .main { margin-left: 240px; padding: 40px; }

    /* Search Bar Styling */
    .search-container { margin-bottom: 25px; display: flex; gap: 10px; }
    .search-input { padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; width: 300px; font-size: 1rem; }
    .btn-search { background: var(--primary); color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; }

    /* Table Styling */
    .table-card { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th { background: var(--primary); color: white; padding: 15px; text-align: left; }
    td { padding: 15px; border-bottom: 1px solid #eee; }
    img { border-radius: 4px; object-fit: cover; }

    /* Status Badge */
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: bold; }
    .status-available { background: #e8f5e9; color: #2e7d32; }

    /* Pagination Styling */
    .pagination { margin-top: 25px; display: flex; justify-content: center; gap: 8px; }
    .pagination a {
        padding: 8px 16px;
        border: 1px solid #ddd;
        color: var(--primary);
        text-decoration: none;
        border-radius: 6px;
        background: white;
        transition: 0.3s;
    }
    .pagination a.active { background: var(--primary); color: white; border-color: var(--primary); }
    .pagination a:hover:not(.active) { background: #eee; }
</style>

<?php include '../includes/sidebar.php'; ?>

<div class="main">
    <h1>Equipment Inventory</h1>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <form action="" method="GET" class="search-container">
            <input type="text" name="search" class="search-input" 
                   placeholder="Search name or category..." 
                   value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn-search">Search</button>
            <?php if(!empty($search)): ?>
                <a href="equipment.php" style="align-self: center; font-size: 0.9rem; color: #666;">Clear</a>
            <?php endif; ?>
        </form>

        <a href="equipment_add.php">
            <button class="btn btn-edit">+ Add Equipment</button>
        </a>
    </div>

    <div class="table-card">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Qty</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <img src="../assets/images/<?php echo !empty($row['image']) ? $row['image'] : 'default.png'; ?>" width="50" height="50">
                        </td>
                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><span class="badge status-available"><?php echo strtoupper($row['status']); ?></span></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>
                            <a href="equipment_view.php?id=<?php echo $row['id']; ?>" class="btn">View</a>
                            <a href="equipment_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">Edit</a>
                            <a href="equipment_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Delete this item?')">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="padding: 40px; text-align: center; color: #666;">
                <p>No equipment found matching "<strong><?php echo htmlspecialchars($search); ?></strong>"</p>
            </div>
        <?php endif; ?>
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