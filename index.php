<?php include('config.php'); ?>
<?php include('includes/public/head_section.php'); ?>
<title>MyWebSite | Home </title>

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
			<h2 class="content-title">Recent Articles</h2>
			<hr>
			<?php
				include(ROOT_PATH . '/includes/all_functions.php');
				$posts = getPublishedPosts();
				foreach ($posts as $post) {
					echo "<div class='post' style='margin-left: 0px;'>";
					echo "<h4>" . $post['topic']['name'] . "</h4>";
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