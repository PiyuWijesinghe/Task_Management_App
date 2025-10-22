import React, { useState } from 'react';
import TaskStats from './TaskStats';
import HighPriorityTasks from './HighPriorityTasks';
import TaskManager from './TaskManager';
import SuccessNotification from './SuccessNotification';
import './Dashboard.css';

const Dashboard = ({ user, onLogout }) => {
  const [activeTab, setActiveTab] = useState('overview');
  const [notification, setNotification] = useState({
    show: false,
    message: '',
    type: 'success'
  });

  const showNotification = (message, type = 'success') => {
    setNotification({
      show: true,
      message,
      type
    });
  };

  const hideNotification = () => {
    setNotification({
      ...notification,
      show: false
    });
  };

  // Export handlers
  const handleExport = (type) => {
    let url = '';
    if (type === 'pdf') {
      url = '/api/reports/tasks/pdf';
    } else if (type === 'excel') {
      url = '/api/reports/tasks/excel';
    }
    // Open in new tab for download
    window.open(url, '_blank');
  };

  return (
    <div className="dashboard-container">
      <div className="dashboard-header">
        <h1>✨ Task Management Pro</h1>
        <div className="header-actions">
          <div className="tab-navigation">
            <button 
              className={`tab-button ${activeTab === 'overview' ? 'active' : ''}`}
              onClick={() => setActiveTab('overview')}
            >
              🏠 Overview
            </button>
            <button 
              className={`tab-button ${activeTab === 'tasks' ? 'active' : ''}`}
              onClick={() => setActiveTab('tasks')}
            >
              📋 Tasks
            </button>
          </div>
          {/* Export Buttons */}
          <button className="export-btn" onClick={() => handleExport('pdf')} title="Export as PDF">
            Export PDF
          </button>
          <button className="export-btn" onClick={() => handleExport('excel')} title="Export as Excel">
            Export Excel
          </button>
          <button className="logout-button" onClick={onLogout}>
            Sign Out
          </button>
        </div>
      </div>
      
      <div className="dashboard-content-wrapper">
        {activeTab === 'overview' ? (
          <>
            <div className="user-info">
              <h2>Welcome back! 👋</h2>
              <div className="user-details">
                <p><strong>📅 Member since:</strong> {new Date(user.created_at).toLocaleDateString()}</p>
              </div>
            </div>
            
            {/* Task Statistics */}
            <TaskStats />
            
            {/* High Priority Tasks Due Soon */}
            <HighPriorityTasks />
            
            <div className="dashboard-content">
              <div className="feature-card" onClick={() => setActiveTab('tasks')}>
                <h3>📋 Tasks</h3>
                <p>Manage your daily tasks efficiently</p>
              </div>
              
              <div className="feature-card">
                <h3>📊 Analytics</h3>
                <p>View your productivity statistics</p>
              </div>
              
              <div className="feature-card">
                <h3>⏰ Reminders</h3>
                <p>Never miss important deadlines</p>
              </div>
            </div>
          </>
        ) : (
          <TaskManager onShowNotification={showNotification} />
        )}
      </div>
      
      {/* Global Notification */}
      <SuccessNotification
        message={notification.message}
        isVisible={notification.show}
        onClose={hideNotification}
        type={notification.type}
        duration={3000}
      />
    </div>
  );
};

export default Dashboard;