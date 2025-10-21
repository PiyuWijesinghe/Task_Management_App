import React, { useState, useEffect } from 'react';
import TaskService from '../services/TaskService';
import ConfirmDialog from './ConfirmDialog';
import SuccessNotification from './SuccessNotification';
import FileUpload from './FileUpload';
import './CreateTask.css';

const CreateTask = ({ onTaskCreated, onClose, isOpen = false }) => {
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    priority: 'Medium',
    status: 'Pending',
    due_date: '',
    assigned_to: '',
  });

  const [attachments, setAttachments] = useState([]);

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [fieldErrors, setFieldErrors] = useState({});
  const [users, setUsers] = useState([]);
  const [loadingUsers, setLoadingUsers] = useState(false);
  const [showCreateConfirm, setShowCreateConfirm] = useState(false);
  const [successMessage, setSuccessMessage] = useState('');
  const [showSuccess, setShowSuccess] = useState(false);

  const showSuccessMessage = (message) => {
    setSuccessMessage(message);
    setShowSuccess(true);
  };


  // Load users for assignment
  useEffect(() => {
    if (isOpen) {
      loadUsers();
    }
  }, [isOpen]);

  const loadUsers = async () => {
    try {
      setLoadingUsers(true);
      const response = await TaskService.getUsers();
      // Handle Laravel paginated response structure
      const usersData = response?.data?.users || response?.data;
      if (Array.isArray(usersData)) {
        setUsers(usersData);
      } else {
        console.warn('API response does not contain users array:', response);
        setUsers([]);
      }
    } catch (error) {
      console.error('Failed to load users:', error);
      setUsers([]); // Set empty array on error
    } finally {
      setLoadingUsers(false);
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
    
    setShowCreateConfirm(true);
  };

  const handleSubmit = async () => {
    try {
      setLoading(true);
      setError('');
      setFieldErrors({});

      // Prepare task data with files
      const taskData = {
        title: formData.title.trim(),
        description: formData.description.trim(),
        priority: formData.priority,
        status: formData.status,
        due_date: formData.due_date || null,
        assigned_to: formData.assigned_to || null,
      };

      const response = await TaskService.createTask(taskData, attachments);
      
      // Reset form
      setFormData({
        title: '',
        description: '',
        priority: 'Medium',
        status: 'Pending',
        due_date: '',
        assigned_to: '',
      });
      
      // Clean up attachments and their preview URLs
      attachments.forEach(file => {
        if (file.preview) {
          URL.revokeObjectURL(file.preview);
        }
      });
      setAttachments([]);

      setShowCreateConfirm(false);

      // Show success message
      showSuccessMessage(`Task "${formData.title}" created successfully!`);

      // Notify parent component
      if (onTaskCreated) {
        onTaskCreated(response.data);
      }

      // Close modal after a brief delay to show success message
      setTimeout(() => {
        if (onClose) {
          onClose();
        }
      }, 2000);

    } catch (error) {
      console.error('Task creation error:', error);
      
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
        setError(error.message || 'Failed to create task. Please try again.');
      }
    } finally {
      setLoading(false);
    }
  };

  const handleCancel = () => {
    setFormData({
      title: '',
      description: '',
      priority: 'Medium',
      status: 'Pending',
      due_date: '',
      assigned_to: '',
    });
    
    // Clean up attachments and their preview URLs
    attachments.forEach(file => {
      if (file.preview) {
        URL.revokeObjectURL(file.preview);
      }
    });
    setAttachments([]);
    
    setError('');
    setFieldErrors({});
    if (onClose) {
      onClose();
    }
  };

  const handleAttachmentsChange = (newAttachments) => {
    setAttachments(newAttachments);
  };

  if (!isOpen) return null;

  return (
    <div className="create-task-overlay">
      <div className="create-task-modal">
        <div className="create-task-header">
          <h2>🆕 Create New Task</h2>
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
            <span>⚠️ {error}</span>
          </div>
        )}

        <form onSubmit={handleFormSubmit} className="create-task-form">
          {/* Task Title */}
          <div className="form-group">
            <label htmlFor="title">Task Title *</label>
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
              <div className="field-error">⚠️ {fieldErrors.title}</div>
            )}
          </div>

          {/* Task Description */}
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
              <div className="field-error">⚠️ {fieldErrors.description}</div>
            )}
          </div>

          {/* Priority and Status Row */}
          <div className="form-row">
            <div className="form-group">
              <label htmlFor="priority">Priority</label>
              <select
                id="priority"
                name="priority"
                value={formData.priority}
                onChange={handleInputChange}
                disabled={loading}
                className={fieldErrors.priority ? 'error' : ''}
              >
                <option value="Low">🟢 Low</option>
                <option value="Medium">🟡 Medium</option>
                <option value="High">🔴 High</option>
              </select>
              {fieldErrors.priority && (
                <div className="field-error">⚠️ {fieldErrors.priority}</div>
              )}
            </div>

            <div className="form-group">
              <label htmlFor="status">Status</label>
              <select
                id="status"
                name="status"
                value={formData.status}
                onChange={handleInputChange}
                disabled={loading}
                className={fieldErrors.status ? 'error' : ''}
              >
                <option value="Pending">⏳ Pending</option>
                <option value="In Progress">🔄 In Progress</option>
                <option value="Completed">✅ Completed</option>
              </select>
              {fieldErrors.status && (
                <div className="field-error">⚠️ {fieldErrors.status}</div>
              )}
            </div>
          </div>

          {/* Due Date and Assignment Row */}
          <div className="form-row">
            <div className="form-group">
              <label htmlFor="due_date">Due Date</label>
              <input
                type="datetime-local"
                id="due_date"
                name="due_date"
                value={formData.due_date}
                onChange={handleInputChange}
                disabled={loading}
                className={fieldErrors.due_date ? 'error' : ''}
              />
              {fieldErrors.due_date && (
                <div className="field-error">⚠️ {fieldErrors.due_date}</div>
              )}
            </div>

            <div className="form-group">
              <label htmlFor="assigned_to">Assign To</label>
              <select
                id="assigned_to"
                name="assigned_to"
                value={formData.assigned_to}
                onChange={handleInputChange}
                disabled={loading || loadingUsers}
                className={fieldErrors.assigned_to ? 'error' : ''}
              >
                <option value="">👤 Select User (Optional)</option>
                {Array.isArray(users) && users.map(user => (
                  <option key={user.id} value={user.id}>
                    {user.username || user.name}
                  </option>
                ))}
              </select>
              {loadingUsers && (
                <small className="loading-text">Loading users...</small>
              )}
              {fieldErrors.assigned_to && (
                <div className="field-error">⚠️ {fieldErrors.assigned_to}</div>
              )}
            </div>
          </div>

          {/* File Upload Section */}
          <FileUpload
            files={attachments}
            onFilesChange={handleAttachmentsChange}
            maxFiles={5}
            maxSizeInMB={10}
          />

          {/* Form Actions */}
          <div className="form-actions">
            <button
              type="button"
              className="cancel-button"
              onClick={handleCancel}
              disabled={loading}
            >
              Cancel
            </button>
            <button
              type="submit"
              className="submit-button"
              disabled={loading || !formData.title.trim()}
            >
              {loading ? '⏳ Creating...' : '✅ Create Task'}
            </button>
          </div>
        </form>
      </div>

      {/* Create Task Confirmation Dialog */}
      <ConfirmDialog
        isOpen={showCreateConfirm}
        title="Create New Task"
        message={`Are you sure you want to create the task "${formData.title}"?`}
        confirmText="Create Task"
        cancelText="Cancel"
        type="success"
        loading={loading}
        onConfirm={handleSubmit}
        onCancel={() => setShowCreateConfirm(false)}
      />

      {/* Success Notification */}
      <SuccessNotification
        message={successMessage}
        isVisible={showSuccess}
        onClose={() => setShowSuccess(false)}
        type="success"
      />
    </div>
  );
};

export default CreateTask;