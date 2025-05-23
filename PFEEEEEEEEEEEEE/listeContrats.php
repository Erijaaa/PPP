<?php 
require_once 'connect.php';

$connect = new ClsConnect();
$pdo = $connect->getConnection();

$etat_demande = 1;
$etat_contrat = null; 
$obj = $connect->traitContrat($etat_demande, $etat_contrat);

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>العقود المحررة</title>
    <link rel="stylesheet" href="css/listeContrat.css">
</head> 
<body>
    <div class="container">
        <div class="header">
            <h2>العقود المحررة</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>تاريخ المطلب</th>
                    <th>عدد مطلب التحرير</th>
                    <th>عدد العقد</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (is_array($obj) && !empty($obj)) { ?>
                    <?php foreach ($obj as $contrat) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($contrat['date_demande']); ?></td>
                            <td><?php echo htmlspecialchars($contrat['annee_contrat']); ?></td>
                            <td><?php echo htmlspecialchars($contrat['id_contrat']); ?></td>
                            <td>
                                <a href="generate_pdf.php?id_demande=<?php echo urlencode($contrat['id_demande']); ?>&id_contrat=<?php echo urlencode($contrat['id_contrat']); ?>">طباعة العقد</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4">لا توجد عقود محررة</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>