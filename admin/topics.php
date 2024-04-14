
<?php include('../config.php'); ?>
<?php  include(ROOT_PATH . '/includes/all_functions.php') ?>
<?php include(ROOT_PATH . '/admin/topic_functions.php'); ?>
<?php include(ROOT_PATH . '/includes/admin/head_section.php'); ?>

 <!-- BTW: ideally we need to create a role_user table (users - role_user-roles) -->
	<!-- // role_user(id, user_id,role_id) -->
<?php
// Get all topics from DB
$topics = getAllTopics();

?>

<title>Admin | Manage topics</title>
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

			<?php if (empty($topics)) : ?>
				<h1>No topics in the database.</h1>
			<?php else : ?>
				<table class="table">
					<thead>
						<th>N</th>
						<th>Name</th>
						<th>Number of published posts</th>
						<th>Rename</th>
						<th>Delete</th>
					</thead>
					<tbody>
						<?php foreach ($topics as $key => $topic) : ?>
							<tr>
								<td><?php echo $key + 1; ?></td>
								<td>
									<a href="/filtered_posts.php?topic=<?php echo $topic['id'] ?>">
									<?php echo $topic['name']?>
									</a>
								</td>
								<td>
                                    <?php echo getNumberPosts($topic['id']); ?>
								</td>
								<td>
									<a class="fa fa-pencil btn edit" href="topics.php?edit-topic=<?php echo $topic['id'] ?>">
									</a>
								</td>
								<td>
									<a class="fa fa-trash btn delete" href="topics.php?delete-topic=<?php echo $topic['id'] ?>">
									</a>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			<?php endif ?>



            <form action="<?php echo BASE_URL . 'admin/topics.php' . ($isEditingTopic ? '?edit-topic=' . $topic_id : ''); ?>" method="post">
                <label for="topic_name">Topic Name:</label>
                <input type="text" id="topic_name" name="topic_name" value="<?php echo $isEditingTopic ? $topic_name : ''; ?>" required>
                <?php if ($isEditingTopic === true): ?> 
                    <input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
                    <button type="submit" class="btn btn-primary" name="update_topic">Rename Topic</button>
                <?php else: ?>
                    <button type="submit" class="btn btn-primary" name="add_topic">Add Topic</button>
                <?php endif ?> 
            </form>
		</div>
		<!-- // Display records from DB -->

	</div>

</body>

</html>
