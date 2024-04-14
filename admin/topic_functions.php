<?php
include(ROOT_PATH . '/admin/post_functions.php');

$errors = array();
$topic_id = 0;
$topic_name = "";
$isEditingTopic = false;

if(isset($_POST['add_topic'])){
    $topic_name = $_POST['topic_name'];
    addTopic($topic_name);
}
if (isset($_GET['edit-topic'])) {
    $topicId = $_GET['edit-topic'];
    editTopic($topicId);
}
if (isset($_POST['update_topic'])) {
    $topicId = $_POST['topic_id'];
    $newName = $_POST['topic_name'];
    renameTopic($topicId, $newName);
}

if (isset($_GET['delete-topic'])) {
    deleteTopic($_GET['delete-topic']);
}



function getNumberPosts($topic_id) {
    global $conn;

    $sql = "SELECT COUNT(*) FROM post_topic JOIN posts ON post_topic.post_id = posts.id WHERE topic_id = ? AND published = 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $topic_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);

    return $count;
}


function addTopic($topic_name) {
    global $conn, $errors;
    $slug = createSlug($topic_name);
    
    // Check if topic with the same name already exists
    $sql = "SELECT * FROM topics WHERE name = '$topic_name'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0) {
        array_push($errors, "This topic already exists");
        return;
    }

    // If no existing topic is found, add the new topic
    $sql = "INSERT INTO topics (name, slug) VALUES ('$topic_name', '$slug')";
    mysqli_query($conn, $sql);

    if(mysqli_affected_rows($conn) > 0){
        $_SESSION['message'] = "Topic added successfully";
        header("Location: topics.php");
        exit(0);
    } else {
        array_push($errors, "Failed to add topic");
    }
}

function getTopicById($topic_id) {
    global $conn;
    $sql = "SELECT * FROM topics WHERE id=$topic_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic;
}

function editTopic($topicId) {
    global $conn, $topic_name, $isEditingTopic, $topic_id;
    $topic_id = $topicId;
    $sql = "SELECT * FROM topics WHERE id=$topic_id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $topic = mysqli_fetch_assoc($result);
    $topic_name = $topic['name'];
    $isEditingTopic = true;
}

function renameTopic($topicId, $newName) {
    global $conn, $errors;
    $newName = mysqli_real_escape_string($conn, $newName);
    $sql = "SELECT * FROM topics WHERE name='$newName'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0){
        array_push($errors,"Topic's name already exists");
        return;
    }
    else {
        $sql = "UPDATE topics SET name='$newName' WHERE id=$topicId";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['message'] = "Topic renamed successfully";
            header('location: topics.php');
            exit(0);
        } else {
            array_push($errors, "Failed to rename topic");
        }
    }
}

function deleteTopic($topicId) {
    global $conn;
    $sql = "DELETE FROM topics WHERE id=$topicId";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['message'] = "Topic deleted successfully";
        header('location: topics.php');
        exit(0);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>