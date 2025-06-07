<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Admin - Sweet Cake</title>
    <link rel="stylesheet" href="/Sweet_Cake/css/admin.css">
    <style>
        .users-section {
            max-width: 900px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(44,44,100,0.07);
            padding: 2rem;
        }
        .users-section h2 {
            color: #2d2d44;
            margin-bottom: 1.5rem;
        }
        .users-table {
            width: 100%;
            border-collapse: collapse;
        }
        .users-table th, .users-table td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid #ececf6;
            text-align: left;
        }
        .users-table th {
            background: #f0f1fa;
            color: #2d2d44;
        }
        .type-badge {
            display: inline-block;
            padding: 0.35em 1em;
            border-radius: 12px;
            font-size: 0.95em;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.5px;
        }
        .badge-user { background: #2980b9; }
        .badge-admin { background: #27ae60; }
        .type-select {
            padding: 0.4rem 1rem;
            border-radius: 4px;
            border: 1px solid #d1d1e0;
            background: #f7f7fa;
            font-size: 1rem;
        }
        .update-btn {
            background: #2d2d44;
            color: #fff;
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
            margin-right: 0.5rem;
        }
        .update-btn:hover {
            background: #44446a;
        }
        .delete-btn {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .delete-btn:hover {
            background: #c0392b;
        }
        .users-table td.actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            justify-content: flex-start;
        }
    </style>
</head>
<body>

    <?php include './includes/admin_nav.php'; ?>

    <div class="users-section">
        <h2>All Users</h2>
        <table class="users-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>User Type</th>
                    <th>Type Badge</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>John Doe</td>
                    <td>john@example.com</td>
                    <td>123-456-7890</td>
                    <td>
                        <select class="type-select">
                            <option selected>User</option>
                            <option>Admin</option>
                        </select>
                    </td>
                    <td><span class="type-badge badge-user">User</span></td>
                    <td class="actions">
                        <button class="update-btn" disabled>Update</button>
                        <button class="delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Jane Smith</td>
                    <td>jane@example.com</td>
                    <td>987-654-3210</td>
                    <td>
                        <select class="type-select">
                            <option>User</option>
                            <option selected>Admin</option>
                        </select>
                    </td>
                    <td><span class="type-badge badge-admin">Admin</span></td>
                    <td class="actions">
                        <button class="update-btn" disabled>Update</button>
                        <button class="delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Mary Lee</td>
                    <td>mary@example.com</td>
                    <td>555-123-4567</td>
                    <td>
                        <select class="type-select" disabled>
                            <option selected>User</option>
                            <option>Admin</option>
                        </select>
                    </td>
                    <td><span class="type-badge badge-user">User</span></td>
                    <td class="actions">
                        <button class="update-btn" disabled>Update</button>
                        <button class="delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Alex Green</td>
                    <td>alex@example.com</td>
                    <td>222-333-4444</td>
                    <td>
                        <select class="type-select" disabled>
                            <option>User</option>
                            <option selected>Admin</option>
                        </select>
                    </td>
                    <td><span class="type-badge badge-admin">Admin</span></td>
                    <td class="actions">
                        <button class="update-btn" disabled>Update</button>
                        <button class="delete-btn">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
