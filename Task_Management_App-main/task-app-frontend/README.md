# Task Management App - Frontend

A modern, responsive React frontend for the Task Management System featuring beautiful UI design, real-time functionality, and comprehensive task management capabilities.

## 🚀 Quick Start

### Prerequisites
- Node.js 18.x or higher
- npm or yarn package manager
- Task Management API backend running on `http://localhost:8000`

### Installation & Setup

1. **Navigate to the frontend directory**
   ```bash
   cd task-app-frontend
   ```

2. **Install dependencies**
   ```bash
   npm install
   # or
   yarn install
   ```

3. **Configure API connection**
   Ensure the API base URL in `src/services/TaskService.js` matches your backend:
   ```javascript
   static baseURL = 'http://localhost:8000/api/v1';
   ```

4. **Start development server**
   ```bash
   npm start
   # or
   yarn start
   ```

5. **Access the application**
   Open [http://localhost:3000](http://localhost:3000) in your browser

## ✨ Features

### 🎨 Modern UI/UX Design
- **Gradient Design System**: Beautiful gradient-based color scheme
- **Responsive Layout**: Perfect display on desktop, tablet, and mobile
- **Dark Mode Support**: Toggle between light and dark themes
- **Glass Morphism**: Modern glassmorphism design elements
- **Smooth Animations**: Hover effects and transitions
- **Professional Loading States**: Advanced loading spinners and overlays

### 📋 Task Management
- **Complete CRUD Operations**: Create, read, update, and delete tasks
- **Multi-User Assignment**: Assign tasks to multiple users with searchable dropdowns
- **Priority System**: Visual priority indicators (High, Medium, Low)
- **Status Workflow**: Track progress (Pending, In Progress, Completed)
- **Due Date Management**: Date picker with overdue alerts
- **Task Postponement**: Reschedule tasks with reason tracking

### 💬 Collaboration Features
- **Real-time Comments**: Add and manage task comments
- **Comment Threading**: Chronological comment display
- **User Attribution**: Comments with author names and timestamps
- **Comment Management**: Delete own comments with confirmation

### 🔍 Advanced Filtering & Search
- **Multi-Level Filtering**: Filter by status, priority, and assignments
- **Smart Search**: Search across task titles and descriptions
- **Sort Options**: Multiple sorting criteria
- **Filter Persistence**: Maintain filter state across navigation
- **User Search**: Searchable user assignment dropdowns

### 🔐 Authentication & Security
- **Secure Login**: Username or email-based authentication
- **Token Management**: Automatic token handling and refresh
- **Session Persistence**: Remember user sessions
- **Protected Routes**: Route-level authentication guards

## 🛠️ Technology Stack

- **Frontend Framework**: React 19.x
- **Styling**: CSS3 with CSS Variables, Tailwind CSS
- **HTTP Client**: Axios
- **Routing**: React Router DOM 6.x
- **Form Management**: React Hook Form
- **Date Handling**: date-fns
- **State Management**: React Context + Hooks
- **Build Tool**: Create React App with React Scripts
- **Testing**: Testing Library (React/Jest/DOM)

## 📁 Project Structure

```
task-app-frontend/
├── public/
│   ├── index.html                 # Main HTML file
│   ├── manifest.json             # PWA manifest
│   └── robots.txt                # SEO robots file
├── src/
│   ├── components/               # Reusable React components
│   │   ├── Dashboard.js/css      # Main dashboard component
│   │   ├── TaskManager.js/css    # Task management interface
│   │   ├── TaskItem.js/css       # Individual task display
│   │   ├── CreateTask.js/css     # Task creation modal
│   │   ├── TaskAssignment.js/css # Multi-user assignment
│   │   ├── LoadingSpinner.js/css # Loading states
│   │   ├── ErrorNotification.js/css # Error handling
│   │   ├── ConfirmDialog.js/css  # Confirmation dialogs
│   │   └── UserSearch.js/css     # User search component
│   ├── contexts/                 # React context providers
│   │   ├── AuthContext.js        # Authentication context
│   │   ├── TaskContext.js        # Task state management
│   │   └── ThemeContext.js       # Theme management
│   ├── services/                 # API service layer
│   │   ├── TaskService.js        # Main API service
│   │   ├── AuthService.js        # Authentication service
│   │   └── UserService.js        # User management service
│   ├── hooks/                    # Custom React hooks
│   │   ├── useAuth.js           # Authentication hook
│   │   ├── useTasks.js          # Task management hook
│   │   └── useApi.js            # Generic API hook
│   ├── utils/                    # Utility functions
│   │   ├── dateUtils.js         # Date formatting utilities
│   │   ├── validators.js        # Form validation
│   │   └── constants.js         # App constants
│   ├── App.js                    # Main application component
│   ├── App.css                   # Global application styles
│   ├── index.js                  # Application entry point
│   ├── index.css                 # Global CSS imports
│   ├── themes.css                # Theme system variables
│   └── setupTests.js             # Test configuration
├── package.json                  # Dependencies and scripts
├── tailwind.config.js            # Tailwind CSS configuration
├── postcss.config.js             # PostCSS configuration
└── README.md                     # This file
```

## 🎯 Available Scripts

### Development Commands

```bash
# Start development server with hot reload
npm start
# Opens http://localhost:3000
# Page reloads on changes
# Lint errors appear in console

# Build for production
npm run build
# Creates optimized build in `build/` folder
# Minifies code and optimizes assets
# Ready for deployment

# Run tests
npm test
# Launches test runner in interactive watch mode
# Automatically re-runs tests on file changes

# Run tests with coverage
npm test -- --coverage
# Generates test coverage report

# Eject configuration (irreversible)
npm run eject
# Copies all configuration files
# Gives full control over build process
# Note: This is a one-way operation!
```

### Using Yarn (Alternative)

```bash
yarn start      # Development server
yarn build      # Production build
yarn test       # Run tests
yarn eject      # Eject configuration
```

## 🔧 Configuration

### Environment Variables
Create a `.env` file in the root directory:

```env
# API Configuration
REACT_APP_API_URL=http://localhost:8000/api/v1
REACT_APP_API_TIMEOUT=10000

# Application Configuration
REACT_APP_NAME="Task Management App"
REACT_APP_VERSION=1.0.0

# Feature Flags
REACT_APP_ENABLE_DARK_MODE=true
REACT_APP_ENABLE_NOTIFICATIONS=true

# Development
REACT_APP_DEBUG=true
GENERATE_SOURCEMAP=true
```

### API Service Configuration
Update `src/services/TaskService.js` to configure API settings:

```javascript
class TaskService {
  static baseURL = process.env.REACT_APP_API_URL || 'http://localhost:8000/api/v1';
  static timeout = parseInt(process.env.REACT_APP_API_TIMEOUT) || 10000;
  
  // ... rest of the service
}
```

### Tailwind CSS Customization
Modify `tailwind.config.js` to customize the design system:

```javascript
module.exports = {
  content: [
    "./src/**/*.{js,jsx,ts,tsx}",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#eff6ff',
          500: '#3b82f6',
          900: '#1e3a8a',
        },
        // ... custom colors
      },
      // ... other customizations
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
```

## 🧪 Testing

### Running Tests
```bash
# Interactive test runner
npm test

# Run all tests once
npm test -- --watchAll=false

# Run tests with coverage
npm test -- --coverage --watchAll=false

# Run specific test file
npm test TaskManager.test.js

# Run tests matching pattern
npm test -- --testNamePattern="should render"
```

### Test Structure
```
src/
├── components/
│   ├── Dashboard.js
│   ├── Dashboard.test.js      # Component tests
│   ├── TaskManager.js
│   └── TaskManager.test.js
├── services/
│   ├── TaskService.js
│   └── TaskService.test.js    # Service tests
└── __tests__/                 # Global tests
    ├── App.test.js
    └── testUtils.js           # Test utilities
```

### Writing Tests
```javascript
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import '@testing-library/jest-dom';
import TaskManager from './TaskManager';

describe('TaskManager', () => {
  test('should render task list', async () => {
    render(<TaskManager />);
    
    await waitFor(() => {
      expect(screen.getByText('My Tasks')).toBeInTheDocument();
    });
  });

  test('should create new task', async () => {
    render(<TaskManager />);
    
    const createButton = screen.getByText('Create Task');
    fireEvent.click(createButton);
    
    // ... test task creation
  });
});
```

## 🚀 Production Deployment

### Build for Production
```bash
# Create production build
npm run build

# The build folder contains:
# - Optimized and minified JavaScript/CSS
# - HTML files with asset hashes
# - Static assets with cache headers
# - Service worker (if enabled)
```

### Deployment Options

#### Static Hosting (Recommended)
```bash
# Deploy to Netlify
npm install -g netlify-cli
netlify deploy --prod --dir=build

# Deploy to Vercel
npm install -g vercel
vercel --prod

# Deploy to GitHub Pages
npm install --save-dev gh-pages
# Add to package.json scripts:
# "deploy": "gh-pages -d build"
npm run deploy
```

#### Docker Deployment
Create `Dockerfile`:
```dockerfile
# Build stage
FROM node:18-alpine AS builder
WORKDIR /app
COPY package*.json ./
RUN npm ci --only=production
COPY . .
RUN npm run build

# Production stage
FROM nginx:alpine
COPY --from=builder /app/build /usr/share/nginx/html
COPY nginx.conf /etc/nginx/nginx.conf
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
```

#### Server Configuration
For SPA routing, configure your server to serve `index.html` for all routes:

**Nginx:**
```nginx
location / {
  try_files $uri $uri/ /index.html;
}
```

**Apache (.htaccess):**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.html [L]
```

## 🔧 Troubleshooting

### Common Issues

#### API Connection Problems
```bash
# Check API endpoint in browser
curl http://localhost:8000/api/v1/health

# Verify CORS settings in backend
# Check browser developer console for CORS errors

# Update API URL in TaskService.js
```

#### Build Failures
```bash
# Clear node modules and reinstall
rm -rf node_modules package-lock.json
npm install

# Clear npm cache
npm cache clean --force

# Check Node.js version
node --version  # Should be 18.x+
```

#### Styling Issues
```bash
# Rebuild Tailwind CSS
npm run build:css

# Check Tailwind configuration
npx tailwindcss-cli build src/index.css -o dist/tailwind.css

# Verify PostCSS configuration
```

#### Performance Issues
```bash
# Analyze bundle size
npm install --save-dev webpack-bundle-analyzer
npx webpack-bundle-analyzer build/static/js/*.js

# Enable React DevTools Profiler
# Use React.memo() for expensive components
# Implement useMemo() and useCallback() for optimization
```

### Debug Mode
Enable debug logging by setting environment variables:
```env
REACT_APP_DEBUG=true
NODE_ENV=development
```

## 📚 Additional Resources

- [React Documentation](https://reactjs.org/)
- [Create React App Documentation](https://create-react-app.dev/)
- [Tailwind CSS Documentation](https://tailwindcss.com/)
- [Testing Library Documentation](https://testing-library.com/)
- [React Router Documentation](https://reactrouter.com/)

## 🎨 Component Usage Examples

### Using Core Components

#### LoadingSpinner
```jsx
import LoadingSpinner from './components/LoadingSpinner';

function MyComponent() {
  const [loading, setLoading] = useState(false);

  return (
    <div>
      {loading && (
        <LoadingSpinner 
          size="large" 
          message="Loading tasks..." 
          overlay={true} 
        />
      )}
    </div>
  );
}
```

#### ErrorNotification
```jsx
import ErrorNotification from './components/ErrorNotification';

function MyComponent() {
  const [error, setError] = useState(null);

  return (
    <div>
      {error && (
        <ErrorNotification
          error="Failed to save task"
          type="error"
          onClose={() => setError(null)}
          autoClose={5000}
        />
      )}
    </div>
  );
}
```

#### ConfirmDialog
```jsx
import ConfirmDialog from './components/ConfirmDialog';

function MyComponent() {
  const [showConfirm, setShowConfirm] = useState(false);

  const handleDelete = () => {
    // Delete logic here
    setShowConfirm(false);
  };

  return (
    <div>
      <button onClick={() => setShowConfirm(true)}>
        Delete Task
      </button>

      <ConfirmDialog
        isOpen={showConfirm}
        title="Delete Task"
        message="Are you sure you want to delete this task?"
        type="danger"
        onConfirm={handleDelete}
        onCancel={() => setShowConfirm(false)}
      />
    </div>
  );
}
```

#### UserSearch
```jsx
import UserSearch from './components/UserSearch';

function TaskAssignment() {
  const [selectedUsers, setSelectedUsers] = useState([]);

  const handleUserSelect = (user) => {
    setSelectedUsers(prev => [...prev, user]);
  };

  return (
    <UserSearch
      onSelect={handleUserSelect}
      selectedUsers={selectedUsers}
      placeholder="Search users to assign..."
      multiple={true}
    />
  );
}
```

### Using Contexts

#### AuthContext
```jsx
import { useContext } from 'react';
import { AuthContext } from '../contexts/AuthContext';

function MyComponent() {
  const { user, login, logout, isAuthenticated } = useContext(AuthContext);

  if (!isAuthenticated) {
    return <div>Please log in</div>;
  }

  return (
    <div>
      <h1>Welcome, {user.name}!</h1>
      <button onClick={logout}>Logout</button>
    </div>
  );
}
```

#### TaskContext
```jsx
import { useContext } from 'react';
import { TaskContext } from '../contexts/TaskContext';

function TaskList() {
  const { 
    tasks, 
    loading, 
    error, 
    createTask, 
    updateTask, 
    deleteTask 
  } = useContext(TaskContext);

  return (
    <div>
      {loading && <LoadingSpinner />}
      {error && <ErrorNotification error={error} />}
      {tasks.map(task => (
        <TaskItem 
          key={task.id} 
          task={task}
          onUpdate={updateTask}
          onDelete={deleteTask}
        />
      ))}
    </div>
  );
}
```

### Custom Hooks Usage

#### useAuth Hook
```jsx
import useAuth from '../hooks/useAuth';

function LoginForm() {
  const { login, loading, error } = useAuth();

  const handleSubmit = async (credentials) => {
    try {
      await login(credentials);
      // Redirect or update UI
    } catch (err) {
      // Error is handled by the hook
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      {/* Form fields */}
      <button disabled={loading}>
        {loading ? 'Logging in...' : 'Login'}
      </button>
      {error && <div className="error">{error}</div>}
    </form>
  );
}
```

#### useTasks Hook
```jsx
import useTasks from '../hooks/useTasks';

