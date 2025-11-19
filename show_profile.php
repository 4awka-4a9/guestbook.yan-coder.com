<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("config.php");
if (empty($_SESSION["user_id"])) {
    header("location: login.php");
}

$stmt = $pdo->prepare(
  "SELECT 
  username, 
  first_name, 
  last_name, 
  about_me, 
  avatar, 
  email 
  FROM `users` 
  WHERE id = :id
  ");

$stmt->execute(array(
  "id" => $_GET["id"]
));

$user = $stmt->fetch(PDO::FETCH_ASSOC);

$user["username"] = htmlspecialchars($user["username"]);
$user["email"] = htmlspecialchars($user["email"]);
$user["first_name"] = htmlspecialchars($user["first_name"]);
$user["last_name"] = htmlspecialchars($user["last_name"]);

$title = "guestbook.yan-coder.com | " . $user["username"];

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

            ?>

            <tbody>

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
                    <th>About me</th>
                    <td><?php echo $about_me;?></td>
                </tr>

            </tbody>

        </table>

      </main>
<?php require_once "footer.php";?>
