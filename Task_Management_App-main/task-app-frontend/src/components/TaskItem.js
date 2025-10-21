import React, { useState, useEffect } from 'react';
import TaskService from '../services/TaskService';
import LoadingSpinner from './LoadingSpinner';
import ErrorNotification from './ErrorNotification';
import ConfirmDialog from './ConfirmDialog';
import AttachmentsList from './AttachmentsList';
import EditTask from './EditTask';
import './TaskItem.css';

const TaskItem = ({ task, onTaskUpdated, onTaskDeleted, showComments = false }) => {
  const [isExpanded, setIsExpanded] = useState(false);
  const [comments, setComments] = useState([]);
  const [newComment, setNewComment] = useState('');
  const [loading, setLoading] = useState(false);
  const [commenting, setCommenting] = useState(false);
  const [assignees, setAssignees] = useState([]);
  const [showStatusMenu, setShowStatusMenu] = useState(false);
  const [showPostponeModal, setShowPostponeModal] = useState(false);
  const [postponeData, setPostponeData] = useState({
    new_due_date: '',
    reason: ''
  });

  const [error, setError] = useState(null);
  const [showDeleteConfirm, setShowDeleteConfirm] = useState(false);
  const [deleting, setDeleting] = useState(false);

  const [showPostponeConfirm, setShowPostponeConfirm] = useState(false);
  const [showEditModal, setShowEditModal] = useState(false);

  // Load comments when expanded
  useEffect(() => {
    if (isExpanded && showComments) {
      loadComments();
      loadAssignees();
    }
  }, [isExpanded, showComments]);

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



  const loadComments = async () => {
    try {
      setLoading(true);
      const response = await TaskService.getTaskComments(task.id);
      // Handle Laravel paginated response structure
      const commentsData = response?.data?.comments || response?.data;
      if (Array.isArray(commentsData)) {
        console.log('Loading comments:', commentsData); // Debug log
        setComments(commentsData);
      } else {
        console.warn('API response does not contain comments array:', response);
        setComments([]);
      }
    } catch (error) {
      console.error('Failed to load comments:', error);
      setComments([]); // Set empty array on error
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

  const handleStatusUpdate = async (newStatus) => {
    try {
      await TaskService.updateTaskStatus(task.id, newStatus);
      if (onTaskUpdated) {
        onTaskUpdated({ ...task, status: newStatus }, 'status');
      }
      setShowStatusMenu(false);
    } catch (error) {
      console.error('Failed to update task status:', error);
    }
  };

  const handleMarkCompleted = async () => {
    try {
      await TaskService.markTaskCompleted(task.id);
      if (onTaskUpdated) {
        onTaskUpdated({ ...task, status: 'Completed' });
      }
    } catch (error) {
      console.error('Failed to mark task as completed:', error);
    }
  };

  const handlePriorityUpdate = async (newPriority) => {
    try {
      await TaskService.updateTaskPriority(task.id, newPriority);
      if (onTaskUpdated) {
        onTaskUpdated({ ...task, priority: newPriority });
      }
    } catch (error) {
      console.error('Failed to update task priority:', error);
    }
  };

  const handlePostponeSubmit = (e) => {
    e.preventDefault();
    setShowPostponeConfirm(true);
  };

  const handlePostpone = async () => {
    try {
      await TaskService.postponeTask(task.id, postponeData);
      if (onTaskUpdated) {
        onTaskUpdated({ ...task, due_date: postponeData.new_due_date }, 'postponed');
      }
      setShowPostponeModal(false);
      setShowPostponeConfirm(false);
      setPostponeData({ new_due_date: '', reason: '' });
    } catch (error) {
      console.error('Failed to postpone task:', error);
      setError('Failed to postpone task. Please try again.');
    }
  };

  const handleAddComment = async (e) => {
    e.preventDefault();
    if (!newComment.trim()) return;

    try {
      setCommenting(true);
      const response = await TaskService.addComment(task.id, {
        comment: newComment.trim()
      });
      // Handle Laravel response structure and ensure comments is always an array
      const currentComments = Array.isArray(comments) ? comments : [];
      const newCommentData = response?.data?.comment || response?.data;
      console.log('Adding comment response:', response); // Debug log
      console.log('New comment data:', newCommentData); // Debug log
      if (newCommentData && typeof newCommentData === 'object') {
        setComments([...currentComments, newCommentData]);
      }
      setNewComment('');
    } catch (error) {
      console.error('Failed to add comment:', error);
    } finally {
      setCommenting(false);
    }
  };

  const handleDeleteTask = async () => {
    try {
      setDeleting(true);
      await TaskService.deleteTask(task.id);
      if (onTaskDeleted) {
        onTaskDeleted(task.id, task.title);
      }
      setShowDeleteConfirm(false);
    } catch (error) {
      console.error('Failed to delete task:', error);
      setError('Failed to delete task. Please try again.');
    } finally {
      setDeleting(false);
    }
  };





  const getStatusIcon = (status) => {
    const normalizedStatus = status?.toLowerCase();
    switch(normalizedStatus) {
      case 'pending': return '⏳';
      case 'in_progress':
      case 'in progress': return '🔄';
      case 'completed': return '✅';
      default: return '📋';
    }
  };

  const getPriorityIcon = (priority) => {
    const normalizedPriority = priority?.toLowerCase();
    switch (normalizedPriority) {
      case 'low': return '🟢';
      case 'medium': return '🟡';
      case 'high': return '🔴';
      default: return '⚪';
    }
  };

  const formatDate = (dateString) => {
    if (!dateString) return 'No due date';
    return new Date(dateString).toLocaleDateString();
  };

  const isOverdue = () => {
    if (!task.due_date) return false;
    const normalizedStatus = task.status?.toLowerCase();
    return new Date(task.due_date) < new Date() && normalizedStatus !== 'completed';
  };

  return (
    <div className={`task-item ${task.status} ${isOverdue() ? 'overdue' : ''}`}>
      <div className="task-header" onClick={() => setIsExpanded(!isExpanded)}>
        <div className="task-main-info">
          <div className="task-title-row">
            <span className="task-status-icon">{getStatusIcon(task.status)}</span>
            <h3 className="task-title">{task.title}</h3>
            <span className="task-priority">{getPriorityIcon(task.priority)}</span>
          </div>
          
          {task.description && (
            <p className="task-description">{task.description}</p>
          )}
          
          <div className="task-meta">
            <span className="due-date">
              📅 {formatDate(task.due_date)}
            </span>
            {Array.isArray(assignees) && assignees.length > 0 && (
              <span className="assignees">
                👤 {assignees.map(a => a.user?.username || a.username || a.user?.name || a.name).join(', ')}
              </span>
            )}
          </div>
        </div>

        <div className="task-actions" onClick={(e) => e.stopPropagation()}>
          <button className="expand-button" onClick={() => setIsExpanded(!isExpanded)}>
            {isExpanded ? '🔼' : '🔽'}
          </button>
        </div>
      </div>

      {isExpanded && (
        <div className="task-details">
          {/* Action Buttons */}
          <div className="task-controls">
            <div className="status-controls">
              {/* Only allow status changes by task creator */}
              {getCurrentUser()?.id === task.user_id ? (
                <div className="dropdown">
                  <button 
                    className="dropdown-button"
                    onClick={() => setShowStatusMenu(!showStatusMenu)}
                  >
                    {getStatusIcon(task.status)} Status
                  </button>
                  {showStatusMenu && (
                    <div className="dropdown-menu">
                      <button onClick={() => handleStatusUpdate('Pending')}>⏳ Pending</button>
                      <button onClick={() => handleStatusUpdate('In Progress')}>🔄 In Progress</button>
                      <button onClick={() => handleMarkCompleted()}>✅ Complete</button>
                    </div>
                  )}
                </div>
              ) : (
                <span className="status-display">
                  {getStatusIcon(task.status)} {task.status}
                </span>
              )}

              {/* Only allow priority changes by task creator */}
              {getCurrentUser()?.id === task.user_id ? (
                <select 
                  value={task.priority?.charAt(0).toUpperCase() + task.priority?.slice(1).toLowerCase() || 'Medium'} 
                  onChange={(e) => handlePriorityUpdate(e.target.value)}
                  className="priority-select"
                >
                  <option value="Low">🟢 Low</option>
                  <option value="Medium">🟡 Medium</option>
                  <option value="High">🔴 High</option>
                </select>
              ) : (
                <span className="priority-display">
                  {task.priority === 'Low' && '🟢 Low'}
                  {task.priority === 'Medium' && '🟡 Medium'}
                  {task.priority === 'High' && '🔴 High'}
                </span>
              )}

              <button 
                className="postpone-button"
                onClick={() => setShowPostponeModal(true)}
              >
                ⏰ Postpone
              </button>

              {/* Only show Edit and Delete buttons to task creator */}
              {getCurrentUser()?.id === task.user_id && (
                <>
                  <button 
                    className="edit-button"
                    onClick={() => setShowEditModal(true)}
                  >
                    ✏️ Edit
                  </button>

                  <button 
                    className="delete-button"
                    onClick={() => setShowDeleteConfirm(true)}
                  >
                    🗑️ Delete
                  </button>
                </>
              )}
            </div>
          </div>

          {/* Attachments Section */}
          {isExpanded && (
            <div className="attachments-section">
              <AttachmentsList 
                taskId={task.id}
                canEdit={getCurrentUser()?.id === task.user_id}
                canUpload={true} // Both creators and assignees can upload files
              />
            </div>
          )}

          {/* Comments Section */}
          {showComments && (
            <div className="comments-section">
              <h4>💬 Comments ({comments.length})</h4>
              
              {loading ? (
                <p className="loading">Loading comments...</p>
              ) : (
                <>
                  {!Array.isArray(comments) || comments.length === 0 ? (
                    <p className="no-comments">No comments yet</p>
                  ) : (
                    <div className="comments-list">
                      {comments.map((comment, index) => {
                        // Ensure comment is a valid object
                        if (!comment || typeof comment !== 'object') {
                          console.warn('Invalid comment object:', comment);
                          return null;
                        }
                        
                        return (
                          <div key={comment.id || index} className="comment-item">
                            <div className="comment-header">
                              <strong>
                                {comment.user?.username || comment.user?.name || 'Unknown User'}
                              </strong>
                              <span className="comment-date">
                                {comment.created_at ? new Date(comment.created_at).toLocaleString() : 'Unknown Date'}
                              </span>
                            </div>
                            <p className="comment-text">
                              {String(comment.comment || comment.text || 'No comment text')}
                            </p>
                          </div>
                        );
                      })}
                    </div>
                  )}

                  {/* Add Comment Form */}
                  <form onSubmit={handleAddComment} className="add-comment-form">
                    <textarea
                      value={newComment}
                      onChange={(e) => setNewComment(e.target.value)}
                      placeholder="Add a comment..."
                      rows="3"
                      disabled={commenting}
                    />
                    <button 
                      type="submit" 
                      disabled={commenting || !newComment.trim()}
                      className="submit-comment-button"
                    >
                      {commenting ? '⏳ Adding...' : '💬 Add Comment'}
                    </button>
                  </form>
                </>
              )}
            </div>
          )}
        </div>
      )}

      {/* Postpone Modal */}
      {showPostponeModal && (
        <div className="modal-overlay">
          <div className="modal">
            <h3>⏰ Postpone Task</h3>
            <form onSubmit={handlePostponeSubmit}>
              <div className="form-group">
                <label>New Due Date</label>
                <input
                  type="datetime-local"
                  value={postponeData.new_due_date}
                  onChange={(e) => setPostponeData({...postponeData, new_due_date: e.target.value})}
                  required
                />
              </div>
              <div className="form-group">
                <label>Reason (Optional)</label>
                <textarea
                  value={postponeData.reason}
                  onChange={(e) => setPostponeData({...postponeData, reason: e.target.value})}
                  placeholder="Why are you postponing this task?"
                  rows="3"
                />
              </div>
              <div className="modal-actions">
                <button type="button" onClick={() => setShowPostponeModal(false)}>Cancel</button>
                <button type="submit">Postpone</button>
              </div>
            </form>
          </div>
        </div>
      )}



      {/* Postpone Task Confirmation Dialog */}
      <ConfirmDialog
        isOpen={showPostponeConfirm}
        title="Postpone Task"
        message={`Are you sure you want to postpone "${task.title}" to ${postponeData.new_due_date ? new Date(postponeData.new_due_date).toLocaleDateString() : 'the selected date'}?`}
        confirmText="Postpone Task"
        cancelText="Cancel"
        type="warning"
        onConfirm={handlePostpone}
        onCancel={() => setShowPostponeConfirm(false)}
      />

      {/* Delete Confirmation Dialog */}
      <ConfirmDialog
        isOpen={showDeleteConfirm}
        title="Delete Task"
        message={`Are you sure you want to delete "${task.title}"? This action cannot be undone.`}
        confirmText="Delete"
        cancelText="Cancel"
        type="danger"
        loading={deleting}
        onConfirm={handleDeleteTask}
        onCancel={() => setShowDeleteConfirm(false)}
      />

      {/* Edit Task Modal */}
      <EditTask
        task={task}
        isOpen={showEditModal}
        onClose={() => setShowEditModal(false)}
        onTaskUpdated={onTaskUpdated}
      />

      {/* Error Notification */}
      <ErrorNotification
        error={error}
        onClose={() => setError(null)}
      />
    </div>
  );
};

export default TaskItem;