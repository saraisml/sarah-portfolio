<?php
// Database connection settings
$servername = "localhost";
$port       = 3306;
$username   = "root";
$password   = "";
$dbname     = "portfolio_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($message)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        if ($stmt === false) {
            $error = "Prepare failed: " . $conn->error;
        } else {
            $stmt->bind_param("sss", $name, $email, $message);
            if ($stmt->execute()) {
                $success = "Message sent successfully!";
            } else {
                $error = "Execution failed: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact - FutureTech WoW</title>
<style>
/* RESET */
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', sans-serif; }
body { background:#0a0a1a; color:#eee; }

/* HEADER */
header {
  background:#11111f;
  padding:20px 50px;
  display:flex; justify-content: space-between; align-items:center;
  box-shadow:0 0 20px rgba(0,255,255,0.2);
}
header h1 { color:#00eaff; }
nav a { color:#eee; margin-left:20px; text-decoration:none; font-weight:bold; transition:0.3s; }
nav a:hover { color:#00eaff; }

/* SECTION */
section {
  text-align:center;
  padding:50px 20px;
}
section h2 {
  font-size:2rem;
  margin-bottom:40px;
  color:#00eaff;
  text-shadow:0 0 10px #00eaff, 0 0 20px #0ff;
  animation: glow 2s infinite alternate;
}

/* FORM */
form {
  max-width:600px;
  margin:0 auto;
  display:flex;
  flex-direction:column;
  gap:20px;
}
input, textarea {
  padding:15px;
  border-radius:10px;
  border:2px solid #00eaff;
  background:#11111f;
  color:#eee;
  font-size:1rem;
  outline:none;
  transition: 0.3s;
}
input:focus, textarea:focus {
  border-color:#0ff;
  box-shadow:0 0 20px #0ff;
}
textarea { min-height:150px; resize:none; }

button {
  padding:15px;
  border:none;
  border-radius:10px;
  background:#00eaff;
  color:#11111f;
  font-weight:bold;
  font-size:1.1rem;
  cursor:pointer;
  transition:0.3s;
}
button:hover {
  background:#0ff;
  box-shadow:0 0 30px #0ff;
}

/* MESSAGES */
p.success { color:#0f0; font-weight:bold; }
p.error   { color:#f00; font-weight:bold; }

/* ANIMATION */
@keyframes glow {
  from { text-shadow:0 0 5px #00eaff, 0 0 10px #0ff; }
  to   { text-shadow:0 0 20px #0ff, 0 0 40px #00eaff; }
}

/* FOOTER */
footer {
  text-align:center;
  padding:30px 20px;
  background:#11111f;
  box-shadow:0 -5px 20px rgba(0,255,255,0.2);
  margin-top:50px;
}
footer a { color:#00eaff; text-decoration:none; }
</style>
</head>
<body>

<header>
  <h1>Sara - Web Developer</h1>
  <nav>
    <a href="index.html">Home</a>
    <a href="about.html">About</a>
    <a href="service.html">Services</a>
    <a href="contact.php">Contact</a>
  </nav>
</header>

<section id="contact">
  <h2>Contact Me</h2>
  <?php if (isset($success)) echo "<p class='success'>" . htmlspecialchars($success) . "</p>"; ?>
  <?php if (isset($error))   echo "<p class='error'>"   . htmlspecialchars($error)   . "</p>"; ?>
  <form action="contact.php" method="post">
    <input type="text" name="name" placeholder="Your Name" required value="<?= isset($name)?htmlspecialchars($name):'' ?>">
    <input type="email" name="email" placeholder="Your Email" required value="<?= isset($email)?htmlspecialchars($email):'' ?>">
    <textarea name="message" placeholder="Your Message" required><?= isset($message)?htmlspecialchars($message):'' ?></textarea>
    <button type="submit">Send</button>
  </form>
</section>

<footer>
  <p>&copy; 2025 Sara – Web Developer. All rights reserved.</p>
  <p>Email: <a href="mailto:saraisml1234@gmail.com">saraisml1234@gmail.com</a></p>
</footer>

</body>
</html>

