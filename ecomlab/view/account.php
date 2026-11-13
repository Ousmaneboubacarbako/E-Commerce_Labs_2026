<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

// Require login to access account page
core_require_login('login.php');

$controller = new CustomerController();
$customer = $controller->getCustomerById($_SESSION['customer_id']);

if (!$customer) {
    $_SESSION['error'] = 'Customer account not found.';
    header('Location: home.php');
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Account - Shoppn</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <?php include __DIR__ . '/layout/header.php'; ?>

    <div class="page-container">
        <main class="main-content">
            <div class="content-header">
                <h1>My Account</h1>
                <p>Manage your profile information</p>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="account-container">
                <!-- Profile Information Card -->
                <div class="account-card">
                    <div class="account-card-header">
                        <h2>Profile Information</h2>
                        <button class="btn btn-secondary btn-sm" onclick="toggleEdit('profile')">Edit</button>
                    </div>

                    <div class="account-card-body">
                        <div id="profile-view" class="profile-view">
                            <div class="profile-image-section">
                                <?php if ($customer['customer_image']): ?>
                                    <img src="../images/customers/<?= htmlspecialchars($customer['customer_image'], ENT_QUOTES, 'UTF-8') ?>" 
                                         alt="Profile" class="profile-image">
                                <?php else: ?>
                                    <div class="profile-no-image">
                                        <span><?= strtoupper(substr($customer['customer_name'], 0, 1)) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="profile-details">
                                <div class="profile-detail-item">
                                    <span class="profile-label">Name:</span>
                                    <span class="profile-value"><?= htmlspecialchars($customer['customer_name'], ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                                <div class="profile-detail-item">
                                    <span class="profile-label">Email:</span>
                                    <span class="profile-value"><?= htmlspecialchars($customer['customer_email'], ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                                <div class="profile-detail-item">
                                    <span class="profile-label">Country:</span>
                                    <span class="profile-value"><?= htmlspecialchars($customer['customer_country'], ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                                <div class="profile-detail-item">
                                    <span class="profile-label">City:</span>
                                    <span class="profile-value"><?= htmlspecialchars($customer['customer_city'], ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                                <div class="profile-detail-item">
                                    <span class="profile-label">Contact:</span>
                                    <span class="profile-value"><?= htmlspecialchars($customer['customer_contact'], ENT_QUOTES, 'UTF-8') ?></span>
                                </div>
                            </div>
                        </div>

                        <form id="profile-edit" class="profile-edit" style="display: none;" 
                              action="../actions/update_profile_action.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="customer_name">Full Name *</label>
                                <input type="text" id="customer_name" name="customer_name" 
                                       value="<?= htmlspecialchars($customer['customer_name'], ENT_QUOTES, 'UTF-8') ?>" 
                                       required minlength="2" maxlength="100">
                            </div>

                            <div class="form-group">
                                <label for="customer_email">Email *</label>
                                <input type="email" id="customer_email" name="customer_email" 
                                       value="<?= htmlspecialchars($customer['customer_email'], ENT_QUOTES, 'UTF-8') ?>" 
                                       required maxlength="50">
                            </div>

                            <div class="form-group">
                                <label for="customer_country">Country *</label>
                                <input type="text" id="customer_country" name="customer_country" 
                                       value="<?= htmlspecialchars($customer['customer_country'], ENT_QUOTES, 'UTF-8') ?>" 
                                       required maxlength="30">
                            </div>

                            <div class="form-group">
                                <label for="customer_city">City *</label>
                                <input type="text" id="customer_city" name="customer_city" 
                                       value="<?= htmlspecialchars($customer['customer_city'], ENT_QUOTES, 'UTF-8') ?>" 
                                       required maxlength="30">
                            </div>

                            <div class="form-group">
                                <label for="customer_contact">Contact Number *</label>
                                <input type="tel" id="customer_contact" name="customer_contact" 
                                       value="<?= htmlspecialchars($customer['customer_contact'], ENT_QUOTES, 'UTF-8') ?>" 
                                       required maxlength="15">
                            </div>

                            <div class="form-group">
                                <label for="customer_image">Profile Image</label>
                                <input type="file" id="customer_image" name="customer_image" accept="image/*">
                                <small>Leave empty to keep current image</small>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button type="button" class="btn btn-secondary" onclick="toggleEdit('profile')">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Password Card -->
                <div class="account-card">
                    <div class="account-card-header">
                        <h2>Change Password</h2>
                        <button class="btn btn-secondary btn-sm" onclick="toggleEdit('password')">Change</button>
                    </div>

                    <div class="account-card-body">
                        <form id="password-edit" class="profile-edit" style="display: none;" 
                              action="../actions/update_password_action.php" method="POST">
                            <div class="form-group">
                                <label for="current_password">Current Password *</label>
                                <input type="password" id="current_password" name="current_password" 
                                       required minlength="6">
                            </div>

                            <div class="form-group">
                                <label for="new_password">New Password *</label>
                                <input type="password" id="new_password" name="new_password" 
                                       required minlength="6">
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password *</label>
                                <input type="password" id="confirm_password" name="confirm_password" 
                                       required minlength="6">
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Password</button>
                                <button type="button" class="btn btn-secondary" onclick="toggleEdit('password')">Cancel</button>
                            </div>
                        </form>

                        <div id="password-view" class="profile-view">
                            <p>Click "Change" to update your password</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleEdit(section) {
            if (section === 'profile') {
                const view = document.getElementById('profile-view');
                const edit = document.getElementById('profile-edit');
                
                if (view.style.display === 'none') {
                    view.style.display = 'block';
                    edit.style.display = 'none';
                } else {
                    view.style.display = 'none';
                    edit.style.display = 'block';
                }
            } else if (section === 'password') {
                const view = document.getElementById('password-view');
                const edit = document.getElementById('password-edit');
                
                if (view.style.display === 'none') {
                    view.style.display = 'block';
                    edit.style.display = 'none';
                } else {
                    view.style.display = 'none';
                    edit.style.display = 'block';
                }
            }
        }

        // Password confirmation validation
        document.getElementById('password-edit')?.addEventListener('submit', function(e) {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;
            
            if (newPass !== confirmPass) {
                e.preventDefault();
                alert('New password and confirmation do not match.');
            }
        });
    </script>
</body>

</html>