function TaskManager() {
  const {
    tasks,
    loading,
    error,
    filters,
    setFilters,
    createTask,
    updateTask,
    deleteTask,
    refreshTasks
  } = useTasks();

  const handleFilterChange = (newFilters) => {
    setFilters(prev => ({ ...prev, ...newFilters }));
  };

  return (
    <div>
      <TaskFilters 
        filters={filters}
        onChange={handleFilterChange}
      />
      <TaskList 
        tasks={tasks}
        loading={loading}
        error={error}
        onUpdate={updateTask}
        onDelete={deleteTask}
      />
    </div>
  );
}
```

## 🎯 Performance Optimization

### Code Splitting
```jsx
import { lazy, Suspense } from 'react';

// Lazy load components
const Dashboard = lazy(() => import('./components/Dashboard'));
const TaskManager = lazy(() => import('./components/TaskManager'));

function App() {
  return (
    <Suspense fallback={<LoadingSpinner />}>
      <Routes>
        <Route path="/dashboard" element={<Dashboard />} />
        <Route path="/tasks" element={<TaskManager />} />
      </Routes>
    </Suspense>
  );
}
```

### Memoization
```jsx
import { memo, useMemo, useCallback } from 'react';

const TaskItem = memo(({ task, onUpdate, onDelete }) => {
  const handleUpdate = useCallback((updates) => {
    onUpdate(task.id, updates);
  }, [task.id, onUpdate]);

  const taskActions = useMemo(() => {
    return task.status === 'completed' ? [] : ['edit', 'complete'];
  }, [task.status]);

  return (
    <div>
      {/* Task content */}
    </div>
  );
});
```

### Virtual Scrolling (for large lists)
```jsx
import { FixedSizeList as List } from 'react-window';

