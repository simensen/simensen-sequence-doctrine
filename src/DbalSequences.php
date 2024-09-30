<?php

declare(strict_types=1);

namespace Simensen\SequenceDoctrine;

use RuntimeException;
use Simensen\Sequence\Configuration\Behavior\ReadsConfigurationFromTraitsBehavior;
use Simensen\Sequence\Sequences\Sequences;

final readonly class DbalSequences implements Sequences
{
    use ReadsConfigurationFromTraitsBehavior;

    public function __construct(private Connections $connections)
    {
    }

    public function generateForClass(string $sequenceClassName): int
    {
        $configuration = $this->readSequenceConfigurationForClass($sequenceClassName);

        if (!$configuration->getTableName()) {
            throw new RuntimeException('Sequence requires Table to be configured for DBAL Sequences');
        }

        $tableName = $configuration->getTableName();
        $columnName = $configuration->getColumnName() ?: 'next';

        $connection = $this->connections->get($configuration->getConnectionName());

        $sql = sprintf(
            'SELECT 1 AS %s FROM %s',
            $columnName,
            $tableName
        );

        /** @var array<int>|false $result */
        $result = $connection->query($sql)->fetchAssociative();

        if (!$result) {
            throw new RuntimeException('Query failed');
        }

        return $result[$columnName];
    }
}
