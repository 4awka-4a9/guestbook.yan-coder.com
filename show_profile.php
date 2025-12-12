<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("core.php");
if (empty($_SESSION["user_id"])) {
    header("location: login.php");
}

$stmt = $pdo->prepare(
  "SELECT 
  id,
  username, 
  first_name, 
  last_name, 
  about_me, 
  avatar, 
  email,
  city,
  birthday 
  FROM `users` 
  WHERE id = :id
  ");

$stmt->execute(array(
  "id" => $_GET["id"]
));

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user == false) {
    $title = "User Not Found";
    require_once "header.php";
    echo "<main><div class='alert alert-danger'>User with this id " . htmlspecialchars($_GET['id']) . " not found.</div></main>";
    require_once "footer.php";
    exit(); 
}

$user["username"] = htmlspecialchars($user["username"]);
$user["email"] = htmlspecialchars($user["email"]);
$user["first_name"] = htmlspecialchars($user["first_name"]);
$user["last_name"] = htmlspecialchars($user["last_name"]);

$title = "guestbook.yan-coder.com | " . $user["username"];
$description = "Guestbook" . $user["username"] . "'s profile page by yan-coder maked with php, sql and bootstrap";

?>

<?php require_once "header.php";?>

      <main>

        <table class="table table-striped table-hover">

            <?php

              if ($user["about_me"]) {
                $about_me = $user["about_me"];
              }
              else {
                $about_me = "None";
              }

              if ($user["avatar"]) {
                $avatar = $user["avatar"];
              }
              else {
                $avatar = "default_avatar.jpg";
              }

            ?>

            <tbody>

                <tr>
                    <th>Avatar</th>
                    <td><img class="avatar me-2" src="avatars/<?php echo $avatar; ?>"></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?php echo $user["username"];?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><a href="mailto:" <?php echo $user["email"];?>><?php echo $user["email"];?></a></td>
                </tr>
                <tr>
                    <th>First name</th>
                    <td><?php echo $user["first_name"];?></td>
                </tr>
                <tr>
                    <th>Last name</th>
                    <td><?php echo $user["last_name"];?></td>
                </tr>
                <tr>
                    <th>City</th>
                    <td><?php echo $user["city"];?></td>
                </tr>
                <tr>
                    <th>Birthday</th>
                    <td><?php echo $user["birthday"];?></td>
                </tr>
                <tr>
                    <th>About me</th>
                    <td><?php echo $about_me;?></td>
                </tr>

            </tbody>

        </table>

      </main>
<?php require_once "footer.php";?>
