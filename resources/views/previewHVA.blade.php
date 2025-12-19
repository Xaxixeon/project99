<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <title>Board Tugas - Preview</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    body {
      background: #f5f6f8;
      color: #222;
      min-height: 100vh;
      display: flex;
    }

    /* SURFACE CONTROL (TOP NAV) */
    #surface-control {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 58px;
      background: #e9ecf2;
      border-bottom: 1px solid #d7dbe3;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 20px;
      z-index: 20;
    }

    #surface-control .left-control {
      display: flex;
      align-items: center;
      gap: 12px;
      cursor: pointer;
      background: transparent;
      border: none;
      padding: 0;
    }

    #surface-control .logo {
      height: 36px;
      width: 36px;
      border-radius: 10px;
      background: linear-gradient(135deg, #1a7bff, #52c41a);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-weight: 700;
      font-size: 14px;
    }

    #surface-control .workspace-name {
      font-size: 14px;
      font-weight: 600;
      color: #1f2937;
    }

    #surface-control .right-control {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    #surface-control .icon-btn {
      background: transparent;
      border: none;
      cursor: pointer;
      font-size: 20px;
      height: 36px;
      width: 36px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: background 0.15s ease;
    }

    #surface-control .icon-btn:hover {
      background: #f2f4f7;
    }

    #surface-control .notif-badge {
      position: relative;
    }

    #surface-control .notif-badge::after {
      content: attr(data-count);
      position: absolute;
      top: 4px;
      right: 4px;
      background: #ef4444;
      color: #fff;
      border-radius: 999px;
      padding: 2px 6px;
      font-size: 10px;
      font-weight: 700;
      line-height: 1;
      transform: scale(1);
      transform-origin: top right;
      transition: transform 0.2s ease, opacity 0.2s ease;
    }

    #surface-control .notif-badge[data-count="0"]::after {
      opacity: 0;
      transform: scale(0.8);
    }

    #surface-control .nine-dots {
      display: grid;
      grid-template-columns: repeat(3, 5px);
      grid-gap: 4px;
    }

    #surface-control .nine-dots span {
      width: 5px;
      height: 5px;
      background: #444;
      border-radius: 2px;
      display: block;
    }

    #surface-control .profile-photo {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      cursor: pointer;
      object-fit: cover;
      border: 1px solid #e5e7eb;
      transition: box-shadow 0.15s ease, transform 0.15s ease;
    }

    #surface-control .profile-photo:hover {
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
      transform: translateY(-1px);
    }

    /* Dropdowns */
    .dropdown {
      position: absolute;
      top: 110%;
      right: 0;
      min-width: 240px;
      background: #1f1f1f;
      border: 1px solid rgba(255, 255, 255, 0.06);
      border-radius: 16px;
      box-shadow: 0 18px 48px rgba(0, 0, 0, 0.2);
      padding: 10px;
      opacity: 0;
      transform: translateY(-6px) scale(0.98);
      pointer-events: none;
      transition: opacity 0.18s ease, transform 0.18s ease;
      z-index: 25;
    }

    .dropdown.show {
      opacity: 1;
      transform: translateY(0) scale(1);
      pointer-events: auto;
    }

    .dropdown hr {
      border: none;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
      margin: 10px 0;
    }

    /* Widget grid */
    .widget-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 12px 10px;
    }

    .widget-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      color: #e5e7eb;
      text-decoration: none;
      padding: 8px 6px;
      border-radius: 10px;
      transition: background 0.15s ease, transform 0.15s ease;
      font-size: 12px;
      text-align: center;
    }

    .widget-item:hover {
      background: rgba(255, 255, 255, 0.05);
      transform: translateY(-1px);
    }

    .widget-icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: grid;
      place-items: center;
      font-size: 22px;
      color: #fff;
    }

    /* Profile card */
    .profile-card {
      color: #e5e7eb;
    }

    .profile-card .identity {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 10px;
    }

    .profile-card .identity .avatar-lg {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid rgba(255, 255, 255, 0.12);
    }

    .profile-card .identity .meta {
      line-height: 1.4;
    }

    .profile-card .identity .meta .email {
      font-size: 13px;
      color: #cbd5e1;
    }

    .profile-card .identity .meta .name {
      font-weight: 700;
      font-size: 16px;
      color: #f8fafc;
    }

    .profile-card .identity .meta .role {
      font-size: 12px;
      color: #9ca3af;
    }

    .profile-card .primary-btn {
      width: 100%;
      border: none;
      border-radius: 12px;
      background: #2563eb;
      color: #fff;
      font-weight: 600;
      padding: 10px;
      cursor: pointer;
      margin-bottom: 12px;
      transition: background 0.15s ease, transform 0.15s ease;
    }

    .profile-card .primary-btn:hover {
      background: #1d4ed8;
      transform: translateY(-1px);
    }

    .profile-card .actions {
      display: flex;
      gap: 10px;
      margin-bottom: 8px;
    }

    .profile-card .actions button {
      flex: 1;
      border: 1px solid rgba(255, 255, 255, 0.12);
      background: rgba(255, 255, 255, 0.04);
      color: #e5e7eb;
      border-radius: 12px;
      padding: 10px;
      cursor: pointer;
      transition: background 0.15s ease, transform 0.15s ease;
    }

    .profile-card .actions button:hover {
      background: rgba(255, 255, 255, 0.08);
      transform: translateY(-1px);
    }

    .profile-card .link-row {
      display: flex;
      justify-content: space-between;
      font-size: 11px;
      color: #9ca3af;
    }

    /* Theme picker */
    .theme-card {
      margin-top: 10px;
      padding: 10px;
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.04);
      color: #e5e7eb;
    }

    .theme-card h4 {
      font-size: 13px;
      margin-bottom: 8px;
      color: #f8fafc;
    }

    .theme-options {
      display: flex;
      gap: 8px;
    }

    .theme-pill {
      border: 1px solid rgba(255, 255, 255, 0.12);
      padding: 8px 12px;
      border-radius: 10px;
      cursor: pointer;
      font-size: 12px;
      color: #e5e7eb;
      background: rgba(255, 255, 255, 0.05);
      transition: background 0.15s ease, transform 0.15s ease, border-color 0.15s ease;
    }

    .theme-pill.active {
      border-color: #2563eb;
      background: rgba(37, 99, 235, 0.18);
      color: #fff;
    }

    .theme-pill:hover {
      background: rgba(255, 255, 255, 0.08);
      transform: translateY(-1px);
    }

    /* Themes */
    body.theme-light {
      background: #f8fafc;
      color: #0f172a;
    }

    body.theme-dark {
      background: #0f172a;
      color: #e2e8f0;
    }

    body.theme-ocean {
      background: radial-gradient(circle at 20% 20%, #1e3a8a, #0f172a 35%), #0b1021;
      color: #e0f2fe;
    }

    /* Themed surfaces */
    body.theme-dark #surface-control,
    body.theme-ocean #surface-control {
      background: #1f2937;
      border-color: rgba(255, 255, 255, 0.08);
    }

    body.theme-dark .sidebar,
    body.theme-ocean .sidebar {
      background: #111827;
      border-color: rgba(255, 255, 255, 0.08);
      color: #e2e8f0;
    }

    body.theme-dark .topbar,
    body.theme-ocean .topbar,
    body.theme-dark .group,
    body.theme-ocean .group {
      background: #111827;
      border-color: rgba(255, 255, 255, 0.1);
      color: #e2e8f0;
    }

    body.theme-dark .board-table thead,
    body.theme-ocean .board-table thead {
      background: #0f172a;
    }

    body.theme-dark .btn,
    body.theme-ocean .btn {
      background: rgba(255, 255, 255, 0.08);
      color: #e2e8f0;
    }

    body.theme-dark .btn-primary,
    body.theme-ocean .btn-primary {
      background: #2563eb;
      color: #fff;
    }

    /* Responsive navbar sizing */
    @media (max-width: 1024px) {
      #surface-control {
        height: 56px;
        padding: 0 16px;
      }
    }

    @media (max-width: 768px) {
      #surface-control {
        height: 54px;
        padding: 0 14px;
      }
    }

    @media (max-width: 640px) {
      #surface-control {
        height: 52px;
        padding: 0 12px;
      }
    }

    /* CONTENT BELOW NAV */
    .page-wrap {
      flex: 1;
      display: flex;
      padding-top: 60px;
    }

    .sidebar {
      width: 230px;
      background: #ffffff;
      border-right: 1px solid #e1e3e8;
      padding: 16px 12px;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .sidebar-logo {
      font-weight: 700;
      font-size: 18px;
      margin-bottom: 8px;
    }

    .panel-link {
      background: transparent;
      border: none;
      padding: 0;
      margin: 0;
      font: inherit;
      color: inherit;
      cursor: pointer;
    }

    .sidebar-section-title {
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      color: #999;
      margin-bottom: 4px;
      padding: 0 4px;
    }

    .sidebar-item {
      padding: 8px 10px;
      border-radius: 8px;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
      color: #444;
      text-decoration: none;
    }

    .sidebar-item.active {
      background: #e5f3ff;
      color: #1a7bff;
      font-weight: 600;
    }

    .sidebar-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #1a7bff;
    }

    .sidebar-spacer {
      flex: 1;
    }

    .main {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .topbar {
      height: 56px;
      background: #ffffff;
      border-bottom: 1px solid #e1e3e8;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 24px;
    }

    .topbar-left {
      display: flex;
      flex-direction: column;
    }

    .board-title {
      font-size: 20px;
      font-weight: 600;
    }

    .topbar-subtitle {
      font-size: 13px;
      color: #888;
    }

    .topbar-actions {
      display: flex;
      gap: 8px;
      align-items: center;
    }

    .btn {
      border-radius: 16px;
      border: none;
      padding: 8px 14px;
      font-size: 13px;
      cursor: pointer;
      background: #f2f4f7;
    }

    .btn-primary {
      background: #1a7bff;
      color: white;
      border-radius: 18px;
      padding: 8px 18px;
      font-weight: 500;
    }

    .btn-icon {
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

    .board-container {
      padding: 16px 24px 24px;
      overflow: auto;
    }

    .board-header-actions {
      display: flex;
      gap: 8px;
      margin-bottom: 12px;
      flex-wrap: wrap;
    }

    .board-header-actions .btn {
      border-radius: 14px;
      padding: 6px 12px;
      font-size: 12px;
    }

    .group {
      margin-top: 16px;
      border-radius: 8px;
      overflow: hidden;
      background: #ffffff;
      border: 1px solid #e1e3e8;
    }

    .group-header {
      display: flex;
      align-items: center;
      padding: 8px 12px;
      background: #fafbfc;
      border-bottom: 1px solid #e1e3e8;
      font-size: 13px;
      font-weight: 600;
      gap: 8px;
    }

    .group-color {
      width: 6px;
      height: 16px;
      border-radius: 999px;
    }

    .group-color.yellow {
      background: #fadb14;
    }

    .group-color.blue {
      background: #40a9ff;
    }

    .group-color.green {
      background: #73d13d;
    }

    table.board-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 13px;
    }

    .board-table thead {
      background: #fafbfc;
    }

    .board-table th,
    .board-table td {
      padding: 10px 12px;
      border-bottom: 1px solid #f0f1f4;
      text-align: left;
      white-space: nowrap;
    }

    .board-table th {
      font-size: 12px;
      color: #888;
      font-weight: 600;
    }

    .board-table tbody tr:hover {
      background: #f7faff;
    }

    .checkbox-cell {
      width: 32px;
    }

    .checkbox {
      width: 16px;
      height: 16px;
      border-radius: 4px;
      border: 1px solid #c8ccd5;
      display: inline-block;
    }

    .owner-avatar {
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

    .pill {
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

    .status-working {
      background: #f59f00;
    }

    .status-done {
      background: #52c41a;
    }

    .status-stuck {
      background: #ff4d4f;
    }

    .priority-low {
      background: #40a9ff;
    }

    .priority-medium {
      background: #9254de;
    }

    .priority-high {
      background: #fa541c;
    }

    .timeline-pill {
      background: #1a7bff;
      min-width: 90px;
    }

    .files-pill {
      border-radius: 6px;
      background: #f2f4f7;
      color: #666;
      padding: 4px 8px;
      font-size: 11px;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .add-row {
      padding: 8px 12px;
      font-size: 13px;
      color: #1a7bff;
      cursor: pointer;
    }

    .add-row:hover {
      background: #f7faff;
    }

    @media (max-width: 900px) {
      #surface-control {
        padding: 0 12px;
        height: 56px;
      }

        #surface-control .workspace-name {
          display: none;
        }

        #surface-control .right-control {
          gap: 10px;
        }

      .sidebar {
        display: none;
      }

      .topbar {
        padding: 0 12px;
      }

      .board-container {
        padding: 12px;
      }

      .board-table {
        font-size: 12px;
      }

      .board-table th,
      .board-table td {
        padding: 6px 8px;
      }
    }
  </style>
</head>

<body>
  @php
    $staff = auth('staff')->user();
    $roleName = $staff?->roles()->first()->name ?? 'Staff';
    $panelLabel = trim($roleName . ' Panel');
    $panelInitial = strtoupper(substr($roleName, 0, 2));
    $dashboardUrl = route('staff.dashboard');
  @endphp
  <div id="surface-control">
    <button class="left-control" id="workspace-panel-btn" type="button">
      <div class="logo">{{ $panelInitial }}</div>
      <span class="workspace-name">{{ $panelLabel }}</span>
    </button>
    <div class="right-control">
      <button class="icon-btn notif-badge" id="notif-btn" title="Notifikasi" data-count="3">🔔</button>
      <button class="icon-btn" id="market-btn" title="Marketplace">🛒</button>
      <div class="relative" style="position: relative;">
        <button class="icon-btn" id="widget-btn" title="Widget">
        <div class="nine-dots">
          <span></span><span></span><span></span>
          <span></span><span></span><span></span>
          <span></span><span></span><span></span>
        </div>
      </button>
        <div class="dropdown" id="widget-dropdown">
          <div class="widget-grid">
            <a class="widget-item" href="{{ $dashboardUrl }}">
              <span class="widget-icon" style="background:#34a853;">G</span>
              <span>Dashboard Staff</span>
            </a>
            <a class="widget-item" href="/drive">
              <span class="widget-icon" style="background:#4285f4;">D</span>
              <span>Drive</span>
            </a>
            <a class="widget-item" href="/apps">
              <span class="widget-icon" style="background:#fbbc05;">A</span>
              <span>Apps</span>
            </a>
            <a class="widget-item" href="/mail">
              <span class="widget-icon" style="background:#ea4335;">M</span>
              <span>Mail</span>
            </a>
            <a class="widget-item" href="/tasks">
              <span class="widget-icon" style="background:#9c27b0;">T</span>
              <span>Tasks</span>
            </a>
            <a class="widget-item" href="/calendar">
              <span class="widget-icon" style="background:#0f9d58;">C</span>
              <span>Calendar</span>
            </a>
            <a class="widget-item" href="/notes">
              <span class="widget-icon" style="background:#f97316;">N</span>
              <span>Notes</span>
            </a>
            <a class="widget-item" href="/reports">
              <span class="widget-icon" style="background:#2563eb;">R</span>
              <span>Reports</span>
            </a>
            <a class="widget-item" href="/settings">
              <span class="widget-icon" style="background:#6b7280;">⚙</span>
              <span>Settings</span>
            </a>
          </div>
          <div class="theme-card">
            <h4>Theme</h4>
            <div class="theme-options">
              <button class="theme-pill" data-theme="light">Light</button>
              <button class="theme-pill active" data-theme="dark">Dark</button>
              <button class="theme-pill" data-theme="ocean">Ocean</button>
            </div>
          </div>
        </div>
      </div>
      <div class="relative" style="position: relative;">
        <img src="https://ui-avatars.com/api/?name=User&background=1a7bff&color=fff" alt="User"
          class="profile-photo" id="profile-btn" title="Profil">
        <div class="dropdown" id="profile-dropdown">
          <div class="profile-card">
            <div class="identity">
              <img class="avatar-lg" src="https://ui-avatars.com/api/?name=User&background=1a7bff&color=fff" alt="User">
              <div class="meta">
                <div class="email">user@example.com</div>
                <div class="name">Halo, User</div>
                <div class="role">Role: Administrator</div>
              </div>
            </div>
            <button class="primary-btn" onclick="safeNavigate('/account')">Kelola Akun</button>
            <div class="actions">
              <button onclick="safeNavigate('/account/add')">+ Tambahkan akun</button>
              <button onclick="safeNavigate('/logout')">Logout</button>
            </div>
            <div class="link-row">
              <span>Privasi</span>
              <span>Syarat</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="page-wrap">
    <aside class="sidebar">
      <div>
        <div class="sidebar-logo">
          <button class="panel-link" type="button" data-panel-link="{{ $dashboardUrl }}">{{ $panelLabel }}</button>
        </div>
        <a class="sidebar-item active" href="{{ $dashboardUrl }}">
          <span class="sidebar-dot"></span>
          <span>Dashboard</span>
        </a>
        <div class="sidebar-section-title">Boards</div>
        <a class="sidebar-item" href="#">
          <span class="sidebar-dot"></span>
          <span>Gose task</span>
        </a>
        <a class="sidebar-item" href="#">
          <span>Dashboard and reporting</span>
        </a>
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
          <button class="btn-primary">New task</button>
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
          <div class="add-row" id="add-task-1">+ Add task</div>
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
          <div class="add-row" id="add-task-2">+ Add task</div>
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
          <div class="add-row" id="add-task-3">+ Add task</div>
        </div>
      </section>
    </main>
  </div>

  <script>
    const surface = document.querySelector("#surface-control");
    const staffDashboardUrl = "{{ $dashboardUrl }}";

    const safeNavigate = (url) => window.location.assign(url);

    document.querySelector("#workspace-panel-btn")?.addEventListener("click", () => safeNavigate(staffDashboardUrl));
    document.querySelectorAll("[data-panel-link]")?.forEach((btn) => {
      btn.addEventListener("click", () => safeNavigate(staffDashboardUrl));
    });
    document.querySelector("#notif-btn")?.addEventListener("click", () => {
      // placeholder for notifications; no alert
      closeAllDropdowns();
    });
    document.querySelector("#market-btn")?.addEventListener("click", () => safeNavigate("/marketplace"));

    const makeRow = () => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td class="checkbox-cell"><span class="checkbox"></span></td>
        <td>Task baru</td>
        <td><div class="owner-avatar">U</div></td>
        <td><span class="pill status-working">Working on it</span></td>
        <td>-</td>
        <td><span class="pill priority-low">Low</span></td>
        <td>-</td>
        <td>$0</td>
        <td>0 files</td>
        <td>-</td>
        <td>baru saja</td>
      `;
      return tr;
    };

    const attachAddHandler = (id) => {
      const trigger = document.getElementById(id);
      if (!trigger) return;
      const table = trigger.previousElementSibling?.querySelector("tbody");
      trigger.addEventListener("click", () => {
        if (!table) return;
        table.appendChild(makeRow());
      });
    };

    attachAddHandler("add-task-1");
    attachAddHandler("add-task-2");
    attachAddHandler("add-task-3");

    // Dropdown handling
    const widgetBtn = document.querySelector("#widget-btn");
    const widgetDropdown = document.querySelector("#widget-dropdown");
    const profileBtn = document.querySelector("#profile-btn");
    const profileDropdown = document.querySelector("#profile-dropdown");

    const closeAllDropdowns = () => {
      widgetDropdown?.classList.remove("show");
      profileDropdown?.classList.remove("show");
    };

    widgetBtn?.addEventListener("click", (e) => {
      e.stopPropagation();
      const isOpen = widgetDropdown.classList.contains("show");
      closeAllDropdowns();
      if (!isOpen) widgetDropdown.classList.add("show");
    });

    profileBtn?.addEventListener("click", (e) => {
      e.stopPropagation();
      const isOpen = profileDropdown.classList.contains("show");
      closeAllDropdowns();
      if (!isOpen) profileDropdown.classList.add("show");
    });

    document.addEventListener("click", (e) => {
      if (!e.target.closest(".dropdown") && !e.target.closest(".icon-btn") && e.target !== profileBtn) {
        closeAllDropdowns();
      }
    });

    // Theme picker
    const body = document.body;
    const themePills = document.querySelectorAll(".theme-pill");
    const setTheme = (theme) => {
      body.classList.remove("theme-light", "theme-dark", "theme-ocean");
      body.classList.add(`theme-${theme}`);
      themePills.forEach((pill) => {
        pill.classList.toggle("active", pill.dataset.theme === theme);
      });
    };
    themePills.forEach((pill) => {
      pill.addEventListener("click", () => setTheme(pill.dataset.theme));
    });

    // Initialize default theme
    setTheme("dark");
  </script>
</body>

</html>
