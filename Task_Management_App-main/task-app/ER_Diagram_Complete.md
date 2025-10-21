# Complete Entity Relationship (ER) Diagram
## Task Management Application Database Schema

```
                                           TASK MANAGEMENT SYSTEM ER DIAGRAM
                                         =====================================

┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                                    USERS ENTITY                                                                        │
├─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ PK  │ id                   │ BIGINT      │ AUTO_INCREMENT │ Primary Key                                                              │
│     │ name                 │ VARCHAR(255)│ NOT NULL       │ Full name of the user                                                   │
│     │ username             │ VARCHAR(255)│ UNIQUE NOT NULL│ Unique username for login                                               │
│     │ email                │ VARCHAR(255)│ UNIQUE NOT NULL│ Email address                                                           │
│     │ email_verified_at    │ TIMESTAMP   │ NULLABLE       │ Email verification timestamp                                            │
│     │ password             │ VARCHAR(255)│ NOT NULL       │ Hashed password                                                         │
│     │ remember_token       │ VARCHAR(100)│ NULLABLE       │ Remember me token                                                       │
│     │ created_at           │ TIMESTAMP   │ NOT NULL       │ Record creation timestamp                                               │
│     │ updated_at           │ TIMESTAMP   │ NOT NULL       │ Record update timestamp                                                 │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘
                                    │                                         │
                                    │ 1:M (creates)                           │ 1:M (assigned_to)
                                    │                                         │
                                    ▼                                         ▼
┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                                    TASKS ENTITY                                                                        │
├─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ PK  │ id                   │ BIGINT      │ AUTO_INCREMENT │ Primary Key                                                              │
│     │ title                │ VARCHAR(255)│ NOT NULL       │ Task title/name                                                         │
│     │ description          │ TEXT        │ NULLABLE       │ Detailed task description                                               │
│     │ due_date             │ DATE        │ NULLABLE       │ Task due date                                                           │
│     │ status               │ ENUM        │ DEFAULT:Pending│ Status: 'Pending', 'In Progress', 'Completed'                          │
│     │ priority             │ ENUM        │ DEFAULT:Medium │ Priority: 'High', 'Medium', 'Low'                                      │
│ FK  │ user_id              │ BIGINT      │ NOT NULL       │ Foreign Key → users.id (task creator)                                  │
│ FK  │ assigned_user_id     │ BIGINT      │ NULLABLE       │ Foreign Key → users.id (single assignee)                               │
│     │ created_at           │ TIMESTAMP   │ NOT NULL       │ Record creation timestamp                                               │
│     │ updated_at           │ TIMESTAMP   │ NOT NULL       │ Record update timestamp                                                 │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘
                    │                          │                          │                          │
                    │ 1:M                      │ 1:M                      │ 1:M                      │ M:M
                    │                          │                          │                          │
                    ▼                          ▼                          ▼                          ▼

┌─────────────────────────────────┐  ┌─────────────────────────────────┐  ┌─────────────────────────────────┐  ┌─────────────────────────────────┐
│        TASK_COMMENTS            │  │         POSTPONEMENTS           │  │         TASK_USER               │  │         SESSIONS                │
├─────────────────────────────────┤  ├─────────────────────────────────┤  ├─────────────────────────────────┤  ├─────────────────────────────────┤
│PK │ id          │ BIGINT       │  │PK │ id          │ BIGINT        │  │PK │ id          │ BIGINT        │  │PK │ id          │ STRING        │
│FK │ task_id     │ BIGINT       │  │FK │ task_id     │ BIGINT        │  │FK │ task_id     │ BIGINT        │  │FK │ user_id     │ BIGINT        │
│FK │ user_id     │ BIGINT       │  │   │ old_due_date│ DATE NULLABLE │  │FK │ user_id     │ BIGINT        │  │   │ ip_address  │ STRING(45)    │
│   │ comment     │ TEXT         │  │   │ new_due_date│ DATE NOT NULL │  │   │ created_at  │ TIMESTAMP     │  │   │ user_agent  │ TEXT          │
│   │ created_at  │ TIMESTAMP    │  │   │ reason      │ TEXT NULLABLE │  │   │ updated_at  │ TIMESTAMP     │  │   │ payload     │ LONGTEXT      │
│   │ updated_at  │ TIMESTAMP    │  │FK │ postponed_by│ BIGINT        │  │   │ UNIQUE(task_id, user_id)    │  │   │ last_activity│ INTEGER      │
└─────────────────────────────────┘  │   │ created_at  │ TIMESTAMP     │  └─────────────────────────────────┘  └─────────────────────────────────┘
                                     │   │ updated_at  │ TIMESTAMP     │
                                     │   │ INDEX(task_id, created_at)  │
                                     └─────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                            PASSWORD_RESET_TOKENS                                                                       │
├─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ PK  │ email                │ VARCHAR(255)│ PRIMARY KEY    │ User email address                                                      │
│     │ token                │ VARCHAR(255)│ NOT NULL       │ Password reset token                                                    │
│     │ created_at           │ TIMESTAMP   │ NULLABLE       │ Token creation timestamp                                                │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

                                              RELATIONSHIP DETAILS
                                         ==============================

┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                                  RELATIONSHIPS                                                                          │
├─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ 1. USERS ──(1:M)──> TASKS (via user_id)                                                                                               │
│    • One user can create many tasks                                                                                                    │
│    • Each task has exactly one creator                                                                                                 │
│    • ON DELETE CASCADE: If user is deleted, all their created tasks are deleted                                                       │
│                                                                                                                                         │
│ 2. USERS ──(1:M)──> TASKS (via assigned_user_id)                                                                                      │
│    • One user can be assigned to many tasks                                                                                            │
│    • Each task can have at most one primary assignee                                                                                   │
│    • ON DELETE SET NULL: If assigned user is deleted, assigned_user_id becomes NULL                                                   │
│                                                                                                                                         │
│ 3. USERS ──(M:M)──> TASKS (via task_user pivot table)                                                                                 │
│    • One user can be assigned to many tasks                                                                                            │
│    • One task can have many assigned users                                                                                             │
│    • UNIQUE constraint on (task_id, user_id) prevents duplicate assignments                                                           │
│    • ON DELETE CASCADE: If user or task is deleted, pivot records are deleted                                                         │
│                                                                                                                                         │
│ 4. TASKS ──(1:M)──> TASK_COMMENTS                                                                                                      │
│    • One task can have many comments                                                                                                   │
│    • Each comment belongs to exactly one task                                                                                          │
│    • ON DELETE CASCADE: If task is deleted, all its comments are deleted                                                              │
│                                                                                                                                         │
│ 5. USERS ──(1:M)──> TASK_COMMENTS                                                                                                      │
│    • One user can make many comments                                                                                                   │
│    • Each comment is made by exactly one user                                                                                          │
│    • ON DELETE CASCADE: If user is deleted, all their comments are deleted                                                            │
│                                                                                                                                         │
│ 6. TASKS ──(1:M)──> POSTPONEMENTS                                                                                                      │
│    • One task can have many postponements (history)                                                                                    │
│    • Each postponement belongs to exactly one task                                                                                     │
│    • ON DELETE CASCADE: If task is deleted, all its postponements are deleted                                                         │
│                                                                                                                                         │
│ 7. USERS ──(1:M)──> POSTPONEMENTS (via postponed_by)                                                                                  │
│    • One user can postpone many tasks                                                                                                  │
│    • Each postponement is done by exactly one user                                                                                     │
│    • ON DELETE CASCADE: If user is deleted, postponement records show who postponed                                                   │
│                                                                                                                                         │
│ 8. USERS ──(1:M)──> SESSIONS                                                                                                           │
│    • One user can have many sessions                                                                                                   │
│    • Each session belongs to at most one user (nullable for guest sessions)                                                           │
│    • Used for session management and user activity tracking                                                                            │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

                                               CONSTRAINTS & INDEXES
                                          ==============================

┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                                   CONSTRAINTS                                                                           │
├─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ PRIMARY KEYS:                                                                                                                           │
│ • users.id, tasks.id, task_comments.id, postponements.id, task_user.id, sessions.id, password_reset_tokens.email                     │
│                                                                                                                                         │
│ UNIQUE CONSTRAINTS:                                                                                                                     │
│ • users.username (Unique username for login)                                                                                           │
│ • users.email (Unique email address)                                                                                                   │
│ • task_user(task_id, user_id) (Prevents duplicate user assignments to same task)                                                      │
│                                                                                                                                         │
│ FOREIGN KEY CONSTRAINTS:                                                                                                                │
│ • tasks.user_id → users.id (CASCADE)                                                                                                   │
│ • tasks.assigned_user_id → users.id (SET NULL)                                                                                         │
│ • task_comments.task_id → tasks.id (CASCADE)                                                                                           │
│ • task_comments.user_id → users.id (CASCADE)                                                                                           │
│ • postponements.task_id → tasks.id (CASCADE)                                                                                           │
│ • postponements.postponed_by → users.id (CASCADE)                                                                                      │
│ • task_user.task_id → tasks.id (CASCADE)                                                                                               │
│ • task_user.user_id → users.id (CASCADE)                                                                                               │
│ • sessions.user_id → users.id (nullable)                                                                                               │
│                                                                                                                                         │
│ INDEXES:                                                                                                                                │
│ • postponements(task_id, created_at) - For efficient postponement history queries                                                     │
│ • sessions.user_id - For session lookups                                                                                               │
│ • sessions.last_activity - For session cleanup                                                                                         │
│                                                                                                                                         │
│ ENUM VALUES:                                                                                                                            │
│ • tasks.status: 'Pending', 'In Progress', 'Completed'                                                                                  │
│ • tasks.priority: 'High', 'Medium', 'Low'                                                                                              │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

                                                BUSINESS RULES
                                           =====================

┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│                                                 BUSINESS LOGIC                                                                          │
├─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ 1. TASK CREATION:                                                                                                                       │
│    • Every task must have a creator (user_id cannot be null)                                                                           │
│    • Task can be created without an assignee (assigned_user_id can be null)                                                           │
│    • Default status is 'Pending', default priority is 'Medium'                                                                         │
│                                                                                                                                         │
│ 2. TASK ASSIGNMENT:                                                                                                                     │
│    • Single Assignment: One primary assignee via assigned_user_id                                                                      │
│    • Multiple Assignment: Multiple users via task_user pivot table                                                                     │
│    • Both assignment methods can be used simultaneously                                                                                 │
│                                                                                                                                         │
│ 3. TASK POSTPONEMENT:                                                                                                                   │
│    • Only task creator, primary assignee, or assigned users can postpone                                                               │
│    • Complete postponement history is maintained                                                                                       │
│    • Each postponement requires new_due_date, reason is optional                                                                       │
│                                                                                                                                         │
│ 4. COMMENTING:                                                                                                                          │
│    • Any logged-in user can comment on any task                                                                                        │
│    • Comments are ordered chronologically (created_at ASC)                                                                             │
│    • Comments cannot be edited or deleted (audit trail)                                                                                │
│                                                                                                                                         │
│ 5. USER MANAGEMENT:                                                                                                                     │
│    • Username is used for authentication (not email)                                                                                   │
│    • Both username and email must be unique                                                                                            │
│    • Password reset uses email as identifier                                                                                           │
│                                                                                                                                         │
│ 6. SESSION MANAGEMENT:                                                                                                                  │
│    • Sessions can exist without user_id (guest sessions)                                                                               │
│    • User sessions track IP, browser, and activity                                                                                     │
│    • Session cleanup based on last_activity timestamp                                                                                  │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

                                              CARDINALITY SUMMARY
                                          =========================

┌─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┐
│ ENTITY                │ CARDINALITY WITH OTHER ENTITIES                                                                                 │
├─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┤
│ USERS                 │ 1:M with TASKS (creator)                                                                                       │
│                       │ 1:M with TASKS (assignee)                                                                                      │
│                       │ M:M with TASKS (via task_user)                                                                                 │
│                       │ 1:M with TASK_COMMENTS                                                                                         │
│                       │ 1:M with POSTPONEMENTS                                                                                         │
│                       │ 1:M with SESSIONS                                                                                              │
│                       │                                                                                                                 │
│ TASKS                 │ M:1 with USERS (creator)                                                                                       │
│                       │ M:1 with USERS (assignee)                                                                                      │
│                       │ M:M with USERS (via task_user)                                                                                 │
│                       │ 1:M with TASK_COMMENTS                                                                                         │
│                       │ 1:M with POSTPONEMENTS                                                                                         │
│                       │                                                                                                                 │
│ TASK_COMMENTS         │ M:1 with TASKS                                                                                                 │
│                       │ M:1 with USERS                                                                                                 │
│                       │                                                                                                                 │
│ POSTPONEMENTS         │ M:1 with TASKS                                                                                                 │
│                       │ M:1 with USERS (postponed_by)                                                                                  │
│                       │                                                                                                                 │
│ TASK_USER (Pivot)     │ M:1 with TASKS                                                                                                 │
│                       │ M:1 with USERS                                                                                                 │
│                       │                                                                                                                 │
│ SESSIONS              │ M:1 with USERS (nullable)                                                                                      │
│                       │                                                                                                                 │
│ PASSWORD_RESET_TOKENS │ Independent entity (email-based)                                                                               │
└─────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────────┘

```

