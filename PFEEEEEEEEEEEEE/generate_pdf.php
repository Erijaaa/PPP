<?php
require_once('connect.php');
require_once('tcpdf/tcpdf.php');

class MYPDF extends TCPDF {
    public function Header() {
        // En-tête avec le logo et le titre
        $this->SetFont('aealarabiya', '', 12);
        $this->Cell(0, 5, 'الجمهورية التونسية', 0, 1, 'R');
        $this->Cell(0, 5, 'وزارة أملاك الدولة والشؤون العقارية', 0, 1, 'R');
        $this->Cell(0, 5, 'الديوان الوطني للملكية العقارية', 0, 1, 'R');
        $this->Cell(0, 5, 'الإدارة الجهوية للملكية العقارية بالكاف', 0, 1, 'R');
        
        // Titre عقد
        $this->Ln(10);
        $this->SetFont('aealarabiya', 'B', 18);
        $this->Cell(0, 10, 'عقد', 0, 1, 'C');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('aealarabiya', '', 8);
        $this->Cell(0, 10, $this->getAliasNumPage().'/'.$this->getAliasNbPages() . ' : الصفحة', 0, 0, 'C');
    }
}

if (isset($_GET['contrat_id'])) {
    $db = new ClsConnect();
    $pdo = $db->getConnection();
    
    // Création du PDF
    $pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    
    // Configuration du document
    $pdf->SetCreator('ONPFF');
    $pdf->SetAuthor('ONPFF');
    $pdf->SetTitle('عقد');
    
    // Marges
    $pdf->SetMargins(20, 20, 20);
    $pdf->SetHeaderMargin(10);
    $pdf->SetFooterMargin(10);
    
    // Ajout de la première page
    $pdf->AddPage();
    
    // Information du contrat
    $pdf->SetFont('aealarabiya', '', 10);
    $pdf->Cell(60, 5, 'تاريخ التحرير : ' . date('Y/m/d'), 0, 1, 'R');
    
    // Cadre des informations principales
    $pdf->Ln(5);
    $pdf->Cell(50, 5, 'عدد مطلب التحرير: ' . $_GET['id_demande'], 0, 0, 'R');
    $pdf->Cell(50, 5, 'عدد الوصل: ' . $_GET['num_recu'], 0, 0, 'R');
    $pdf->Cell(50, 5, 'عدد العقد: ' . $_GET['num_contrat'], 0, 1, 'R');
    
    // موضوع العقد
    $pdf->Ln(5);
    $pdf->SetFont('aealarabiya', 'B', 12);
    $pdf->Cell(0, 10, 'موضوع العقد', 1, 1, 'C');
    $pdf->SetFont('aealarabiya', '', 12);
    $pdf->Cell(0, 10, 'وعد بيع', 1, 1, 'C');
    
    // القسم الأول: البيانات المتعلقة بطالب الخدمة
    $pdf->Ln(5);
    $pdf->SetFont('aealarabiya', 'B', 12);
    $pdf->Cell(0, 10, 'القسم الأول: البيانات المتعلقة بطالب الخدمة', 0, 1, 'R');
    
    // القسم الثاني: البيانات المتعلقة بهوية وإلتزامات المحرر
    $pdf->Ln(5);
    $pdf->SetFont('aealarabiya', 'B', 12);
    $pdf->Cell(0, 10, 'القسم الثاني: البيانات المتعلقة بهوية وإلتزامات المحرر', 0, 1, 'R');
    
    // Tableau des المؤيدات
    $pdf->Ln(5);
    $pdf->SetFont('aealarabiya', 'B', 12);
    $pdf->Cell(0, 10, 'القسم الثالث: البيانات المتعلقة بالمؤيدات', 0, 1, 'R');
    
    // En-têtes du tableau
    $pdf->SetFont('aealarabiya', '', 10);
    $header = array('ع.الورق', 'تاريخها', 'مراجع التسجيل', 'تاريخها', 'الوثيقة', 'العدد الرتبي');
    $w = array(20, 30, 30, 30, 40, 20);
    
    // En-tête du tableau
    for($i=0; $i<count($header); $i++)
        $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
    $pdf->Ln();
    
    // القسم الرابع: البيانات المتعلقة بأطراف التعاقد
    $pdf->AddPage();
    $pdf->SetFont('aealarabiya', 'B', 12);
    $pdf->Cell(0, 10, 'القسم الرابع: البيانات المتعلقة بأطراف التعاقد', 0, 1, 'R');
    
    // القسم الخامس: البيانات المتعلقة بموضوع التعاقد
    $pdf->Ln(5);
    $pdf->Cell(0, 10, 'القسم الخامس: البيانات المتعلقة بموضوع التعاقد', 0, 1, 'R');
    
    // Tableau du prix
    $pdf->SetFont('aealarabiya', '', 10);
    $pdf->Cell(30, 7, 'القابض', 1, 0, 'C');
    $pdf->Cell(30, 7, 'المدة', 1, 0, 'C');
    $pdf->Cell(40, 7, 'القيمة أو الثمن', 1, 0, 'C');
    $pdf->Cell(30, 7, 'المحتوى', 1, 0, 'C');
    $pdf->Cell(40, 7, 'موضوع التعاقد', 1, 1, 'C');
    
    // الفصول
    $pdf->AddPage();
    $pdf->SetFont('aealarabiya', 'B', 12);
    $pdf->Cell(0, 10, 'الفصول', 0, 1, 'R');
    
    // القسم السابع: إمضاءات الأطراف والتعريف بها
    $pdf->Ln(5);
    $pdf->Cell(0, 10, 'القسم السابع: إمضاءات الأطراف والتعريف بها', 0, 1, 'R');
    
    // Tableau des signatures
    $pdf->SetFont('aealarabiya', '', 10);
    $pdf->Cell(40, 7, 'الإمضاءات', 1, 0, 'C');
    $pdf->Cell(30, 7, 'الصفة', 1, 0, 'C');
    $pdf->Cell(30, 7, 'اللقب', 1, 0, 'C');
    $pdf->Cell(30, 7, 'إسم الجد', 1, 0, 'C');
    $pdf->Cell(30, 7, 'إسم الأب', 1, 0, 'C');
    $pdf->Cell(30, 7, 'الإسم', 1, 1, 'C');
    
    // معاليم التحرير
    $pdf->AddPage();
    $pdf->SetFont('aealarabiya', 'B', 12);
    $pdf->Cell(0, 10, 'معاليم التحرير', 0, 1, 'R');
    
    // Tableau des frais
    $pdf->SetFont('aealarabiya', '', 10);
    $pdf->Cell(30, 7, 'التاريخ', 1, 0, 'C');
    $pdf->Cell(30, 7, 'عدد الوصل', 1, 0, 'C');
    $pdf->Cell(40, 7, 'المبلغ المستخلص', 1, 0, 'C');
    $pdf->Cell(40, 7, 'المبلغ المستوجب', 1, 0, 'C');
    $pdf->Cell(40, 7, 'الجهة المستخلصة', 1, 1, 'C');
    
    // Génération du PDF
    $pdf->Output('contrat.pdf', 'I');
} else {
    echo "ID du contrat non spécifié";
}
?> 