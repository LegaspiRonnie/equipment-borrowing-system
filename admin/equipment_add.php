<?php
require_once '../config/auth.php';
require_role('admin');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Equipment | Dashboard</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --bg-color: #f8f9fa;
            --card-bg: #ffffff;
            --text-main: #2b2d42;
            --border-color: #e0e0e0;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            display: flex;
        }

        .main {
            flex-grow: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .form-container {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 500px;
        }

        h1 {
            color: var(--text-main);
            margin-bottom: 25px;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #666;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box; /* Prevents padding from breaking width */
            transition: border-color 0.3s ease;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        /* Styling the file input */
        input[type="file"] {
            padding: 10px 0;
        }

        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 14px 20px;
            border-radius: 8px;
            width: 100%;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #3046c9;
        }

        /* Subtle helper text */
        .helper-text {
            font-size: 0.8rem;
            color: #888;
            margin-top: 4px;
        }
    </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="main">
    <div class="form-container">
        <h1>Add New Equipment</h1>
        
        <form action="../process/equipment_add_process.php" method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Equipment Name</label>
                <input type="text" name="name" placeholder="e.g. Logitech MX Master 3" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" placeholder="e.g. Mouse, Keyboard, Monitor" required>
            </div>

            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="quantity" placeholder="0" min="1" required>
            </div>

            <div class="form-group">
                <label>Initial Status</label>
                <select name="status">
                    <option value="available">Available</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>

            <div class="form-group">
                <label>Equipment Image</label>
                <input type="file" name="image" accept="image/*" required>
                <div class="helper-text">Upload a clear photo of the asset.</div>
            </div>

            <button type="submit" class="btn-submit">Add Equipment</button>
        </form>
    </div>
</div>

<?php include '../includes/footer.html'; ?>