## Database Schema SQL Commands

```sql
-- Create USERS table
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create TASKS table
CREATE TABLE tasks (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    due_date DATE NULL,
    status ENUM('Pending', 'In Progress', 'Completed') DEFAULT 'Pending',
    priority ENUM('High', 'Medium', 'Low') DEFAULT 'Medium',
    user_id BIGINT NOT NULL,
    assigned_user_id BIGINT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Create TASK_COMMENTS table
CREATE TABLE task_comments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    task_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create POSTPONEMENTS table
CREATE TABLE postponements (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    task_id BIGINT NOT NULL,
    old_due_date DATE NULL,
    new_due_date DATE NOT NULL,
    reason TEXT NULL,
    postponed_by BIGINT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (postponed_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_task_created (task_id, created_at)
);

-- Create TASK_USER pivot table
CREATE TABLE task_user (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    task_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_task_user (task_id, user_id)
);

-- Create SESSIONS table
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    
    FOREIGN KEY (user_id) REFERENCES users(id),
    INDEX idx_user_id (user_id),
    INDEX idx_last_activity (last_activity)
);

-- Create PASSWORD_RESET_TOKENS table
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
);
```

මේ complete ER diagram එක ඔබගේ Task Management Application එකේ සම්පූර්ණ database structure එක විස්තර කරයි, සියලුම entities, attributes, relationships, constraints, සහ business rules සමඟ.