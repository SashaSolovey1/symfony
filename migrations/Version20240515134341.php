<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240515134341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates product table and fills it with random data';
    }

    public function up(Schema $schema): void
    {
        // Create product table
        $this->addSql('CREATE TABLE product (
            id INT AUTO_INCREMENT PRIMARY KEY,
            code INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            type VARCHAR(255) NOT NULL,
            price INT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            quantity INT NOT NULL
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Fill product table with random data
        $this->addSql($this->generateRandomProductsSQL(100));
    }

    public function down(Schema $schema): void
    {
        // Drop product table
        $this->addSql('DROP TABLE product');
    }

    private function generateRandomProductsSQL(int $count): string
    {
        $values = [];
        for ($i = 0; $i < $count; $i++) {
            $code = random_int(1, 10);
            $name = $this->connection->quote('Product ' . $i);
            $type = ['type-1', 'type-2', 'type-3'][array_rand(['type-1', 'type-2', 'type-3'])];
            $price = random_int(100, 1000);
            $quantity = random_int(1, 100);
            $values[] = "INSERT INTO product (code, name, type, price, quantity) VALUES ($code, $name, '$type', $price, $quantity)";
        }
        return implode(';', $values);
    }
}

