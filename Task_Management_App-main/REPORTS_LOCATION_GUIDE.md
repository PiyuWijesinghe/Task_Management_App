# 🇱🇰 Task Reports කොහෙද හොයන්නේ? / Where to Find Task Reports?

## 📍 **Reports Section Location:**

### **Dashboard Sidebar එකේ:**
```
✅ Dashboard
📋 Task Management  
➕ Create Task
👥 Assign User
⏳ Pending Tasks
⚡ In Progress Tasks
✅ Completed Tasks
📅 Postponed Tasks
📊 Task Reports  ← මෙතනට click කරන්න / Click Here!
```

### **Navigation Bar එකේ (Top):**
```
Dashboard | Reports ← මෙතනට click කරන්න / Click Here!
```

---

## 🚀 **Server Start කරන්න / Start Server:**

### **Option 1: Batch File**
- `start_server.bat` file එක double-click කරන්න
- Browser එකේ `http://127.0.0.1:8000` යන්න

### **Option 2: Manual Command**
```bash
cd "C:\Users\CYBORG 15\Downloads\Task_Management_App-main (1)\Task_Management_App-main\task-app"
php artisan serve
```

---

## 📊 **Reports Page එකේ තියෙන්නේ:**

### **Filters:**
- ✅ **Status Filter:** All / To Do / In Progress / Completed
- ⚡ **Priority Filter:** All / High / Medium / Low  
- 👤 **Assignee Filter:** All Users / Specific User
- 📅 **Date Range:** From Date - To Date
- 📄 **Output Format:** PDF Download / HTML Preview

### **Features:**
- 📈 **Summary Statistics** (completed, pending, overdue count)
- 📋 **Detailed Task List** with color coding
- 🚨 **Overdue Tasks** highlighted in red
- ⚠️ **Due Today** highlighted in yellow
- 🎨 **Priority Color Coding:**
  - 🔴 High Priority (Red)
  - 🟡 Medium Priority (Yellow) 
  - 🟢 Low Priority (Green)

---

## 🎯 **Quick Steps:**

1. **Start Server:** Run `start_server.bat`
2. **Open Browser:** Go to `http://127.0.0.1:8000`
3. **Login:** Use your credentials
4. **Find Reports:** Look for "📊 Task Reports" in sidebar
5. **Generate:** Select filters and click "Generate Report"
6. **Download:** Choose PDF or HTML preview

---

## ⚠️ **Troubleshooting:**

**Problem:** Reports link එක පෙන්නන්නේ නැහැ
**Solution:** 
- Browser refresh කරන්න (Ctrl+F5)
- Cache clear කරන්න
- Server restart කරන්න

**Problem:** "Could not open input file: artisan"
**Solution:**
- Make sure you're in the `task-app` folder
- Use the `start_server.bat` file instead

---

## ✅ **Success!**
ඔබේ Task Management App එකේ දැන් Reports section එක available! PDF reports generate කරලා project progress track කරන්න පුළුවන්! 🎉