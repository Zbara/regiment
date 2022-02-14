<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220214231407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_script (id INT AUTO_INCREMENT NOT NULL, platform_id INT NOT NULL, created INT NOT NULL, photo_50 VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_time INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE users_script');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE regiment_users CHANGE achievements achievements LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:object)\', CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE photo_50 photo_50 VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE stats_logs_visit CHANGE ip ip VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE time time VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE referar referar VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE ua ua VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE page page VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE vkontakte_id vkontakte_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE access_token access_token VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE first_name first_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE last_name last_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE screen_name screen_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE photo_medium photo_medium VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
