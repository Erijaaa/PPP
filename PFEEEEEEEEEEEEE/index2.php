<?php
/*require_once 'connect.php';
$cls = new ClsConnect();

$user = $cls->verifierUtilisateur($_POST['cin'], $_POST['password']);
if ($user) {
    $cls->demarrerSessionUtilisateur($user);
    $cls->redirigerParRole($_SESSION['userAuth']['role']);
} */


?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style_index.css" />
  </head>
  <body>
    <div class="container">
      <div class="header">
        <div class="logo-container">
          <div class="header-text">
            <img src="media/ministere.png" alt="" />
          </div>
        </div>
      </div>
      <div class="main-content">
        <form action="verifier_email.php" method="post">
          <div class="login-form">
            <h1 class="form-title">محرر العقد</h1>

            <div class="form-group" style="display: flex; align-items: center">
              <label class="form-label" style="margin-right: 10px"></label>
              عدد الصلاحية   </label
              >
              <select name="region" class="form-input" dir="rtl" required>
                <option value="">-- --</option>
                <option value="Tunis">0</option>
                <option value="Tunis">1</option>
                <option value="Tunis">2</option>
              </select>
            </div>

            <div class="form-group" style="display: flex; align-items: center">
              <label class="form-label" style="margin-right: 10px"
                >الإدارة الجهوية</label
              >
              <select name="region" class="form-input" dir="rtl" required>
                <option value="">-- --</option>
                <option value="Tunis">تونس</option>
                <option value="Beja">باجة</option>
                <option value="Jendouba">جندوبة</option>
                <option value="Ariana">أريانة</option>
                <option value="Ben Arous">بن عروس</option>
                <option value="Manouba">منوبة</option>
                <option value="Nabeul_1">نابل_1</option>
                <option value="Nabeul_2">نابل_2</option>
                <option value="Bizerte">بنزرت</option>
                <option value="Beja">باجة</option>
                <option value="Jendouba">جندوبة</option>
                <option value="Kef">الكاف</option>
                <option value="Siliana">سليانة</option>
                <option value="Sousse">سوسة</option>
                <option value="Monastir">المنستير</option>
                <option value="Mahdia">المهدية</option>
                <option value="Sfax">صفاقس</option>
                <option value="Kairouan">القيروان</option>
                <option value="Kasserine">القصرين</option>
                <option value="Sidi Bouzid">سيدي بوزيد</option>
                <option value="Gabes">قابس</option>
                <option value="Medenine">مدنين</option>
                <option value="Gafsa">قفصة</option>
                <option value="Kebili">قبلي</option>
              </select>
            </div>

            <div class="form-group" style="display: flex; align-items: center">
              <label class="form-label" style="margin-right: 10px"
                >رقم التعريف</label
              >
              <input type="text" name="cin_admin" class="form-input" required />
            </div>

            <div class="form-group" style="display: flex; align-items: center">
              <label class="form-label" style="margin-right: 10px">الاسم</label>
              <input type="text" name="nom_admin" class="form-input" required />
            </div>

            <div class="form-group" style="display: flex; align-items: center">
              <label class="form-label" style="margin-right: 10px"
                >كلمة المرور</label
              >
              <input
                type="password"
                name="password"
                class="form-input"
                required
              />
            </div>

            <button type="submit" class="btn-connect">CONNECTER</button>
          </div>
        </form>

        <div class="logo-section">
          <img class="onpf-logo" src="media/logo.png" alt="ONPF Logo" />
        </div>
      </div>
    </div>
  </body>
</html>