<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User | Management</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --bg-gray: #f8fafc;
            --border: #e2e8f0;
            --text-main: #1e293b;
        }

        body { font-family: 'Inter', system-ui, sans-serif; background: var(--bg-gray); margin: 0; }

        .main { padding: 40px; margin-left: 260px; display: flex; flex-direction: column; align-items: center; }

        .form-card {
            background: #ffffff;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 450px;
        }

        .form-header { text-align: center; margin-bottom: 24px; }
        .form-header h1 { margin: 0; font-size: 1.5rem; color: var(--text-main); }
        .form-header p { color: #64748b; font-size: 0.9rem; margin-top: 5px; }

        .form-group { margin-bottom: 20px; }

        label { 
            display: block; 
            margin-bottom: 6px; 
            font-weight: 600; 
            font-size: 0.85rem; 
            color: #475569; 
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
            box-sizing: border-box;
            background-color: #fff;
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-submit {
            width: 100%;
            background-color: var(--primary);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
        }

        .btn-submit:hover { background-color: var(--primary-dark); }

        .back-link {
            margin-top: 20px;
            text-decoration: none;
            color: #64748b;
            font-size: 0.9rem;
        }
        .back-link:hover { color: var(--primary); }
    </style>
</head>
<body>

<?php include '../includes/sidebar.php'; ?>

<div class="main">
    <div class="form-card">
        <div class="form-header">
            <h1>Add New User</h1>
            <p>Create a new account for the system</p>
        </div>

        <form action="../process/user_add_process.php" method="POST">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" placeholder="e.g. John Doe" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" placeholder="john@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label for="role">User Role</label>
                <select name="role" id="role">
                    <option value="user">Standard User</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Create Account</button>
        </form>
    </div>

    <a href="users.php" class="back-link">← Back to User List</a>
</div>

</body>
</html>