
<?php include('../config.php'); ?>
<?php  include(ROOT_PATH . '/includes/all_functions.php') ?>
<?php include(ROOT_PATH . '/admin/comment_functions.php'); ?>
<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>

 <!-- BTW: ideally we need to create a role_user table (users - role_user-roles) -->
	<!-- // role_user(id, user_id,role_id) -->
<?php
// Get all comments from DB
$comments = getAllComments();

?>

<title>Admin | Manage comments</title>
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
            <!-- validation errors for the form -->
			<?php include(ROOT_PATH . '/includes/public/errors.php') ?>

			<?php if (empty($comments)) : ?>
				<h1>No comments in the database.</h1>
			<?php else : ?>
				<table class="table">
					<thead>
						<th>N</th>
						<th>Author</th>
						<th>Content</th>
						<th>Published</th>
						<th>Edit</th>
						<th>Delete</th>
					</thead>
					<tbody>
						<?php foreach ($comments as $key => $comment) : ?>
							<tr>
								<td><?php echo $key + 1; ?></td>
								<td>
									<?php echo getUser($comment['user_id']); ?>
								</td>
								<td>
                                    <?php echo $comment['content']; ?>
								</td>
								<td>
                                    <?php if ($comment["published"] == 1) : ?>
                                        <a class="fa fa-check btn unpublish" href="comments.php?unpublish=<?php echo $comment['id'] ?>">
                                        </a>
                                    <?php else: ?>
                                        <a class="fa fa-close btn publish" href="comments.php?publish=<?php echo $comment['id'] ?>">
                                        </a>
                                    <?php endif; ?>
								</td>
								<td>
									<a class="fa fa-pencil btn edit" href="comments.php?edit-comment=<?php echo $comment['id'] ?>">
									</a>
								</td>
								<td>
									<a class="fa fa-trash btn delete" href="comments.php?delete-comment=<?php echo $comment['id'] ?>">
									</a>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>



            <?php if (isset($_GET['edit-comment'])): ?>
                <form action="<?php echo BASE_URL . 'admin/comments.php?edit-comment=' . $comment_id; ?>" method="post">
                    <label for="content">Comment Text:</label>
                    <textarea id="content" name="content" required><?php echo $comment_content; ?></textarea>
                    <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
                    <button type="submit" class="btn btn-primary" name="update_comment">Update Comment</button>
                </form>
            <?php endif; ?>
		</div>
		<!-- // Display records from DB -->

	</div>

</body>

</html>
