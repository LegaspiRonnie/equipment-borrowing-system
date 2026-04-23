<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
}

/* SIDEBAR (DESKTOP) */
.sidebar {
    width: 220px;
    height: 100vh;
    background: #1e293b;
    position: fixed;
    top: 0;
    left: 0;
    color: white;
    transition: 0.3s;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

/* COLLAPSE */
.sidebar.collapsed {
    width: 70px;
}

/* HEADER */
.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 18px; /* improved padding */
    width: 100%;
    box-sizing: border-box;
}

/* TITLE */
.sidebar h2 {
    margin: 0;
    font-size: 16px;
}

.sidebar.collapsed h2 {
    display: none;
}

/* BURGER */
.burger {
    cursor: pointer;
    font-size: 22px;
    margin-left: auto;   /* pushes it inward */
    padding-right: 5px;  /* prevents clipping */
}

/* LINKS */
.sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    color: white;
    text-decoration: none;
}

.sidebar a:hover {
    background: #334155;
}

.sidebar a.active {
    background: #3b82f6;
}

/* ICON */
.sidebar a i {
    width: 20px;
}

/* HIDE TEXT */
.sidebar.collapsed a span {
    display: none;
}

/* MAIN */
.main {
    margin-left: 220px;
    padding: 20px;
    transition: 0.3s;
}

.main.collapsed {
    margin-left: 70px;
}

/* ========================= */
/* 📱 MOBILE NAVBAR VERSION */
/* ========================= */
@media (max-width: 768px) {

    .sidebar {
        width: 100%;
        height: auto;
        position: fixed;
        flex-direction: column;
        top: 0;
        left: 0;
        z-index: 1000;
        overflow: visible;
    }

    .sidebar-header {
        width: 100%;
        background: #1e293b;
        padding: 15px 20px; /* extra space for burger */
        box-sizing: border-box;
    }

    /* HIDE LINKS INITIALLY */
    .sidebar a {
        display: none;
        width: 100%;
        border-top: 1px solid #334155;
    }

    /* SHOW WHEN OPEN */
    .sidebar.show a {
        display: flex;
    }

    /* MAIN RESET */
    .main {
        margin-left: 0;
        margin-top: 60px;
    }
}
</style>

<div id="sidebar" class="sidebar">

    <!-- HEADER -->
    <div class="sidebar-header">
        <h2>ADMIN</h2>
        <div class="burger" onclick="toggleSidebar()">☰</div>
    </div>

    <a href="dashboard.php" class="<?= $current_page == 'dashboard.php' ? 'active' : '' ?>">
        <i>🏠</i> <span>Dashboard</span>
    </a>

    <a href="equipment.php" class="<?= $current_page == 'equipment.php' ? 'active' : '' ?>">
        <i>💻</i> <span>Equipment</span>
    </a>

    <a href="borrow_requests.php" class="<?= $current_page == 'borrow_requests.php' ? 'active' : '' ?>">
        <i>📥</i> <span>Borrow Requests</span>
    </a>

    <a href="return_items.php" class="<?= $current_page == 'return_items.php' ? 'active' : '' ?>">
        <i>📤</i> <span>Returns</span>
    </a>

    <a href="reports.php" class="<?= $current_page == 'reports.php' ? 'active' : '' ?>">
        <i>📊</i> <span>Reports</span>
    </a>

    <a href="history.php" class="<?= $current_page == 'history.php' ? 'active' : '' ?>">
        <i>📜</i> <span>History</span>
    </a>

    <a href="users.php" class="<?= $current_page == 'users.php' ? 'active' : '' ?>">
        <i>👥</i> <span>Users</span>
    </a>

    <a href="settings.php" class="<?= $current_page == 'settings.php' ? 'active' : '' ?>">
        <i>⚙️</i> <span>Settings</span>
    </a>

</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");

    if (window.innerWidth <= 768) {
        sidebar.classList.toggle("show");
    } else {
        sidebar.classList.toggle("collapsed");

        const main = document.querySelector(".main");
        if (main) main.classList.toggle("collapsed");
    }
}
</script>