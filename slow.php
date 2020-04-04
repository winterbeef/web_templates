<?php
header('Content-type: application/json');
usleep(501);
echo json_encode(['val' => mt_rand(4, 100)]);
