<?php
require_once 'vendor/autoload.php';
require_once 'connect.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Initialisation de la base de données
$db = new ClsConnect();
$pdo = $db->getConnection();

// Récupération des paramètres
$id_contrat = $_GET['id_contrat'] ?? null;
$id_demande = $_GET['id_demande'] ?? null;

if (!$id_contrat || !$id_demande) {
    die("Paramètres manquants");
}

// Récupération des données du contrat
$contrat = $db->getContratComplet($id_contrat);

// Configuration de DOMPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

// Création du contenu HTML
$html = '
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            direction: rtl;
        }
        .header {
            text-align: right;
            margin-bottom: 20px;
        }
        .contract-title {
            text-align: center;
            margin: 20px 0;
        }
        .section {
            margin: 15px 0;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table, th, td {
            border: 1px solid black;
            padding: 5px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>';

// En-tête
$html .= '
<div class="header">
    <h1>الجمهورية التونسية</h1>
    <p>وزارة أملاك الدولة والشؤون العقارية</p>
    <p>الديوان الوطني للملكية العقارية</p>
    <p>الإدارة الجهوية للملكية العقارية بالكاف</p>
    <p>تاريخ التحرير: ' . date('Y/m/d') . '</p>
</div>';

// Titre du contrat
$html .= '
<div class="contract-title">
    <h2>عقد</h2>
    <p>عدد: ' . $contrat['base']['id_contrat'] . '</p>
</div>';

// Informations de base
$html .= '
<div class="section">
    <p>عدد مطلب التحرير: ' . $id_demande . '/' . date('Y') . '</p>
    <p>عدد الوصل: ' . $contrat['base']['num_recu'] . '</p>
    <p>تاريخه: ' . $contrat['base']['date_demande'] . '</p>
</div>';

// Section des parties
$html .= '
<div class="section">
    <div class="section-title">القسم الرابع: البيانات المتعلقة بأطراف التعاقد</div>';

foreach ($contrat['parties'] as $partie) {
    $html .= '
    <div class="partie">
        <p>الإسم: ' . $partie['nom'] . '</p>
        <p>اللقب: ' . $partie['prenom'] . '</p>
        <p>تاريخ الولادة: ' . $partie['date_naissance'] . '</p>
        <p>مكان الولادة: ' . $partie['lieu_naissance'] . '</p>
        <p>العنوان: ' . $partie['adresse'] . '</p>
    </div>';
}

// Section des documents
$html .= '
<div class="section">
    <div class="section-title">القسم الثالث: البيانات المتعلقة بالمؤيدات</div>
    <table>
        <tr>
            <th>ع ر</th>
            <th>الوثيقة</th>
            <th>تاريخها</th>
            <th>مراجع التسجيل</th>
        </tr>';

foreach ($contrat['documents'] as $index => $document) {
    $html .= '
        <tr>
            <td>' . ($index + 1) . '</td>
            <td>' . $document['type_document'] . '</td>
            <td>' . $document['date_document'] . '</td>
            <td>' . $document['reference'] . '</td>
        </tr>';
}

$html .= '
    </table>
</div>';

// Détails du contrat
if (isset($contrat['details'])) {
    $html .= '
    <div class="section">
        <div class="section-title">القسم الخامس: البيانات المتعلقة بموضوع التعاقد</div>
        <p>المبلغ: ' . number_format($contrat['details']['montant'], 3) . ' دينار</p>
        <p>المساحة: ' . $contrat['details']['surface'] . ' متر مربع</p>
        <p>العنوان: ' . $contrat['details']['adresse_bien'] . '</p>
        <p>الوصف: ' . $contrat['details']['description_bien'] . '</p>
    </div>';
}

// Pied de page
$html .= '
<div class="footer">
    <p>صفحة {PAGENO}</p>
    <p>تاريخ التحرير: ' . date('Y/m/d') . '</p>
</div>
</body>
</html>';

// Génération du PDF
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Envoi du PDF au navigateur
$dompdf->stream("contrat_" . $id_contrat . ".pdf", array("Attachment" => false));
?> 