import React, { useState, useEffect } from 'react';
import api from '../services/api';
import './TaskStats.css';

const TaskStats = () => {
  const [stats, setStats] = useState({
    totalTasks: 0,
    pendingTasks: 0,
    inProgressTasks: 0,
    completedTasks: 0,
    highPriorityDueSoon: 0,
    overdueTasks: 0
  });
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    fetchDashboardStats();
  }, []);

  const fetchDashboardStats = async () => {
    try {
      setLoading(true);
      
      // Fetch dashboard statistics
      const dashboardResponse = await api.get('/tasks/dashboard');
      
      if (dashboardResponse.data.success) {
        const statistics = dashboardResponse.data.data.statistics;
        
        // For high priority tasks due soon, we'll use the due_today count from dashboard
        // or make a simple call to get high priority tasks
        let highPriorityDueSoon = 0;
        
        try {
          // Try to get high priority pending tasks
          const pendingHighPriorityResponse = await api.get('/tasks', {
            params: {
              priority: 'High',
              status: 'Pending',
              per_page: 50
            }
          });
          
          // Try to get high priority in-progress tasks  
          const inProgressHighPriorityResponse = await api.get('/tasks', {
            params: {
              priority: 'High',
              status: 'In Progress',
              per_page: 50
            }
          });
          
          const today = new Date();
          const threeDaysLater = new Date();
          threeDaysLater.setDate(today.getDate() + 3);
          
          // Count tasks due within next 3 days from both responses
          const countDueSoon = (tasks) => {
            return tasks?.filter(task => {
              if (!task.due_date) return false;
              const dueDate = new Date(task.due_date);
              return dueDate >= today && dueDate <= threeDaysLater;
            }).length || 0;
          };
          
          let pendingCount = 0;
          let inProgressCount = 0;
          
          if (pendingHighPriorityResponse.data.success) {
            pendingCount = countDueSoon(pendingHighPriorityResponse.data.data.tasks);
          }
          
          if (inProgressHighPriorityResponse.data.success) {
            inProgressCount = countDueSoon(inProgressHighPriorityResponse.data.data.tasks);
          }
          
          highPriorityDueSoon = pendingCount + inProgressCount;
          
        } catch (priorityError) {
          console.log('Could not fetch high priority tasks, using due_today as fallback');
          highPriorityDueSoon = statistics.due_today || 0;
        }
        
        setStats({
          totalTasks: statistics.total || 0,
          pendingTasks: statistics.pending || 0,
          inProgressTasks: statistics.in_progress || 0,
          completedTasks: statistics.completed || 0,
          overdueTasks: statistics.overdue || 0,
          highPriorityDueSoon: highPriorityDueSoon
        });
      }
    } catch (error) {
      console.error('Error fetching dashboard stats:', error);
      setError('Failed to load dashboard statistics');
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="task-stats-loading">
        <div className="loading-spinner">Loading statistics...</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="task-stats-error">
        <p>{error}</p>
        <button onClick={fetchDashboardStats} className="retry-button">
          Retry
        </button>
      </div>
    );
  }

  return (
    <div className="task-stats-container">
      <h3 className="stats-title">Task Overview</h3>
      
      <div className="stats-grid">
        {/* Total Tasks */}
        <div className="stat-card total-tasks">
          <div className="stat-icon">📋</div>
          <div className="stat-content">
            <h4>Total Tasks</h4>
            <span className="stat-number">{stats.totalTasks || 0}</span>
          </div>
        </div>

        {/* Pending Tasks */}
        <div className="stat-card pending-tasks">
          <div className="stat-icon">⏳</div>
          <div className="stat-content">
            <h4>Pending</h4>
            <span className="stat-number">{stats.pendingTasks || 0}</span>
          </div>
        </div>

        {/* In Progress Tasks */}
        <div className="stat-card progress-tasks">
          <div className="stat-icon">🔄</div>
          <div className="stat-content">
            <h4>In Progress</h4>
            <span className="stat-number">{stats.inProgressTasks || 0}</span>
          </div>
        </div>

        {/* Completed Tasks */}
        <div className="stat-card completed-tasks">
          <div className="stat-icon">✅</div>
          <div className="stat-content">
            <h4>Completed</h4>
            <span className="stat-number">{stats.completedTasks || 0}</span>
          </div>
        </div>

        {/* High Priority Due Soon */}
        <div className="stat-card priority-tasks">
          <div className="stat-icon">🔥</div>
          <div className="stat-content">
            <h4>High Priority Due Soon</h4>
            <span className="stat-number urgent">{stats.highPriorityDueSoon || 0}</span>
          </div>
        </div>

        {/* Overdue Tasks */}
        <div className="stat-card overdue-tasks">
          <div className="stat-icon">⚠️</div>
          <div className="stat-content">
            <h4>Overdue</h4>
            <span className="stat-number danger">{stats.overdueTasks || 0}</span>
          </div>
        </div>
      </div>

      {/* Progress Bar */}
      <div className="progress-section">
        <h4>Completion Progress</h4>
        <div className="progress-bar">
          <div 
            className="progress-fill"
            style={{ 
              width: stats.totalTasks > 0 
                ? `${(stats.completedTasks / stats.totalTasks) * 100}%` 
                : '0%' 
            }}
          ></div>
        </div>
        <div className="progress-text">
          {stats.totalTasks > 0 
            ? `${Math.round((stats.completedTasks / stats.totalTasks) * 100)}% Complete`
            : 'No tasks yet'
          }
        </div>
      </div>
    </div>
  );
};

export default TaskStats;