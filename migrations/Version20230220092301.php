<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230220092301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, comment_id INT DEFAULT NULL, hiring_id INT DEFAULT NULL, purpose_id INT DEFAULT NULL, coupon_id INT DEFAULT NULL, article_id INT DEFAULT NULL, experience_id INT DEFAULT NULL, create_at DATETIME NOT NULL, content VARCHAR(255) NOT NULL, edate DATETIME DEFAULT NULL, INDEX IDX_B26681EA76ED395 (user_id), INDEX IDX_B26681EF8697D13 (comment_id), INDEX IDX_B26681E83E909A6 (hiring_id), INDEX IDX_B26681E7FC21131 (purpose_id), INDEX IDX_B26681E66C5951B (coupon_id), INDEX IDX_B26681E7294869C (article_id), INDEX IDX_B26681E46E90E27 (experience_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stats_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, like_article INT NOT NULL, UNIQUE INDEX UNIQ_CCFFBE09A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EF8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E83E909A6 FOREIGN KEY (hiring_id) REFERENCES work (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E7FC21131 FOREIGN KEY (purpose_id) REFERENCES purpose (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E66C5951B FOREIGN KEY (coupon_id) REFERENCES code_redeem (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E46E90E27 FOREIGN KEY (experience_id) REFERENCES experience (id)');
        $this->addSql('ALTER TABLE stats_user ADD CONSTRAINT FK_CCFFBE09A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE badge ADD name VARCHAR(100) NOT NULL, ADD description VARCHAR(255) DEFAULT NULL, ADD image VARCHAR(255) DEFAULT NULL, DROP ajout');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEF0481D5E237E06 ON badge (name)');
        $this->addSql('ALTER TABLE work CHANGE revenu revenu INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EA76ED395');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EF8697D13');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E83E909A6');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E7FC21131');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E66C5951B');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E7294869C');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E46E90E27');
        $this->addSql('ALTER TABLE stats_user DROP FOREIGN KEY FK_CCFFBE09A76ED395');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE stats_user');
        $this->addSql('DROP INDEX UNIQ_FEF0481D5E237E06 ON badge');
        $this->addSql('ALTER TABLE badge ADD ajout VARCHAR(255) NOT NULL, DROP name, DROP description, DROP image');
        $this->addSql('ALTER TABLE work CHANGE revenu revenu VARCHAR(255) DEFAULT NULL');
    }
}
