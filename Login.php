<?php
    session_start();
    include "connection.php";

        if(isset($_POST['btnLogin'])){
            $id = trim($_POST['UserID']);
            $password = $_POST['UserPassword'];

            $_SESSION["login_errors"] = array();
            

            $_SESSION["login_input"] = [
                "ID" => $id,
            ];

            function checkLogin($connection, $id, $password) {
                $validAccount = "SELECT Password FROM admin_details WHERE AdminID = '$id'";
                $result = mysqli_query($connection, $validAccount);

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    if (password_verify($password, $row['Password'])) { // Use password_verify
                        return 'Admin';
                    } else {
                        return 'wrong_password';
                    }
                }

                $validAccount = "SELECT Password FROM student_details WHERE StudentID = '$id'";
                $result = mysqli_query($connection, $validAccount);

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    if (password_verify($password, $row['Password'])) { // Use password_verify
                        return 'Student';
                    } else {
                        return 'wrong_password';
                    }
                }

                $validAccount = "SELECT Password FROM lecturer_details WHERE LecturerID = '$id'";
                $result = mysqli_query($connection, $validAccount);

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    
                    error_log("Lecturer hash: " . $row['Password']);
                    error_log("Entered password: " . $password);

                    if (password_verify($password, $row['Password'])) { // Use password_verify
                        return 'Lecturer';
                    } else {
                        return 'wrong_password';
                    }
                }
                return 'not_found';
            }


        $loginResult = checkLogin($connection, $id, $password);
        if ($loginResult === 'Admin') {
            $_SESSION['AdminID'] = $id;
            header("Location: AdminHomepage.php"); 
            unset($_SESSION["login_input"]);
            unset($_SESSION["login_errors"]);
            exit;
        } elseif ($loginResult === 'Student') {
            $_SESSION['StudentID'] = $id; 
            header("Location: StudentHomepage.php");
            unset($_SESSION["login_input"]);
            unset($_SESSION["login_errors"]);
            exit;
        } elseif ($loginResult === 'Lecturer') {
            $_SESSION['LecturerID'] = $id; 
            header("Location: LecturerHomepage.php");
            unset($_SESSION["login_input"]);
            unset($_SESSION["login_errors"]);
            exit;
        }elseif ($loginResult === 'wrong_password') {
            $_SESSION["login_errors"]["password"] = "*Invalid Password.";
        } else {
            $_SESSION["login_errors"]["Account"] = "*You have not register yet.";
        }
    }

    if (isset($_POST['btnResetPass'])) {
        $id = $_POST['UserID'];
        $password = $_POST['UserPassword'];
        $conPass = $_POST['ConfirmPass'];

        $_SESSION["reset_errors"] = array();
        $_SESSION["register_inputs"] = [
            "id" => $id,
        ];

        if ($password !== $conPass) {
            $_SESSION["reset_errors"]["confirmPass"] = "Password does not match.";
            $_SESSION["showRegisterForm"] = true;
        } else if (empty($password) || !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,20}$/', $password)) {
            $_SESSION["reset_errors"]["password"] = "Password must be 8 to 20 characters long and include at least one uppercase letter, one lowercase letter, one digit, and one special character.";
            $_SESSION["showRegisterForm"] = true;
        } else {
            // Check if the user exists in any of the tables
            $queryStudent = "SELECT * FROM student_details WHERE StudentID = '$id'";
            $queryAdmin = "SELECT * FROM admin_details WHERE AdminID = '$id'";
            $queryLecturer = "SELECT * FROM lecturer_details WHERE LecturerID = '$id'";

            $resultStudent = mysqli_query($connection, $queryStudent);
            $resultAdmin = mysqli_query($connection, $queryAdmin);
            $resultLecturer = mysqli_query($connection, $queryLecturer);

            if (mysqli_num_rows($resultStudent) > 0 || mysqli_num_rows($resultAdmin) > 0 || mysqli_num_rows($resultLecturer) > 0) {
                // Hash the password before updating
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                if (mysqli_num_rows($resultStudent) > 0) {
                    $updateQuery = "UPDATE student_details SET Password = ? WHERE StudentID = ?";
                    $stmt = $connection->prepare($updateQuery);
                    $stmt->bind_param("ss", $hashedPassword, $id);
                    $stmt->execute();
                    header("Location: Login.php");
                    exit;
                } elseif (mysqli_num_rows($resultAdmin) > 0) {
                    $updateQuery = "UPDATE admin_details SET Password = ? WHERE AdminID = ?";
                    $stmt = $connection->prepare($updateQuery);
                    $stmt->bind_param("ss", $hashedPassword, $id);
                    $stmt->execute();
                    header("Location: Login.php");
                    exit;
                } else {
                    $updateQuery = "UPDATE lecturer_details SET Password = ? WHERE LecturerID = ?";
                    $stmt = $connection->prepare($updateQuery);
                    $stmt->bind_param("ss", $hashedPassword, $id);
                    $stmt->execute();
                    header("Location: Login.php");
                    exit;
                }
            } else {
                $_SESSION["reset_errors"]["account"] = "ID not found.";
            }
        }
    }
    $showResetForm = isset($_SESSION["showRegisterForm"]) ? $_SESSION["showRegisterForm"] : false;
    



    mysqli_close($connection);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniSphere</title>
    <link rel="stylesheet" href="Login.css?v=<?php echo time(); ?>">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" href="img/UniSphere.png" type="image/png">
