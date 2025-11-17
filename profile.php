<?php

require_once("config.php");
if (empty($_SESSION["user_id"])) {
    header("location: login.php");
}

$errors = [];
if (!empty($_POST)) {

    $sql = "";
    $update = ["username" => $_POST["user_name"], "user_id" => $_SESSION["user_id"], "first_name" => $_POST["first_name"], "last_name" => $_POST["last_name"], "about_me" => $_POST["about_me"], "password" => sha1($_POST["password"].SALT)];

    if (empty($_POST["user_name"])) {
        $errors[] = "Please enter user name";
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

    if (isset($_FILES["fileToUpload"]["name"])) {
      $target_dir = "/avatars/";
      $extension = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
      $filename = "avatar{$_SESSION['user_id']}." . $extension;
      $target_file = $_SERVER['DOCUMENT_ROOT'] . $target_dir . $filename;
      $uploadOk = 1;

      $update["avatar"] = $filename;
      $sql = ", avatar = :avatar";

      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

      if ($check !== false) {
          $uploadOk = 1;
      }

      else {
          $errors[] = "File is not an image.";
          $uploadOk = 0;
      }

      if ($_FILES["fileToUpload"]["size"] > 5000000) {
          $errors[] = "Sorry, your file is too large.";
          $uploadOk = 0;
      }

      if ($extension != "jpg" && $extension != "png" && $extension != "jpeg" && $extension != "gif") {
          $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
          $uploadOk = 0;
      } 

      if ($uploadOk == 0) {
          $errors[] = "Sorry, your file was not uploaded.";
      }

      else {
          if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        
          }

          else {
              $errors[] = "Sorry, your file was not uploaded.";
          }
        }
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE users SET username = :username, first_name = :first_name, last_name = :last_name, about_me = :about_me, password = :password $sql WHERE id = :user_id");
        $stmt->execute($update);
        $id = $stmt->fetchColumn();
    }
}

