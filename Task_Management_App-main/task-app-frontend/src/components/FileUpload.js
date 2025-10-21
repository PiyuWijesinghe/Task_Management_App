import React, { useState } from 'react';
import './FileUpload.css';

const FileUpload = ({ files, onFilesChange, maxFiles = 5, maxSizeInMB = 10 }) => {
  const [dragActive, setDragActive] = useState(false);
  const [errors, setErrors] = useState([]);

  const allowedTypes = [
    'application/pdf',
    'image/jpeg', 
    'image/jpg',
    'image/png',
    'image/gif',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'text/plain',
    'application/vnd.ms-powerpoint',
    'application/vnd.openxmlformats-officedocument.presentationml.presentation'
  ];

  const getFileIcon = (fileType) => {
    if (fileType.startsWith('image/')) return '🖼️';
    if (fileType === 'application/pdf') return '📄';
    if (fileType.includes('word') || fileType.includes('document')) return '📝';
    if (fileType.includes('excel') || fileType.includes('sheet')) return '📊';
    if (fileType.includes('powerpoint') || fileType.includes('presentation')) return '📈';
    if (fileType === 'text/plain') return '📋';
    return '📎';
  };

  const formatFileSize = (bytes) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  };

  const validateFile = (file) => {
    const errors = [];
    
    // Check file type
    if (!allowedTypes.includes(file.type)) {
      errors.push(`${file.name}: File type not allowed`);
    }
    
    // Check file size (convert MB to bytes)
    const maxSizeInBytes = maxSizeInMB * 1024 * 1024;
    if (file.size > maxSizeInBytes) {
      errors.push(`${file.name}: File size exceeds ${maxSizeInMB}MB limit`);
    }
    
    return errors;
  };

  const handleFiles = (newFiles) => {
    const fileArray = Array.from(newFiles);
    let allErrors = [];
    
    // Check total file count
    if (files.length + fileArray.length > maxFiles) {
      allErrors.push(`Cannot upload more than ${maxFiles} files`);
      setErrors(allErrors);
      return;
    }
    
    // Validate each file
    const validFiles = [];
    fileArray.forEach(file => {
      const fileErrors = validateFile(file);
      if (fileErrors.length === 0) {
        // Add preview URL for images
        if (file.type.startsWith('image/')) {
          file.preview = URL.createObjectURL(file);
        }
        validFiles.push(file);
      } else {
        allErrors = [...allErrors, ...fileErrors];
      }
    });
    
    setErrors(allErrors);
    
    if (validFiles.length > 0) {
      onFilesChange([...files, ...validFiles]);
    }
  };

  const removeFile = (index) => {
    const newFiles = [...files];
    const removedFile = newFiles[index];
    
    // Revoke preview URL to prevent memory leaks
    if (removedFile.preview) {
      URL.revokeObjectURL(removedFile.preview);
    }
    
    newFiles.splice(index, 1);
    onFilesChange(newFiles);
  };

  const handleDrag = (e) => {
    e.preventDefault();
    e.stopPropagation();
    if (e.type === "dragenter" || e.type === "dragover") {
      setDragActive(true);
    } else if (e.type === "dragleave") {
      setDragActive(false);
    }
  };

  const handleDrop = (e) => {
    e.preventDefault();
    e.stopPropagation();
    setDragActive(false);
    
    if (e.dataTransfer.files && e.dataTransfer.files[0]) {
      handleFiles(e.dataTransfer.files);
    }
  };

  const handleInputChange = (e) => {
    if (e.target.files && e.target.files[0]) {
      handleFiles(e.target.files);
    }
  };

  return (
    <div className="file-upload-container">
      <label className="file-upload-label">📎 Attachments</label>
      
      <div
        className={`file-upload-area ${dragActive ? 'drag-active' : ''}`}
        onDragEnter={handleDrag}
        onDragLeave={handleDrag}
        onDragOver={handleDrag}
        onDrop={handleDrop}
      >
        <div className="file-upload-content">
          <div className="upload-icon">📁</div>
          <p>Drag & drop files here or <button type="button" className="browse-button">browse</button></p>
          <small>
            Supported: PDF, Images, Word, Excel, PowerPoint, Text files
            <br />
            Max size: {maxSizeInMB}MB per file, {maxFiles} files total
          </small>
          <input
            type="file"
            multiple
            onChange={handleInputChange}
            accept=".pdf,.jpg,.jpeg,.png,.gif,.doc,.docx,.xls,.xlsx,.txt,.ppt,.pptx"
            className="file-input-hidden"
          />
        </div>
      </div>

      {/* Error Messages */}
      {errors.length > 0 && (
        <div className="file-upload-errors">
          {errors.map((error, index) => (
            <div key={index} className="error-message">
              ⚠️ {error}
            </div>
          ))}
        </div>
      )}

      {/* File List */}
      {files.length > 0 && (
        <div className="uploaded-files">
          <h4>📎 Attached Files ({files.length}/{maxFiles})</h4>
          <div className="files-list">
            {files.map((file, index) => (
              <div key={index} className="file-item">
                <div className="file-info">
                  <span className="file-icon">{getFileIcon(file.type)}</span>
                  <div className="file-details">
                    <div className="file-name">{file.name}</div>
                    <div className="file-size">{formatFileSize(file.size)}</div>
                  </div>
                  {file.preview && (
                    <div className="file-preview">
                      <img src={file.preview} alt="Preview" className="preview-image" />
                    </div>
                  )}
                </div>
                <button
                  type="button"
                  className="remove-file-button"
                  onClick={() => removeFile(index)}
                  title="Remove file"
                >
                  ✕
                </button>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
};

export default FileUpload;