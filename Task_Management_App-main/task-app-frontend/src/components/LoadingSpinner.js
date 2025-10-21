import React from 'react';
import './LoadingSpinner.css';

const LoadingSpinner = ({ 
  size = 'medium', 
  message = 'Loading...', 
  overlay = false,
  color = 'primary' 
}) => {
  if (overlay) {
    return (
      <div className="loading-overlay">
        <div className={`loading-spinner ${size} ${color}`}>
          <div className="spinner-circle"></div>
        </div>
        {message && <p className="loading-message">{message}</p>}
      </div>
    );
  }

  return (
    <div className={`loading-container ${size}`}>
      <div className={`loading-spinner ${size} ${color}`}>
        <div className="spinner-circle"></div>
      </div>
      {message && <p className="loading-message">{message}</p>}
    </div>
  );
};

export default LoadingSpinner;