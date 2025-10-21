import React, { useState, useEffect } from 'react';
import TaskService from '../services/TaskService';
import DebugUploadService from '../services/DebugUploadService';
import FileUpload from './FileUpload';
import './AttachmentsList.css';

const AttachmentsList = ({ taskId, canEdit = true, canUpload = true }) => {
  const [attachments, setAttachments] = useState([]);
  const [loading, setLoading] = useState(false);
  const [uploading, setUploading] = useState(false);
  const [error, setError] = useState('');
  const [showUpload, setShowUpload] = useState(false);

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

  useEffect(() => {
    if (taskId) {
      loadAttachments();
    }
  }, [taskId]);

  const loadAttachments = async () => {
    try {
      setLoading(true);
      const response = await TaskService.getTaskAttachments(taskId);
      setAttachments(response.data || []);
    } catch (error) {
      console.error('Failed to load attachments:', error);
      setAttachments([]);
    } finally {
      setLoading(false);
    }
  };

  const getFileIcon = (filename) => {
    const extension = filename.split('.').pop().toLowerCase();
    
    switch (extension) {
      case 'pdf':
        return '📄';
      case 'jpg':
      case 'jpeg':
      case 'png':
      case 'gif':
      case 'webp':
        return '🖼️';
      case 'doc':
      case 'docx':
        return '📝';
      case 'xls':
      case 'xlsx':
        return '📊';
      case 'ppt':
      case 'pptx':
        return '📈';
      case 'txt':
        return '📋';
      case 'zip':
      case 'rar':
        return '📦';
      default:
        return '📎';
    }
  };

  const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  };

  const handleAddAttachment = async (files) => {
    if (!files || files.length === 0) return;

    setUploading(true);
    setError('');

    try {
      // First try debug upload to test if basic upload works
      console.log('Testing debug upload first with file:', files[0]);
      try {
        const debugResult = await DebugUploadService.testUpload(files[0]);
        console.log('✅ Debug upload SUCCESS:', debugResult);
      } catch (debugError) {
        console.log('❌ Debug upload FAILED:', debugError.message);
        throw new Error(`Debug upload failed: ${debugError.message}`);
      }
      
      // If debug upload works, try the real upload
      console.log('Debug upload worked, now trying real API upload...');
      const uploadPromises = files.map(file => 
        TaskService.addTaskAttachment(taskId, file)
      );

      await Promise.all(uploadPromises);
      await loadAttachments(); // Refresh the list
      setShowUpload(false);
    } catch (error) {
      console.error('Failed to upload attachments:', error);
      console.error('Error details:', {
        message: error.message,
        taskId: taskId,
        filesCount: files.length,
        currentUser: getCurrentUser()
      });
      setError(`Upload failed: ${error.message || 'Unknown error'}. Please check console for details.`);
    } finally {
      setUploading(false);
    }
  };

  const handleDownload = async (attachment) => {
    try {
      const blob = await TaskService.downloadTaskAttachment(taskId, attachment.id);
      const url = window.URL.createObjectURL(blob);
      const link = document.createElement('a');
      link.href = url;
      link.download = attachment.original_name;
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
      window.URL.revokeObjectURL(url);
    } catch (error) {
      console.error('Failed to download attachment:', error);
      setError('Failed to download file');
    }
  };

  const handleDelete = async (attachment) => {
    if (!window.confirm(`Are you sure you want to delete "${attachment.original_name}"?`)) {
      return;
    }

    try {
      await TaskService.deleteTaskAttachment(taskId, attachment.id);
      await loadAttachments(); // Refresh the list
    } catch (error) {
      console.error('Failed to delete attachment:', error);
      setError('Failed to delete attachment');
    }
  };

  if (loading) {
    return (
      <div className="attachments-loading">
        <div className="loading-spinner">⏳</div>
        <span>Loading attachments...</span>
      </div>
    );
  }

  return (
    <div className="attachments-container">
      <div className="attachments-header">
        <h4>📎 Attachments ({attachments.length})</h4>
        {canUpload && (
          <button
            className="add-attachment-button"
            onClick={() => setShowUpload(!showUpload)}
            disabled={uploading}
          >
            {showUpload ? '✕ Cancel' : '➕ Add Files'}
          </button>
        )}
        {!canUpload && attachments.length > 0 && (
          <span className="view-only-indicator">
            👁️ View & Download Only
          </span>
        )}
      </div>

      {error && (
        <div className="error-message">
          ⚠️ {error}
        </div>
      )}

      {showUpload && canUpload && (
        <div className="upload-section">
          <FileUpload
            files={[]}
            onFilesChange={handleAddAttachment}
            maxFiles={10}
            maxSizeInMB={10}
          />
          {uploading && (
            <div className="uploading-message">
              ⏳ Uploading files...
            </div>
          )}
        </div>
      )}

      {attachments.length === 0 ? (
        <div className="no-attachments">
          📎 No attachments yet
          {canUpload ? (
            <p>
              <button
                className="upload-first-button"
                onClick={() => setShowUpload(true)}
              >
                Upload your first file
              </button>
            </p>
          ) : (
            <p className="no-attachments-message">
              No files have been uploaded yet.
            </p>
          )}
        </div>
      ) : (
        <div className="attachments-list">
          {attachments.map((attachment) => (
            <div key={attachment.id} className="attachment-item">
              <div className="attachment-info">
                <span className="file-icon">{getFileIcon(attachment.original_name)}</span>
                <div className="attachment-details">
                  <div className="attachment-name">{attachment.original_name}</div>
                  <div className="attachment-meta">
                    <span className="file-size">{formatFileSize(attachment.size)}</span>
                    <span className="upload-date">Uploaded: {formatDate(attachment.created_at)}</span>
                    {attachment.uploaded_by && (
                      <span className="uploaded-by">
                        👤 By: {attachment.uploaded_by.name}
                      </span>
                    )}
                  </div>
                </div>
              </div>
              <div className="attachment-actions">
                <button
                  className="download-button"
                  onClick={() => handleDownload(attachment)}
                  title="Download"
                >
                  ⬇️
                </button>
                {getCurrentUser()?.id === attachment.uploaded_by?.id && (
                  <button
                    className="delete-button"
                    onClick={() => handleDelete(attachment)}
                    title="Delete (Only uploader can delete)"
                  >
                    🗑️
                  </button>
                )}
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default AttachmentsList;