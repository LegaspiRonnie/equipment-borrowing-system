<?php
require_once '../config/auth.php';
require_role('admin');
include '../config/db.php';

// 1. Pagination Setup
$limit = 10; 
$page = isset($_GET['page']) ? (int)$GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 2. Search Logic
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where_clause = "";
if (!empty($search)) {
    $where_clause = " WHERE name LIKE '%$search%' OR email LIKE '%$search%' ";
}

// 3. Count Total Records for Pagination
$total_result = $conn->query("SELECT COUNT(*) AS total FROM users $where_clause");
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// 4. Fetch Results (Ordered by Name Alphabetically)
$query = "SELECT * FROM users $where_clause ORDER BY name ASC LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
?>

<?php include '../includes/header.html'; ?>

<style>
    :root { --primary: #2c3e50; --bg: #f4f7f6; }
    body { font-family: 'Inter', sans-serif; background: var(--bg); }
    .main { margin-left: 240px; padding: 40px; }

    /* Search Bar Layout */
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .search-form { display: flex; gap: 10px; }
    .search-input { padding: 10px; border: 1px solid #ddd; border-radius: 6px; width: 250px; }
    .btn-search { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; }

    /* Table Styling */
    .table-card { background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    th { background: var(--primary); color: white; padding: 15px; text-align: left; }
    td { padding: 15px; border-bottom: 1px solid #eee; }

    /* Pagination Styling */
    .pagination { margin-top: 25px; display: flex; justify-content: center; gap: 8px; }
    .pagination a {
        padding: 8px 16px;
        border: 1px solid #ddd;
        color: var(--primary);
        text-decoration: none;
        border-radius: 6px;
        background: white;
    }
    .pagination a.active { background: var(--primary); color: white; border-color: var(--primary); }
    .pagination a:hover:not(.active) { background: #eee; }
</style>
<?php include '../partials/alert.php'; ?>
<?php include '../includes/sidebar.php'; ?>

<div class="main">
    <h1>Users Directory</h1>

    <div class="action-bar">
        <form action="" method="GET" class="search-form">
            <input type="text" name="search" class="search-input" 
                   placeholder="Search name or email..." 
                   value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn-search">Search</button>
            <?php if(!empty($search)): ?>
                <a href="users.php" style="align-self: center; font-size: 0.8rem; color: #666;">Clear</a>
            <?php endif; ?>
        </form>

        <a href="user_add.php">
            <button class="btn btn-edit">+ Add User</button>
        </a>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><span style="text-transform: capitalize;"><?php echo $row['role']; ?></span></td>
                        <td><?php echo date("M d, Y", strtotime($row['created_at'])); ?></td>
                        <td>
                            <a href="user_view.php?id=<?php echo $row['id']; ?>"><button class="btn">View</button></a>
                            <a href="user_edit.php?id=<?php echo $row['id']; ?>"><button class="btn btn-edit">Edit</button></a>
                            <a href="user_delete.php?id=<?php echo $row['id']; ?>"><button class="btn btn-delete">Delete</button></a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align:center; padding: 40px; color: #666;">No users found.</td></tr>
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