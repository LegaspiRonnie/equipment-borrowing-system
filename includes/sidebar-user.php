<style>
:root {
    --sidebar-bg: #1e293b;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f1f5f9;
}

/* SIDEBAR */
.sidebar {
    width: 220px;
    height: 100vh;
    background: var(--sidebar-bg);
    position: fixed;
    top: 0;
    left: 0;
    color: white;
    padding: 15px 10px;
    overflow-y: auto;
    transition: 0.3s;
}

/* COLLAPSED */
.sidebar.collapsed {
    width: 70px;
}

/* HEADER */
.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
}

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
    margin-left: auto;
}

/* LINKS */
.sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: white;
    padding: 12px;
    text-decoration: none;
    border-radius: 6px;
    transition: 0.2s;
}

/* ICON STYLE */
.sidebar a .icon {
    font-size: 18px;
    min-width: 25px;
    text-align: center;
}

/* TEXT */
.sidebar a .text {
    white-space: nowrap;
}

/* HOVER */
.sidebar a:hover {
    background: #334155;
}

/* ACTIVE */
.sidebar a.active {
    background: #0ea5e9;
    font-weight: bold;
}

/* COLLAPSED MODE */
.sidebar.collapsed a {
    justify-content: center;
}

/* hide ONLY text */
.sidebar.collapsed a .text {
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
</style>

<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div id="sidebar" class="sidebar">

    <!-- HEADER -->
    <div class="sidebar-header">
        <h2>USER</h2>
        <div class="burger" onclick="toggleSidebar()">☰</div>
    </div>

    <!-- NAVIGATION -->

    <a href="dashboard.php" class="<?= $currentPage == 'dashboard.php' ? 'active' : '' ?>">
        <span class="icon">🏠</span>
        <span class="text">Home</span>
    </a>

    <a href="borrow.php" class="<?= $currentPage == 'borrow.php' ? 'active' : '' ?>">
        <span class="icon">📥</span>
        <span class="text">Borrow</span>
    </a>

    <a href="my_items.php" class="<?= $currentPage == 'my_items.php' ? 'active' : '' ?>">
        <span class="icon">📦</span>
        <span class="text">My Items</span>
    </a>

    <a href="history.php" class="<?= $currentPage == 'history.php' ? 'active' : '' ?>">
        <span class="icon">📜</span>
        <span class="text">History</span>
    </a>

</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");

    sidebar.classList.toggle("collapsed");

    const main = document.querySelector(".main");
    if (main) main.classList.toggle("collapsed");
}
</script>