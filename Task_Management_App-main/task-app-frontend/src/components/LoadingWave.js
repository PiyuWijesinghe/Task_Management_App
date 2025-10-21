import React from 'react';
import './LoadingWave.css';

const LoadingWave = ({ 
  size = 'medium', 
  color = 'primary',
  message = null
}) => {
  return (
    <div className="loading-wave-container">
      <div className={`loading-wave ${size} ${color}`}>
        <div className="wave-bar"></div>
        <div className="wave-bar"></div>
        <div className="wave-bar"></div>
        <div className="wave-bar"></div>
        <div className="wave-bar"></div>
      </div>
      {message && <p className="loading-message">{message}</p>}
    </div>
  );
};

export default LoadingWave;