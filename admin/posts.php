<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/post_functions.php'); ?>
<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>

 <!-- BTW: ideally we need to create a role_user table (users - role_user-roles) -->
	<!-- // role_user(id, user_id,role_id) -->
<?php
// Get all posts from DB
$posts = getAllPosts();

?>

<title>Admin | Manage posts</title>
</head>

<body>
	<!-- admin navbar -->
	<?php include(ROOT_PATH . '/includes/admin/header.php') ?>
	<div class="container content">
		<!-- Left side menu -->
		<?php include(ROOT_PATH . '/includes/admin/menu.php') ?>


		<!-- Display records from DB-->
		<div class="table-div">

			<!-- Display notification message -->
			<?php include(ROOT_PATH . '/includes/public/messages.php') ?>

			<?php if (empty($posts)) : ?>
				<h1>No posts in the database.</h1>
			<?php else : ?>
				<table class="table">
					<thead>
						<th>N</th>
						<th>Author</th>
						<th>Title</th>
						<th>Views</th>
						<th>Publish</th>
						<th>Edit</th>
						<th>Delete</th>
					</thead>
					<tbody>
						<?php foreach ($posts as $key => $post) : ?>
							<tr>
								<td><?php echo $key + 1; ?></td>
								<td>
									<?php echo getPostAuthorById($post['user_id']); ?>
								</td>
								<td>
									<a href="/single_post.php?post-slug=<?php echo $post['slug'] ?>">
									<?php echo $post['title']?>
									</a>
								</td>
								<td>
									<?php echo $post["views"] ?>
								</td>
								<td>
									<?php if ($post["published"] == 1) : ?>
									<a class="fa fa-check btn unpublish" href="posts.php?unpublish=<?php echo $post['id'] ?>">
									</a>
									<?php else: ?>
									<a class="fa fa-close btn publish" href="posts.php?publish=<?php echo $post['id'] ?>">
									</a>
									<?php endif; ?>

								</td>
								<td>
									<a class="fa fa-pencil btn edit" href="create_post.php?edit-post=<?php echo $post['id'] ?>">
									</a>
								</td>
								<td>
									<a class="fa fa-trash btn delete" href="create_post.php?delete-post=<?php echo $post['id'] ?>">
									</a>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>
		</div>
		<!-- // Display records from DB -->

	</div>

</body>

</html>
