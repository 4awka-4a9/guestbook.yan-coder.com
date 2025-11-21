<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("config.php");
if (empty($_SESSION["user_id"])) {
    header("location: login.php");
}

$errors = [];
if (!empty($_POST)) {

    $sql_password = "";
    $sql_avatar = "";
    $update = [
        "username" => $_POST["user_name"],
        "user_id" => $_SESSION["user_id"],
        "first_name" => $_POST["first_name"],
        "last_name" => $_POST["last_name"],
        "about_me" => $_POST["about_me"],
    ];

    if (empty($_POST["user_name"])) {
        $errors[] = "Please enter user name";
    }
    if (empty($_POST["first_name"])) {
        $errors[] = "Please enter first Name";
    }
    if (empty($_POST["last_name"])) {
        $errors[] = "Please enter last Name";
    }
    // if (empty($_POST["password"])) {
    //     $errors[] = "Please enter password";
    // }
    // if (empty($_POST["confirm_password"])) {
    //     $errors[] = "Please confirm password";
    // }

    if (strlen($_POST["user_name"]) > 100) {
        $errors[] = "User name if too long";
    }
    if (strlen($_POST["first_name"]) > 80) {
        $errors[] = "First name is too long";
    }
    // if (strlen($_POST["last_name"]) > 100) {
    //     $errors[] = "Last name if too long";
    // }
    // if (strlen($_POST["password"]) < 6) {
    //     $errors[] = "Password is too short";
    // }
    if ($_POST["password"] !== $_POST["confirm_password"]) {
        $errors[] = "Your confirm password is not match password";
    }

    if (isset($_FILES["fileToUpload"]["name"]) and $_FILES["fileToUpload"]["name"]) {
        $target_dir = "/avatars/";
        $extension = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
        $filename = "avatar{$_SESSION['user_id']}." . $extension;
        $target_file = $_SERVER['DOCUMENT_ROOT'] . $target_dir . $filename;

        $update["avatar"] = $filename;
        $sql_avatar = ", avatar = :avatar";

        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

        if ($check !== false) {

        } else {

            $errors[] = "File is not an image.";

        }

        if ($_FILES["fileToUpload"]["size"] > 5000000) {

            $errors[] = "Sorry, your file is too large.";

        }

        $extension = strtolower($extension);

        if ($extension != "jpg" && $extension != "png" && $extension != "jpeg" && $extension != "gif") {

            $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";

        }

        if (!move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

            $errors[] = "Sorry, your file was not uploaded.";
        }

    }

    if (isset($_POST["password"]) and $_POST["password"]) {

        $update["password"] = sha1($_POST["password"] . SALT);
        $sql_password = ", password = :password";
    }

    if (empty($errors)) {

        $stmt = $pdo->prepare(
            "UPDATE users SET 
            username = :username, 
            first_name = :first_name, 
            last_name = :last_name, 
            about_me = :about_me 
            $sql_password
            $sql_avatar 
            WHERE id = :user_id"
        );

        $stmt->execute($update);
    }
}

$stmt = $pdo->prepare(
    "SELECT 
    username, 
    first_name, 
    last_name, 
    about_me, 
    avatar 
    FROM `users` 
    WHERE id = :user_id"
);

$stmt->execute(array("user_id" => $_SESSION["user_id"]));
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$user["username"] = htmlspecialchars($user["username"]);
$user["first_name"] = htmlspecialchars($user["first_name"]);
$user["last_name"] = htmlspecialchars($user["last_name"]);

$title = "guestbook.yan-coder.com | " . $user["username"];
$description = "Guestbook profile page by yan-coder maked with php, sql and bootstrap";

?>

<?php require_once "header.php"; ?>
<main>

    <form method="POST" enctype="multipart/form-data">

        <label class="m-t-b">Edit profile</label>

        <div style="color: red;">
            <?php foreach ($errors as $error): ?>
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

        <div class="input-group m-t-b">
            <input type="file" class="form-control d-none" id="fileToUpload" name="fileToUpload" />

            <label class="btn btn-primary upload-avatar-btn" for="fileToUpload" id="uploadButtonLabel">
                <img class="avatar me-2" src="avatars/<?php echo $user["avatar"]; ?>">
                Upload avatar
            </label>

            <input type="text" class="form-control" id="fileNameDisplay" placeholder="No file selected" readonly>
        </div>

        <div class="form-floating m-t-b">
            <input type="text" class="form-control input" id="floatingInput" placeholder="name@example.com"
                name="user_name" value="<?php echo $user["username"]; ?>" />
            <label for="floatingInput">Username</label>
        </div>

        <div class="form-floating m-t-b">
            <input type="text" class="form-control input" id="floatingPassword" placeholder="last name"
                name="first_name" required="" value="<?php echo $user["first_name"]; ?>" />
            <label for="floatingPassword">First name</label>
        </div>

        <div class="form-floating m-t-b">
            <input type="text" class="form-control input" id="floatingPassword" placeholder="Last name" name="last_name"
                required="" value="<?php echo $user["last_name"]; ?>" />
            <label for="floatingPassword">Last name</label>
        </div>

        <div class="form-floating m-t-b">
            <input type="password" class="form-control input" id="floatingPassword" placeholder="Password"
                name="password" value="" />
            <label for="floatingPassword">Password</label>
        </div>

        <div class="form-floating m-t-b">
            <input type="password" class="form-control input" id="floatingPassword" placeholder="Password"
                name="confirm_password" value="" />
            <label for="floatingPassword">Confirm password</label>
        </div>

        <div class="form-floating m-t-b">
            <textarea type="text" class="form-control input" id="floatingPassword" placeholder="About me"
                name="about_me"><?php echo $user["about_me"]; ?></textarea>

            <label for="floatingPassword">About me</label>
        </div>

        <div>

            <input class="btn btn-outline-secondary m-t-b" type="submit" name="submit" value="Save">

        </div>

    </form>

</main>
<?php require_once "footer.php"; ?>