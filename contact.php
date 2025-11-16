<?php
// Database connection settings
$servername = "localhost";   // MySQL host
$port       = 3306;          // MySQL port (default unless changed)
$username   = "root";
$password   = "";
$dbname     = "portfolio_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact</title>
  <link rel="stylesheet" href="style.css" />
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
    <?php if (isset($success)) { echo "<p style='color:green;'>" . htmlspecialchars($success) . "</p>"; } ?>
    <?php if (isset($error))   { echo "<p style='color:red;'>"   . htmlspecialchars($error)   . "</p>"; } ?>
    <form action="contact.php" method="post">
      <input type="text"     name="name"    placeholder="Your Name"  required value="<?= isset($name)    ? htmlspecialchars($name)    : '' ?>">
      <input type="email"    name="email"   placeholder="Your Email" required value="<?= isset($email)   ? htmlspecialchars($email)   : '' ?>">
      <textarea name="message" placeholder="Your Message" required><?= isset($message) ? htmlspecialchars($message) : '' ?></textarea>
      <button type="submit">Send</button>
    </form>
  </section>
  <footer>
      <p>&copy; 2025 Sara – Web Developer. All rights reserved.</p>
  <p>Email: <a href="mailto:saraisml1234@gmail.com">saraisml1234@gmail.com</a></p>
  </footer>
</body>
</html>
