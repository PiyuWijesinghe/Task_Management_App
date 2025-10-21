import React from 'react';
import './LoadingDots.css';

const LoadingDots = ({ 
  size = 'medium', 
  color = 'primary',
  message = null
}) => {
  return (
    <div className="loading-dots-container">
      <div className={`loading-dots ${size} ${color}`}>
        <div className="dot"></div>
        <div className="dot"></div>
        <div className="dot"></div>
      </div>
      {message && <p className="loading-message">{message}</p>}
    </div>
  );
};

export default LoadingDots;