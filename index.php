<?php
require_once __DIR__ . '/vendor/autoload.php';

use AcademicEmailVerifier\AcademicEmailVerifier;

$verifier = new AcademicEmailVerifier();
$result = null;
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reset'])) {
        $email = '';
        $result = null;
    } else {
        $email = $_POST['email'] ?? '';
        if (!empty($email)) {
            $result = $verifier->verify($email);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Email Verifier Demo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        input[type="email"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .button-group {
            display: flex;
            gap: 10px;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            flex: 1;
        }
        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }
        button[type="submit"]:hover {
            background-color: #45a049;
        }
        button[name="reset"] {
            background-color: #f44336;
            color: white;
        }
        button[name="reset"]:hover {
            background-color: #da190b;
        }
        .result {
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
        }
        .success {
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            color: #a94442;
        }
        .info {
            background-color: #d9edf7;
            border: 1px solid #bce8f1;
            color: #31708f;
            margin-top: 20px;
            padding: 15px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Academic Email Verifier Demo</h1>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Enter Email Address:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            <div class="button-group">
                <button type="submit">Verify Email</button>
                <button type="submit" name="reset">Reset</button>
            </div>
        </form>

        <?php if ($result !== null): ?>
            <div class="result <?php echo $result['is_academic'] ? 'success' : 'error'; ?>">
                <h3>Verification Result:</h3>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                <p><strong>Status:</strong> <?php echo $result['is_academic'] ? 'Academic Email' : 'Non-Academic Email'; ?></p>
                <?php if ($result['university']): ?>
                    <p><strong>Institution:</strong> <?php echo htmlspecialchars($result['university']); ?></p>
                <?php endif; ?>
                <?php if ($result['error']): ?>
                    <p><strong>Error:</strong> <?php echo htmlspecialchars($result['error']); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="info">
            <h3>Example Academic Emails:</h3>
            <ul>
                <li>student@harvard.edu</li>
                <li>researcher@mit.edu</li>
                <li>professor@stanford.edu</li>
            </ul>
        </div>
    </div>
</body>
</html> 