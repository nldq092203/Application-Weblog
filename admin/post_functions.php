<?php

function createSlug($string) {
    // Convert string to lowercase
    $string = strtolower($string);
    
    // Replace spaces with hyphens
    $string = str_replace(' ', '-', $string);
    
    // Remove special characters
    $string = preg_replace('/[^a-z0-9\-]/', '', $string);
    
    // Remove consecutive hyphens
    $string = preg_replace('/-+/', '-', $string);
    
    // Trim hyphens from the beginning and end
    $string = trim($string, '-');
    
    return $string;
}

$post_id = 0;
$isEditingPost = false;
$published = 1;
$title = "";
$post_slug = "";
$body = "";
$featured_image = "";
$post_topic = "";

if (isset($_POST['update_post'])) {
    updatePost($_POST);
}

if (isset($_POST['create_post'])) {
    createPost($_POST);
}

if (isset($_GET['edit-post'])) {
    editPost($_GET['edit-post']);
}

if (isset($_GET['delete-post'])) {
    deletePost($_GET['delete-post']);
}

if (isset($_GET['publish'])) {
    togglePublishPost($_GET['publish'], "Published post successfully");
}

if (isset($_GET['unpublish'])) {
    togglePublishPost($_GET['unpublish'], "Unpublished post successfully");
}
function getAllPosts() {
    global $conn;
    $sql = "SELECT * FROM posts";
    $result = mysqli_query($conn, $sql);
    $posts = array();
    if ($result) {
        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            $posts[] = $row;
        }
    }
    return $posts;
}

function getPostAuthorById($user_id) {
    global $conn;
    $sql = "SELECT username FROM users WHERE id=$user_id LIMIT 1";
    $result = mysqli_query($conn, $sql) ;
    if ($result) {
        return mysqli_fetch_assoc($result)['username'] ;
    } else {
        return null ;
    }
}

function createPost($request_values) {
    global $conn, $errors;
    $title = $request_values['title'];
    $topic_id = $request_values['topic_id'];
    $body = strip_tags($request_values['body']);
    $user_id = $_SESSION['user']['id'];
    if (isset($_FILES['featured_image'])) {
        $featured_image = $_FILES['featured_image']['name'];
        $target = "../static/images/" . basename($featured_image);
        if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
            array_push($errors, "Failed to upload image. Please check file settings for your server.");
        }
    }

    if (empty($body)) { array_push($errors, "Content is required"); }
    if (empty($topic_id)) { array_push($errors, "Topic is required"); }

    if (!empty($title)) {
        $sql = "SELECT * FROM posts WHERE title='$title' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $post = mysqli_fetch_assoc($result);
    } else {
        array_push($errors, "Title is required");
    }

    if ($post) { // If user exists
        array_push($errors, "Title already exists");
    }
    if (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] != 0) {
        $user_id = $_SESSION['user']['id'];
    } else {
        array_push($errors, "Invalid user ID");
    }
    
    if (empty($errors)) {
        $slug = createSlug($title);
        $sql = "INSERT INTO posts (user_id, title, slug, image, body, published, created_at) VALUES ('$user_id', '$title', '$slug', '$featured_image', '$body', '0', NOW())";
        if (mysqli_query($conn, $sql)) {
            $post_id = mysqli_insert_id($conn); // Get the ID of the newly created post
            $sql = "INSERT INTO post_topic (post_id, topic_id) VALUES ('$post_id', '$topic_id')";
            if (mysqli_query($conn, $sql)) {
                $_SESSION['message'] = "Post and topic created successfully";
                header('location: posts.php');
                exit(0);
            } else {
                array_push($errors, "Failed to link post to topic");
            }
        } else {
            array_push($errors, "Failed to create post");
        }
    }
}

function editPost($postId) {
    global $conn, $title, $post_slug, $body, $isEditingPost, $post_id, $published;
    $post_id = $postId;
    $sql = "SELECT * FROM posts WHERE id=$post_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $post = mysqli_fetch_assoc($result);
    $title = $post['title'];
    $post_slug = $post['slug'];
    $body = $post['body'];
    $published = $post['published'];
    $isEditingPost = true;
}

function updatePost($request_values){
    global $conn, $errors, $title, $featured_image, $topic_id, $body, $published;

    // Check if post_id is set in $request_values
    if (!isset($request_values['post_id'])) {
        array_push($errors, "Post ID is required");
        return;
    }

    $post_id = $request_values['post_id'];
    $title = $request_values['title'];
    $topic_id = $request_values['topic_id'];
    $body = strip_tags($request_values['body']);
    if (isset($_FILES['featured_image'])) {
        $featured_image = $_FILES['featured_image']['name'];
        $target = "../static/images/" . basename($featured_image);
        if (!move_uploaded_file($_FILES['featured_image']['tmp_name'], $target)) {
            array_push($errors, "Failed to upload image. Please check file settings for your server.");
        }
    }

    if (empty($body)) { array_push($errors, "Content is required"); }
    if (empty($topic_id)) { array_push($errors, "Topic is required"); }

    if (!empty($title)) {
        $sql = "SELECT * FROM posts WHERE title='$title' LIMIT 1";
        $result = mysqli_query($conn, $sql);
        $post = mysqli_fetch_assoc($result);
    } else {
        array_push($errors, "Title is required");
    }

    if (empty($errors)) {
        $slug = createSlug($title);
        $sql = "UPDATE posts SET title='$title', slug='$slug', image='$featured_image', body='$body', updated_at=NOW() WHERE id=$post_id";        
        if (mysqli_query($conn, $sql)) {
            $sql = "SELECT topic_id FROM post_topic WHERE post_id=$post_id LIMIT 1";
            $result = mysqli_query($conn, $sql);
            $current_topic = mysqli_fetch_assoc($result)['topic_id'];
            if ($current_topic != $topic_id) {
                $sql = "UPDATE post_topic SET topic_id=$topic_id WHERE post_id=$post_id";
                if (!mysqli_query($conn, $sql)) {
                    array_push($errors, "Failed to update topic");
                }
            }
            $_SESSION['message'] = "Post updated successfully";
            header('location: posts.php');
            exit(0);
        } else {
            array_push($errors, "Failed to update post");
        }
    }
}

function deletePost($post_id){
    global $conn;
    $sql = "DELETE FROM post_topic WHERE post_id=$post_id";
    if (!mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Failed to delete post topic";
        header("location: posts.php");
        exit(0);
    }
    $sql = "DELETE FROM posts WHERE id=$post_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Post successfully deleted";
        header("location: posts.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Failed to delete post";
        header("location: posts.php");
        exit(0);
    }
}

function togglePublishPost($post_id, $message){
    global $conn;
    $post_id = intval($post_id);
    $sql = "UPDATE posts SET published = CASE WHEN published = 1 THEN 0 ELSE 1 END WHERE id=$post_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = $message;
        header("location: posts.php");
        exit(0);
    } else {
        $_SESSION['message'] = "Failed to toggle publish";
        header("location: posts.php");
        exit(0);
    }
}
?>
