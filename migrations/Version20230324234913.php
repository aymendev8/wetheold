<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230324234913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commandes_articles (commandes_id INT NOT NULL, articles_id INT NOT NULL, INDEX IDX_D470CD148BF5C2E6 (commandes_id), INDEX IDX_D470CD141EBAF6CC (articles_id), PRIMARY KEY(commandes_id, articles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commandes_articles ADD CONSTRAINT FK_D470CD148BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commandes_articles ADD CONSTRAINT FK_D470CD141EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commandes_articles DROP FOREIGN KEY FK_D470CD148BF5C2E6');
        $this->addSql('ALTER TABLE commandes_articles DROP FOREIGN KEY FK_D470CD141EBAF6CC');
        $this->addSql('DROP TABLE commandes_articles');
    }
}
