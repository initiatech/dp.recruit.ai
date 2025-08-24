<?php

declare(strict_types=1);

namespace Tests\Database;

use PDO;
use Tests\DatabaseTestCase;

/**
 * A simple smoke test to ensure that the Phinx migrations run correctly
 * and create the expected tables in the schema.
 */
class SchemaTest extends DatabaseTestCase
{
    public function testTablesAreCreatedByMigrations(): void
    {
        /** @var PDO $pdo */
        global $pdo;
        $pdo = self::$pdo;

        $tables = [
            'candidates',
            'jobs',
            'users',
            'labels',
            'conversations',
            'messages',
            'interview_progress',
            'heat_scores',
            'recruiter_notes',
            'candidate_labels',
            'audit_logs',
            'phinxlog', // Also check that the migration log table is created
        ];

        foreach ($tables as $tableName) {
            try {
                // The query doesn't need to return rows, just succeed.
                $result = $pdo->query("SELECT 1 FROM `{$tableName}` LIMIT 1");
            } catch (\Exception $e) {
                $this->fail("Table '{$tableName}' does not exist or is invalid after migration: " . $e->getMessage());
            }
            // assertNotFalse is good, but assertInstanceOf is more specific for PDO statements
            $this->assertInstanceOf(\PDOStatement::class, $result, "Query failed for table '{$tableName}'.");
        }
    }
}
