<?php

require_once("config.php");

$current_form = "";

$title = "guestbook.yan-coder.com | confirm password reset";
$description = "Guestbook confirm reset password page by yan-coder maked with php, sql and bootstrap";

$errors = [];

$form = <<<TXT

    <div class="form-floating">
          <input
            type="password"
            class="form-control input"
            id="floatingPassword"
            placeholder="Password"
            name="password" 
            required="" value=""
          />
          <label for="floatingPassword">Password</label>
    </div>

    <div class="form-floating">
          <input
            type="password"
            class="form-control input"
            id="floatingPassword"
            placeholder="Password"
            name="confirm_password" 
            required="" 
            value=""
          />
          <label for="floatingPassword">Confirm password</label>
    </div>

    <input class="btn btn-primary w-100 py-2 submit" type="submit" name="submit" value="Reset password">

    <p class="mt-5 mb-3 text-body-secondary">&copy; yan-coder 2025</p>

    <!-- Yandex.Metrika informer --> <a href="https://metrika.yandex.ru/stat/?id=105184923&amp;from=informer"
      target="_blank" rel="nofollow"> <img
        src="https://informer.yandex.ru/informer/105184923/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
        style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика"
        title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)"
        class="ym-advanced-informer" data-cid="105184923" data-lang="ru" /> </a> <!-- /Yandex.Metrika informer -->
    <!-- Yandex.Metrika counter -->
    <script
      type="text/javascript">     (function (m, e, t, r, i, k, a) { m[i] = m[i] || function () { (m[i].a = m[i].a || []).push(arguments) }; m[i].l = 1 * new Date(); for (var j = 0; j < document.scripts.length; j++) { if (document.scripts[j].src === r) { return; } } k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a) })(window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js?id=105184923', 'ym'); ym(105184923, 'init', { ssr: true, webvisor: true, clickmap: true, ecommerce: "dataLayer", accurateTrackBounce: true, trackLinks: true }); </script>
    <noscript>
      <div><img src="https://mc.yandex.ru/watch/105184923" style="position:absolute; left:-9999px;" alt="" /></div>
    </noscript> <!-- /Yandex.Metrika counter -->

  </form>

TXT;

$stmt = $pdo->prepare(
    "SELECT id, reset_password_secret
      FROM users 
      WHERE id = :user_id AND reset_password_secret = :reset_password_secret
      ");

  $stmt->execute(['user_id' => $_GET["user_id"], 'reset_password_secret' => $_GET["secret"]]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

if (empty($user)) {

    $current_form = "<h1>Wrong link</h1>";

}
else {

    $current_form = $form;
             
}

if (!empty($_POST)) {

    if (empty($_POST["password"])) {
        $errors[] = "Please enter password";
    }
    if (strlen($_POST["password"]) < 6) {
        $errors[] = "Password is too short";
    }
    if (empty($_POST["confirm_password"])) {
        $errors[] = "Please confirm password";
    }
    if ($_POST["password"] !== $_POST["confirm_password"]) {
        $errors[] = "Your confirm password is not match password";
    }
    if (empty($errors)) {

        $stmt = $pdo->prepare(
    "UPDATE users
            SET password = :password 
            WHERE id = :user_id AND reset_password_secret = :reset_password_secret
            ");

        $stmt->execute(['user_id' => $_GET["user_id"], 'reset_password_secret' => $_GET["secret"], 'password' => sha1($_POST["password"].SALT)]);

        header("location: login.php");

    }

}

?>

<?php require_once "auth_header.php"; ?>

<main class="form-signin w-100 m-auto">

    <form method="POST">

        <h1 class="h3 mb-3 fw-normal">Reset password</h1>

        <div style="color: red;">
          <?php foreach ($errors as $error) :?>
            <p><?php echo $error; ?></p>
          <?php endforeach; ?>
        </div>

    <?php echo $current_form;?>
  
</main>
<?php require_once "auth_footer.php"; ?>