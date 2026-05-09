<style>
@import url("../assets/css/theme.css");

:root {
    --sidebar-bg: #1e293b;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
}

/* SIDEBAR */
.sidebar {
    width: 220px;
    height: 100vh;
    background:
        linear-gradient(180deg, rgba(15, 23, 42, 0.98), rgba(30, 41, 59, 0.98)),
        radial-gradient(circle at top left, rgba(14, 165, 233, 0.3), transparent 38%);
    position: fixed;
    top: 0;
    left: 0;
    color: white;
    padding: 15px 10px;
    overflow-y: auto;
    transition: 0.3s;
    border-right: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: 12px 0 35px rgba(15, 23, 42, 0.12);
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
    background: rgba(51, 65, 85, 0.78);
}

/* ACTIVE */
.sidebar a.active {
    background: linear-gradient(135deg, #0ea5e9, #10b981);
    box-shadow: 0 10px 24px rgba(14, 165, 233, 0.28);
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
    min-height: 100vh;
    box-sizing: border-box;
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
<a href="../logout.php" class="<?= $current_page == 'logout.php' ? 'active' : '' ?>">
    <i>🚪</i> <span>Logout</span>
</a>

</div>

<script>
document.body.classList.add('app-shell-theme');

function addEquipmentPageStrip() {
    const main = document.querySelector(".main");
    if (!main || main.querySelector(".dashboard-hero") || main.querySelector(".page-equipment-strip")) {
        return;
    }

    const title = main.querySelector("h1")?.textContent?.trim() || "Equipment Library";
    const strip = document.createElement("section");
    strip.className = "page-equipment-strip";
    strip.innerHTML = `
        <div>
            <h2>${title}</h2>
            <p>Browse, borrow, track, and return computer equipment through a simple borrowing workspace.</p>
        </div>
        <div class="equipment-icons" aria-label="Computer equipment examples">
            <div class="equipment-icon"><span class="icon-symbol icon-laptop"></span>Laptop</div>
            <div class="equipment-icon"><span class="icon-symbol icon-keyboard"></span>Keyboard</div>
            <div class="equipment-icon"><span class="icon-symbol icon-monitor"></span>Monitor</div>
            <div class="equipment-icon"><span class="icon-symbol icon-system"></span>System Unit</div>
        </div>
    `;
    main.prepend(strip);
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", addEquipmentPageStrip);
} else {
    addEquipmentPageStrip();
}

function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");

    sidebar.classList.toggle("collapsed");

    const main = document.querySelector(".main");
    if (main) main.classList.toggle("collapsed");
}
</script>
