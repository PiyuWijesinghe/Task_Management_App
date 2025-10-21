import React, { useState, useEffect } from 'react';
import './ErrorNotification.css';

const ErrorNotification = ({ 
  error, 
  onClose, 
  autoClose = true, 
  duration = 5000,
  type = 'error' 
}) => {
  const [visible, setVisible] = useState(false);

  useEffect(() => {
    if (error) {
      setVisible(true);
      if (autoClose) {
        const timer = setTimeout(() => {
          handleClose();
        }, duration);
        return () => clearTimeout(timer);
      }
    }
  }, [error, autoClose, duration]);

  const handleClose = () => {
    setVisible(false);
    setTimeout(() => {
      if (onClose) onClose();
    }, 300);
  };

  if (!error || !visible) return null;

  const getIcon = () => {
    switch (type) {
      case 'success': return '✅';
      case 'warning': return '⚠️';
      case 'info': return 'ℹ️';
      default: return '❌';
    }
  };

  const getMessage = () => {
    if (typeof error === 'string') return error;
    if (error.message) return error.message;
    if (error.response?.data?.message) return error.response.data.message;
    return 'An unexpected error occurred';
  };

  return (
    <div className={`error-notification ${type} ${visible ? 'visible' : ''}`}>
      <div className="error-content">
        <span className="error-icon">{getIcon()}</span>
        <div className="error-text">
          <p className="error-message">{getMessage()}</p>
        </div>
        <button 
          className="error-close" 
          onClick={handleClose}
          aria-label="Close notification"
        >
          ✕
        </button>
      </div>
    </div>
  );
};

export default ErrorNotification;