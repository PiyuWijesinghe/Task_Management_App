import React from 'react';
import { useTheme } from '../contexts/ThemeContext';

const ThemeToggle = () => {
  const { isDarkTheme, toggleTheme } = useTheme();

  return (
    <button
      className="theme-toggle"
      onClick={toggleTheme}
      title={`Switch to ${isDarkTheme ? 'Light' : 'Dark'} Theme`}
      aria-label={`Switch to ${isDarkTheme ? 'Light' : 'Dark'} Theme`}
    >
      {isDarkTheme ? '🌞' : '🌙'}
    </button>
  );
};

export default ThemeToggle;