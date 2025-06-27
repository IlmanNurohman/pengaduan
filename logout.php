<?php
session_start();
session_unset();
session_destroy();
?>

<!-- Bersihkan juga localStorage via JavaScript -->
<script>
localStorage.removeItem('userData');
localStorage.removeItem('user_id');
// Arahkan ke login.html (bukan index.php agar konsisten dengan offline login)
window.location.href = 'login.html';
</script>