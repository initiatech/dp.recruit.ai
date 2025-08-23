<?php

declare(strict_types=1);

namespace Tests;

use App\Utils\Database;
use PHPUnit\Framework\TestCase;
use Phinx\Console\PhinxApplication;
use Phinx\Wrapper\TextWrapper;
use PDO;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Base class for tests that need a database.
 * It sets up an in-memory SQLite database and runs migrations before each test.
 */
abstract class DatabaseTestCase extends TestCase
{
    private static ?PDO $pdo = null;
    private static ?TextWrapper $phinxWrapper = null;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Setup Phinx Application
        $app = new PhinxApplication();
        $app->setAutoExit(false);

        self::$phinxWrapper = new TextWrapper($app, [
            'configuration' => realpath(__DIR__ . '/../phinx.php'),
            'parser' => 'php'
        ]);
    }

    public function setUp(): void
    {
        parent::setUp();

        // Create a new in-memory SQLite database for each test to ensure isolation.
        self::$pdo = new PDO('sqlite::memory:');
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // SQLite does not enable foreign key constraints by default.
        self::$pdo->exec('PRAGMA foreign_keys = ON;');

        // Set the static instance for the application to use.
        Database::setInstance(self::$pdo);

        // Run migrations to set up the schema.
        $this->migrate();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Destroy the database connection
        Database::setInstance(null);
        self::$pdo = null;
    }

    protected function migrate(): void
    {
        $output = self::$phinxWrapper->getMigrate('testing');
        if (self::$phinxWrapper->getExitCode() > 0) {
            $this->fail("Database migration failed:\n" . $output);
        }
    }

    protected function seed(string $seederClass): void
    {
        $output = self::$phinxWrapper->getSeed('testing', $seederClass);
         if (self::$phinxWrapper->getExitCode() > 0) {
            $this->fail("Database seeding failed for {$seederClass}:\n" . $output);
        }
    }
}
