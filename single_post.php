<?php include('config.php'); ?>
<?php include(ROOT_PATH . '/includes/all_functions.php'); ?>
<?php include('includes/public/head_section.php'); ?>


<?php
if (isset($_GET['post-slug'])) {
    $post = getPost($_GET['post-slug']);
}
?>
<title> <?php echo $post['title'] ?> | MyWebSite</title>
</head>
<body>
<div class="container">
    <!-- Navbar -->
    <?php include( ROOT_PATH . '/includes/public/navbar.php'); ?>
    <!-- // Navbar -->
    <div class="content" >
        <!-- Page wrapper -->
        <div class="post-wrapper">
            <!-- full post div -->
            <div class="full-post-div">
                <h2 class="post-title"><?php echo $post['title']; ?></h2>
                <?php echo "<h4>" . getUser($post['user_id']). "</h4>"; ?>
                <?php echo "<img src='/static/images/" . $post['image'] .  "' style='width: 100%; height: auto;' class='post_image' alt='Post image'>"; ?>
                <div class="post-body-div">
                    <p><?php echo $post['body']; ?></p>
                </div>  
            </div>
            <!-- // full post div -->
        </div>
        <!-- // Page wrapper -->
        <!-- post sidebar -->
        <div class="post-sidebar">
            <div class="card">
                <div class="card-header">
                    <h2>Topics</h2>
                </div>
                <div class="card-content">
                    <?php
                        $topics = getAllTopics();
                        foreach($topics as $topic){
                            echo "<a href='filtered_posts.php?topic=" . $topic['id'] . "'>" . $topic['name']. "</a>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
<!-- // content -->
    <!-- Comment Section -->

    <?php if(isset($_SESSION['user']) && $_SESSION['user']['id'] != $post['user_id']): ?>
        <div class="comment">
            <?php echo "<h3> ".countCommentsById($post['id'])." Comments</h3>"; ?>
            <form method="post" action="<?php echo BASE_URL . 'includes/post_comment.php'; ?>">
                <textarea name="content" placeholder="Write your comment here..." required></textarea>
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <input type="hidden" name="post_slug" value="<?php echo $post['slug']; ?>">
                <button type="submit" name="submit_comment" class="btn">Submit Comment</button>
            </form>
            <?php
            $post_id = $post['id'];
            $comments = fetchComments($post_id);

            foreach($comments as $comment) {
                echo "<div class='post-comments card border-primary'>";
                echo "<h4>" . getUser($comment['user_id']). "</h4>";
                echo "<small>" . $comment['created_at'] . "</small>";
                echo "<p>" . $comment['content'] . "</p>";
                echo "</div>";
            }
            ?>
        </div>
    <?php endif; ?>

</div>
<?php include( ROOT_PATH . '/includes/public/footer.php'); ?>