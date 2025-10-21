import React, { useState, useEffect } from 'react';
import TaskService from '../services/TaskService';
import TaskItem from './TaskItem';
import CreateTask from './CreateTask';
import TaskAssignment from './TaskAssignment';
import LoadingSpinner from './LoadingSpinner';
import ErrorNotification from './ErrorNotification';
import './TaskManager.css';

const TaskManager = ({ onShowNotification }) => {
  const [tasks, setTasks] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [showAssignmentModal, setShowAssignmentModal] = useState(false);
  const [selectedTask, setSelectedTask] = useState(null);
  const [filters, setFilters] = useState({
    status: '',
    priority: '',
    search: ''
  });

  useEffect(() => {
    loadTasks();
  }, [filters]);

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

  const loadTasks = async () => {
    try {
      setLoading(true);
      setError('');
      
      const queryFilters = {};
      if (filters.status) queryFilters.status = filters.status;
      if (filters.priority) queryFilters.priority = filters.priority;
      if (filters.search) queryFilters.search = filters.search;
      
      const response = await TaskService.getTasks(queryFilters);
      
      // Handle Laravel paginated response structure
      const tasksData = response?.data?.tasks || response?.data;
      if (Array.isArray(tasksData)) {
        setTasks(tasksData);
      } else {
        console.warn('API response does not contain array data:', response);
        setTasks([]);
      }
    } catch (error) {
      setError(error.message || 'Failed to load tasks');
      console.error('Failed to load tasks:', error);
      // Ensure tasks is still an array on error
      setTasks([]);
    } finally {
      setLoading(false);
    }
  };

  const handleTaskCreated = (newTask) => {
    setTasks([newTask, ...tasks]);
    setShowCreateModal(false);
    
    // Show success notification
    if (onShowNotification) {
      onShowNotification(`🎉 Task "${newTask.title}" created successfully!`, 'success');
    }
  };

  const handleTaskUpdated = (updatedTask, updateType = 'updated') => {
    setTasks(tasks.map(task => 
      task.id === updatedTask.id ? updatedTask : task
    ));
    
    // Show success notification based on update type
    if (onShowNotification) {
      let message = '';
      switch(updateType) {
        case 'edited':
          message = `✏️ Task "${updatedTask.title}" edited successfully!`;
          break;
        case 'postponed':
          message = `⏳ Task "${updatedTask.title}" postponed successfully!`;
          break;
        case 'status':
          message = `📋 Task "${updatedTask.title}" status updated!`;
          break;
        default:
          message = `✅ Task "${updatedTask.title}" updated successfully!`;
      }
      onShowNotification(message, 'success');
    }
  };

  const handleTaskDeleted = (taskId, taskTitle) => {
    setTasks(tasks.filter(task => task.id !== taskId));
    
    // Show success notification
    if (onShowNotification) {
      onShowNotification(`🗑️ Task "${taskTitle}" deleted successfully!`, 'success');
    }
  };

  const handleAssignmentUpdated = (taskId) => {
    // Refresh the specific task or reload all tasks
    loadTasks();
  };

  const openAssignmentModal = (task) => {
    setSelectedTask(task);
    setShowAssignmentModal(true);
  };

  const closeAssignmentModal = () => {
    setSelectedTask(null);
    setShowAssignmentModal(false);
  };

  const handleFilterChange = (filterType, value) => {
    setFilters(prev => ({
      ...prev,
      [filterType]: value
    }));
  };

  const clearFilters = () => {
    setFilters({
      status: '',
      priority: '',
      search: ''
    });
  };

  const getTaskStats = () => {
    // Ensure tasks is always an array to prevent filter errors
    const taskArray = Array.isArray(tasks) ? tasks : [];
    
    const stats = {
      total: taskArray.length,
      pending: taskArray.filter(t => 
        t.status === 'pending' || t.status === 'Pending'
      ).length,
      in_progress: taskArray.filter(t => 
        t.status === 'in_progress' || t.status === 'in progress' || t.status === 'In Progress'
      ).length,
      completed: taskArray.filter(t => 
        t.status === 'completed' || t.status === 'Completed'
      ).length,
      overdue: taskArray.filter(t => {
        if (!t.due_date || t.status === 'completed' || t.status === 'Completed') return false;
        return new Date(t.due_date) < new Date();
      }).length
    };
    return stats;
  };

  const stats = getTaskStats();

  return (
    <div className="task-manager">
      <div className="task-manager-header">
        <div className="header-top">
          <h1>📋 Task Manager</h1>
          <button 
            className="create-task-button"
            onClick={() => setShowCreateModal(true)}
          >
            ➕ Create Task
          </button>
        </div>

        {/* Task Statistics */}
        <div className="task-stats">
          <div className="stat-item">
            <span className="stat-icon">📊</span>
            <span className="stat-label">Total</span>
            <span className="stat-value">{stats.total}</span>
          </div>
          <div className="stat-item">
            <span className="stat-icon">⏳</span>
            <span className="stat-label">Pending</span>
            <span className="stat-value">{stats.pending}</span>
          </div>
          <div className="stat-item">
            <span className="stat-icon">🔄</span>
            <span className="stat-label">In Progress</span>
            <span className="stat-value">{stats.in_progress}</span>
          </div>
          <div className="stat-item">
            <span className="stat-icon">✅</span>
            <span className="stat-label">Completed</span>
            <span className="stat-value">{stats.completed}</span>
          </div>
          <div className="stat-item overdue">
            <span className="stat-icon">🚨</span>
            <span className="stat-label">Overdue</span>
            <span className="stat-value">{stats.overdue}</span>
          </div>
        </div>

        {/* Filters */}
        <div className="task-filters">
          <div className="filter-row">
            <input
              type="text"
              placeholder="🔍 Search tasks..."
              value={filters.search}
              onChange={(e) => handleFilterChange('search', e.target.value)}
              className="search-input"
            />
            
            <select
              value={filters.status}
              onChange={(e) => handleFilterChange('status', e.target.value)}
              className="filter-select"
            >
              <option value="">All Statuses</option>
              <option value="Pending">⏳ Pending</option>
              <option value="In Progress">🔄 In Progress</option>
              <option value="Completed">✅ Completed</option>
            </select>

            <select
              value={filters.priority}
              onChange={(e) => handleFilterChange('priority', e.target.value)}
              className="filter-select"
            >
              <option value="">All Priorities</option>
              <option value="Low">🟢 Low</option>
              <option value="Medium">🟡 Medium</option>
              <option value="High">🔴 High</option>
            </select>

            {(filters.status || filters.priority || filters.search) && (
              <button 
                className="clear-filters-button"
                onClick={clearFilters}
              >
                🗑️ Clear
              </button>
            )}

            <button 
              className="refresh-button"
              onClick={loadTasks}
              disabled={loading}
            >
              🔄 Refresh
            </button>
          </div>
        </div>
      </div>

      <div className="task-manager-content">
        {error && (
          <div className="error-message">
            <span>⚠️ {error}</span>
            <button onClick={loadTasks} className="retry-button">
              🔄 Retry
            </button>
          </div>
        )}

        {loading ? (
          <LoadingSpinner 
            message="Loading tasks..." 
            size="large" 
          />
        ) : (!Array.isArray(tasks) || tasks.length === 0) ? (
          <div className="no-tasks">
            <div className="no-tasks-icon">📝</div>
            <h3>No Tasks Found</h3>
            <p>
              {Object.values(filters).some(f => f) 
                ? "No tasks match your current filters. Try adjusting your search criteria."
                : "You haven't created any tasks yet. Click the 'Create Task' button to get started!"
              }
            </p>
            {!Object.values(filters).some(f => f) && (
              <button 
                className="create-first-task-button"
                onClick={() => setShowCreateModal(true)}
              >
                ➕ Create Your First Task
              </button>
            )}
          </div>
        ) : (
          <div className="tasks-list">
            {Array.isArray(tasks) && tasks.map(task => (
              <div key={task.id} className="task-wrapper">
                <TaskItem
                  task={task}
                  onTaskUpdated={handleTaskUpdated}
                  onTaskDeleted={handleTaskDeleted}
                  showComments={true}
                />
                <div className="task-actions-bar">
                  {/* Only show assignment management to task creator */}
                  {getCurrentUser()?.id === task.user_id && (
                    <button
                      className="assign-task-button"
                      onClick={() => openAssignmentModal(task)}
                    >
                      👥 Manage Assignments
                    </button>
                  )}
                </div>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Modals */}
      <CreateTask
        isOpen={showCreateModal}
        onClose={() => setShowCreateModal(false)}
        onTaskCreated={handleTaskCreated}
      />

      <TaskAssignment
        task={selectedTask}
        isOpen={showAssignmentModal}
        onClose={closeAssignmentModal}
        onAssignmentUpdated={handleAssignmentUpdated}
        onShowNotification={onShowNotification}
      />

      {/* Error Notification */}
      <ErrorNotification
        error={error}
        onClose={() => setError('')}
      />
    </div>
  );
};

export default TaskManager;