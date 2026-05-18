<?php
echo shell_exec('git status');
echo "\n\nGit log:\n";
echo shell_exec('git log -n 3');
