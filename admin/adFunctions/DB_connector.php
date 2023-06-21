<?php
$DB_Connector = mysqli_connect("localhost", "root", '', "GMMD");
if (!$DB_Connector) { ?>
    <script>
        alert('Error in connecting to DB');
    </script>
<?php } ?>