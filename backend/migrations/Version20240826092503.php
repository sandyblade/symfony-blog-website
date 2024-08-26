<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240826092503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activities (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, user_id BIGINT UNSIGNED NOT NULL, event VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_B5F1AFE5A76ED395 (user_id), INDEX IDX_B5F1AFE53BAE0AA7 (event), INDEX IDX_B5F1AFE58B8E8428 (created_at), INDEX IDX_B5F1AFE543625D9F (updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, user_id BIGINT UNSIGNED NOT NULL, image VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, content LONGTEXT NOT NULL, tags LONGTEXT DEFAULT NULL, categories LONGTEXT DEFAULT NULL, total_viewer INT UNSIGNED DEFAULT 0 NOT NULL, total_comment INT UNSIGNED DEFAULT 0 NOT NULL, status SMALLINT UNSIGNED DEFAULT 0 NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_BFDD3168A76ED395 (user_id), INDEX IDX_BFDD3168C53D045F (image), INDEX IDX_BFDD3168989D9B62 (slug), INDEX IDX_BFDD31682B36786B (title), INDEX IDX_BFDD3168CB488F43 (total_viewer), INDEX IDX_BFDD31686068F31E (total_comment), INDEX IDX_BFDD31687B00651C (status), INDEX IDX_BFDD31688B8E8428 (created_at), INDEX IDX_BFDD316843625D9F (updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, parent_id BIGINT UNSIGNED DEFAULT NULL, user_id BIGINT UNSIGNED NOT NULL, article_id BIGINT UNSIGNED NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_5F9E962A727ACA70 (parent_id), INDEX IDX_5F9E962AA76ED395 (user_id), INDEX IDX_5F9E962A7294869C (article_id), INDEX IDX_5F9E962A8B8E8428 (created_at), INDEX IDX_5F9E962A43625D9F (updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, user_id BIGINT UNSIGNED NOT NULL, subject VARCHAR(255) DEFAULT NULL, message TEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_6000B0D3A76ED395 (user_id), INDEX IDX_6000B0D3FBCE3E7A (subject), INDEX IDX_6000B0D38B8E8428 (created_at), INDEX IDX_6000B0D343625D9F (updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(64) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, first_name VARCHAR(191) DEFAULT NULL, last_name VARCHAR(191) DEFAULT NULL, gender VARCHAR(2) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, instagram VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, linked_in VARCHAR(255) DEFAULT NULL, job_title VARCHAR(255) DEFAULT NULL, address TEXT DEFAULT NULL, about_me TEXT DEFAULT NULL, confirm_token VARCHAR(36) DEFAULT NULL, reset_token VARCHAR(36) DEFAULT NULL, confirmed SMALLINT UNSIGNED DEFAULT 0 NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_1483A5E935C246D5 (password), INDEX IDX_1483A5E9C53D045F (image), INDEX IDX_1483A5E9A9D1C132 (first_name), INDEX IDX_1483A5E9C808BA5A (last_name), INDEX IDX_1483A5E9C7470A42 (gender), INDEX IDX_1483A5E95373C966 (country), INDEX IDX_1483A5E96B74C8E0 (facebook), INDEX IDX_1483A5E984A87EC3 (instagram), INDEX IDX_1483A5E9166A7BB6 (twitter), INDEX IDX_1483A5E9B60378FA (linked_in), INDEX IDX_1483A5E92A6AC51B (job_title), INDEX IDX_1483A5E9A8C9AA51 (confirm_token), INDEX IDX_1483A5E9D7C8DC19 (reset_token), INDEX IDX_1483A5E9C846DFDC (confirmed), INDEX IDX_1483A5E98B8E8428 (created_at), INDEX IDX_1483A5E943625D9F (updated_at), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), UNIQUE INDEX UNIQ_IDENTIFIER_PHONE (phone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE viewers (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, user_id BIGINT UNSIGNED NOT NULL, article_id BIGINT UNSIGNED NOT NULL, status SMALLINT UNSIGNED DEFAULT 0 NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C36DC7B00651C (status), INDEX IDX_C36DCA76ED395 (user_id), INDEX IDX_C36DC7294869C (article_id), INDEX IDX_C36DC8B8E8428 (created_at), INDEX IDX_C36DC43625D9F (updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activities ADD CONSTRAINT FK_B5F1AFE5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD3168A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A727ACA70 FOREIGN KEY (parent_id) REFERENCES comments (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE viewers ADD CONSTRAINT FK_C36DCA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE viewers ADD CONSTRAINT FK_C36DC7294869C FOREIGN KEY (article_id) REFERENCES articles (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activities DROP FOREIGN KEY FK_B5F1AFE5A76ED395');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD3168A76ED395');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A727ACA70');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A7294869C');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3A76ED395');
        $this->addSql('ALTER TABLE viewers DROP FOREIGN KEY FK_C36DCA76ED395');
        $this->addSql('ALTER TABLE viewers DROP FOREIGN KEY FK_C36DC7294869C');
        $this->addSql('DROP TABLE activities');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE viewers');
    }
}
