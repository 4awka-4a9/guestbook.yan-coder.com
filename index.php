<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("config.php");
if (empty($_SESSION["user_id"])) {
    header("location: login.php");
}

if (!empty($_POST["comment"])) {
        $stmt = $pdo->prepare(
          "INSERT INTO comments
          (`user_id`, `comment`) 
          VALUES
          (:user_id, :comment)");

        $stmt->execute(array(
          "user_id" => $_SESSION["user_id"], 
          "comment" => $_POST["comment"]
        ));
    }   

if (isset($_GET["action"]) && $_GET["action"] == "delete_comment") {

    $stmt = $pdo->prepare(
      "DELETE FROM `comments` 
      WHERE id = :id AND user_id = :user_id");

    $stmt->execute(array(
      "user_id" => $_SESSION["user_id"], 
      "id" => $_GET["comment_id"]
    ));

    header("location: index.php");
}

$stmt = $pdo->prepare(
    "SELECT users.username, 
    comments.comment, 
    comments.created_at, 
    users.avatar, comments.id, 
    comments.user_id 
    FROM `comments` 
    LEFT JOIN users ON user_id=users.id 
    ORDER BY comments.id DESC;
  ");

$stmt->execute();
$comments = $stmt->fetchAll();

$title = "guestbook.yan-coder.com | home";

?>

      <?php require_once "header.php";?>

      <main>
        
        <div id="#comments-form"><h3>Please add your comment</h3>

        <form method="POST" action="index.php">

            <div>

                <label>Comment</label>
                <div>
                    <textarea class="form-control textarea" name="comment"></textarea>
                </div>

            </div>

            <div>

                <br>
                <input class="btn btn-outline-secondary"type="submit" name="submit" value="Save">

            </div>

        </form>

        </div>  

        <div id="#comments-panel">

          <h3 class="commentsTitle">Comments:</h3>

            <?php foreach ( $comments as $comment ) : ?>

            <?php

            $comment["comment"] = htmlspecialchars($comment["comment"]);
            $comment["username"] = htmlspecialchars($comment["username"]);

            $comment["comment"] = preg_replace('~https?://[^\s]+|www\.[^\s]+~i', '<a href="$0">$0</a>', $comment["comment"]);

            if ($comment["avatar"]) {
              $avatar = $comment["avatar"];
            }
            else {
              $avatar = "default_avatar.jpg";
            }

            $delete_comment = "";

            if ($_SESSION["user_id"] == $comment["user_id"]) {
                $delete_comment = '<a href="index.php?action=delete_comment&comment_id=' . htmlspecialchars($comment["id"]) . '" class="ms-auto">Delete</a>';
            }
            
            $commentTemplate = <<<TXT
            <div class="card">
              <div class="card-header d-flex align-items-center">
                <img class="avatar me-2" src="avatars/{$avatar}">
                <a href="show_profile.php?id={$comment["user_id"]}">{$comment["username"]}</a>
                {$delete_comment}
              </div>
              <div class="card-body">
                <figure>
                  <blockquote class="blockquote">
                    <p><pre>{$comment["comment"]}</pre></p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                      {$comment["created_at"]}
                  </figcaption>
                </figure>
              </div>
            </div>
            TXT;
            
            ?>

            <?php echo $commentTemplate;?>
            <?php endforeach; ?>

        </div>

      </main>
<?php require_once "footer.php";?>
