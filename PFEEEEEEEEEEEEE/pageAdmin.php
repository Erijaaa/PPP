<?php

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم   </title>
    <link rel="stylesheet" href="css/pageAdmin.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>لوحة التحكم</h2>
            <ul class="sidebar-menu">
                <li>
                    <a href="#" class="menu-item active" data-section="agents">
                        <i>👥</i>
                        إدارة الوكلاء
                    </a>
                </li>
                <li>
                    <a href="#" class="menu-item" data-section="requests">
                        <i>📋</i>
                        قائمة المطالب
                    </a>
                </li>
                <li>
                    <a href="#" class="menu-item" data-section="contracts">
                        <i>📄</i>
                        قائمة العقود
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <h1>   لوحة التحكم</h1>
            </div>

            <!-- Agents Management Section -->
            <div id="agents-content" class="content-section active">
                <h2>إدارة الوكلاء</h2>
                
                <div class="form-container">
                    <form id="agentForm">
                        <div class="form-group">
                            <label for="agentName">عدد الصلاحية </label>
                            <input type="number" id="post" name="post" required>
                        </div>

                        <div class="form-group">
                            <label for="agentName">الاسم</label>
                            <input type="text" id="agentName" name="agentName" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="agentEmail"> رقم التعريف</label>
                            <input type="text" id="cin" name="cin" required>
                        </div>

                        
                        <div class="form-group">
                            <label for="agentEmail">البريد الإلكتروني</label>
                            <input type="email" id="agentEmail" name="agentEmail" required>
                        </div>

                        <div class="form-group">
                            <label for="agentEmail"> كلمة المرور</label>
                            <input type="text" id="password" name="password" required>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">إضافة</button>
                            <button type="button" class="btn btn-secondary" onclick="clearForm()">إلغاء</button>
                        </div>
                    </form>
                </div>

            </div>

            <!-- Requests Management Section -->
            <div id="requests-content" class="content-section">
                <h2>قائمة المطالب</h2>
                <p>هنا ستظهر قائمة بجميع المطالب المقدمة في النظام...</p>
                <!-- Add your requests content here -->
            </div>

            <!-- Contracts Management Section -->
            <div id="contracts-content" class="content-section">
                <h2>قائمة العقود</h2>
                <p>هنا ستظهر قائمة بجميع العقود المسجلة في النظام...</p>
                <!-- Add your contracts content here -->
            </div>
        </div>
    </div>
    <script src="script/script.js"></script>
</body>
</html>