function TaskList({ tasks }) {
  const Row = ({ index, style }) => (
    <div style={style}>
      <TaskItem task={tasks[index]} />
    </div>
  );

  return (
    <List
      height={600}
      itemCount={tasks.length}
      itemSize={80}
    >
      {Row}
    </List>
  );
}
```

## 🌐 Internationalization (i18n)

### Setup i18n (Optional)
```bash
npm install react-i18next i18next

# Create language files
mkdir public/locales/en public/locales/es
```

```javascript
// src/i18n.js
import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';

i18n
  .use(initReactI18next)
  .init({
    resources: {
      en: {
        translation: {
          "welcome": "Welcome",
          "tasks": "Tasks",
          "create_task": "Create Task"
        }
      }
    },
    lng: "en",
    fallbackLng: "en",
    interpolation: {
      escapeValue: false
    }
  });

export default i18n;
```

### Usage
```jsx
import { useTranslation } from 'react-i18next';

function MyComponent() {
  const { t } = useTranslation();

  return (
    <div>
      <h1>{t('welcome')}</h1>
      <button>{t('create_task')}</button>
    </div>
  );
}
```

## 📱 Progressive Web App (PWA)

### Enable PWA Features
```javascript
// src/index.js
import * as serviceWorkerRegistration from './serviceWorkerRegistration';

