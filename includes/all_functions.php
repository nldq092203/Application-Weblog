<?php
function getPostTopic($post_id){
    global $conn;
    $sql = "SELECT * FROM topics WHERE id=(SELECT topic_id FROM post_topic WHERE post_id=$post_id LIMIT 1)";
    $result = mysqli_query($conn, $sql);
    $topic = mysqli_fetch_assoc($result);
    return $topic;
}
function getPublishedPosts() {
    global $conn;
    $sql = "SELECT * FROM posts WHERE published=true";
    $result = mysqli_query($conn, $sql);
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $final_posts = array();
    foreach ($posts as $post) {
        $post['topic'] = getPostTopic($post['id']);
        array_push($final_posts, $post);
    }
    return $final_posts;
}
function getPost($slug)
{
    global $conn;
    $query = "SELECT * FROM posts WHERE slug='$slug' AND published=true";
    $result = mysqli_query($conn, $query);
    $post = mysqli_fetch_assoc($result);

    // Get the topic of the post
    $post_id = $post['id'];
    $post['topic'] = getPostTopic($post_id);
    return $post;
}

function getAllTopics()
{
    global $conn;
    $query = "SELECT * FROM topics";
    $result = mysqli_query($conn, $query);
    $topics = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $topics;
}

function getPublishedPostsByTopic($topic_id) {
    global $conn;
    $sql = "SELECT posts.* FROM posts 
    JOIN post_topic ON posts.id = post_topic.post_id 
    WHERE post_topic.topic_id = $topic_id AND posts.published = true";
    $result = mysqli_query($conn, $sql);
    $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $final_posts = array();
    foreach ($posts as $post) {
        $post['topic'] = getPostTopic($post['id']);
        array_push($final_posts, $post);
    }
    return $final_posts;
}
function getTopicName($topic_id) {
    global $conn;
    $query = "SELECT name FROM topics WHERE id='$topic_id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    $topic = mysqli_fetch_assoc($result);
    return $topic['name'];
}
?>