</head>
<body>
    <div class="background"></div>

    <div class="container">
        <div class="content hideOnMobile">
            <h2 class="logo"><i class='bx bxl-firebase' ></i>UniSphere</h2>
            <div class="text-sci">
                <h2>Welcome to UniSphere <br></h2> <span><p>The all-in-one campus facility management system of University of Modern Technology (UMT)</p></span>
                <p>Easily access and manage bookings, transport, timetables, library services, and more – all in one place. Designed to simplify campus life for students, staff, and administrators.</p>
            </div>

            <div class="social-icons">
                <a href="#"><i class='bx bxl-linkedin' ></i></a>
                <a href="#"><i class='bx bxl-facebook' ></i></a>
                <a href="#"><i class='bx bxl-instagram' ></i></a>
                <a href="#"><i class='bx bxl-twitter' ></i></a>
            </div>
        </div>

        <div class="logreg-box <?php echo $showResetForm ? 'active' : ''; ?>">
            <div class="form-box login">
                <form action="#" method="post">
                    <h2>Sign In</h2>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-user'></i></span>
                        <input type="text" name="UserID" value="<?php echo isset($_SESSION['login_input']['ID']) ? htmlspecialchars($_SESSION['login_input']['ID']) : ''; ?>" required>
                        <label>User ID</label>
                    </div>
                    <span class="error-message" style="color: red; display: <?php echo isset($_SESSION["login_errors"]["Account"]) ? 'block' : 'none'; ?>">
                        <?php echo $_SESSION["login_errors"]["Account"] ?? ''; ?>
                    </span>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-lock-alt' id="login-password-icon"></i></span>
                        <input type="password" name="UserPassword" class="password-field" id="login-password" required>
                        <label>Password</label>
                    </div>
                    <span class="error-message" style="color: red; display: <?php echo isset($_SESSION["login_errors"]["password"]) ? 'block' : 'none'; ?>">
                        <?php echo $_SESSION["login_errors"]["password"] ?? ''; ?>
                    </span>

                    <div class="show-password">
                        <label><input type="checkbox" class="toggle-password" onchange="ShowPassword(this, [
                                { inputId: 'login-password', iconContainerId: 'login-password-icon' }
                            ])"> Show Password</label>
                    </div>

                    <button type="submit" class="btn" name="btnLogin">Sign In</button>

                    <div class="login-register">
                        <p>Forgot your password? <a href="#" class="register-link">Reset Password</a></p>
                    </div>
                </form>
            </div>

            <div class="form-box register">
                <form action="Login.php?form=register" method="post">
                    <h2>Reset Password</h2>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-user'></i></span>
                        <input type="text" name="UserID" value="<?php echo isset($_SESSION['register_inputs']['id']) ? htmlspecialchars($_SESSION['register_inputs']['id']) : ''; ?>" required>
                        <label>User ID</label>
                    </div>
                    <span class="error-message" style="color: red; display: <?php echo isset($_SESSION["reset_errors"]["account"]) ? 'block' : 'none'; ?>">
                        <?php echo $_SESSION["reset_errors"]["account"] ?? ''; ?>
                    </span>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-lock-alt' id="new-password-icon"></i></span>
                        <input type="password" name="UserPassword" id="new-password" required>
                        <label>New Password</label>
                    </div>
                    <span class="error-message" style="color: red; display: <?php echo isset($_SESSION["reset_errors"]["password"]) ? 'block' : 'none'; ?>">
                        <?php echo $_SESSION["reset_errors"]["password"] ?? ''; ?>
                    </span>

                    <div class="input-box">
                        <span class="icon"><i class='bx bxs-lock-alt' id="confirm-password-icon"></i></span>
                        <input type="password" name="ConfirmPass" id="confirm-password" required>
                        <label>Confirm Password</label>
                    </div>
                    <span class="error-message" style="color: red; display: <?php echo isset($_SESSION["reset_errors"]["confirmPass"]) ? 'block' : 'none'; ?>">
                        <?php echo $_SESSION["reset_errors"]["confirmPass"] ?? ''; ?>
                    </span>

                    <div class="show-password">
                        <label><input type="checkbox" onchange="ShowPassword(this, [
                                { inputId: 'new-password', iconContainerId: 'new-password-icon' },
                                { inputId: 'confirm-password', iconContainerId: 'confirm-password-icon' }
                            ])"> Show Password</label>
                    </div>

                    <button type="submit" class="btn" name="btnResetPass">Submit</button>

                    <div class="login-register">
                        <p>Already have an account? <a href="#" class="login-link">Sign In</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="Login.js?v=<?php echo time(); ?>"></script>
</body>
</html>

<?php 
    unset($_SESSION["login_errors"]); 
    unset($_SESSION["reset_errors"]); 
    unset($_SESSION["showRegisterForm"]);
    unset($_SESSION["register_inputs"]);
    unset($_SESSION["login_input"]);
?>