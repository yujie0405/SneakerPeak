<?php
require_once SNEAKERPEAK_ROOT . '/src/App/Services/EmailService.php';
	include SNEAKERPEAK_ROOT . '/database/Database_Connection.php';
	if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add Admin - Admin Panel</title>
	<link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
	
	<link rel="stylesheet" href="css/admin.css">
	<link rel="stylesheet" href="css/addproduct.css">
	<style>
		.contenbar
		{
			padding-top: 20px;
			padding-left: 20px;
			height: 100%;
			width: 98%;
		}
		.table
		{
			width: 99%;
			height: 90vh;
			background-color: #fff5;
			backdrop-filter: blur(7px);
			box-shadow: 0 .4rem .8rem #0005;
			border-radius: .8rem;
			overflow: hidden;
		}
		.table__header {
			width: 100%;
			height: 10%;
			background-color: #fff4;
			padding: .8rem 1rem;
			font-size: 25px;
			display: flex;
			justify-content: space-between;
			align-items: center;
		}
		.table__body {
			width: 98%;
			max-height: calc(89% - 1.6rem);
			background-color: #fffb;
			margin: .8rem auto;
			border-radius: .6rem;
			overflow: auto;
			overflow: overlay;
		}
		.table__body::-webkit-scrollbar{
			width: 0.5rem;
			height: 0.5rem;
		}
		.table__body::-webkit-scrollbar-thumb{
			border-radius: .5rem;
			background-color: #0004;
			visibility: hidden;
		}

		.table__body:hover::-webkit-scrollbar-thumb{ 
			visibility: visible;
		}
		table {
			width: 100%;
		}

		tbody td img {
			width: 70px;
			height: 90px;
			margin-right: .5rem;
			border-radius: 8px;
			vertical-align: middle;
		}

		table, th, td {
			border-collapse: collapse;
			padding: 1rem;
			text-align: left;
		}
		thead th {
			position: sticky;
			top: 0;
			left: 0;
			background-color: #d5d1defe;
			cursor: pointer;
			text-transform: capitalize;
		}

		tbody tr:nth-child(even) {
			background-color: #0000000b;
		}
		tbody tr {
			--delay: .1s;
			transition: .5s ease-in-out var(--delay), background-color 0s;
		}

		tbody tr.hide {
			opacity: 0;
			transform: translateX(100%);
		}

		tbody tr:hover {
			background-color: #fff6 !important;
		}
		tbody tr td,
		tbody tr td p,
		tbody tr td img {
			transition: .2s ease-in-out;
		}

		tbody tr.hide td,
		tbody tr.hide td p {
			padding: 0;
			font: 0 / 0 sans-serif;
			transition: .2s ease-in-out .5s;
		}

		tbody tr.hide td img {
			width: 0;
			height: 0;
			transition: .2s ease-in-out .5s;
		}
		.status {
			padding: .4rem 0;
			border-radius: 2rem;
			text-align: center;
		}

		.status.delivered {
			background-color: #86e49d;
			color: #006b21;
		}

		.status.cancelled {
			background-color: #d893a3;
			color: #b30021;
		}

		.status.pending {
			background-color: #ebc474;
		}
		.status.shipped {
			background-color: #6fcaea;
		}

		.text-danger {
			color: red;
		}

		#add-button {
			width: 10%;
			height: 10%;
			background-color: #0b0b49;
			color: white;
			padding: 12px 20px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			margin-bottom: 20px;
			margin-top: 20px;
			font-weight: bold;
			font-size: 16px;
		}

		#add-button:hover {
			background-color: blue;
		}
	</style>