// Register service worker
serviceWorkerRegistration.register();
```

### Update Manifest
```json
// public/manifest.json
{
  "short_name": "Task Manager",
  "name": "Task Management Application",
  "icons": [
    {
      "src": "favicon.ico",
      "sizes": "64x64 32x32 24x24 16x16",
      "type": "image/x-icon"
    }
  ],
  "start_url": ".",
  "display": "standalone",
  "theme_color": "#000000",
  "background_color": "#ffffff"
}
```

## 🔐 Security Best Practices

### Token Management
```javascript
// Secure token storage
class TokenManager {
  static setToken(token) {
    localStorage.setItem('authToken', token);
  }

  static getToken() {
    return localStorage.getItem('authToken');
  }

  static removeToken() {
    localStorage.removeItem('authToken');
  }

  static isTokenExpired(token) {
    // Implement token expiry check
    try {
      const payload = JSON.parse(atob(token.split('.')[1]));
      return payload.exp < Date.now() / 1000;
    } catch {
      return true;
    }
  }
}
```

### Input Sanitization
```javascript
// Sanitize user inputs
import DOMPurify from 'dompurify';

function sanitizeInput(input) {
  return DOMPurify.sanitize(input);
}

// Usage in components
const handleSubmit = (data) => {
  const sanitizedData = {
    title: sanitizeInput(data.title),
    description: sanitizeInput(data.description)
  };
  // Submit sanitized data
};
```

## 🎨 Theme Customization

### CSS Variables for Theming
```css
/* themes.css */
:root {
  --color-primary: #3b82f6;
  --color-secondary: #6b7280;
  --color-success: #10b981;
  --color-warning: #f59e0b;
  --color-error: #ef4444;
  --color-background: #ffffff;
  --color-surface: #f8fafc;
  --color-text: #1f2937;
  
  --border-radius: 0.5rem;
  --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  --transition: all 0.2s ease-in-out;
}

