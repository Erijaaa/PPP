<?php
require_once('tcpdf/tcpdf.php');
require_once('connect.php');

class ContractPDF extends TCPDF {
    public function Header() {
        // Logo et en-tête
        $this->SetFont('dejavusans', '', 12);
        $this->Cell(0, 10, 'الجمهورية التونسية', 0, 1, 'C');
        $this->Cell(0, 10, 'وزارة أملاك الدولة والشؤون العقارية', 0, 1, 'C');
        $this->Cell(0, 10, 'الديوان الوطني للملكية العقارية', 0, 1, 'C');
        $this->Cell(0, 10, 'الإدارة الجهوية للملكية العقارية بالكاف', 0, 1, 'C');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('dejavusans', '', 8);
        $this->Cell(0, 10, 'الصفحة ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }
}

// Récupération des données du formulaire
$db = new ClsConnect();
$id_demande = $_GET['id_demande'] ?? '';
$id_contrat = $_GET['id_contrat'] ?? '';

// Récupérer toutes les données nécessaires de la base de données
$demande = $db->getDemandeById($id_demande);
$agent = $db->getAgent($id_demande);
$deposant = $db->getDeposant($id_demande);
$pieces_jointes = $db->getPiecesJointesByDemande($id_demande);

// Création du PDF
$pdf = new ContractPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Paramètres du document
$pdf->SetCreator('Système de Gestion des Contrats');
$pdf->SetAuthor('ONPFF');
$pdf->SetTitle('عقد رقم ' . $id_contrat);

// Ajout d'une page
$pdf->AddPage();
$pdf->SetFont('dejavusans', '', 12);

// En-tête du contrat
$pdf->Cell(0, 10, 'عقد', 1, 1, 'C');
$pdf->Ln(5);

// Informations du contrat
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(50, 10, 'عدد مطلب التحرير: ' . $id_demande, 0, 0, 'R');
$pdf->Cell(50, 10, 'تاريخه: ' . $demande['date_demande'], 0, 0, 'R');
$pdf->Cell(50, 10, 'عدد العقد: ' . $id_contrat, 0, 1, 'R');

// Section 1: Données du demandeur
$pdf->Ln(10);
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0, 10, 'القسم الأول: البيانات المتعلقة بطالب الخدمة', 0, 1, 'R');
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(50, 10, 'الإسم: ' . $deposant['prenom_deposant'], 0, 0, 'R');
$pdf->Cell(50, 10, 'اللقب: ' . $deposant['nom_deposant'], 0, 1, 'R');

// Section 2: Données du rédacteur
$pdf->Ln(5);
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0, 10, 'القسم الثاني: البيانات المتعلقة بهوية وإلتزامات المحرر', 0, 1, 'R');
$pdf->SetFont('dejavusans', '', 10);
$pdf->Cell(50, 10, 'الإسم: ' . $agent['prenom_agent'], 0, 0, 'R');
$pdf->Cell(50, 10, 'اللقب: ' . $agent['nom_agent'], 0, 1, 'R');

// Section 3: Documents joints
$pdf->Ln(5);
$pdf->SetFont('dejavusans', 'B', 12);
$pdf->Cell(0, 10, 'القسم الثالث: البيانات المتعلقة بالمؤيدات', 0, 1, 'R');
$pdf->SetFont('dejavusans', '', 10);

// Tableau des documents
$pdf->SetFillColor(240, 240, 240);
$pdf->Cell(20, 10, 'ع ر', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'الوثيقة', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'تاريخها', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'مراجع التسجيل', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'تاريخها', 1, 1, 'C', true);

foreach ($pieces_jointes as $index => $piece) {
    $pdf->Cell(20, 10, $index + 1, 1, 0, 'C');
    $pdf->Cell(40, 10, $piece['libelle_piece'], 1, 0, 'C');
    $pdf->Cell(30, 10, $piece['date_piece'], 1, 0, 'C');
    $pdf->Cell(40, 10, $piece['ref_piece'], 1, 0, 'C');
    $pdf->Cell(30, 10, $piece['date_ref'], 1, 1, 'C');
}

// Sections suivantes...
// Ajouter les autres sections du contrat de la même manière

// Génération du PDF
$pdf->Output('contrat_' . $id_contrat . '.pdf', 'I');
?> 