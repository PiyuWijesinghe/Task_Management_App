<!-- Attachments Section -->
<div class="border-t border-gray-200 dark:border-gray-600 pt-8 mt-8">
    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
            </svg>
        </div>
        Attachments ({{ $task->attachments->count() }})
    </h3>

    @if($task->attachments->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($task->attachments as $attachment)
                <div class="bg-white/60 dark:bg-gray-700/60 rounded-xl p-4 border border-gray-200/50 dark:border-gray-600/50 hover:bg-white/80 dark:hover:bg-gray-600/80 transition-all duration-200 group">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-3 flex-1 min-w-0">
                            <!-- File Icon -->
                            <div class="text-2xl flex-shrink-0">
                                @php
                                    $extension = strtolower(pathinfo($attachment->original_name, PATHINFO_EXTENSION));
                                    $fileIcon = match($extension) {
                                        'pdf' => '📄',
                                        'jpg', 'jpeg', 'png', 'gif', 'webp' => '🖼️',
                                        'doc', 'docx' => '📝',
                                        'xls', 'xlsx' => '📊',
                                        'ppt', 'pptx' => '📈',
                                        'txt' => '📋',
                                        default => '📎'
                                    };
                                @endphp
                                {{ $fileIcon }}
                            </div>
                            
                            <!-- File Details -->
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate" title="{{ $attachment->original_name }}">
                                    {{ $attachment->original_name }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ number_format($attachment->size / 1024, 1) }} KB
                                    <span class="mx-1">•</span>
                                    {{ $attachment->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <!-- Download Button -->
                            <a href="{{ route('tasks.attachments.download', [$task, $attachment]) }}" 
                               class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition-all duration-200"
                               title="Download {{ $attachment->original_name }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </a>
                            
                            <!-- Delete Button (if user can modify task) -->
                            @can('update', $task)
                                <form action="{{ route('tasks.attachments.destroy', [$task, $attachment]) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete {{ $attachment->original_name }}?');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center justify-center w-8 h-8 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-all duration-200"
                                            title="Delete {{ $attachment->original_name }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                    
                    <!-- Image Preview for Image Files -->
                    @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                        <div class="mt-3 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800">
                            <img src="{{ route('tasks.attachments.download', [$task, $attachment]) }}" 
                                 alt="{{ $attachment->original_name }}"
                                 class="w-full h-32 object-cover hover:scale-105 transition-transform duration-200 cursor-pointer"
                                 onclick="openImageModal('{{ route('tasks.attachments.download', [$task, $attachment]) }}', '{{ $attachment->original_name }}')">
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <!-- Add More Attachments (if user can modify task) -->
        @can('update', $task)
            <div class="mt-6 border-t border-gray-200 dark:border-gray-600 pt-6">
                <form action="{{ route('tasks.attachments.store', $task) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <input type="file" 
                                   name="attachment" 
                                   id="new-attachment"
                                   accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx,.txt,.ppt,.pptx"
                                   class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900/20 dark:file:text-green-400 dark:hover:file:bg-green-900/40 file:transition-all file:duration-200">
                        </div>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 shadow-md hover:shadow-lg hover:scale-105">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Upload
                        </button>
                    </div>
                    @error('attachment')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </form>
            </div>
        @endcan
    @else
        <!-- No Attachments State -->
        <div class="text-center py-12 bg-gray-50/50 dark:bg-gray-800/50 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600">
            <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
            </svg>
            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No attachments yet</h4>
            <p class="text-gray-500 dark:text-gray-400 mb-4">Add files to provide more context for this task</p>
            
            @can('update', $task)
                <form action="{{ route('tasks.attachments.store', $task) }}" method="POST" enctype="multipart/form-data" class="inline-flex flex-col items-center space-y-4">
                    @csrf
                    <input type="file" 
                           name="attachment" 
                           id="first-attachment"
                           accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx,.txt,.ppt,.pptx"
                           class="block text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-green-50 file:text-green-700 hover:file:bg-green-100 dark:file:bg-green-900/20 dark:file:text-green-400 dark:hover:file:bg-green-900/40 file:transition-all file:duration-200">
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-sm font-medium rounded-lg hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 shadow-md hover:shadow-lg hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Upload First File
                    </button>
                    @error('attachment')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </form>
            @endcan
        </div>
    @endif
</div>

<!-- Image Modal for Preview -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50" onclick="closeImageModal()">
    <div class="max-w-4xl max-h-full p-4" onclick="event.stopPropagation()">
        <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg">
        <div class="text-center mt-4">
            <p id="modalTitle" class="text-white text-lg font-medium"></p>
            <button onclick="closeImageModal()" class="mt-2 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

<script>
function openImageModal(src, title) {
    const modal = document.getElementById('imageModal');
    document.getElementById('modalImage').src = src;
    document.getElementById('modalTitle').textContent = title;
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>