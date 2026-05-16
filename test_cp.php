<?php
shell_exec('mkdir -p src/BANNERS dest/BANNERS');
shell_exec('touch src/BANNERS/1.jpg');
shell_exec('cp -rn src/* dest/');
echo shell_exec('ls -R dest');
