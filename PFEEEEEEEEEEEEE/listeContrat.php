<?php 
require_once 'connect.php';

$connect = new ClsConnect();
$pdo = $connect->getConnection();

$etat_demande = 1;
$data=$connect->traitContrat($etat_demande);


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الطلبات</title>
    <link rel="stylesheet" href="css/consultation.css">
</head> 
<body>
    <div class="container">
        <div class="header">
            <h2>طلبات نوع الخدمة <?php echo $type_demande; ?></h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>تاريخ المطلب</th>
                    <th>عدد مطلب التحرير</th>
                    <th>عدد الوصل</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php if (is_array($data)) { ?>
                <?php foreach ($data as $demande) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($demande['date_demande']); ?></td>
                        <td><?php echo htmlspecialchars($demande['id_demande']); ?></td>
                        <td><?php echo htmlspecialchars($demande['num_recu']); ?></td>
                        <td>
                            <!--<a href="Traitement.php?id_demande=<?php echo urlencode($demande['id_demande']); ?>&num_recu=<?php echo urlencode($demande['num_recu']); ?>">معالجة</a>-->
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
        </table>
    </div>
</body>
</html>