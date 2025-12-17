<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once("core.php");
if (empty($_SESSION["user_id"])) {
  header("location: login.php");
}

$comment_added = false;
$comment_deleted = false;

if (isset($_POST["action"]) && $_POST["action"] == "post_comment") {
  $stmt = $pdo->prepare(
    "INSERT INTO comments
          (`user_id`, `comment`) 
          VALUES
          (:user_id, :comment)"
  );

  $stmt->execute(array(
    "user_id" => $_SESSION["user_id"],
    "comment" => $_POST["comment"]
  ));

  $comment_added = true;

}

if (isset($_POST["action"]) && $_POST["action"] == "delete_comment") {

  $stmt = $pdo->prepare(
    "DELETE FROM `comments` 
      WHERE id = :id AND user_id = :user_id"
  );

  $stmt->execute(array(
    "user_id" => $_SESSION["user_id"],
    "id" => $_POST["comment_id"]
  ));

  $comment_deleted = true;

}

$stmt = $pdo->prepare(
  "SELECT users.username, 
    comments.comment, 
    comments.created_at, 
    users.avatar,
    comments.id, 
    comments.user_id 
    FROM `comments` 
    LEFT JOIN users ON user_id=users.id 
    ORDER BY comments.id DESC;
  "
);

$stmt->execute();
$comments = $stmt->fetchAll();

$title = "guestbook.yan-coder.com | home";
$description = "Guestbook home page by yan-coder maked with php, sql and bootstrap";


?>

<?php require_once "header.php"; ?>

<main>

  <div id="#comments-form">
    <h3>Please add your comment</h3>

    <?php
    
      if ($comment_added == true) {
        echo "<p class='text-succes'>Comment was added!</p>";
      }
      if ($comment_deleted == true) {
        echo "<p class='text-danger'>Comment was deleted!</p>";
      }
    
    ?>

    <form method="POST" action="index.php">

      <div>

        <label>Comment</label>
        <div>
          <textarea class="form-control textarea" name="comment"></textarea>
        </div>

      </div>

      <div>

        <br>
        <input type="hidden" name="action" value="post_comment">
        <input class="btn btn-outline-secondary" type="submit" name="submit" value="Save">

      </div>

    </form>

  </div>

  <div id="#comments-panel">

    <h3 class="commentsTitle">Comments:</h3>

    <?php foreach ($comments as $comment): ?>

      <?php

      $comment["comment"] = htmlspecialchars($comment["comment"]);
      $comment["username"] = htmlspecialchars($comment["username"]);

      $comment["comment"] = preg_replace('~https?://[^\s]+|www\.[^\s]+~i', '<a href="$0">$0</a>', $comment["comment"]);

      if ($comment["avatar"]) {
        $avatar = $comment["avatar"];
      } else {
        $avatar = "default_avatar.jpg";
      }

      $delete_comment = "";

      if ($_SESSION["user_id"] == $comment["user_id"]) {

        $delete_comment = <<<TXT

                <div class="ms-auto">
                    <form method="POST" action="index.php" class="m-0">
                        <input type="hidden" name="action" value="delete_comment">
                        <input type="hidden" name="comment_id" value="{$comment["id"]}">
                        <button type="submit" class="btn btn-danger btn-sm">
                            Delete
                        </button>
                    </form>
                </div>

                TXT;

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

      <?php echo $commentTemplate; ?>
    <?php endforeach; ?>

  </div>

</main>
<?php require_once "footer.php"; ?>