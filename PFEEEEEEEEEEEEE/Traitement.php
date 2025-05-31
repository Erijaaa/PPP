<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("connect.php");

$db = new ClsConnect();

if (isset($_GET['id_demande']) && isset($_GET['num_recu'])) {
    $id_demande = $_GET['id_demande'];
    $num_recu = $_GET['num_recu'];
    $demande = $db->getDemandeById($id_demande); 
} else {
    echo "Paramètres manquants dans l'URL.";
    exit;
}


$id_contrat = ($cc = $db->getidcontract()) && isset($cc['nextval']) ? $cc['nextval'] : '';



$pdo = $db->getConnection();
$contratManagement = new contratManager($pdo);


$id_demande = $_GET['id_demande'] ?? '';
$annee_demande = $_POST['annee_demande'] ?? '';
$date_demande = date('Y-m-d');


$success = $contratManagement->enregistrerContrat($id_demande, $annee_demande, $date_demande, $id_contrat);

if ($success) {
    echo "Contrat enregistré avec succès !";
} else {
    echo "Échec de l'enregistrement du contrat.";
}

$id_demande = isset($_GET['id_demande']) ? intval($_GET['id_demande']) : 0;

$pieces_jointes = $db->getPiecesJointesByDemande($id_demande);
$agent = $db->getAgent($id_demande);
$deposant = $db->getDeposant($id_demande);
$sujetContrat = $db->getSubject($id_demande);

?> 




<!DOCTYPE html>
<html dir="rtl" lang="ar">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>عقد</title>
    <link rel="stylesheet" href="css/Traitement.css" />
  </head>

  <body>
    <div class="contract-container">
      <!-- Header -->
      <div class="contract-header">
        <div class="header-top">
          <div class="right-align">
            <h1>الجمهورية التونسية</h1>
            <p>وزارة أملاك الدولة والشؤون العقارية</p>
            <p>الديوان الوطني للملكية العقارية</p>
            <p>الإدارة الجهوية للملكية العقارية بالكاف</p>
          </div>
          <div class="left-align">
            <p>تاريخ التحرير: <?php echo date('Y/m/d'); ?></p>
          </div>
        </div>
        
        <div class="contract-title">
          <h2>عقد</h2>
        </div>

        <div class="contract-info">
          <div class="info-row">
            <span>عدد مطلب التحرير:</span>
            <input type="text" readonly value="<?php echo isset($demande['id_demande']) ? $demande['id_demande'] : ''; ?>/<?php echo date('Y'); ?>">
            <span>عدد الوصل:</span>
            <input type="text" readonly value="<?php echo isset($demande['num_recu']) ? $demande['num_recu'] : ''; ?>">
            <span>تاريخه:</span>
            <input type="text" readonly value="<?php echo isset($demande['date_demande']) ? $demande['date_demande'] : ''; ?>">
            <span>عدد العقد:</span>
            <input type="text" readonly value="<?php echo isset($id_contrat) ? $id_contrat : ''; ?>">
          </div>
          <div class="contract-subject">
            <div class="subject-label">موضوع العقد</div>
            <div class="subject-value">وعد بيع</div>
          </div>
        </div>
      </div>

      <!-- Main Sections -->
      <div class="contract-sections">
        <!-- Section 1 -->
        <div class="section" id="section1">
          <h3>القسم الأول: البيانات المتعلقة بطالب الخدمة</h3>
          <div class="section-content">
            <div class="field-row">
              <label>الإسم:</label>
              <input type="text" readonly>
              <label>اللقب:</label>
              <input type="text" readonly>
            </div>
          </div>
        </div>

        <!-- Section 2 -->
        <div class="section" id="section2">
          <h3>القسم الثاني: البيانات المتعلقة بهوية وإلتزامات المحرر</h3>
          <div class="section-content">
            <div class="field-row">
              <label>الإسم:</label>
              <input type="text" value="سفيان" readonly>
              <label>اللقب:</label>
              <input type="text" value="رمضاني" readonly>
              <label>عدد بطاقة التعريف الوطنية:</label>
              <input type="text" value="03771732" readonly>
            </div>
            <div class="field-row">
              <p>أني إطلعت على الرسم العقاري أو الرسوم العقارية:</p>
              <p>موضوع هذا الصك وأشعرت الأطراف المتعاقدة بالحالة القانونية الواردة به (بها) وبعدم وجود أي مانع قانوني للتحرير</p>
            </div>
          </div>
        </div>

        <!-- Section 3 -->
        <div class="section" id="section3">
          <h3>القسم الثالث: البيانات المتعلقة بالمؤيدات</h3>
          <table id="documents-table">
            <thead>
              <tr>
                <th>ع ر</th>
                <th>العدد الرتبي</th>
                <th>الوثيقة</th>
                <th>تاريخها</th>
                <th>مراجع التسجيل</th>
                <th>تاريخها</th>
                <th>ع الأوراق</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td><input type="text"></td>
                <td><input type="text"></td>
                <td><input type="text"></td>
                <td><input type="text"></td>
                <td><input type="text"></td>
                <td><input type="text"></td>
              </tr>
            </tbody>
          </table>
          <button class="btn-add" data-table="documents-table">إضافة سطر</button>
        </div>

        <!-- Section 4 -->
        <div class="section" id="section4">
          <h3>القسم الرابع: البيانات المتعلقة بأطراف التعاقد</h3>
          <div class="contract-parties">
            <!-- Party tables will be dynamically added here -->
          </div>
        </div>

        <!-- Section 5 -->
        <div class="section" id="section5">
          <h3>القسم الخامس: البيانات المتعلقة بموضوع التعاقد ومراجع إنجراره بالرسم العقاري</h3>
          <div class="property-details">
            <!-- Property details will be added here -->
          </div>
        </div>

        <!-- Section 6 -->
        <div class="section" id="section6">
          <h3>القسم السادس: الأحكام التعاقدية الأخرى</h3>
          <div class="contract-terms">
            <!-- Contract terms will be added here -->
          </div>
        </div>

        <!-- Section 7 -->
        <div class="section" id="section7">
          <h3>القسم السابع: إمضاءات الأطراف والتعريف بها</h3>
          <table id="signatures-table">
            <thead>
              <tr>
                <th>الإسم</th>
                <th>إسم الأب</th>
                <th>إسم الجد</th>
                <th>اللقب</th>
                <th>الصفة</th>
                <th>الإمضاءات</th>
              </tr>
            </thead>
            <tbody>
              <!-- Signature rows will be added here -->
            </tbody>
          </table>
        </div>
      </div>

      <!-- Footer -->
      <div class="contract-footer">
        <div class="page-info">
          <span>عدد العقد: <?php echo isset($id_contrat) ? $id_contrat : ''; ?></span>
          <span>الصفحة: <span class="page-number">1</span>/5</span>
          <span>تاريخ التحرير: <?php echo date('Y/m/d'); ?></span>
        </div>
      </div>

      <!-- Add this before the closing </div> of contract-container -->
      <div class="action-buttons">
          <form action="generatePDF.php" method="GET" target="_blank">
              <input type="hidden" name="id_demande" value="<?php echo htmlspecialchars($id_demande); ?>">
              <input type="hidden" name="id_contrat" value="<?php echo htmlspecialchars($id_contrat); ?>">
              <button type="submit" class="btn-print">
                  <i class="fas fa-print"></i> طباعة العقد
              </button>
          </form>
      </div>
    </div>

    <script src="js/script.js"></script>
  </body>
</html>
