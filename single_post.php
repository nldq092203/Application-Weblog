


<?php include('config.php'); ?>
<?php include('includes/public/head_section.php'); ?>
<?php include(ROOT_PATH . '/includes/all_functions.php'); ?>

<?php 
if (isset($_GET['post-slug'])) {
    $post = getPost($_GET['post-slug']);
}?>
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
<!-- // post sidebar -->
</div>
</div>
<!-- // content -->
<?php include( ROOT_PATH . '/includes/public/footer.php'); ?>