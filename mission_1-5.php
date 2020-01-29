<?php
$age = 22;
$min_age = 18;
$max_age = 85;
if ($age < $min_age) {
  echo "自動車免許はまだ取得できません";
} elseif ($age > $max_age) {
  echo "免許を返納しませんか？";
} else {
  echo "自動車免許が取れます";
}
?>
