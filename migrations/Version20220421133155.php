<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220421133155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ads (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, messages TINYTEXT NOT NULL, created INT NOT NULL, redirect VARCHAR(255) NOT NULL, count INT NOT NULL, last_time INT DEFAULT NULL, type VARCHAR(255) NOT NULL, views INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_7EC9F620A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regiment_stats_users (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created DATE NOT NULL, level INT NOT NULL, sut INT NOT NULL, used_talents INT NOT NULL, total_damage INT NOT NULL, achievements LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', experience INT NOT NULL, update_time INT NOT NULL, INDEX IDX_70A6B4D5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE regiment_users (id INT AUTO_INCREMENT NOT NULL, soc_id INT NOT NULL, level INT NOT NULL, sut INT NOT NULL, used_talents INT NOT NULL, login_time INT NOT NULL, total_damage INT NOT NULL, created INT NOT NULL, achievements LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, photo_50 VARCHAR(255) NOT NULL, update_time INT NOT NULL, experience INT NOT NULL, UNIQUE INDEX UNIQ_7EE7AF1497857A9F (soc_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stats_logs_visit (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(255) NOT NULL, time VARCHAR(255) NOT NULL, referar VARCHAR(255) NOT NULL, ua VARCHAR(255) NOT NULL, page VARCHAR(255) NOT NULL, platform_id INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, vkontakte_id VARCHAR(255) NOT NULL, update_time INT NOT NULL, access_token VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, screen_name VARCHAR(255) NOT NULL, photo_medium VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_script (id INT AUTO_INCREMENT NOT NULL, platform_id INT NOT NULL, created INT NOT NULL, photo_50 VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_time INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_token (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, access_token VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_B8400572A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ads ADD CONSTRAINT FK_7EC9F620A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE regiment_stats_users ADD CONSTRAINT FK_70A6B4D5A76ED395 FOREIGN KEY (user_id) REFERENCES regiment_users (id)');
        $this->addSql('ALTER TABLE users_token ADD CONSTRAINT FK_B8400572A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE regiment_stats_users DROP FOREIGN KEY FK_70A6B4D5A76ED395');
        $this->addSql('ALTER TABLE ads DROP FOREIGN KEY FK_7EC9F620A76ED395');
        $this->addSql('ALTER TABLE users_token DROP FOREIGN KEY FK_B8400572A76ED395');
        $this->addSql('DROP TABLE ads');
        $this->addSql('DROP TABLE regiment_stats_users');
        $this->addSql('DROP TABLE regiment_users');
        $this->addSql('DROP TABLE stats_logs_visit');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE users_script');
        $this->addSql('DROP TABLE users_token');
    }
}
