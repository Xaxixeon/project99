{{-- resources/views/admin/staff/ui-preview.blade.php --}}
<x-admin.layout>
    <style>
        .board-preview,
        .board-preview * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .board-preview {
            background: #f5f6f8;
            color: #222;
            min-height: calc(100vh - 120px);
            display: flex;
            padding: 16px;
            border-radius: 18px;
        }

        .board-preview .sidebar {
            width: 230px;
            background: #ffffff;
            border: 1px solid #e1e3e8;
            border-radius: 12px;
            padding: 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        }

        .board-preview .sidebar-logo {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 8px;
        }

        .board-preview .sidebar-section-title {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #999;
            margin-bottom: 4px;
            padding: 0 4px;
        }

        .board-preview .sidebar-item {
            padding: 8px 10px;
            border-radius: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: #444;
        }

        .board-preview .sidebar-item.active {
            background: #e5f3ff;
            color: #1a7bff;
            font-weight: 600;
        }

        .board-preview .sidebar-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #1a7bff;
        }

        .board-preview .sidebar-spacer {
            flex: 1;
        }

        .board-preview .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            margin-left: 16px;
        }

        .board-preview .topbar {
            height: 56px;
            background: #ffffff;
            border: 1px solid #e1e3e8;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        }

        .board-preview .topbar-left {
            display: flex;
            flex-direction: column;
        }

        .board-preview .board-title {
            font-size: 20px;
            font-weight: 600;
        }

        .board-preview .topbar-subtitle {
            font-size: 13px;
            color: #888;
        }

        .board-preview .topbar-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .board-preview .btn {
            border-radius: 16px;
            border: none;
            padding: 8px 14px;
            font-size: 13px;
            cursor: pointer;
            background: #f2f4f7;
        }

        .board-preview .btn-primary {
            background: #1a7bff;
            color: white;
            border-radius: 18px;
            padding: 8px 18px;
            font-weight: 500;
        }

        .board-preview .btn-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f2f4f7;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .board-preview .board-container {
            padding: 16px 0 0 0;
            overflow: auto;
        }

        .board-preview .board-header-actions {
            display: flex;
            gap: 8px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .board-preview .board-header-actions .btn {
            border-radius: 14px;
            padding: 6px 12px;
            font-size: 12px;
        }

        .board-preview .group {
            margin-top: 16px;
            border-radius: 8px;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #e1e3e8;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        }

        .board-preview .group-header {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background: #fafbfc;
            border-bottom: 1px solid #e1e3e8;
            font-size: 13px;
            font-weight: 600;
            gap: 8px;
        }

        .board-preview .group-color {
            width: 6px;
            height: 16px;
            border-radius: 999px;
        }

        .board-preview .group-color.yellow {
            background: #fadb14;
        }

        .board-preview .group-color.blue {
            background: #40a9ff;
        }

        .board-preview .group-color.green {
            background: #73d13d;
        }

        .board-preview table.board-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .board-preview .board-table thead {
            background: #fafbfc;
        }

        .board-preview .board-table th,
        .board-preview .board-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #f0f1f4;
            text-align: left;
            white-space: nowrap;
        }

        .board-preview .board-table th {
            font-size: 12px;
            color: #888;
            font-weight: 600;
        }

        .board-preview .board-table tbody tr:hover {
            background: #f7faff;
        }

        .board-preview .checkbox-cell {
            width: 32px;
        }

        .board-preview .checkbox {
            width: 16px;
            height: 16px;
            border-radius: 4px;
            border: 1px solid #c8ccd5;
            display: inline-block;
        }

        .board-preview .owner-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a7bff, #52c41a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: 600;
        }

        .board-preview .pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
            color: #fff;
            min-width: 80px;
        }

        .board-preview .status-working {
            background: #f59f00;
        }

        .board-preview .status-done {
            background: #52c41a;
        }

        .board-preview .status-stuck {
            background: #ff4d4f;
        }

        .board-preview .priority-low {
            background: #40a9ff;
        }

        .board-preview .priority-medium {
            background: #9254de;
        }

        .board-preview .priority-high {
            background: #fa541c;
        }

        .board-preview .timeline-pill {
            background: #1a7bff;
            min-width: 90px;
        }

        .board-preview .files-pill {
            border-radius: 6px;
            background: #f2f4f7;
            color: #666;
            padding: 4px 8px;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .board-preview .add-row {
            padding: 8px 12px;
            font-size: 13px;
            color: #1a7bff;
            cursor: pointer;
        }

        .board-preview .add-row:hover {
            background: #f7faff;
        }

        @media (max-width: 900px) {
            .board-preview .sidebar {
                display: none;
            }

            .board-preview .topbar {
                padding: 0 12px;
            }

            .board-preview .board-container {
                padding: 12px 0 0 0;
            }

            .board-preview .board-table {
                font-size: 12px;
            }

            .board-preview .board-table th,
            .board-preview .board-table td {
                padding: 6px 8px;
            }
        }
    </style>

    <div class="board-preview">
        <aside class="sidebar">
            <div>
                <div class="sidebar-logo">Main workspace</div>
                <div class="sidebar-section-title">Boards</div>
                <div class="sidebar-item active">
                    <span class="sidebar-dot"></span>
                    <span>Gose task</span>
                </div>
                <div class="sidebar-item">
                    <span>Dashboard and reporting</span>
                </div>
            </div>
            <div class="sidebar-spacer"></div>
            <div class="sidebar-section-title">Help</div>
            <div class="sidebar-item">Support</div>
        </aside>

        <main class="main">
            <header class="topbar">
                <div class="topbar-left">
                    <span class="board-title">Gose task</span>
                    <span class="topbar-subtitle">Main table · Timeline</span>
                </div>
                <div class="topbar-actions">
                    <button class="btn">Calendar</button>
                    <button class="btn btn-primary">New task</button>
                    <button class="btn-icon">⋯</button>
                </div>
            </header>

            <section class="board-container">
                <div class="board-header-actions">
                    <button class="btn">Search</button>
                    <button class="btn">Person</button>
                    <button class="btn">Filter</button>
                    <button class="btn">Sort</button>
                    <button class="btn">Group by</button>
                </div>

                <div class="group">
                    <div class="group-header">
                        <div class="group-color yellow"></div>
                        <span>New Group</span>
                    </div>
                    <table class="board-table">
                        <thead>
                            <tr>
                                <th class="checkbox-cell"></th>
                                <th>Task</th>
                                <th>Owner</th>
                                <th>Status</th>
                                <th>Due date</th>
                                <th>Priority</th>
                                <th>Notes</th>
                                <th>Budget</th>
                                <th>Files</th>
                                <th>Timeline</th>
                                <th>Last updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="checkbox-cell"><span class="checkbox"></span></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>-</td>
                                <td></td>
                                <td></td>
                                <td>$0</td>
                                <td>0 files</td>
                                <td>-</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="add-row">+ Add task</div>
                </div>

                <div class="group">
                    <div class="group-header">
                        <div class="group-color blue"></div>
                        <span>To-Do</span>
                    </div>
                    <table class="board-table">
                        <thead>
                            <tr>
                                <th class="checkbox-cell"></th>
                                <th>Task</th>
                                <th>Owner</th>
                                <th>Status</th>
                                <th>Due date</th>
                                <th>Priority</th>
                                <th>Notes</th>
                                <th>Budget</th>
                                <th>Files</th>
                                <th>Timeline</th>
                                <th>Last updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="checkbox-cell"><span class="checkbox"></span></td>
                                <td>Desain</td>
                                <td>
                                    <div class="owner-avatar">A</div>
                                </td>
                                <td><span class="pill status-working">Working on it</span></td>
                                <td>Dec 7</td>
                                <td><span class="pill priority-low">Low</span></td>
                                <td>Action items</td>
                                <td>$100</td>
                                <td><span class="files-pill">📄 File</span></td>
                                <td><span class="pill timeline-pill">Dec 7 - 8</span></td>
                                <td>4 minutes ago</td>
                            </tr>
                            <tr>
                                <td class="checkbox-cell"><span class="checkbox"></span></td>
                                <td>Cetak</td>
                                <td>
                                    <div class="owner-avatar">B</div>
                                </td>
                                <td><span class="pill status-done">Done</span></td>
                                <td>Dec 8</td>
                                <td><span class="pill priority-high">High</span></td>
                                <td>Meeting notes</td>
                                <td>$1,000</td>
                                <td><span class="files-pill">📄 File</span></td>
                                <td><span class="pill timeline-pill">Dec 9 - 10</span></td>
                                <td>4 minutes ago</td>
                            </tr>
                            <tr>
                                <td class="checkbox-cell"><span class="checkbox"></span></td>
                                <td>Finishing</td>
                                <td>
                                    <div class="owner-avatar">C</div>
                                </td>
                                <td><span class="pill status-stuck">Stuck</span></td>
                                <td>Dec 9</td>
                                <td><span class="pill priority-medium">Medium</span></td>
                                <td>Other</td>
                                <td>$500</td>
                                <td>—</td>
                                <td><span class="pill timeline-pill">Dec 11 - 12</span></td>
                                <td>4 minutes ago</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="add-row">+ Add task</div>
                </div>

                <div class="group">
                    <div class="group-header">
                        <div class="group-color green"></div>
                        <span>Completed</span>
                    </div>
                    <table class="board-table">
                        <thead>
                            <tr>
                                <th class="checkbox-cell"></th>
                                <th>Task</th>
                                <th>Owner</th>
                                <th>Status</th>
                                <th>Due date</th>
                                <th>Priority</th>
                                <th>Notes</th>
                                <th>Budget</th>
                                <th>Files</th>
                                <th>Timeline</th>
                                <th>Last updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="checkbox-cell"><span class="checkbox"></span></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>-</td>
                                <td></td>
                                <td></td>
                                <td>$0</td>
                                <td>0 files</td>
                                <td>-</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="add-row">+ Add task</div>
                </div>
            </section>
        </main>
    </div>
</x-admin.layout>
