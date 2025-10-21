import React, { useState, useEffect } from 'react';
import api from '../services/api';
import './HighPriorityTasks.css';

const HighPriorityTasks = () => {
  const [tasks, setTasks] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    fetchHighPriorityTasks();
  }, []);

  const fetchHighPriorityTasks = async () => {
    try {
      setLoading(true);
      setError('');

      // Fetch high priority pending tasks
      const pendingResponse = await api.get('/tasks', {
        params: {
          priority: 'High',
          status: 'Pending',
          per_page: 20
        }
      });

      // Fetch high priority in-progress tasks
      const inProgressResponse = await api.get('/tasks', {
        params: {
          priority: 'High',
          status: 'In Progress',
          per_page: 20
        }
      });

      let allTasks = [];
      
      if (pendingResponse.data.success) {
        allTasks = [...allTasks, ...(pendingResponse.data.data.tasks || [])];
      }
      
      if (inProgressResponse.data.success) {
        allTasks = [...allTasks, ...(inProgressResponse.data.data.tasks || [])];
      }

      // Filter tasks due within next 7 days
      const today = new Date();
      const sevenDaysLater = new Date();
      sevenDaysLater.setDate(today.getDate() + 7);

      const dueSoonTasks = allTasks.filter(task => {
        if (!task.due_date) return false;
        const dueDate = new Date(task.due_date);
        return dueDate >= today && dueDate <= sevenDaysLater;
      });

      // Sort by due date (earliest first)
      dueSoonTasks.sort((a, b) => new Date(a.due_date) - new Date(b.due_date));

      setTasks(dueSoonTasks);
    } catch (error) {
      console.error('Error fetching high priority tasks:', error);
      setError('Failed to load high priority tasks');
    } finally {
      setLoading(false);
    }
  };

  const formatDueDate = (dateString) => {
    const date = new Date(dateString);
    const today = new Date();
    const diffTime = date - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 0) return 'Due Today';
    if (diffDays === 1) return 'Due Tomorrow';
    if (diffDays < 7) return `Due in ${diffDays} days`;
    return date.toLocaleDateString();
  };

  const getStatusColor = (status) => {
    switch (status) {
      case 'Pending': return '#f59e0b';
      case 'In Progress': return '#06b6d4';
      default: return '#6b7280';
    }
  };

  const getDueDateUrgency = (dateString) => {
    const date = new Date(dateString);
    const today = new Date();
    const diffTime = date - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    if (diffDays === 0) return 'urgent';
    if (diffDays <= 1) return 'warning';
    return 'normal';
  };

  if (loading) {
    return (
      <div className="high-priority-loading">
        <div className="loading-spinner">Loading high priority tasks...</div>
      </div>
    );
  }

  return (
    <div className="high-priority-container">
      <div className="high-priority-header">
        <h3>🔥 High Priority Tasks Due Soon</h3>
        <button onClick={fetchHighPriorityTasks} className="refresh-button">
          🔄 Refresh
        </button>
      </div>

      {error && (
        <div className="error-message">
          {error}
        </div>
      )}

      {tasks.length === 0 ? (
        <div className="no-tasks">
          <div className="no-tasks-icon">🎉</div>
          <p>No high priority tasks due soon!</p>
          <small>Great job staying on top of your priorities</small>
        </div>
      ) : (
        <div className="tasks-list">
          {tasks.map((task) => (
            <div key={task.id} className="task-item">
              <div className="task-content">
                <div className="task-header">
                  <h4 className="task-title">{task.title}</h4>
                  <span 
                    className="task-status"
                    style={{ backgroundColor: getStatusColor(task.status) }}
                  >
                    {task.status}
                  </span>
                </div>
                
                {task.description && (
                  <p className="task-description">{task.description}</p>
                )}
                
                <div className="task-footer">
                  <span 
                    className={`due-date ${getDueDateUrgency(task.due_date)}`}
                  >
                    📅 {formatDueDate(task.due_date)}
                  </span>
                  <span className="priority-badge">
                    🔥 High Priority
                  </span>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}

      {tasks.length > 0 && (
        <div className="tasks-summary">
          <p>
            <strong>{tasks.length}</strong> high priority task{tasks.length !== 1 ? 's' : ''} 
            need{tasks.length === 1 ? 's' : ''} your attention
          </p>
        </div>
      )}
    </div>
  );
};

export default HighPriorityTasks;