<?php
require_once __DIR__ . '/../core/core.php';
core_require_login();

// This is the "view" for listing every customer.
// Flow: this page includes the functions file -> calls
// getAllCustomersList() -> which calls the controller -> which calls
// the model -> which runs a SELECT and returns the rows as an array.
require_once __DIR__ . '/../functions/customer_functions.php';

// $customers is now an array of associative arrays, one per customer row,
// e.g. $customers[0]['customer_name']
$customers = getAllCustomersList();
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>All Customers</title>
	<link rel="stylesheet" href="../css/styles.css">
</head>

<body>
	<?php require __DIR__ . '/layout/header.php'; ?>
	<div class="page-shell">
		<div class="card">
			<nav>
				<a href="../index.php">Home</a>
				<a href="register.php">Register Customer</a>
			</nav>

			<section class="section">
				<h1>All Customers</h1>

				<div class="table-wrap">
					<table>
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Email</th>
								<th>Country</th>
								<th>City</th>
								<th>Contact</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($customers as $customer) { ?>
								<!--
									htmlspecialchars() converts special characters (like <, >, &)
									into safe HTML entities before printing them. This stops a
									malicious customer_name (or any field) from being run as
									HTML/JavaScript in the browser - this is called an XSS attack.
								-->
								<tr>
									<td><?php echo htmlspecialchars($customer['customer_id']); ?></td>
									<td><?php echo htmlspecialchars($customer['customer_name']); ?></td>
									<td><?php echo htmlspecialchars($customer['customer_email']); ?></td>
									<td><?php echo htmlspecialchars($customer['customer_country']); ?></td>
									<td><?php echo htmlspecialchars($customer['customer_city']); ?></td>
									<td><?php echo htmlspecialchars($customer['customer_contact']); ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</section>
		</div>
	</div>
</body>

</html>