[data-theme="dark"] {
  --color-background: #1f2937;
  --color-surface: #374151;
  --color-text: #f9fafb;
}
```

### Theme Toggle Component
```jsx
import { useState, useEffect } from 'react';

function ThemeToggle() {
  const [darkMode, setDarkMode] = useState(false);

  useEffect(() => {
    document.documentElement.setAttribute(
      'data-theme', 
      darkMode ? 'dark' : 'light'
    );
  }, [darkMode]);

  return (
    <button 
      onClick={() => setDarkMode(!darkMode)}
      className="theme-toggle"
    >
      {darkMode ? '🌞' : '🌙'}
    </button>
  );
}
```

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📞 Support

For support, questions, or feature requests:
- Create an issue on GitHub
- Check the troubleshooting section above
- Review the API documentation in the backend

---

**Built with ❤️ using React, modern web technologies, and best practices.**

**Version**: 1.0.0  
**Last Updated**: October 13, 2025  
**React Version**: 19.x  
**Node.js**: 18.x+

### Code Splitting

This section has moved here: [https://facebook.github.io/create-react-app/docs/code-splitting](https://facebook.github.io/create-react-app/docs/code-splitting)

### Analyzing the Bundle Size

This section has moved here: [https://facebook.github.io/create-react-app/docs/analyzing-the-bundle-size](https://facebook.github.io/create-react-app/docs/analyzing-the-bundle-size)

### Making a Progressive Web App

This section has moved here: [https://facebook.github.io/create-react-app/docs/making-a-progressive-web-app](https://facebook.github.io/create-react-app/docs/making-a-progressive-web-app)

### Advanced Configuration

This section has moved here: [https://facebook.github.io/create-react-app/docs/advanced-configuration](https://facebook.github.io/create-react-app/docs/advanced-configuration)

### Deployment

This section has moved here: [https://facebook.github.io/create-react-app/docs/deployment](https://facebook.github.io/create-react-app/docs/deployment)

### `yarn build` fails to minify

This section has moved here: [https://facebook.github.io/create-react-app/docs/troubleshooting#npm-run-build-fails-to-minify](https://facebook.github.io/create-react-app/docs/troubleshooting#npm-run-build-fails-to-minify)
