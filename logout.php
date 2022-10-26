<?php

declare(strict_types=1);

require 'helpers.php';

session_start();
session_destroy();

redirect('index.php');
