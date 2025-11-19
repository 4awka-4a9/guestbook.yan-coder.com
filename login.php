<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("config.php");
if (!empty($_SESSION["user_id"])) {
    header("location: index.php");
}

$errors = [];
if (!empty($_POST)) {
    
    if (empty($_POST["user_name"])) {
        $errors[] = "Please enter Username or Email";
    }
    if (empty($_POST["password"])) {
        $errors[] = "Please enter Password";
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare(
          "SELECT id 
          FROM users 
          WHERE (username = :username or email = :username) and password = :password");

        $stmt->execute(array(
          "username" => $_POST["user_name"], 
          "password" => sha1($_POST["password"].SALT)
        ));

        $id = $stmt->fetchColumn();
        
        if (!empty($id)) {
            $_SESSION["user_id"] = $id;
            header("location: index.php");
        }
        else {
            $errors[] = "Please enter valid credentails";
        }
    }
}

$title = "guestbook.yan-coder.com | log in";

?>

<?php require_once "auth_header.php";?>

    <main class="form-signin w-100 m-auto">
      <form method="POST">

        <h1 class="h3 mb-3 fw-normal">Login now!</h1>

        <div style="color: red;">
          <?php foreach ($errors as $error) :?>
            <p><?php echo $error; ?></p>
          <?php endforeach; ?>
        </div>

        <div class="form-floating">
          <input
            type="text"
            class="form-control input"
            id="floatingInput"
            placeholder="name@example.com"
            name="user_name"
            required=""
            value="<?php echo (!empty($_POST["user_name"]) ? $_POST["user_name"] : ''); ?>"
          />
          <label for="floatingInput">Username</label>
        </div>

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

        <!-- <div class="form-check text-start my-3">
          <input
            class="form-check-input"
            type="checkbox"
            value="remember-me"
            id="checkDefault"
          />
          <label class="form-check-label" for="checkDefault">
            Remember me
          </label>
        </div> -->

        <input class="btn btn-primary w-100 py-2 submit" type="submit" name="submit" value="Login">

        <a href="registration.php">Don't have an account? Register now!</a>

        <p class="mt-5 mb-3 text-body-secondary">&copy; yan-coder 2025</p>

        <!-- Yandex.Metrika informer --> <a href="https://metrika.yandex.ru/stat/?id=105184923&amp;from=informer" target="_blank" rel="nofollow">     <img src="https://informer.yandex.ru/informer/105184923/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"          style="width:88px; height:31px; border:0;"          alt="Яндекс.Метрика"          title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)"         class="ym-advanced-informer" data-cid="105184923" data-lang="ru"/> </a> <!-- /Yandex.Metrika informer -->  <!-- Yandex.Metrika counter --> <script type="text/javascript">     (function(m,e,t,r,i,k,a){         m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};         m[i].l=1*new Date();         for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}         k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)     })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=105184923', 'ym');      ym(105184923, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", accurateTrackBounce:true, trackLinks:true}); </script> <noscript><div><img src="https://mc.yandex.ru/watch/105184923" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->   

      </form>
    </main>
<?php require_once "auth_footer.php";?>
