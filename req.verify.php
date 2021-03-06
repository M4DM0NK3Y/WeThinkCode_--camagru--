<?PHP
/*********************HEADER********************/
session_start();

if (!$_SESSION['user_id']){
	header("Location: index.php?login=required");
	exit;
}
?>
<HTML>
<HEAD>
		<link rel="stylesheet" href="stylesheet.css">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> 
		<TITLE>Camagru!</TITLE>
	</HEAD>
	<BODY>
		<HEADER>
			<NAV class=nav-header>
				<DIV class=header-content>
					<A href="index.php">Home</A>
					<?PHP
					if (!$_SESSION['user_id']){
						echo '
						<DIV class="nav-login">
						<FORM action="back/login.back.php" method="POST">
						<INPUT type="text" name="login" placeholder="E-Mail/Username" required>
						<INPUT type="password" name="pwd" placeholder="Password" required>
						<BUTTON type="submit" name="submit" value="OK">Login</BUTTON>
						<A href="forgot.pwd.php">Forgot Password</A>
						<A href="signup.php">Sign Up</A>
						</FORM>
						</DIV>';
					}
					else{
						echo '
						<A href="camera.php">Upload/Camera</A>
						<DIV class="nav-login">
							<FORM action="back/logout.back.php" method="POST">
								<BUTTON type="submit" name="submit" value="OK">Logout</BUTTON>
								<A href="account.php">My Account</A>
							</FORM>
						</DIV>';
					}
					?>
				</DIV>
				<DIV class=clear>
				</DIV>
			</NAV>
		</HEADER>
		<DIV class="content-wrapper">
		<?PHP
/*********************END********************/

/*********************BODY********************/

			$status	= $_GET['verify'];
			if ($status == "email_sent") {
				echo '
					<p>Verification e-mail has been sent to: '.$_SESSION['user_email'].'</p>';
			}
			else {
				echo '
				<p>Your Current Email is set to:<br />'.$_SESSION['user_email'].'</p>';
			}
		?>
		<p>In order to keep the website secure we require you to verify your email account using the link below:</p>
		<FORM action="back/req.verify.back.php" method="POST">
			<BUTTON type="submit" name="submit" value="OK">Verify E-Mail</BUTTON>
		</FORM>
		<p>In the rare and unlikely case that you lied about your email please use the form below to change your E-Mail:</p>
		<FORM action="back/acc.email_change.back.php" method="POST">
			<INPUT type="text" name="email" placeholder="New Email">
			<INPUT type="password" name="pwd" placeholder="Current Password">
			<BUTTON type="submit" name="submit" value="OK">Change E-Mail</BUTTON>
		</FORM>
<?PHP
/*********************END********************/

/*********************FOOTER********************/
include_once "footer.php";
/*********************END********************/