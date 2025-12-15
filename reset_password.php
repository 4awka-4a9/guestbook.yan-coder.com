<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
header('Content-Type: text/html; charset=utf-8');

require_once("core.php");

$title = "guestbook.yan-coder.com | password reset";
$description = "Guestbook reset password page by yan-coder maked with php, sql and bootstrap";

if (!empty($_SESSION["user_id"])) {
  header("location: index.php");
}

$errors = [];
$succes = false;
$secret_link = "";

if (isset($_POST["action"]) && $_POST["action"] == "password_reset") {

  $stmt = $pdo->prepare(
    "SELECT email, id
      FROM users 
      WHERE email = :email
      "
  );

  $stmt->execute([':email' => $_POST["email"]]);
  $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

  if (empty($_POST["email"])) {
    $errors[] = "Please enter your email";
  }

  if ($user_data == false) {
    $errors[] = "Email not found";
  }

  if (empty($errors)) {

    $secret_link = generateRandomString();

    $stmt = $pdo->prepare(
      "UPDATE users SET 
              reset_password_secret = :secret_code
              WHERE id = :user_id"
    );

    $stmt->execute(array("secret_code" => $secret_link, 'user_id' => $user_data["id"]));

    $link = 'https://guestbook.yan-coder.com/reset_password_confirm.php?user_id=' . $user_data["id"] . '&secret=' . $secret_link;

    $mailText = <<<TXT

          <p>
          Hello, you requested a password reset for the guestbook.yan-coder.com. To restore your password, please follow the link below.<br>
          </p>
          <a href='$link'>Reset password</a>

        TXT;

    sendEmail(
      $_POST["email"],
      "Reset password",
      $mailText
    );

    $succes = true;
  }

}


?>

<?php require_once "auth_header.php"; ?>

<main class="form-signin w-100 m-auto">
  <form method="POST">

    <h1 class="h3 mb-3 fw-normal">Reset password</h1>

    <div style="color: red;">
      <?php foreach ($errors as $error): ?>
        <p><?php echo $error; ?></p>
      <?php endforeach; ?>
    </div>

    <div class="text-success">

      <?php

      if ($succes == true) {
        echo "Email sent!";
      }

      ?>

    </div>

    <div class="form-floating">
      <input type="text" class="form-control input" id="floatingPassword" placeholder="Email" name="email"
        required="" />
      <label for="floatingPassword">Email address</label>
    </div>

    <input type="hidden" name="action" value="password_reset">
    <input class="btn btn-primary w-100 py-2 submit" type="submit" name="submit" value="Send reset password link">

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
</main>
<?php require_once "auth_footer.php"; ?>