<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.2;
            color: #333;
            margin: 0;
            padding: 4px 4px 12px 4px;
        }

        /* Make header even more compact */
        .header {
            text-align: center;
            margin-bottom: 4px;
            border-bottom: 1px solid #007bff;
            padding-bottom: 2px;
        }

        .header h1 {
            color: #007bff;
            margin: 0 0 2px 0;
            font-size: 13px;
        }

        .header .subtitle {
            color: #666;
            margin: 0;
            font-size: 8px;
        }
        
        .report-info {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .report-info-row {
            display: table-row;
        }
        
        .report-info-cell {
            display: table-cell;
            padding: 8px 12px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }
        
        .report-info-cell:first-child {
            background-color: #f8f9fa;
            font-weight: bold;
            width: 25%;
        }
        
        .filters {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        
        .filters h3 {
            margin: 0 0 10px 0;
            color: #007bff;
            font-size: 16px;
        }
        
        .filter-grid {
            display: table;
            width: 100%;
        }
        
        .filter-row {
            display: table-row;
        }
        
        .filter-cell {
            display: table-cell;
            padding: 4px 10px 4px 0;
            width: 50%;
        }
        
        .filter-label {
            font-weight: bold;
            color: #555;
        }
        
        .filter-value {
            color: #333;
        }
        
        .summary {
            background-color: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        
        .summary h3 {
            margin: 0 0 15px 0;
            color: #007bff;
            font-size: 16px;
        }
        
        .summary-grid {
            display: table;
            width: 100%;
        }
        
        .summary-row {
            display: table-row;
        }
        
        .summary-cell {
            display: table-cell;
            padding: 5px 15px 5px 0;
            width: 33.333%;
        }
        
        .summary-label {
            font-weight: bold;
            color: #555;
            font-size: 11px;
        }
        
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #007bff;
        }
        
        .tasks-section h3 {
            color: #007bff;
            margin: 0 0 15px 0;
            font-size: 18px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .task-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .task-table th {
            background-color: #007bff;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        
        .task-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            vertical-align: top;
            font-size: 10px;
        }
        
        .task-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .task-table tr.overdue {
            background-color: #ffe6e6;
        }
        
        .task-table tr.due-today {
            background-color: #fff3cd;
        }
        
        .priority-high {
            background-color: #dc3545;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .priority-medium {
            background-color: #ffc107;
            color: #212529;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .priority-low {
            background-color: #28a745;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .status-completed {
            background-color: #28a745;
            color: white;
        }
        
        .status-in-progress {
            background-color: #007bff;
            color: white;
        }
        
        .status-todo {
            background-color: #6c757d;
            color: white;
        }
        
        /* Footer for printed PDF */
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0;
            right: 0;
            height: 50px;
            text-align: center;
            padding: 8px 10px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 10px;
        }

        @page {
            margin: 40px 30px 70px 30px; /* leave room for footer */
        }

        .pagenum:before {
            content: counter(page);
        }
        
        .no-tasks {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        
        .task-description {
            max-width: 200px;
            word-wrap: break-word;
        }
        
        .assignee-list {
            font-size: 9px;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <?php
        // Try to find a company logo in public/images
        $logoPath = public_path('images/company-logo.png');
        $logoData = null;
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $data = file_get_contents($logoPath);
            $logoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // Company branding defaults (edit if you have real data)
        $companyName = config('app.company_name', 'Infoglobal Innovator');
        $companyAddress = config('app.company_address', 'No 550/1H, Kandy Road, Malwatts, Sri Lanka');
        $companyContact = config('app.company_contact', '+94 11 1234 567');
        $companyEmail = config('app.company_email', 'info@infoglobal.lk');
    ?>

    <!-- Branded Header -->
    <div style="display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #007bff;padding-bottom:2px;margin-bottom:4px">
        <div style="display:flex;align-items:center;gap:4px">
            @if($logoData)
                <img src="{{ $logoData }}" alt="{{ $companyName }}" style="height:22px;object-fit:contain;" />
            @else
                <div style="height:22px;width:22px;background:#007bff;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:bold;border-radius:4px;font-size:9px;">
                    {{ strtoupper(substr($companyName,0,2)) }}
                </div>
            @endif
            <div>
                <h1 style="margin:0;color:#007bff;font-size:10px">{{ $companyName }}</h1>
                <div style="font-size:7px;color:#666">{{ $companyAddress }}</div>
            </div>
        </div>
        <div style="text-align:right;font-size:7px;color:#666">
            <div>Generated: {{ $generated_at->format('F j, Y \a\t g:i:s A T') }}</div>
            <div>By: {{ $generated_by->name }}</div>
        </div>
    </div>

    <!-- Report Information -->
    <div class="report-info">
        <div class="report-info-row">
            <div class="report-info-cell">Generated On:</div>
            <div class="report-info-cell">{{ $generated_at->format('F j, Y \a\t g:i:s A T') }}</div>
        </div>
        <div class="report-info-row">
            <div class="report-info-cell">Generated By:</div>
            <div class="report-info-cell">{{ $generated_by->name }} ({{ $generated_by->email }})</div>
        </div>
        <div class="report-info-row">
            <div class="report-info-cell">Total Tasks:</div>
            <div class="report-info-cell">{{ $total_tasks }}</div>
        </div>
    </div>

    <!-- Applied Filters -->
    <div class="filters">
        <h3>Applied Filters</h3>
        <div class="filter-grid">
            <div class="filter-row">
                <div class="filter-cell">
                    <span class="filter-label">Status:</span>
                    <span class="filter-value">{{ $filters['status'] ?: 'All' }}</span>
                </div>
                <div class="filter-cell">
                    <span class="filter-label">Priority:</span>
                    <span class="filter-value">{{ $filters['priority'] ?: 'All' }}</span>
                </div>
            </div>
            <div class="filter-row">
                <div class="filter-cell">
                    <span class="filter-label">Assignee:</span>
                    <span class="filter-value">{{ $filters['assignee_name'] ?: 'All' }}</span>
                </div>
                <div class="filter-cell">
                    <span class="filter-label">Date Range:</span>
                    <span class="filter-value">
                        @if($filters['date_from'] || $filters['date_to'])
                            {{ $filters['date_from'] ? \Carbon\Carbon::parse($filters['date_from'])->format('M j, Y') : 'Start' }}
                            to
                            {{ $filters['date_to'] ? \Carbon\Carbon::parse($filters['date_to'])->format('M j, Y') : 'End' }}
                        @else
                            All Dates
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="summary">
        <h3>Summary Statistics</h3>
        <div class="summary-grid">
            <div class="summary-row">
                <div class="summary-cell">
                    <div class="summary-label">COMPLETED</div>
                    <div class="summary-value">{{ $summary['completed_count'] }}</div>
                </div>
                <div class="summary-cell">
                    <div class="summary-label">PENDING</div>
                    <div class="summary-value">{{ $summary['pending_count'] }}</div>
                </div>
                <div class="summary-cell">
                    <div class="summary-label">OVERDUE</div>
                    <div class="summary-value">{{ $summary['overdue_count'] }}</div>
                </div>
            </div>
            <div class="summary-row">
                <div class="summary-cell">
                    <div class="summary-label">DUE TODAY</div>
                    <div class="summary-value">{{ $summary['due_today_count'] }}</div>
                </div>
                <div class="summary-cell">
                    <div class="summary-label">HIGH PRIORITY</div>
                    <div class="summary-value">{{ $summary['by_priority']['High'] ?? 0 }}</div>
                </div>
                <div class="summary-cell">
                    <div class="summary-label">TOTAL TASKS</div>
                    <div class="summary-value">{{ $total_tasks }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="tasks-section">
        <h3>Task Details</h3>
        
        @if($tasks->count() > 0)
            <table class="task-table">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 16%">Title</th>
                        <th style="width: 22%">Description</th>
                        <th style="width: 10%">Priority</th>
                        <th style="width: 10%">Status</th>
                        <th style="width: 12%">Due Date</th>
                        <th style="width: 13%">Assignees</th>
                        <th style="width: 12%">Creator</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $index => $task)
                        <tr class="{{ $task->isOverdue() ? 'overdue' : ($task->isDueToday() ? 'due-today' : '') }}">
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $task->title }}</strong></td>
                            <td class="task-description">
                                {{ Str::limit($task->description, 150) }}
                            </td>
                            <td>
                                <span class="priority-{{ strtolower($task->priority) }}">
                                    {{ $task->priority }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $task->status)) }}">
                                    {{ $task->status }}
                                </span>
                            </td>
                            <td>
                                @if($task->due_date)
                                    {{ $task->due_date->format('M j, Y') }}
                                    @if($task->isOverdue())
                                        <br><small style="color: #dc3545; font-weight: bold;">OVERDUE</small>
                                    @elseif($task->isDueToday())
                                        <br><small style="color: #ffc107; font-weight: bold;">DUE TODAY</small>
                                    @endif
                                @else
                                    <em>No due date</em>
                                @endif
                            </td>
                            <td class="assignee-list">
                                @if($task->assignedUser)
                                    <div>{{ $task->assignedUser->name }}</div>
                                @endif
                                @foreach($task->assignedUsers as $user)
                                    <div>{{ $user->name }}</div>
                                @endforeach
                                @if(!$task->assignedUser && $task->assignedUsers->count() === 0)
                                    <em>Unassigned</em>
                                @endif
                            </td>
                            <td>
                                {{ $task->user ? $task->user->name : 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-tasks">
                <h4>No tasks found matching the selected criteria.</h4>
                <p>Please adjust your filters and try again.</p>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <div style="display:flex;justify-content:space-between;align-items:center;max-width:1000px;margin:0 auto;padding:0 10px;">
            <div style="text-align:left;font-size:11px;color:#666">
                <div>{{ $companyName }}</div>
                <div>{{ $companyContact }} | {{ $companyEmail }}</div>
            </div>
            <div style="text-align:center;font-size:11px;color:#666">
                <div>This report was generated automatically by the Task Management System</div>
                <div>Report ID: {{ md5($generated_at . $generated_by->id) }}</div>
            </div>
            <div style="text-align:right;font-size:11px;color:#666">
                Page <span class="pagenum"></span>
            </div>
        </div>
    </div>
</body>
</html>