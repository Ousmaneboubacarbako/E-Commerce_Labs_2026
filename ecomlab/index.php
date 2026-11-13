<!--
	This is the homepage / entry point of the app.
	It links out to the two customer pages under the view/ folder.
-->
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Ecom LAB</title>
	<link rel="stylesheet" href="css/styles.css">
</head>

<body>
	<?php require_once __DIR__ . '/core/core.php'; ?>
	<div class="container" style="text-align: center; padding: 60px 20px;">
		<h1>Welcome to Shoppn</h1>
		
		<nav style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
			<a href="view/home.php" class="btn btn-primary">Browse Products</a>
			
			<?php if (!is_logged_in()): ?>
				<a href="view/register.php" class="btn btn-secondary">Register</a>
				<a href="view/login.php" class="btn btn-secondary">Login</a>
			<?php endif; ?>
			
			<?php if (is_admin()): ?>
				<a href="view/customers.php" class="btn btn-success">View Customers</a>
				<a href="view/admin/brand.php" class="btn btn-success">Brands</a>
				<a href="view/admin/category.php" class="btn btn-success">Categories</a>
				<a href="view/admin/product.php" class="btn btn-success">Products</a>
			<?php endif; ?>
		</nav>
	</div>
</body>

</html>