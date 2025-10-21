<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Task Reports') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Task Reports</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Generate detailed reports of your tasks with custom filters</p>
    </div>

    <!-- Report Form Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">
                <i class="fas fa-filter mr-2"></i>Report Filters
            </h2>

            <form action="{{ route('reports.generate') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Filter Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-tasks mr-1"></i>Status
                        </label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="" {{ old('status') === null || old('status') === '' ? 'selected' : '' }}>All Statuses</option>
                            <option value="Pending" {{ old('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ old('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ old('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <!-- Priority Filter -->
                    <div>
                        <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Priority
                        </label>
                        <select name="priority" id="priority" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="" {{ old('priority') === null || old('priority') === '' ? 'selected' : '' }}>All Priorities</option>
                            <option value="High" {{ old('priority') === 'High' ? 'selected' : '' }}>High</option>
                            <option value="Medium" {{ old('priority') === 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="Low" {{ old('priority') === 'Low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>

                    <!-- Assignee Filter -->
                    <div>
                        <label for="assignee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-user mr-1"></i>Assignee
                        </label>
                        <select name="assignee_id" id="assignee_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Assignees</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assignee_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-calendar-alt mr-1"></i>Date From
                        </label>
                        <input type="date" name="date_from" id="date_from" value="{{ old('date_from') }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-calendar-alt mr-1"></i>Date To
                        </label>
                        <input type="date" name="date_to" id="date_to" value="{{ old('date_to') }}" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <!-- Format Selection -->
                    <div>
                        <label for="format" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <i class="fas fa-file-export mr-1"></i>Output Format
                        </label>
                        <select name="format" id="format" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                            <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>PDF Export</option>
                            <option value="html" {{ old('format') == 'html' ? 'selected' : '' }}>HTML Preview</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" class="flex-1 sm:flex-none px-6 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-file-pdf mr-2"></i>Generate Report
                    </button>
                    
                    <button type="button" onclick="clearFilters()" class="flex-1 sm:flex-none px-6 py-3 bg-gray-500 text-white font-medium rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-eraser mr-2"></i>Clear Filters
                    </button>
                    
                    <!-- Download PDF Button -->
                    <button type="button" onclick="downloadExport('pdf')" class="flex-1 sm:flex-none px-6 py-3 bg-red-600 text-white font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-file-pdf mr-2"></i>Export PDF
                    </button>

                    <!-- Download Excel/CSV Button -->
                    <button type="button" onclick="downloadExport('csv')" class="flex-1 sm:flex-none px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                        <i class="fas fa-file-excel mr-2"></i>Export Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Help Section -->
    <div class="mt-8 bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">
            <i class="fas fa-info-circle mr-2"></i>Report Features
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800 dark:text-blue-200">
            <div>
                <h4 class="font-medium mb-2">Filters Available:</h4>
                <ul class="space-y-1">
                    <li>• Status (To Do, In Progress, Completed, etc.)</li>
                    <li>• Priority (High, Medium, Low)</li>
                    <li>• Assignee (Any user assigned to tasks)</li>
                    <li>• Date Range (Task creation date)</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium mb-2">Report Includes:</h4>
                <ul class="space-y-1">
                    <li>• Complete task details</li>
                    <li>• Summary statistics</li>
                    <li>• Overdue task highlighting</li>
                    <li>• Priority-based sorting</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function clearFilters() {
    document.getElementById('status').value = '';
    document.getElementById('priority').value = '';
    document.getElementById('assignee_id').value = '';
    document.getElementById('date_from').value = '';
    document.getElementById('date_to').value = '';
    document.getElementById('format').value = 'pdf';
}

// Date validation
document.getElementById('date_from').addEventListener('change', function() {
    const dateFrom = this.value;
    const dateTo = document.getElementById('date_to');
    if (dateFrom) {
        dateTo.min = dateFrom;
    }
});

document.getElementById('date_to').addEventListener('change', function() {
    const dateTo = this.value;
    const dateFrom = document.getElementById('date_from');
    if (dateTo) {
        dateFrom.max = dateTo;
    }
});
</script>

<script>
function downloadExcel() {
    // Backwards compatibility: call new exporter with csv
    downloadExport('csv');
}
</script>

<script>
function downloadExport(format) {
    // Gather current filter values
    const status = document.getElementById('status').value;
    const priority = document.getElementById('priority').value;
    const assignee_id = document.getElementById('assignee_id').value;
    const date_from = document.getElementById('date_from').value;
    const date_to = document.getElementById('date_to').value;

    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (priority) params.append('priority', priority);
    if (assignee_id) params.append('assignee_id', assignee_id);
    if (date_from) params.append('date_from', date_from);
    if (date_to) params.append('date_to', date_to);
    if (format) params.append('format', format);

    // Use the named route for export
    const baseUrl = "{{ route('reports.export') }}";
    const url = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;

    // Open in same tab to trigger download using session cookies
    window.location.href = url;
}
</script>

</x-app-layout>