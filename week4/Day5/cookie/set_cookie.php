<?php
if (setcookie("username", "Thomas", time() + 600)) {
    echo "Cookie has been set.";
} else {
    echo "Cookie has not been set.";
}
?>