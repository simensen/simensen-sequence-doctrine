<?php

declare(strict_types=1);

namespace Simensen\SequenceDoctrine;

use Doctrine\DBAL\Driver\Connection;

interface Connections
{
    public function get(?string $name = null): Connection;
}
