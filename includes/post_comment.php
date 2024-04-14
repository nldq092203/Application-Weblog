<?php
include('../config.php');

if (isset($_POST['submit_comment'])) {
    submitComment($_POST);
}

function submitComment($request_values) {
    global $conn;
    if ($conn === null) {
        echo "Error: Database connection is not established.";
        return;
    }
    $user_id = $_SESSION['user']['id'];
    $post_id = $request_values['post_id'];
    $content = $request_values['content'];
    $post_slug = $request_values['post_slug'];
    
    $sql = "INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'iis', $user_id, $post_id, $content);

    if (mysqli_stmt_execute($stmt)){
        // Redirect back to the post
        header('Location: /single_post.php?post-slug=' . $post_slug);
        exit(0);
    } else {
        // Handle error here
        echo "Error: " . mysqli_error($conn);
    }
}
?>