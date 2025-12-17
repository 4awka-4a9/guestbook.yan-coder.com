<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("core.php");
if (!empty($_SESSION["user_id"])) {
  header("location: registration.php");
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["user_name"];
  $email = $_POST["email"];
}

if (isset($_POST["action"]) && $_POST["action"] == "register") {

  if (empty($_POST["user_name"])) {
    $errors[] = "Please enter user name";
  }
  if (empty($_POST["email"])) {
    $errors[] = "Please enter email";
  }
  if (empty($_POST["first_name"])) {
    $errors[] = "Please enter first Name";
  }
  if (empty($_POST["last_name"])) {
    $errors[] = "Please enter last Name";
  }
  if (empty($_POST["password"])) {
    $errors[] = "Please enter password";
  }
  if (empty($_POST["confirm_password"])) {
    $errors[] = "Please confirm password";
  }

  if (strlen($_POST["user_name"]) > 100) {
    $errors[] = "User name if too long";
  }
  if (strlen($_POST["first_name"]) > 80) {
    $errors[] = "First name is too long";
  }
  if (strlen($_POST["last_name"]) > 100) {
    $errors[] = "Last name if too long";
  }
  if (strlen($_POST["password"]) < 6) {
    $errors[] = "Password is too short";
  }
  if ($_POST["password"] !== $_POST["confirm_password"]) {
    $errors[] = "Your confirm password is not match password";
  }

  if (validateEMAIL($email) == false) {
    $errors[] = "Email is not valid";
  }
  $stmt = $pdo->prepare(
    "SELECT COUNT(*) AS count 
      FROM users 
      WHERE username = :username OR email = :email
      "
  );

  $stmt->execute([':username' => $username, ':email' => $email]);
  $count = $stmt->fetchColumn();

  if (!empty($count) && $count > 0) {
    $errors[] = "The username or email is busy by another user";
  }

  if (empty($errors)) {


    $stmt = $pdo->prepare(
      "INSERT INTO users
            (`username`, `email`, `password`, `first_name`, `last_name`) 
            VALUES
            (:username, :email, :password, :first_name, :last_name)"
    );

    $stmt->execute(array(
      "username" => $_POST["user_name"],
      "email" => $_POST["email"],
      "password" => sha1($_POST["password"] . SALT),
      "first_name" => $_POST["first_name"],
      "last_name" => $_POST["last_name"]
    ));

    header("location: login.php?registration=1");

  }
}


$title = "guestbook.yan-coder.com | registration";
$description = "Guestbook registration page by yan-coder maked with php, sql and bootstrap";

?>

<?php require_once "auth_header.php"; ?>

<main class="form-signin w-100 m-auto">
  <form method="POST">

    <h1 class="h3 mb-3 fw-normal">Register now!</h1>

    <div class="text-danger">
      <?php foreach ($errors as $error): ?>
        <p><?php echo $error; ?></p>
      <?php endforeach; ?>
    </div>

    <div class="form-floating">
      <input type="text" class="form-control input" id="floatingInput" placeholder="name@example.com" name="user_name"
        required="" value="<?php echo (!empty($_POST["user_name"]) ? $_POST["user_name"] : ''); ?>">
      <label for="floatingInput">Username</label>
    </div>

    <div class="form-floating">
      <input type="text" class="form-control input" id="floatingInput" placeholder="Email" name="email" required=""
        value="<?php echo (!empty($_POST["email"]) ? $_POST["email"] : ''); ?>">
      <label for="floatingInput">Email address</label>
    </div>

    <div class="form-floating">
      <input type="text" class="form-control input" id="floatingInput" placeholder="last name" name="first_name"
        required="" value="<?php echo (!empty($_POST["first_name"]) ? $_POST["first_name"] : ''); ?>">
      <label for="floatingInput">First name</label>
    </div>

    <div class="form-floating">
      <input type="text" class="form-control input" id="floatingInput" placeholder="Last name" name="last_name"
        required="" value="<?php echo (!empty($_POST["last_name"]) ? $_POST["last_name"] : ''); ?>">
      <label for="floatingInput">Last name</label>
    </div>

    <div class="form-floating">
      <input type="password" class="form-control input"  placeholder="Password" name="password"
        required="" value="">
      <label for="floatingInput">Password</label>
    </div>

    <div class="form-floating">
      <input type="password" class="form-control input" id="floatingInput" placeholder="Password"
        name="confirm_password" required="" value="">
      <label for="floatingInput">Confirm password</label>
    </div>

    <!-- <div class="form-check text-start my-3">
          <input
            class="form-check-input"
            type="checkbox"
            value="remember-me"
            id="checkDefault"
          
          <label class="form-check-label" for="checkDefault">
            Remember me
          </label>
        </div> -->

    <input type="hidden" name="action" value="register">
    <input class="btn btn-primary w-100 py-2 submit" type="submit" name="submit" value="Register">

    <a href="login.php">Have an account? Login now!</a>

    <p class="mt-5 mb-3 text-body-secondary">&copy; yan-coder 2025</p>

    <!-- Yandex.Metrika informer --> <a href="https://metrika.yandex.ru/stat/?id=105184923&amp;from=informer"
      target="_blank" rel="nofollow"> <img
        src="https://informer.yandex.ru/informer/105184923/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
        style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика"
        title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)"
        class="ym-advanced-informer" data-cid="105184923" data-lang="ru" > </a> <!-- /Yandex.Metrika informer -->
    <!-- Yandex.Metrika counter -->
    <script
      >     (function (m, e, t, r, i, k, a) { m[i] = m[i] || function () { (m[i].a = m[i].a || []).push(arguments) }; m[i].l = 1 * new Date(); for (var j = 0; j < document.scripts.length; j++) { if (document.scripts[j].src === r) { return; } } k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a) })(window, document, 'script', 'https://mc.yandex.ru/metrika/tag.js?id=105184923', 'ym'); ym(105184923, 'init', { ssr: true, webvisor: true, clickmap: true, ecommerce: "dataLayer", accurateTrackBounce: true, trackLinks: true }); </script>
    <noscript>
      <div><img src="https://mc.yandex.ru/watch/105184923" style="position:absolute; left:-9999px;" alt=""></div>
    </noscript> <!-- /Yandex.Metrika counter -->

  </form>
</main>
<?php require_once "auth_footer.php"; ?>