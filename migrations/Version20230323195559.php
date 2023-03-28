<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230323195559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commandes_user (commandes_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_99B0BCDC8BF5C2E6 (commandes_id), INDEX IDX_99B0BCDCA76ED395 (user_id), PRIMARY KEY(commandes_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commandes_user ADD CONSTRAINT FK_99B0BCDC8BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes_user ADD CONSTRAINT FK_99B0BCDCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_cart ADD commandes_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_cart ADD CONSTRAINT FK_864BAA168BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id)');
        $this->addSql('CREATE INDEX IDX_864BAA168BF5C2E6 ON product_cart (commandes_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_cart DROP FOREIGN KEY FK_864BAA168BF5C2E6');
        $this->addSql('ALTER TABLE commandes_user DROP FOREIGN KEY FK_99B0BCDC8BF5C2E6');
        $this->addSql('ALTER TABLE commandes_user DROP FOREIGN KEY FK_99B0BCDCA76ED395');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE commandes_user');
        $this->addSql('DROP INDEX IDX_864BAA168BF5C2E6 ON product_cart');
        $this->addSql('ALTER TABLE product_cart DROP commandes_id');
    }
}