<?php require SNEAKERPEAK_ROOT . '/resources/views/layouts/theme.php'; ?>
</head>
<body>
	<?php 
		include SNEAKERPEAK_ROOT . '/resources/views/partials/admin_sidebar.php'; 
	?>
	<section class="home-section">
		<div class="home-content">
			<i class='bx bx-menu'></i>
			<span class="text">View Admins</span>
		</div>
		<div class="contenbar">
			<div class="table">
				<section class="table__header">
					<h1>All Admins</h1>
				</section>
				<section class="table__body">
					<table>
						<thead>
							<tr>
								<th>S.N <span class="icon-arrow"></span></th>
								<th>Username <span class="icon-arrow"></span></th>
								<th>Email <span class="icon-arrow"></span></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT u.Id AS id, u.DisplayName AS name, u.Email AS email, u.Phone AS Admin_ContactNumber FROM T_User u JOIN T_Admin a ON a.UserId=u.Id WHERE u.IsAdmin=1 AND a.AdminFlag=1";
							$result = $conn->query($sql);
							$count = 1;
							if ($result->num_rows > 0) {
								while ($row = $result->fetch_assoc()) {
									?>
									<tr>
										<td><?=$count?></td>
										<td><?=$row["name"]?></td>
										<td><?=$row["email"]?></td>
									</tr>
									<?php
									$count++;
								}
							}
							?>
						</tbody>
					</table>
				</section>
			</div>
		</div>
	</section>

	<section class="home-section">
		<br>
		<br>
		<div class="itembox">
			<h1>Add New Admin</h1>
			<div class="inputbox">
				<form action="" method="post">
					<div class="box">
						<label for="name">Name:<br></label>
						<input type="text" id="name" name="name" required>
						<br><span id="name-error" class="text-danger"></span>
					</div>
					<div class="box">
						<label for="email">Email:<br></label>
						<input type="email" id="email" name="email" required>
						<br><span id="email-error" class="text-danger"></span>
					</div>
					<div class="box">
						<label for="password">Password:<br></label>
						<input type="password" id="password" name="password" required>
						<br><span id="password-error" class="text-danger"></span>
	          <span id="requirement_1" class="text-danger"></span>
						<span id="requirement_2" class="text-danger"></span>
						<span id="requirement_3" class="text-danger"></span>
						<span id="requirement_4" class="text-danger"></span>
						<span id="requirement_5" class="text-danger"></span>
					</div>
					<div class="box">
						<label for="confirm-password">Confirm Password:<br></label>
						<input type="password" id="confirm-password" name="cpassword" required>
						<br><span id="password-match-error" class="text-danger"></span>
					</div>
					<button class="btn btn-primary" type="submit" id="add-button" name="add" onclick="return validateForm()">Add Admin</button>
				</form>
			</div>
		</div>
	</section>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		let arrow = document.querySelectorAll(".arrow");
  for (var i = 0; i < arrow.length; i++) {
    arrow[i].addEventListener("click", (e)=>{
   let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
   arrowParent.classList.toggle("showMenu");
    });
  }
  let sidebar = document.querySelector(".sidebar");
  let sidebarBtn = document.querySelector(".bx-menu");
  console.log(sidebarBtn);
  sidebarBtn.addEventListener("click", ()=>{
    sidebar.classList.toggle("close");
	});

		function validateForm() {
			var name = document.getElementById("name").value;
			var email = document.getElementById("email").value;
			var password = document.getElementById("password").value;
			var confirmPassword = document.getElementById("confirm-password").value;

			var nameError = document.getElementById("name-error");
			var emailError = document.getElementById("email-error");
			var passwordError = document.getElementById("password-error");
			var passwordMatchError = document.getElementById("password-match-error");
			var requirement1Error = document.getElementById("requirement_1");
			var requirement2Error = document.getElementById("requirement_2");
			var requirement3Error = document.getElementById("requirement_3");
			var requirement4Error = document.getElementById("requirement_4");
			var requirement5Error = document.getElementById("requirement_5");

			// Reset error messages
			nameError.textContent = "";
			emailError.textContent = "";
			passwordError.textContent = "";
			passwordMatchError.textContent = "";
			requirement1Error.innerHTML = "";
			requirement2Error.innerHTML = "";
			requirement3Error.innerHTML = "";
			requirement4Error.innerHTML = "";
			requirement5Error.innerHTML = "";

			// Check if the fields are empty
			var isValid = true;

			if (name === "") {
				nameError.textContent = "Please enter a name.";
				isValid = false;
			}

			if (email === "") {
				emailError.textContent = "Please enter an email address.";
				isValid = false;
			} else if (!validateEmail(email)) {
				emailError.textContent = "Please enter a valid email address.";
				isValid = false;
			}

			if (password === "") {
				passwordError.innerHTML = "Please enter a password.";
				isValid = false;
			} else {
				// Check individual requirements and display corresponding error messages
				if (password.length < 8) {
					requirement1Error.innerHTML = "Password must be at least 8 characters.<br>";
					isValid = false;
				}
				if (!/[A-Z]/.test(password)) {
					requirement2Error.innerHTML = "Password must contain at least 1 uppercase letter.<br>";
					isValid = false;
				}
				if (!/[a-z]/.test(password)) {
					requirement3Error.innerHTML = "Password must contain at least 1 lowercase letter.<br>";
					isValid = false;
					}
				if (!/[0-9]/.test(password)) {
					requirement4Error.innerHTML = "Password must contain at least 1 digit.<br>";
					isValid = false;
				}
				if (!/[!@#$%^&*()_+\-=[\]{};:'\"|,.<>/?`~]/.test(password)) {
					requirement5Error.innerHTML = "Password must contain at least 1 special character.<br>";
					isValid = false;
				}
			}

			if (confirmPassword === "") {
				passwordMatchError.textContent = "Please confirm the password.";
				isValid = false;
			} else if (password !== confirmPassword) {
				passwordMatchError.textContent = "Confirm password and password do not match.";
				isValid = false;
			}

			return isValid;
		}

		// Function to validate email address using regex
		function validateEmail(email) {
			var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			return emailRegex.test(email);
		}
	</script>
<?php
$email = "";
$name = "";
$errors = array();

if (isset($_POST['add'])) {
    require_once SNEAKERPEAK_ROOT . '/src/App/Services/AuthService.php';
    require_once SNEAKERPEAK_ROOT . '/src/App/Models/UserModel.php';
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)
        || $password !== ($_POST['cpassword'] ?? null) || strlen($password) < 8
        || !\SneakerPeak\App\Services\AuthService::canHash($password)) {
        $message = 'Enter a name, valid email and matching passwords.';
    } else {
        $conn->begin_transaction();
        try {
            $conn->execute_query('INSERT INTO T_User (DisplayName, Email, EmailKey, PasswordHash, IsAdmin, EmailVerified)
                VALUES (?, ?, ?, ?, 1, 1)', [$name, $email, \SneakerPeak\App\Models\UserModel::emailKey($email), password_hash($password, PASSWORD_DEFAULT)]);
            $id = $conn->insert_id;
            $conn->execute_query('INSERT INTO T_Admin (UserId, AdminFlag, GrantedDate, GrantedByUserId) VALUES (?, 1, UTC_TIMESTAMP(6), ?)',
                [$id, $_SESSION['AdminID']]);
            $conn->commit();
            $message = 'Admin added.';
        } catch (Throwable $error) {
            $conn->rollback();
            $message = $error->getCode() === 1062 ? 'This email already exists.' : 'Could not add admin.';
        }
    } ?>
    <script>Swal.fire({title: <?= json_encode($message, JSON_HEX_TAG) ?>});</script>
<?php } ?>
</body>
</html>