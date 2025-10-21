import React, { useState, useEffect } from 'react';
import TaskService from '../services/TaskService';
import ConfirmDialog from './ConfirmDialog';
import './TaskAssignment.css';

const TaskAssignment = ({ task, onAssignmentUpdated, isOpen, onClose, onShowNotification }) => {
  const [users, setUsers] = useState([]);
  const [assignees, setAssignees] = useState([]);
  const [selectedUser, setSelectedUser] = useState('');
  const [searchQuery, setSearchQuery] = useState('');
  const [loading, setLoading] = useState(false);
  const [assigning, setAssigning] = useState(false);
  const [error, setError] = useState('');
  const [showSuggestions, setShowSuggestions] = useState(false);
  const [selectedSuggestion, setSelectedSuggestion] = useState(-1);
  const [showAssignConfirm, setShowAssignConfirm] = useState(false);
  const [showUnassignConfirm, setShowUnassignConfirm] = useState(false);
  const [userToAssign, setUserToAssign] = useState(null);
  const [userToUnassign, setUserToUnassign] = useState(null);

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
      setError('Failed to load users');
      setUsers([]); // Set empty array on error
    } finally {
      setLoading(false);
    }
  };

  const loadAssignees = async () => {
    try {
      const response = await TaskService.getTaskAssignees(task.id);
      // Handle Laravel response structure
      const assigneesData = response?.data?.assignees || response?.data;
      if (Array.isArray(assigneesData)) {
        console.log('Loaded assignees:', assigneesData); // Debug log
        setAssignees(assigneesData);
      } else {
        console.warn('API response does not contain assignees array:', response);
        setAssignees([]);
      }
    } catch (error) {
      console.error('Failed to load assignees:', error);
      setAssignees([]); // Set empty array on error
    }
  };

  const showAssignConfirmation = (userId) => {
    if (!userId) return;
    
    const selectedUserObj = users.find(user => user.id === parseInt(userId));
    if (!selectedUserObj) {
      setError('Selected user not found');
      return;
    }
    
    setUserToAssign(selectedUserObj);
    setShowAssignConfirm(true);
  };

  const handleAssignUser = async () => {
    if (!userToAssign) return;

    try {
      setAssigning(true);
      setError('');
      
      if (!userToAssign.username) {
        setError('Selected user missing username');
        return;
      }
      
      console.log('Assigning user with username:', userToAssign.username); // Debug log
      await TaskService.assignUserToTask(task.id, userToAssign.username, 'additional');
      
      // Refresh assignees list
      await loadAssignees();
      
      // Clear selection
      setSelectedUser('');
      setShowAssignConfirm(false);
      setUserToAssign(null);
      
      // Notify parent
      if (onAssignmentUpdated) {
        onAssignmentUpdated(task.id);
      }
      
      if (onShowNotification) {
        onShowNotification(`👥 User "${userToAssign.name || userToAssign.username}" assigned successfully!`, 'success');
      }
      
    } catch (error) {
      console.error('Assignment error:', error);
      let errorMessage = 'Failed to assign user';
      
      // Handle specific error messages from backend
      if (error.message.includes('Validation failed')) {
        errorMessage = 'Invalid user data. Please try again.';
      } else if (error.message.includes('Target user not found')) {
        errorMessage = 'Selected user not found';
      } else if (error.message.includes('permission')) {
        errorMessage = 'You do not have permission to assign users to this task';
      } else {
        errorMessage = error.message || 'Failed to assign user';
      }
      
      setError(errorMessage);
    } finally {
      setAssigning(false);
    }
  };

  const showUnassignConfirmation = (userId, userName) => {
    setUserToUnassign({ id: userId, name: userName });
    setShowUnassignConfirm(true);
  };

  const handleUnassignUser = async () => {
    if (!userToUnassign) return;

    try {
      setAssigning(true);
      setError('');
      
      await TaskService.unassignUserFromTask(task.id, userToUnassign.id);
      
      // Refresh assignees list
      await loadAssignees();
      setShowUnassignConfirm(false);
      
      // Notify parent
      if (onAssignmentUpdated) {
        onAssignmentUpdated(task.id);
      }
      
      if (onShowNotification) {
        onShowNotification(`👤 User "${userToUnassign.user?.name || userToUnassign.name}" unassigned successfully!`, 'success');
      }
      setUserToUnassign(null);
      
    } catch (error) {
      setError(error.message || 'Failed to unassign user');
    } finally {
      setAssigning(false);
    }
  };

  const getAvailableUsers = () => {
    if (!Array.isArray(users) || !Array.isArray(assignees)) return [];
    // Handle both nested structure {user: {...}} and direct user objects
    const assignedUserIds = assignees.map(assignee => {
      return assignee.user?.id || assignee.id;
    }).filter(Boolean); // Remove any undefined values
    
    return users.filter(user => !assignedUserIds.includes(user.id));
  };

  const getFilteredUsers = () => {
    const availableUsers = getAvailableUsers();
    if (!searchQuery) return availableUsers;
    
    return availableUsers.filter(user => 
      (user.username && user.username.toLowerCase().includes(searchQuery.toLowerCase())) ||
      (user.name && user.name.toLowerCase().includes(searchQuery.toLowerCase()))
    );
  };

  const handleSearchChange = (e) => {
    const value = e.target.value;
    setSearchQuery(value);
    setShowSuggestions(value.trim().length > 0);
    setSelectedSuggestion(-1);
  };

  const handleSuggestionSelect = (user) => {
    showAssignConfirmation(user.id);
    setSearchQuery('');
    setShowSuggestions(false);
    setSelectedSuggestion(-1);
  };

  const handleKeyDown = (e) => {
    const filteredUsers = getFilteredUsers();
    
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      setSelectedSuggestion(prev => 
        prev < filteredUsers.length - 1 ? prev + 1 : prev
      );
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      setSelectedSuggestion(prev => prev > 0 ? prev - 1 : -1);
    } else if (e.key === 'Enter') {
      e.preventDefault();
      if (selectedSuggestion >= 0 && filteredUsers[selectedSuggestion]) {
        handleSuggestionSelect(filteredUsers[selectedSuggestion]);
      }
    } else if (e.key === 'Escape') {
      setShowSuggestions(false);
      setSelectedSuggestion(-1);
    }
  };

  const handleClose = () => {
    setSelectedUser('');
    setSearchQuery('');
    setError('');
    setShowSuggestions(false);
    setSelectedSuggestion(-1);
    if (onClose) {
      onClose();
    }
  };

  if (!isOpen || !task) return null;

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
          {/* Current Assignees */}
          <div className="current-assignees">
            <h4>Current Assignees ({assignees.length})</h4>
            {!Array.isArray(assignees) || assignees.length === 0 ? (
              <p className="no-assignees">No users assigned to this task</p>
            ) : (
              <div className="assignees-list">
                {assignees.map((assignee, index) => {
                  // Handle both direct user object and nested structure
                  const user = assignee.user || assignee;
                  const assigneeType = assignee.type || 'assigned';
                  const userId = user.id || assignee.id;
                  const userName = user.username || user.name || assignee.username || assignee.name || 'Unknown User';
                  
                  return (
                    <div key={userId || index} className="assignee-item">
                      <div className="assignee-info">
                        <span className="assignee-name">
                          {userName} {assigneeType === 'primary' && '(Primary)'}
                        </span>
                      </div>
                      <button
                        className="unassign-button"
                        onClick={() => showUnassignConfirmation(userId, userName)}
                        disabled={assigning}
                        title="Unassign user"
                      >
                        🗑️
                      </button>
                    </div>
                  );
                })}
              </div>
            )}
          </div>

          {/* Add New Assignee */}
          <div className="add-assignee">
            <h4>Add Assignee</h4>
            
            {loading ? (
              <p className="loading">Loading users...</p>
            ) : (
              <>
                {/* Autocomplete Search */}
                <div className="assignee-search">
                  <label className="search-label">
                    👤 Add Assignee
                  </label>
                  <div className="autocomplete-container">
                    <input
                      type="text"
                      placeholder="Type to search and select a user..."
                      value={searchQuery}
                      onChange={handleSearchChange}
                      onKeyDown={handleKeyDown}
                      onFocus={() => setShowSuggestions(searchQuery.trim().length > 0)}
                      onBlur={() => setTimeout(() => setShowSuggestions(false), 200)}
                      className="autocomplete-input"
                      disabled={assigning}
                    />
                    
                    {showSuggestions && (
                      <div className="suggestions-dropdown">
                        {getFilteredUsers().length === 0 ? (
                          <div className="no-suggestions">
                            {searchQuery ? 'No users found' : 'All users are already assigned'}
                          </div>
                        ) : (
                          getFilteredUsers().map((user, index) => (
                            <div
                              key={user.id}
                              className={`suggestion-item ${index === selectedSuggestion ? 'selected' : ''}`}
                              onClick={() => handleSuggestionSelect(user)}
                            >
                              <span className="user-avatar">👤</span>
                              <div className="user-details">
                                <div className="user-primary">{user.username || user.name}</div>
                                {user.username && user.name && (
                                  <div className="user-secondary">{user.name}</div>
                                )}
                              </div>
                            </div>
                          ))
                        )}
                      </div>
                    )}
                  </div>
                </div>

                {/* Quick Assign Buttons */}
                {!searchQuery && getFilteredUsers().length > 0 && (
                  <div className="quick-assign">
                    <h5>Quick Assign:</h5>
                    <div className="quick-assign-buttons">
                      {getFilteredUsers().slice(0, 5).map(user => (
                        <button
                          key={user.id}
                          className="quick-assign-button"
                          onClick={() => showAssignConfirmation(user.id)}
                          disabled={assigning}
                          title={`Assign to ${user.name}`}
                        >
                          👤 {user.name}
                        </button>
                      ))}
                    </div>
                  </div>
                )}
              </>
            )}
          </div>
        </div>

        <div className="assignment-actions">
          <button
            className="done-button"
            onClick={handleClose}
            disabled={assigning}
          >
            ✅ Done
          </button>
        </div>
      </div>

      {/* Assignment Confirmation Dialog */}
      <ConfirmDialog
        isOpen={showAssignConfirm}
        title="Assign User to Task"
        message={`Are you sure you want to assign "${userToAssign?.name || userToAssign?.username}" to "${task?.title}"?`}
        confirmText="Assign User"
        cancelText="Cancel"
        type="success"
        loading={assigning}
        onConfirm={handleAssignUser}
        onCancel={() => {
          setShowAssignConfirm(false);
          setUserToAssign(null);
        }}
      />

      {/* Unassignment Confirmation Dialog */}
      <ConfirmDialog
        isOpen={showUnassignConfirm}
        title="Unassign User from Task"
        message={`Are you sure you want to unassign "${userToUnassign?.name}" from "${task?.title}"?`}
        confirmText="Unassign User"
        cancelText="Cancel"
        type="warning"
        loading={assigning}
        onConfirm={handleUnassignUser}
        onCancel={() => {
          setShowUnassignConfirm(false);
          setUserToUnassign(null);
        }}
      />

    </div>
  );
};

export default TaskAssignment;