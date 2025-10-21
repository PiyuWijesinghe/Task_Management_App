import React, { useState, useEffect } from 'react';
import TaskService from '../services/TaskService';
import AttachmentsList from './AttachmentsList';
import ConfirmDialog from './ConfirmDialog';
import SuccessNotification from './SuccessNotification';
import './EditTask.css';

const EditTask = ({ task, onTaskUpdated, onClose, isOpen = false }) => {
  const [formData, setFormData] = useState({
    title: task?.title || '',
    description: task?.description || '',
    priority: task?.priority || 'Medium',
    status: task?.status || 'Pending',
    due_date: task?.due_date || ''
  });

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [fieldErrors, setFieldErrors] = useState({});
  const [availableUsers, setAvailableUsers] = useState([]);
  const [selectedAssignees, setSelectedAssignees] = useState([]);
  const [assignees, setAssignees] = useState([]);
  const [userSearchQuery, setUserSearchQuery] = useState('');
  const [showSaveConfirm, setShowSaveConfirm] = useState(false);
  const [successMessage, setSuccessMessage] = useState('');
  const [showSuccess, setShowSuccess] = useState(false);

  // Update form data when task changes
  useEffect(() => {
    if (task && isOpen) {
      setFormData({
        title: task.title || '',
        description: task.description || '',
        priority: task.priority || 'Medium',
        status: task.status || 'Pending',
        due_date: task.due_date || ''
      });
      loadAssignees();
      loadAvailableUsers();
    }
  }, [task, isOpen]);

  // Helper function to get current user
  const getCurrentUser = () => {
    try {
      const storedUser = localStorage.getItem('user');
      return storedUser ? JSON.parse(storedUser) : null;
    } catch (error) {
      console.error('Error parsing user from localStorage:', error);
      return null;
    }
  };

  // Load current assignees
  const loadAssignees = async () => {
    if (!task?.id) return;
    try {
      const response = await TaskService.getTaskAssignees(task.id);
      const assigneesData = response?.data?.assignees || response?.data || [];
      setAssignees(assigneesData);
      
      // Set selected assignees for editing
      if (Array.isArray(assigneesData) && assigneesData.length > 0) {
        const currentAssignees = assigneesData.map(assignee => ({
          id: assignee.user?.id || assignee.id,
          name: assignee.user?.name || assignee.name,
          username: assignee.user?.username || assignee.username
        }));
        setSelectedAssignees(currentAssignees);
      } else {
        setSelectedAssignees([]);
      }
    } catch (error) {
      console.error('Failed to load assignees:', error);
      setAssignees([]);
      setSelectedAssignees([]);
    }
  };

  // Load available users
  const loadAvailableUsers = async () => {
    try {
      const response = await TaskService.getUsers();
      const usersData = response?.data?.users || response?.data || [];
      setAvailableUsers(Array.isArray(usersData) ? usersData : []);
    } catch (error) {
      console.error('Failed to load users:', error);
      setAvailableUsers([]);
    }
  };

  // Search users
  const searchUsers = async (query) => {
    if (!query.trim()) {
      loadAvailableUsers();
      return;
    }
    try {
      const response = await TaskService.searchUsers(query);
      const usersData = response?.data?.users || response?.data || [];
      setAvailableUsers(Array.isArray(usersData) ? usersData : []);
    } catch (error) {
      console.error('Failed to search users:', error);
      setAvailableUsers([]);
    }
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
    
    // Clear field error when user starts typing
    if (fieldErrors[name]) {
      setFieldErrors(prev => ({
        ...prev,
        [name]: ''
      }));
    }
    
    // Clear general error
    if (error) {
      setError('');
    }
  };

  const validateForm = () => {
    const errors = {};
    
    // Title validation
    if (!formData.title?.trim()) {
      errors.title = 'Task title is required';
    } else if (formData.title.trim().length < 3) {
      errors.title = 'Task title must be at least 3 characters';
    }
    
    // Description validation (optional but if provided, minimum length)
    if (formData.description && formData.description.trim().length < 10) {
      errors.description = 'Description should be at least 10 characters if provided';
    }
    
    // Due date validation
    if (formData.due_date) {
      const dueDate = new Date(formData.due_date);
      const now = new Date();
      if (dueDate < now) {
        errors.due_date = 'Due date cannot be in the past';
      }
    }
    
    return errors;
  };

  const handleFormSubmit = (e) => {
    e.preventDefault();
    
    // Validate form
    const errors = validateForm();
    
    if (Object.keys(errors).length > 0) {
      setFieldErrors(errors);
      setError('Please fix the validation errors below');
      return;
    }
    
    setShowSaveConfirm(true);
  };

  const handleSubmit = async () => {
    try {
      setLoading(true);
      setError('');
      setFieldErrors({});

      // Update task basic info
      const updatedTask = await TaskService.updateTask(task.id, formData);
      
      // Handle assignees updates
      if (Array.isArray(selectedAssignees)) {
        // Get current assignee IDs
        const currentAssigneeIds = (assignees || []).map(a => a.user?.id || a.id);
        const newAssigneeIds = selectedAssignees.map(a => a.id);
        
        // Remove unassigned users
        for (const currentId of currentAssigneeIds) {
          if (!newAssigneeIds.includes(currentId)) {
            try {
              await TaskService.unassignUserFromTask(task.id, currentId);
            } catch (error) {
              console.error('Failed to unassign user:', error);
            }
          }
        }
        
        // Add new assignees
        for (const assignee of selectedAssignees) {
          if (!currentAssigneeIds.includes(assignee.id)) {
            try {
              await TaskService.assignUserToTask(task.id, assignee.username || assignee.name);
            } catch (error) {
              console.error('Failed to assign user:', error);
            }
          }
        }
      }

      setShowSaveConfirm(false);

      // Show success message
      setSuccessMessage(`Task "${formData.title}" updated successfully!`);
      setShowSuccess(true);

      // Notify parent component
      if (onTaskUpdated) {
        const taskData = updatedTask?.data?.task || updatedTask?.data || updatedTask;
        onTaskUpdated(taskData, 'edited');
      }

      // Close modal after a brief delay to show success message
      setTimeout(() => {
        if (onClose) {
          onClose();
        }
      }, 2000);

    } catch (error) {
      console.error('Task update error:', error);
      
      // Handle validation errors from API
      if (error.response?.status === 422 && error.response?.data?.errors) {
        const apiErrors = error.response.data.errors;
        const formattedErrors = {};
        
        Object.keys(apiErrors).forEach(field => {
          formattedErrors[field] = Array.isArray(apiErrors[field]) 
            ? apiErrors[field][0] 
            : apiErrors[field];
        });
        
        setFieldErrors(formattedErrors);
        setError('Please fix the validation errors below');
      } else if (error.response?.data?.message) {
        setError(error.response.data.message);
      } else {
        setError(error.message || 'Failed to update task. Please try again.');
      }
    } finally {
      setLoading(false);
    }
  };

  const handleCancel = () => {
    setFormData({
      title: task?.title || '',
      description: task?.description || '',
      priority: task?.priority || 'Medium',
      status: task?.status || 'Pending',
      due_date: task?.due_date || ''
    });
    setSelectedAssignees([]);
    setUserSearchQuery('');
    setError('');
    setFieldErrors({});
    if (onClose) {
      onClose();
    }
  };

  const formatDateForInput = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toISOString().slice(0, 16); // Format for datetime-local input
  };

  const handleAddAssignee = (user) => {
    if (!selectedAssignees.find(assignee => assignee.id === user.id)) {
      setSelectedAssignees([...selectedAssignees, user]);
    }
    setUserSearchQuery('');
  };

  const handleRemoveAssignee = (userId) => {
    setSelectedAssignees(selectedAssignees.filter(assignee => assignee.id !== userId));
  };

  const showSuccessMessage = (message) => {
    setSuccessMessage(message);
    setShowSuccess(true);
  };

  if (!isOpen || !task) return null;

  return (
    <div className="edit-task-overlay">
      <div className="edit-task-modal">
        <div className="edit-task-header">
          <h2>✏️ Edit Task</h2>
          <button 
            className="close-button"
            onClick={handleCancel}
            disabled={loading}
          >
            ✕
          </button>
        </div>

        {error && (
          <div className="error-message">
            ⚠️ {error}
          </div>
        )}

        <form onSubmit={handleFormSubmit} className="edit-task-form">
          {/* Basic Task Information */}
          <div className="form-section">
            <h3>📋 Task Information</h3>
            
            <div className="form-group">
              <label htmlFor="title">
                Task Title <span className="required">*</span>
              </label>
              <input
                type="text"
                id="title"
                name="title"
                value={formData.title}
                onChange={handleInputChange}
                placeholder="Enter task title..."
                required
                disabled={loading}
                className={fieldErrors.title ? 'error' : ''}
              />
              {fieldErrors.title && (
                <span className="field-error">❌ {fieldErrors.title}</span>
              )}
            </div>

            <div className="form-group">
              <label htmlFor="description">Description</label>
              <textarea
                id="description"
                name="description"
                value={formData.description}
                onChange={handleInputChange}
                placeholder="Enter task description..."
                rows="4"
                disabled={loading}
                className={fieldErrors.description ? 'error' : ''}
              />
              {fieldErrors.description && (
                <span className="field-error">❌ {fieldErrors.description}</span>
              )}
            </div>

            <div className="form-row">
              <div className="form-group">
                <label htmlFor="priority">Priority</label>
                <select
                  id="priority"
                  name="priority"
                  value={formData.priority}
                  onChange={handleInputChange}
                  disabled={loading}
                >
                  <option value="Low">🟢 Low</option>
                  <option value="Medium">🟡 Medium</option>
                  <option value="High">🔴 High</option>
                </select>
              </div>

              <div className="form-group">
                <label htmlFor="status">Status</label>
                <select
                  id="status"
                  name="status"
                  value={formData.status}
                  onChange={handleInputChange}
                  disabled={loading}
                >
                  <option value="Pending">⏳ Pending</option>
                  <option value="In Progress">🔄 In Progress</option>
                  <option value="Completed">✅ Completed</option>
                </select>
              </div>
            </div>

            <div className="form-group">
              <label htmlFor="due_date">Due Date</label>
              <input
                type="datetime-local"
                id="due_date"
                name="due_date"
                value={formatDateForInput(formData.due_date)}
                onChange={handleInputChange}
                disabled={loading}
                className={fieldErrors.due_date ? 'error' : ''}
              />
              {fieldErrors.due_date && (
                <span className="field-error">❌ {fieldErrors.due_date}</span>
              )}
            </div>
          </div>

          {/* Assignees Section */}
          <div className="form-section">
            <h3>👥 Assignees</h3>
            
            {/* Current Assignees */}
            {selectedAssignees.length > 0 && (
              <div className="current-assignees">
                <label>Current Assignees:</label>
                <div className="assignees-list">
                  {selectedAssignees.map(assignee => (
                    <div key={assignee.id} className="assignee-tag">
                      <span>👤 {assignee.username || assignee.name}</span>
                      <button
                        type="button"
                        onClick={() => handleRemoveAssignee(assignee.id)}
                        className="remove-assignee-button"
                        disabled={loading}
                      >
                        ✕
                      </button>
                    </div>
                  ))}
                </div>
              </div>
            )}
            
            {/* User Search */}
            <div className="form-group">
              <label htmlFor="user-search">Add Assignees</label>
              <div className="user-search">
                <input
                  type="text"
                  id="user-search"
                  placeholder="Search users to assign..."
                  value={userSearchQuery}
                  onChange={(e) => {
                    setUserSearchQuery(e.target.value);
                    searchUsers(e.target.value);
                  }}
                  disabled={loading}
                />
                
                {/* Available Users Dropdown */}
                {userSearchQuery && availableUsers.length > 0 && (
                  <div className="users-dropdown">
                    {availableUsers
                      .filter(user => !selectedAssignees.find(a => a.id === user.id))
                      .slice(0, 5) // Limit to 5 results
                      .map(user => (
                        <div
                          key={user.id}
                          className="user-option"
                          onClick={() => handleAddAssignee(user)}
                        >
                          <span>👤 {user.username}</span>
                          <span className="user-fullname">{user.name}</span>
                        </div>
                      ))}
                  </div>
                )}
              </div>
            </div>
          </div>

          {/* Attachments Section */}
          <div className="form-section">
            <h3>📎 Attachments</h3>
            <div className="attachments-container">
              <AttachmentsList 
                taskId={task.id}
                canEdit={getCurrentUser()?.id === task.user_id}
                canUpload={true} // Both creators and assignees can upload files
              />
            </div>
          </div>

          {/* Form Actions */}
          <div className="form-actions">
            <button 
              type="button" 
              onClick={handleCancel}
              className="cancel-button"
              disabled={loading}
            >
              ❌ Cancel
            </button>
            <button 
              type="submit" 
              className="save-button"
              disabled={loading}
            >
              {loading ? '⏳ Updating...' : '✅ Save Changes'}
            </button>
          </div>
        </form>

        {/* Save Confirmation Dialog */}
        <ConfirmDialog
          isOpen={showSaveConfirm}
          title="Save Changes"
          message={`Are you sure you want to save the changes to "${formData.title}"?`}
          confirmText="Save Changes"
          cancelText="Cancel"
          type="success"
          loading={loading}
          onConfirm={handleSubmit}
          onCancel={() => setShowSaveConfirm(false)}
        />

        {/* Success Notification */}
        <SuccessNotification
          message={successMessage}
          isVisible={showSuccess}
          onClose={() => setShowSuccess(false)}
        />
      </div>
    </div>
  );
};

export default EditTask;
