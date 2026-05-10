<?php
require_once '../config/auth.php';
require_role('user');
include '../config/db.php';

// 1. Pagination Settings
$limit = 9; // Grid of 3x3 looks best
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 2. Search Logic
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$where_clause = "";
if (!empty($search)) {
    $where_clause = " WHERE name LIKE '%$search%' OR category LIKE '%$search%'";
}

// 3. Count total for pagination
$total_res = $conn->query("SELECT COUNT(*) AS total FROM equipment $where_clause");
$total_rows = $total_res->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// 4. Get Equipment (Alphabetical Order)
$equipment = $conn->query("SELECT * FROM equipment $where_clause ORDER BY name ASC LIMIT $limit OFFSET $offset");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        /* Search Bar Style */
        .search-box {
            display: flex;
            gap: 10px;
        }

        .search-box input {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
            width: 250px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
            text-align: center;
            border: 1px solid transparent;
        }

        .card:hover { transform: translateY(-8px); border-color: #4CAF50; }

        .card img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .category { font-size: 12px; color: gray; text-transform: uppercase; }
        .qty { margin: 10px 0; font-weight: bold; }

        .status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            display: inline-block;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .available { background: #d4edda; color: #155724; }
        .notavailable { background: #f8d7da; color: #721c24; }

        .btn { padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: bold; width: 100%; transition: 0.2s; }
        .btn-borrow { background: #4CAF50; color: white; }
        .btn-borrow:hover { background: #45a049; }
        .btn-disabled { background: #e5e7eb; color: #9ca3af; cursor: not-allowed; }

        /* Pagination */
        .pagination {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .pagination a {
            padding: 8px 16px;
            background: white;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
            border-radius: 5px;
        }
        .pagination a.active { background: #4CAF50; color: white; border-color: #4CAF50; }

        #datetime { font-weight: bold; color: #4CAF50; }
    </style>
</head>
<body class="dashboard-theme">
    <?php include '../partials/alert.php'; ?>

<?php include '../includes/sidebar-user.php'; ?>

<div class="main">

    <div class="dashboard-hero">
        <div>
            <h1>Equipment Library</h1>
            <p>Browse available computer equipment, check stock, and start a borrowing request.</p>
            <div id="datetime"></div>
        </div>

        <div class="equipment-icons" aria-label="Computer equipment examples">
            <div class="equipment-icon">
                <span class="icon-symbol icon-laptop"></span>
                Laptop
            </div>
            <div class="equipment-icon">
                <span class="icon-symbol icon-keyboard"></span>
                Keyboard
            </div>
            <div class="equipment-icon">
                <span class="icon-symbol icon-monitor"></span>
                Monitor
            </div>
            <div class="equipment-icon">
                <span class="icon-symbol icon-system"></span>
                System Unit
            </div>
        </div>
    </div>

    <div class="topbar">
        <div></div>
        
        <form method="GET" class="search-box">
            <input type="text" name="search" placeholder="Search equipment..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="btn btn-borrow" style="width: auto;">Search</button>
            <?php if(!empty($search)): ?>
                <a href="dashboard.php" class="btn" style="background:#eee; color:#333; width:auto; text-decoration:none;">Clear</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="grid">
    <?php if ($equipment && $equipment->num_rows > 0): ?>
        <?php while($row = $equipment->fetch_assoc()): ?>
            <div class="card">
                <img src="../assets/images/<?php echo htmlspecialchars($row['image']); ?>" 
                     onerror="this.src='https://via.placeholder.com/120?text=No+Image'">

                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <div class="category"><?php echo htmlspecialchars($row['category']); ?></div>
                <div class="qty">Available: <?php echo (int)$row['quantity']; ?></div>

                <?php if ($row['quantity'] > 0): ?>
                    <div class="status available">READY TO BORROW</div>
                    <a href="borrow.php?id=<?php echo $row['id']; ?>">
                        <button class="btn btn-borrow">Borrow Now</button>
                    </a>
                <?php else: ?>
                    <div class="status notavailable">CURRENTLY OUT</div>
                    <button class="btn btn-disabled" disabled>Out of Stock</button>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty-state">
            <h2>No matches found</h2>
            <p>Try searching for a different keyword or check back later.</p>
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

<script>
    function updateTime() {
        const now = new Date();
        document.getElementById("datetime").innerHTML = now.toLocaleDateString() + " | " + now.toLocaleTimeString();
    }
    setInterval(updateTime, 1000);
    updateTime();
</script>

<?php include '../includes/footer.html'; ?>
