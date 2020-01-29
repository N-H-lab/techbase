<?php
$birth_year = 1997;
$this_year = 2020;
$duration = $this_year - $birth_year;
$amari = $duration % 4;
$value = ($duration - $amari) / 4;
echo $value;
?>
