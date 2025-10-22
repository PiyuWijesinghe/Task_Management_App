import React, { useState, useEffect } from 'react';
import TaskService from '../services/TaskService';
import './TaskAssignment.css';

const TaskAssignment = ({ task, onAssignmentUpdated, isOpen, onClose, onShowNotification }) => {
  const [users, setUsers] = useState([]);
  const [assignees, setAssignees] = useState([]);
  const [selectedUsers, setSelectedUsers] = useState([]);
  const [loading, setLoading] = useState(false);
  const [assigning, setAssigning] = useState(false);
  const [error, setError] = useState('');

  useEffect(() => {
    if (isOpen && task) {
      loadUsers();
      loadAssignees();
    }
  }, [isOpen, task]);

  const loadUsers = async () => {
    try {
      setLoading(true);
      const response = await TaskService.getUsers();
      const usersData = response?.data?.users || response?.data;
      if (Array.isArray(usersData)) {
        setUsers(usersData);
      } else {
        setUsers([]);
      }
    } catch (error) {
      setError('Failed to load users');
      setUsers([]);
    } finally {
      setLoading(false);
    }
  };

  const loadAssignees = async () => {
    try {
      const response = await TaskService.getTaskAssignees(task.id);
      const assigneesData = response?.data?.assignees || response?.data;
      if (Array.isArray(assigneesData)) {
        setAssignees(assigneesData);
        setSelectedUsers(assigneesData.map(a => a.user?.id || a.id));
      } else {
        setAssignees([]);
        setSelectedUsers([]);
      }
    } catch (error) {
      setAssignees([]);
      setSelectedUsers([]);
    }
  };

  // Remove all single-user assign, search, and suggestion logic
  const handleClose = () => {
    setError('');
    if (onClose) {
      onClose();
    }
  };

  if (!isOpen || !task) return null;

  // Handle checkbox change
  const handleCheckboxChange = (userId) => {
    setSelectedUsers(prev =>
      prev.includes(userId)
        ? prev.filter(id => id !== userId)
        : [...prev, userId]
    );
  };

  // Save assignments
  const handleSaveAssignments = async () => {
    setAssigning(true);
    setError('');
    try {
      await TaskService.updateTaskAssignees(task.id, selectedUsers);
      if (onAssignmentUpdated) onAssignmentUpdated();
      if (onShowNotification) onShowNotification('Assignees updated successfully!', 'success');
      loadAssignees();
    } catch (e) {
      setError('Failed to update assignees');
    } finally {
      setAssigning(false);
    }
  };

  return (
    <div className="assignment-overlay">
      <div className="assignment-modal">
        <div className="assignment-header">
          <h2>👥 Assign Task</h2>
          <button 
            className="close-button"
            onClick={handleClose}
            disabled={assigning}
          >
            ✕
          </button>
        </div>

        <div className="task-info">
          <h3>{task.title}</h3>
          <p>{task.description}</p>
        </div>

        {error && (
          <div className="error-message">
            <span>⚠️ {error}</span>
          </div>
        )}

        <div className="assignment-content">
          <div className="current-assignees">
            <h4>Current Assignees ({selectedUsers.length})</h4>
            {users.length === 0 ? (
              <p className="no-assignees">No users available</p>
            ) : (
              <select
                multiple
                className="assignees-multiselect"
                value={selectedUsers.map(String)}
                onChange={e => {
                  const options = Array.from(e.target.selectedOptions, option => option.value);
                  setSelectedUsers(options.map(Number));
                }}
                disabled={assigning}
                size={Math.min(8, users.length)}
                style={{ width: '100%', minHeight: '120px', fontSize: '1rem', padding: '8px' }}
              >
                {users.map(user => (
                  <option key={user.id} value={user.id}>
                    {user.username || user.name}
                  </option>
                ))}
              </select>
            )}
          </div>
        </div>

        <div className="assignment-actions">
          <button
            className="save-button"
            onClick={handleSaveAssignments}
            disabled={assigning}
          >
            💾 Save
          </button>
          <button
            className="done-button"
            onClick={handleClose}
            disabled={assigning}
          >
            ✅ Done
          </button>
        </div>
      </div>
    </div>
  );
};

export default TaskAssignment;