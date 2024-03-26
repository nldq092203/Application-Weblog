<?php
/*--------------------
post_functions.php
------------------------*/
/* - - - - - - - - - -
- Post functions
- - - - - - - - - - -*/
// get all posts from WEBLOG DATABSE
function getAllPosts() {
    global $conn ;
    $sql = "SELECT * FROM posts";
    $result = mysqli_query($conn, $sql);
    if ($result)
        $posts = mysqli_fetch_array($result);
        return $posts ;
}
// get the author/username of a post
//cette fonction est dans post_functions.php
function getPostAuthorById($user_id){
 global $conn ;
 $sql = "SELECT username FROM users WHERE id=$user_id LIMIT 1";
 $result = mysqli_query($conn, $sql) ;
 if ($result) {
// return username
    return mysqli_fetch_assoc($result)['username'] ;
 } else {
 return null ;
 }
}
