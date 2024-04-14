<?php

$errors = array();
$comment_id = 0;
$comment_content = "";

if (isset($_GET['publish'])) {
    togglePublishComment($_GET['publish'], "Published comment successfully");
}

if (isset($_GET['unpublish'])) {
    togglePublishComment($_GET['unpublish'], "Unpublished comment successfully");
}

if (isset($_GET['edit-comment'])) {
    $commentId = $_GET['edit-comment'];
    editComment($commentId);
}
if (isset($_POST['update_comment'])) {
    $commentId = $_POST['comment_id'];
    $newContent = $_POST['content'];
    updateComment($commentId, $newContent);
}

if (isset($_GET['delete-comment'])) {
    deleteComment($_GET['delete-comment']);
}


function togglePublishComment($comment_id, $message){
    global $conn;
    $comment_id = intval($comment_id);
    $sql = "UPDATE comments SET published = CASE WHEN published = 1 THEN 0 ELSE 1 END WHERE id=$comment_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = $message;
        header("location: comments.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Failed to toggle publish";
        header("location: comments.php");
        exit(0);
    }
}


function editComment($commentId) {
    global $conn, $comment_content, $comment_id;
    $comment_id = $commentId;
    $sql = "SELECT * FROM comments WHERE id=$comment_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $comment = mysqli_fetch_assoc($result);
    $comment_content = $comment['content'];
}

function updateComment($commentId, $newContent) {
    global $conn, $errors;
    $newContent = mysqli_real_escape_string($conn, $newContent);
    $sql = "UPDATE comments SET content='$newContent' WHERE id=$commentId";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Comment updated successfully";
        header('location: comments.php');
        exit(0);
    } else {
        array_push($errors, "Failed to update comment");
    }
}

function deleteComment($commentId) {
    global $conn;
    $sql = "DELETE FROM comments WHERE id=$commentId";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Comment deleted successfully";
        header('location: comments.php');
        exit(0);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}


?>