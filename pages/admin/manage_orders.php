<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders | Admin - Sweet Cake</title>
    <link rel="stylesheet" href="/Sweet_Cake/css/admin.css">
    <style>
        .orders-section {
            max-width: 1100px;
            margin: 2rem auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(44,44,100,0.07);
            padding: 2rem;
        }
        .orders-section h2 {
            color: #2d2d44;
            margin-bottom: 1.5rem;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }
        .orders-table th, .orders-table td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid #ececf6;
            text-align: left;
        }
        .orders-table th {
            background: #f0f1fa;
            color: #2d2d44;
        }
        .status-select {
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
        .order-details-list {
            margin: 0;
            padding-left: 1.2rem;
            font-size: 0.98rem;
            color: #44446a;
        }
        .status-badge {
            display: inline-block;
            padding: 0.35em 1em;
            border-radius: 12px;
            font-size: 0.95em;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.5px;
        }
        .badge-pending { background: #f39c12; }
        .badge-processing { background: #2980b9; }
        .badge-shipped { background: #8e44ad; }
        .badge-delivered { background: #27ae60; }
        .badge-cancelled { background: #e74c3c; }
    </style>
</head>
<body>


        <?php include './includes/admin_nav.php'; ?>
    <div class="orders-section">
        <h2>All Orders</h2>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Order Details</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Status Badge</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>John Doe</td>
                    <td>
                        <ul class="order-details-list">
                            <li><b>Chocolate Cake</b> x1 ($15)</li>
                            <li><b>Cookies</b> x2 ($5)</li>
                        </ul>
                        <div style="margin-top:0.5rem; font-size:0.95em; color:#888;">
                            Address: 123 Main St, City<br>
                            Email: john@example.com<br>
                            Phone: 123-456-7890
                        </div>
                    </td>
                    <td>$25</td>
                    <td>
                        <select class="status-select" disabled>
                            <option selected>Pending</option>
                            <option>Processing</option>
                            <option>Shipped</option>
                            <option>Delivered</option>
                            <option>Cancelled</option>
                        </select>
                    </td>
                    <td><span class="status-badge badge-pending">Pending</span></td>
                    <td>2025-06-07</td>
                    <td>
                        <button class="update-btn" disabled>Update</button>
                        <button class="delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Jane Smith</td>
                    <td>
                        <ul class="order-details-list">
                            <li><b>Pastries</b> x3 ($18)</li>
                        </ul>
                        <div style="margin-top:0.5rem; font-size:0.95em; color:#888;">
                            Address: 456 Oak Ave, Town<br>
                            Email: jane@example.com<br>
                            Phone: 987-654-3210
                        </div>
                    </td>
                    <td>$18</td>
                    <td>
                        <select class="status-select" disabled>
                            <option>Pending</option>
                            <option>Processing</option>
                            <option selected>Shipped</option>
                            <option>Delivered</option>
                            <option>Cancelled</option>
                        </select>
                    </td>
                    <td><span class="status-badge badge-shipped">Shipped</span></td>
                    <td>2025-06-06</td>
                    <td>
                        <button class="update-btn" disabled>Update</button>
                        <button class="delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Mary Lee</td>
                    <td>
                        <ul class="order-details-list">
                            <li><b>Bread</b> x2 ($10)</li>
                        </ul>
                        <div style="margin-top:0.5rem; font-size:0.95em; color:#888;">
                            Address: 789 Pine Rd, Village<br>
                            Email: mary@example.com<br>
                            Phone: 555-123-4567
                        </div>
                    </td>
                    <td>$10</td>
                    <td>
                        <select class="status-select" disabled>
                            <option>Pending</option>
                            <option selected>Processing</option>
                            <option>Shipped</option>
                            <option>Delivered</option>
                            <option>Cancelled</option>
                        </select>
                    </td>
                    <td><span class="status-badge badge-processing">Processing</span></td>
                    <td>2025-06-05</td>
                    <td>
                        <button class="update-btn" disabled>Update</button>
                        <button class="delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Alex Green</td>
                    <td>
                        <ul class="order-details-list">
                            <li><b>Red Velvet Cake</b> x1 ($20)</li>
                        </ul>
                        <div style="margin-top:0.5rem; font-size:0.95em; color:#888;">
                            Address: 321 Maple St, City<br>
                            Email: alex@example.com<br>
                            Phone: 222-333-4444
                        </div>
                    </td>
                    <td>$20</td>
                    <td>
                        <select class="status-select" disabled>
                            <option>Pending</option>
                            <option>Processing</option>
                            <option>Shipped</option>
                            <option selected>Delivered</option>
                            <option>Cancelled</option>
                        </select>
                    </td>
                    <td><span class="status-badge badge-delivered">Delivered</span></td>
                    <td>2025-06-04</td>
                    <td>
                        <button class="update-btn" disabled>Update</button>
                        <button class="delete-btn">Delete</button>
                    </td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Sam Brown</td>
                    <td>
                        <ul class="order-details-list">
                            <li><b>Cookies</b> x5 ($12)</li>
                        </ul>
                        <div style="margin-top:0.5rem; font-size:0.95em; color:#888;">
                            Address: 654 Elm St, Town<br>
                            Email: sam@example.com<br>
                            Phone: 777-888-9999
                        </div>
                    </td>
                    <td>$12</td>
                    <td>
                        <select class="status-select" disabled>
                            <option>Pending</option>
                            <option>Processing</option>
                            <option>Shipped</option>
                            <option>Delivered</option>
                            <option selected>Cancelled</option>
                        </select>
                    </td>
                    <td><span class="status-badge badge-cancelled">Cancelled</span></td>
                    <td>2025-06-03</td>
                    <td>
                        <button class="update-btn" disabled>Update</button>
                        <button class="delete-btn">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