$stmt = $pdo->prepare("SELECT username, first_name, last_name, about_me, avatar FROM `users` WHERE id = :user_id");
$stmt->execute(array("user_id" => $_SESSION["user_id"]));
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="guestbook" />
    <meta
      name="author"
      content="yan-coder"
    />
    <meta name="generator" content="Astro v5.13.2" />
    <title>profile | guestbook.yan-coder.com</title>

    <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">

    <link
      rel="canonical"
      href="https://getbootstrap.com/docs/5.3/examples/pricing/"
    />
    <script src="js/color-modes.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <meta name="theme-color" content="#712cf9" />
    <link href="css/main.css" rel="stylesheet" />
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }
      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      .b-example-divider {
        width: 100%;
        height: 3rem;
        background-color: #0000001a;
        border: solid rgba(0, 0, 0, 0.15);
        border-width: 1px 0;
        box-shadow:
          inset 0 0.5em 1.5em #0000001a,
          inset 0 0.125em 0.5em #00000026;
      }
      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }
      .bi {
        vertical-align: -0.125em;
        fill: currentColor;
      }
      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }
      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }
      .btn-bd-primary {
        --bd-violet-bg: #712cf9;
        --bd-violet-rgb: 112.520718, 44.062154, 249.437846;
        --bs-btn-font-weight: 600;
        --bs-btn-color: var(--bs-white);
        --bs-btn-bg: var(--bd-violet-bg);
        --bs-btn-border-color: var(--bd-violet-bg);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-hover-bg: #6528e0;
        --bs-btn-hover-border-color: #6528e0;
        --bs-btn-focus-shadow-rgb: var(--bd-violet-rgb);
        --bs-btn-active-color: var(--bs-btn-hover-color);
        --bs-btn-active-bg: #5a23c8;
        --bs-btn-active-border-color: #5a23c8;
      }
      .bd-mode-toggle {
        z-index: 1500;
      }
      .bd-mode-toggle .bi {
        width: 1em;
        height: 1em;
      }
      .bd-mode-toggle .dropdown-menu .active .bi {
        display: block !important;
      }
      #comments-header{ text-align: center;}
      #comments-form{border: 1px dotted black; width: 50%; padding-left: 20px;}
      #comments-form textarea{width: 70%; min-height: 100px;}
      #comments-panel{border: 1px dashed black; width: 50%; padding-left: 20px; margin-top: 20px;}
      .comment-date{font-style: italic;}
      
      .card {
        margin-top: 10px;
        margin-bottom: 10px;
      }

      .textarea {
        resize: none;
      }

      .m-t-b {
        margin-top: 10px;
        margin-bottom: 10px;
      }

    </style>

  </head>
  <body>

    <div class="container py-3">
      <header>
        <div
          class="d-flex flex-column flex-md-row align-items-center pb-3 mb-4 border-bottom"
        >
          <a
            href="#"
            class="d-flex align-items-center link-body-emphasis text-decoration-none"
          >
            <span class="fs-4">Guest Book</span>
          </a>
          <nav class="d-inline-flex mt-2 mt-md-0 ms-md-auto">
            <a
              class="btn btn-outline-secondary me-3 py-2 link-body-emphasis text-decoration-none"
              href="index.php"
              >Home</a
            >
            <a
              class="btn  btn-outline-secondary me-3 py-2 link-body-emphasis text-decoration-none"
              href="logout.php"
              >Log out</a
            >
          </nav>
        </div>
      </header>
      <main>
        
        <form method="POST" enctype="multipart/form-data">

            <?php

              $user["username"] = htmlspecialchars($user["username"]);
              $user["first_name"] = htmlspecialchars($user["first_name"]);
              $user["last_name"] = htmlspecialchars($user["last_name"]);

            ?>

            <label class="m-t-b">Edit profile</label>

            <div style="color: red;">
                <?php foreach ($errors as $error) :?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>

            <!-- <div class="form-floating m-t-b">
                <input
                    type="file"
                    class="form-control input"
                    id="fileToUpload"
                    name="fileToUpload"
                />
                <label for="floatingInput">Upload avatar</label>
            </div> -->

            <div class="input-group">
              <input
                  type="file"
                  class="form-control d-none" 
                  id="fileToUpload"
                  name="fileToUpload"
              />
              
              <label class="btn btn-primary upload-avatar-btn" for="fileToUpload" id="uploadButtonLabel">
                  Upload avatar
              </label>

              <input type="text" class="form-control" id="fileNameDisplay" placeholder="No file selected" readonly>
            </div>

            <div class="form-floating m-t-b">
                <input
                    type="text"
                    class="form-control input"
                    id="floatingInput"
                    placeholder="name@example.com"
                    name="user_name"
                    value="<?php echo $user["username"];?>"
                />
                <label for="floatingInput">Username</label>
            </div>

            <div class="form-floating m-t-b">
                <input
                    type="text"
                    class="form-control input"
                    id="floatingPassword"
                    placeholder="last name"
                    name="first_name" 
                    required="" 
                    value="<?php echo $user["first_name"];?>"
                />
                <label for="floatingPassword">First name</label>
            </div>

            <div class="form-floating m-t-b">
                <input
                    type="text"
                    class="form-control input"
                    id="floatingPassword"
                    placeholder="Last name"
                    name="last_name" 
                    required="" 
                    value="<?php echo $user["last_name"];?>"
                />
                <label for="floatingPassword">Last name</label>
            </div>

            <div class="form-floating m-t-b">
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

            <div class="form-floating m-t-b">
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

            <div class="form-floating m-t-b">
                <textarea
                    type="text"
                    class="form-control input"
                    id="floatingPassword"
                    placeholder="About me"
                    name="about_me"  
                ><?php echo $user["about_me"];?></textarea>
                
                <label for="floatingPassword">About me</label>
            </div>

            <div>

                <input class="btn btn-outline-secondary m-t-b" type="submit" name="submit" value="Save">

            </div>

        </form>

      </main>
      <footer class="pt-4 my-md-5 pt-md-5 border-top">
        <div class="row">
          <div class="col-12 col-md">
            <small class="d-block mb-3 text-body-secondary"
              >&copy; yan-coder 2025</small
            >
            <!-- Yandex.Metrika informer --> <a href="https://metrika.yandex.ru/stat/?id=105184923&amp;from=informer" target="_blank" rel="nofollow">     <img src="https://informer.yandex.ru/informer/105184923/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"          style="width:88px; height:31px; border:0;"          alt="Яндекс.Метрика"          title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)"         class="ym-advanced-informer" data-cid="105184923" data-lang="ru"/> </a> <!-- /Yandex.Metrika informer -->  <!-- Yandex.Metrika counter --> <script type="text/javascript">     (function(m,e,t,r,i,k,a){         m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};         m[i].l=1*new Date();         for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}         k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)     })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=105184923', 'ym');      ym(105184923, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", accurateTrackBounce:true, trackLinks:true}); </script> <noscript><div><img src="https://mc.yandex.ru/watch/105184923" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->   
          </div>
          <div class="col-6 col-md">
            <h5><a class="btn  btn-outline-secondary me-3 py-2 link-body-emphasis text-decoration-none" href="index.php">Home</a></h5>
          </div>
          <div class="col-6 col-md">
            <h5><a class="btn  btn-outline-secondary me-3 py-2 link-body-emphasis text-decoration-none" href="profile.php">Edit profile</a></h5>
          </div>
          <div class="col-6 col-md">
            <h5><a class="btn  btn-outline-secondary me-3 py-2 link-body-emphasis text-decoration-none" href="logout.php">Log out</a></h5>
          </div>
        </div>
      </footer>
    </div>
    <script
      src="js/bootstrap.bundle.min.js"
      class="astro-vvvwv3sm"
    ></script>
  </body>
</html>
