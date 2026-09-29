<?php require_once __DIR__ . '/../core/core.php'; ?>
<!--
	This is the "view" for registering a new customer.
	Flow: user fills this form -> clicks Register -> js/customer.js
	validates it and sends it to actions/customer_register_action.php
	-> which calls the controller -> which calls the model -> which
	inserts the row into the database.
-->
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Register Customer</title>
</head>

<body>
	<div class="page-shell">
		<div class="card">
			<!-- <nav>
				<a href="../index.php">Home</a>
				<a href="customers.php">View All Customers</a>
			</nav> -->

			<section class="section">
				<h1>Customer Registration</h1>

				<div class="form-panel">
					<!--
						Each input's "name" attribute matches a column in the `customer`
						table (see classes/CustomerClass.php) and is what PHP reads via
						$_POST in actions/customer_register_action.php. The "id" attribute
						is what js/customer.js uses to read the value in the browser.
					-->
					<form id="registerForm" novalidate>
						<div class="form-group">
							<label for="customer_name">Name</label>
							<input type="text" name="customer_name" id="customer_name"
								aria-describedby="customer_name_error">
							<span class="field-error" id="customer_name_error" aria-live="polite"></span>
						</div>
						<div class="form-group">
							<label for="customer_email">Email</label>
							<input type="text" name="customer_email" id="customer_email"
								aria-describedby="customer_email_error">
							<span class="field-error" id="customer_email_error" aria-live="polite"></span>
						</div>
						<div class="form-group">
							<label for="customer_pass">Password</label>
							<input type="password" name="customer_pass" id="customer_pass"
								aria-describedby="customer_pass_error">
							<span class="field-error" id="customer_pass_error" aria-live="polite"></span>
						</div>
						<div class="form-group">
							<label for="customer_country">Country</label>
							<input type="text" name="customer_country" id="customer_country"
								aria-describedby="customer_country_error">
							<span class="field-error" id="customer_country_error" aria-live="polite"></span>
						</div>
						<div class="form-group">
							<label for="customer_city">City</label>
							<input type="text" name="customer_city" id="customer_city"
								aria-describedby="customer_city_error">
							<span class="field-error" id="customer_city_error" aria-live="polite"></span>
						</div>
						<div class="form-group">
							<label for="customer_contact">Contact</label>
							<input type="tel" name="customer_contact" id="customer_contact"
								aria-describedby="customer_contact_error">
							<span class="field-error" id="customer_contact_error" aria-live="polite"></span>
						</div>
						<div class="form-group full">
							<label for="customer_image">Image (optional)</label>
							<input type="text" name="customer_image" id="customer_image">
						</div>
						<div class="form-group full">
							<button type="submit" id="registerButton">Register</button>
						</div>
					</form>

					<!-- Validation/success/error messages get written into here by customer.js -->
					<p id="formMessage"></p>

					<p class="auth-link">
						Already have an account?
						<a href="login.php">Login</a>
					</p>
				</div>
			</section>
		</div>
	</div>

	<!-- Loads the shared JavaScript file that contains registerCustomer() -->
	<script src="../js/customer.js"></script>
	<script src="../js/validate.js"></script>
</body>

</html>