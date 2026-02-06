<?php
// 1. Start the session
// This is necessary to access and manipulate the session data.
session_start();

// 2. Unset all session variables
// This clears all the data stored in the session, like user_id, name, and role.
$_SESSION = array();

// 3. Destroy the session
// This completely removes the session from the server.
session_destroy();

// 4. Redirect to the login page
// After logging out, the user is sent back to the login page.
header("Location: login.php");
exit(); // This is a crucial step to ensure the script stops running after the redirect.
?>