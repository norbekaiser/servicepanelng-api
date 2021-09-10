<?php

require_once __DIR__ . '/../lib/Gateways/SessionGateway.php';

use norb_api\Gateways\SessionGateway;

$SessionGateway = new SessionGateway();
$SessionGateway->clear_all_sessions();
