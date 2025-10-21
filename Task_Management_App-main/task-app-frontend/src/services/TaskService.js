// API service for task management operations
class TaskService {
  static baseURL = 'http://localhost:8000/api/v1';

  // Helper method to get auth token
  static getAuthToken() {
    return localStorage.getItem('authToken');
  }

  // Helper method for API requests with auth
  static async apiRequest(url, options = {}) {
    const token = this.getAuthToken();
    
    const config = {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(token && { 'Authorization': `Bearer ${token}` }),
        ...options.headers,
      },
      ...options,
    };

    try {
      const response = await fetch(`${this.baseURL}${url}`, config);
      const data = await response.json();
      
      if (!response.ok) {
        throw new Error(data.message || 'API request failed');
      }
      
      return data;
    } catch (error) {
      console.error('API Error:', error);
      throw error;
    }
  }

  // Task CRUD Operations
  static async getTasks(filters = {}) {
    const params = new URLSearchParams(filters).toString();
    const url = `/tasks${params ? `?${params}` : ''}`;
    return await this.apiRequest(url);
  }

  static async getTask(taskId) {
    return await this.apiRequest(`/tasks/${taskId}`);
  }

  static async createTask(taskData, files = []) {
    const token = this.getAuthToken();
    
    // If there are files, use FormData
    if (files && files.length > 0) {
      const formData = new FormData();
      
      // Add task data
      Object.keys(taskData).forEach(key => {
        if (taskData[key] !== null && taskData[key] !== undefined) {
          formData.append(key, taskData[key]);
        }
      });
      
      // Add files
      files.forEach((file, index) => {
        formData.append(`attachments[${index}]`, file);
      });
      
      const response = await fetch(`${this.baseURL}/tasks`, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          ...(token && { 'Authorization': `Bearer ${token}` }),
        },
        body: formData,
      });
      
      const data = await response.json();
      
      if (!response.ok) {
        throw new Error(data.message || 'API request failed');
      }
      
      return data;
    } else {
      // No files, use regular JSON request
      return await this.apiRequest('/tasks', {
        method: 'POST',
        body: JSON.stringify(taskData),
      });
    }
  }

  static async updateTask(taskId, taskData) {
    return await this.apiRequest(`/tasks/${taskId}`, {
      method: 'PUT',
      body: JSON.stringify(taskData),
    });
  }

  static async deleteTask(taskId) {
    return await this.apiRequest(`/tasks/${taskId}`, {
      method: 'DELETE',
    });
  }

  // Task Status Operations
  static async updateTaskStatus(taskId, status) {
    return await this.apiRequest(`/tasks/${taskId}/status`, {
      method: 'PATCH',
      body: JSON.stringify({ status }),
    });
  }

  static async markTaskCompleted(taskId) {
    return await this.apiRequest(`/tasks/${taskId}/complete`, {
      method: 'PATCH',
    });
  }

  static async updateTaskPriority(taskId, priority) {
    return await this.apiRequest(`/tasks/${taskId}/priority`, {
      method: 'PATCH',
      body: JSON.stringify({ priority }),
    });
  }

  // Task Postponement
  static async postponeTask(taskId, postponeData) {
    return await this.apiRequest(`/tasks/${taskId}/postpone`, {
      method: 'POST',
      body: JSON.stringify(postponeData),
    });
  }

  static async getPostponementHistory(taskId) {
    return await this.apiRequest(`/tasks/${taskId}/postponements`);
  }

  // Task Assignment
  static async assignUserToTask(taskId, username, type = 'additional') {
    return await this.apiRequest(`/tasks/${taskId}/assign`, {
      method: 'POST',
      body: JSON.stringify({ 
        username: username,
        type: type 
      }),
    });
  }

  static async unassignUserFromTask(taskId, userId) {
    return await this.apiRequest(`/tasks/${taskId}/unassign/${userId}`, {
      method: 'DELETE',
    });
  }

  static async getTaskAssignees(taskId) {
    return await this.apiRequest(`/tasks/${taskId}/assignees`);
  }

  // Comments
  static async getTaskComments(taskId) {
    return await this.apiRequest(`/tasks/${taskId}/comments`);
  }

  static async addComment(taskId, commentData) {
    return await this.apiRequest(`/tasks/${taskId}/comments`, {
      method: 'POST',
      body: JSON.stringify(commentData),
    });
  }

  static async updateComment(taskId, commentId, commentData) {
    return await this.apiRequest(`/tasks/${taskId}/comments/${commentId}`, {
      method: 'PUT',
      body: JSON.stringify(commentData),
    });
  }

  static async deleteComment(taskId, commentId) {
    return await this.apiRequest(`/tasks/${taskId}/comments/${commentId}`, {
      method: 'DELETE',
    });
  }

  // Dashboard and Statistics
  static async getDashboardData() {
    return await this.apiRequest('/tasks/dashboard');
  }

  static async getTaskStatistics() {
    return await this.apiRequest('/tasks/statistics');
  }

  // Task Categories
  static async getOverdueTasks() {
    return await this.apiRequest('/tasks/overdue');
  }

  static async getTasksDueToday() {
    return await this.apiRequest('/tasks/due-today');
  }

  static async getPostponedTasks() {
    return await this.apiRequest('/tasks/postponed');
  }

  // User Operations
  static async getUsers() {
    return await this.apiRequest('/users');
  }

  static async searchUsers(query) {
    return await this.apiRequest(`/users/search?q=${encodeURIComponent(query)}`);
  }

  // Bulk Operations
  static async bulkCompleteTask(taskIds) {
    return await this.apiRequest('/bulk/tasks/complete', {
      method: 'POST',
      body: JSON.stringify({ task_ids: taskIds }),
    });
  }

  static async bulkDeleteTasks(taskIds) {
    return await this.apiRequest('/bulk/tasks/delete', {
      method: 'POST',
      body: JSON.stringify({ task_ids: taskIds }),
    });
  }

  static async bulkAssignTasks(taskIds, userId) {
    return await this.apiRequest('/bulk/tasks/assign', {
      method: 'POST',
      body: JSON.stringify({ task_ids: taskIds, user_id: userId }),
    });
  }

  // File Attachment Operations
  static async getTaskAttachments(taskId) {
    return await this.apiRequest(`/tasks/${taskId}/attachments`);
  }

  static async addTaskAttachment(taskId, file) {
    console.log('🔄 Starting API upload...', {
      taskId: taskId,
      fileName: file.name,
      fileSize: file.size,
      fileType: file.type
    });
    
    const token = this.getAuthToken();
    console.log('🔑 Auth token:', token ? 'Present' : 'Missing');
    
    const formData = new FormData();
    formData.append('attachment', file);

    try {
      console.log('📡 Making API request to:', `${this.baseURL}/tasks/${taskId}/attachments`);
      const response = await fetch(`${this.baseURL}/tasks/${taskId}/attachments`, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          ...(token && { 'Authorization': `Bearer ${token}` }),
        },
        body: formData,
      });

      console.log('📋 Response status:', response.status, response.statusText);

      if (!response.ok) {
        const errorText = await response.text();
        console.log('❌ Error response text:', errorText);
        let errorMessage = 'Failed to upload attachment';
        
        try {
          const errorData = JSON.parse(errorText);
          console.log('❌ Parsed error data:', errorData);
          errorMessage = errorData.message || errorMessage;
        } catch (parseError) {
          // If response is not JSON, use status text
          errorMessage = `Server error (${response.status}): ${response.statusText}`;
          console.log('❌ Non-JSON error response');
        }
        
        throw new Error(errorMessage);
      }

      const data = await response.json();
      console.log('✅ Upload success:', data);
      return data;
    } catch (error) {
      console.log('💥 Upload error details:', {
        errorName: error.name,
        errorMessage: error.message,
        errorStack: error.stack
      });
      
      if (error.name === 'TypeError' && error.message.includes('fetch')) {
        throw new Error('Cannot connect to server. Please ensure Laravel server is running on port 8000.');
      }
      throw error;
    }
  }

  static async deleteTaskAttachment(taskId, attachmentId) {
    return await this.apiRequest(`/tasks/${taskId}/attachments/${attachmentId}`, {
      method: 'DELETE',
    });
  }

  static async downloadTaskAttachment(taskId, attachmentId) {
    const token = this.getAuthToken();
    
    const response = await fetch(`${this.baseURL}/tasks/${taskId}/attachments/${attachmentId}/download`, {
      headers: {
        ...(token && { 'Authorization': `Bearer ${token}` }),
      },
    });

    if (!response.ok) {
      throw new Error('Failed to download attachment');
    }

    return response.blob();
  }

  // Search
  static async searchTasks(query, filters = {}) {
    const params = new URLSearchParams({ q: query, ...filters }).toString();
    return await this.apiRequest(`/search/tasks?${params}`);
  }
}

export default TaskService;