<?php
require_once '../config/auth.php';
require_role('user');
include '../config/db.php';

$user_id = (int) $_SESSION['user_id'];

// 1. Pagination Settings
$limit = 6; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 2. Search Logic
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$search_query = "";
if (!empty($search)) {
    $search_query = " AND e.name LIKE '%$search%' ";
}

// 3. Get Total Records for Pagination
$total_res = $conn->query("
    SELECT COUNT(*) AS total 
    FROM borrow_records br
    JOIN equipment e ON br.equipment_id = e.id
    WHERE br.user_id = $user_id 
    AND (br.status = 'borrowed' OR br.status = 'pending')
    $search_query
");
$total_rows = $total_res->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// 4. Fetch Paginated Results
$result = $conn->query("
    SELECT br.*, e.name, e.image 
    FROM borrow_records br
    JOIN equipment e ON br.equipment_id = e.id
    WHERE br.user_id = $user_id 
    AND (br.status = 'borrowed' OR br.status = 'pending')
    $search_query
    ORDER BY br.id DESC
    LIMIT $limit OFFSET $offset
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Items</title>
    <style>
        body { margin: 0; font-family: Arial; background: #f4f6f8; }
        .main { margin-left: 220px; padding: 20px; }
        
        /* Alert Banner */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            animation: slideDown 0.5s ease-out;
        }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

        @keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* Search Bar Styling */
        .top-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .search-box { display: flex; gap: 8px; }
        .search-box input { padding: 10px; border: 1px solid #ddd; border-radius: 8px; width: 250px; }

        /* Grid & Cards */
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 18px; }
        .card { background: white; border-radius: 12px; padding: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); transition: 0.2s; }
        .card img { width: 100%; height: 140px; object-fit: cover; border-radius: 10px; }
        .title { font-weight: bold; margin-top: 10px; font-size: 16px; }
        .meta { font-size: 13px; color: #6b7280; margin-top: 4px; }
        .badge { padding: 3px 8px; border-radius: 6px; font-size: 12px; font-weight: bold; }
        .borrowed { background: #dcfce7; color: #166534; }
        .pending { background: #fef3c7; color: #92400e; }
        
        .actions { margin-top: 12px; display: flex; gap: 6px; }
        .btn { padding: 7px 10px; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; text-decoration: none; display: inline-block; text-align: center; }
        .btn-return { background: #2563eb; color: white; }
        .btn-cancel { background: #ef4444; color: white; }

        /* Pagination Styling */
        .pagination { margin-top: 30px; display: flex; justify-content: center; gap: 8px; }
        .pagination a { padding: 8px 14px; background: white; border: 1px solid #ddd; text-decoration: none; color: #333; border-radius: 6px; }
        .pagination a.active { background: #2563eb; color: white; border-color: #2563eb; }
    </style>
</head>
<body>
    <?php include '../partials/alert.php'; ?>

<?php include '../includes/sidebar-user.php'; ?>

<div class="main">

    <?php if (isset($_SESSION['msg'])): ?>
        <div class="alert alert-<?= $_SESSION['msg_type']; ?>" id="alert-box">
            <?= $_SESSION['msg']; ?>
        </div>
        <?php unset($_SESSION['msg']); unset($_SESSION['msg_type']); ?>
        <script>
            setTimeout(() => {
                const box = document.getElementById('alert-box');
                if(box) box.style.display = 'none';
            }, 4000);
        </script>
    <?php endif; ?>

    <div class="top-flex">
        <h1>My Borrowed Items</h1>
        
        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Search item name..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-return">Search</button>
            <?php if(!empty($search)): ?>
                <a href="my_items.php" class="btn" style="background:#eee; color:#333;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="grid">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="card">
                <img src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" onerror="this.src='https://via.placeholder.com/300x140'">
                <div class="title"><?php echo htmlspecialchars($row['name']); ?></div>
                <div class="meta">Qty: <?php echo (int)$row['quantity']; ?></div>
                <div class="meta">Due: <?php echo date('M d, Y', strtotime($row['return_date'])); ?></div>
                
                <div class="status" style="margin-top:8px;">
                    <?php if ($row['status'] == 'borrowed'): ?>
                        <span class="badge borrowed">Borrowed</span>
                    <?php else: ?>
                        <span class="badge pending">Return Pending</span>
                    <?php endif; ?>
                </div>

                <div class="actions">
                    <?php if ($row['status'] == 'borrowed'): ?>
                        <a class="btn btn-return" 
                           href="../process/return_request.php?id=<?php echo $row['id']; ?>" 
                           onclick="return confirm('Request return for this item?')">
                           Return Item
                        </a>
                    <?php else: ?>
                        <a class="btn btn-cancel" 
                           href="../process/cancel_return.php?id=<?php echo $row['id']; ?>"
                           onclick="return confirm('Are you sure you want to cancel the return request?')">
                           Cancel Return
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty" style="grid-column: 1/-1; text-align: center; padding: 50px;">
            <h3>No items found</h3>
            <p>You aren't currently borrowing any items matching your search.</p>
            <a class="btn btn-return" href="borrow.php">Browse Equipment</a>
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
