<?php
$data = array();
$qr = mysqli_query($con, "SELECT * FROM users WHERE usertype='2'");
while ($row = mysqli_fetch_assoc($qr)) {
    array_push($data, $row);
}
?>
</div>

<footer class="footer fixed-bottom">
    <div class="text-center p-3 sam-footer">
        <p>© 2024 File Management System. All rights reserved.</p>
    </div>
</footer>

</div>

</body>
</html>