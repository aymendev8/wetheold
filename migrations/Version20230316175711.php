<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230316175711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_wishlist (id INT AUTO_INCREMENT NOT NULL, wishlist_id INT NOT NULL, INDEX IDX_575140A6FB8E54CD (wishlist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_wishlist_articles (product_wishlist_id INT NOT NULL, articles_id INT NOT NULL, INDEX IDX_BB1797CF57A1CAD2 (product_wishlist_id), INDEX IDX_BB1797CF1EBAF6CC (articles_id), PRIMARY KEY(product_wishlist_id, articles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishlist (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nb_articles INT NOT NULL, UNIQUE INDEX UNIQ_9CE12A31A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_wishlist ADD CONSTRAINT FK_575140A6FB8E54CD FOREIGN KEY (wishlist_id) REFERENCES wishlist (id)');
        $this->addSql('ALTER TABLE product_wishlist_articles ADD CONSTRAINT FK_BB1797CF57A1CAD2 FOREIGN KEY (product_wishlist_id) REFERENCES product_wishlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_wishlist_articles ADD CONSTRAINT FK_BB1797CF1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wishlist ADD CONSTRAINT FK_9CE12A31A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_wishlist DROP FOREIGN KEY FK_575140A6FB8E54CD');
        $this->addSql('ALTER TABLE product_wishlist_articles DROP FOREIGN KEY FK_BB1797CF57A1CAD2');
        $this->addSql('ALTER TABLE product_wishlist_articles DROP FOREIGN KEY FK_BB1797CF1EBAF6CC');
        $this->addSql('ALTER TABLE wishlist DROP FOREIGN KEY FK_9CE12A31A76ED395');
        $this->addSql('DROP TABLE product_wishlist');
        $this->addSql('DROP TABLE product_wishlist_articles');
        $this->addSql('DROP TABLE wishlist');
    }
}
