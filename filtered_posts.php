
<?php include('config.php'); ?>
<?php include('includes/public/head_section.php'); ?>
<?php include(ROOT_PATH . '/includes/all_functions.php'); ?>


<title><?php echo getTopicName($_GET['topic']); ?> | MyWebSite</title>

</head>

<body>

	<div class="container">

		<!-- Navbar -->
		<?php include(ROOT_PATH . '/includes/public/navbar.php'); ?>
		<!-- // Navbar -->

		<!-- Banner -->
		<?php include(ROOT_PATH . '/includes/public/banner.php'); ?>
		<!-- // Banner -->

		<!-- Messages -->
		
		<!-- // Messages -->

		<!-- content -->
		<div class="content">
            <h2 class="content-title"><?php echo getTopicName($_GET['topic']); ?></h2>
			<hr>
			<?php
                $posts = getPublishedPostsByTopic($_GET['topic']);
				foreach ($posts as $post) {
					echo "<div class='post' style='margin-left: 0px;'>";
					echo "<img src='/static/images/" . $post['image'] . "' class='post_image' alt='Post image'>";
					echo "<h2>" . $post['title'] . "</h2>";
					echo "<p>" . $post['created_at'] . "</p>";
					echo "<a href='single_post.php?post-slug=" . $post['slug'] . "'>Read more</a>";
					echo "</div>";
				}
			?>
		</div>
		<!-- // content -->


	</div>
	<!-- // container -->


	<!-- Footer -->
	<?php include(ROOT_PATH . '/includes/public/footer.php'); ?>
	<!-- // Footer -->