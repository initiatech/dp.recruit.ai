-- Recruiter-AI Database Seed Data
--
-- This file contains sample data to populate the tables for development and testing.
-- It is designed to be idempotent and can be re-imported safely.

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

-- To make this script idempotent, we delete data from tables in reverse order of dependency.
TRUNCATE TABLE `audit_logs`;
TRUNCATE TABLE `candidate_labels`;
TRUNCATE TABLE `recruiter_notes`;
TRUNCATE TABLE `heat_scores`;
TRUNCATE TABLE `interview_progress`;
TRUNCATE TABLE `messages`;
TRUNCATE TABLE `conversations`;
TRUNCATE TABLE `labels`;
TRUNCATE TABLE `users`;
TRUNCATE TABLE `jobs`;
TRUNCATE TABLE `candidates`;


--
-- Seeding data for table `users`
--
LOCK TABLES `users` WRITE;
INSERT INTO `users` (`id`, `full_name`, `email`, `role`, `status`, `password_hash`, `created_at`) VALUES
(1, 'מנהל מערכת', 'admin@example.com', 'admin', 'active', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
(2, 'מגייס ראשי', 'recruiter@example.com', 'recruiter', 'active', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW());
UNLOCK TABLES;

--
-- Seeding data for table `jobs`
--
LOCK TABLES `jobs` WRITE;
INSERT INTO `jobs` (`id`, `title`, `description`, `required_fields_json`, `must_have`, `nice_to_have`, `heat_threshold`, `created_at`) VALUES
(1, 'מפתח/ת Full-Stack בכיר/ה', 'דרוש/ה מפתח/ת Full-Stack עם נסיון של 5 שנים לפחות ב-PHP ו-React.', '{\"experience_years\": \"כמה שנות נסיון יש לך בפיתוח Full-Stack?\", \"core_skills\": \"מהן טכנולוגיות הליבה שלך (לדוגמה: PHP, React, Node.js)?\", \"salary_expectation\": \"מהן ציפיות השכר שלך?\", \"availability\": \"מתי תוכל/י להתחיל לעבוד?\"}', '[\"PHP\", \"React\", \"MySQL\"]', '[\"Docker\", \"AWS\", \"TypeScript\"]', 75, NOW()),
(2, 'מנהל/ת מוצר', 'חברת סטארטאפ בצמיחה מחפשת מנהל/ת מוצר להובלת קו מוצרים חדשני.', '{\"product_experience\": \"ספר/י על נסיונך בניהול מוצר.\", \"b2b_saas\": \"האם יש לך נסיון עם מוצרי B2B SaaS?\", \"methodologies\": \"עם אילו מתודולוגיות Agile עבדת?\"}', '[\"ניהול מוצר\", \"Agile\", \"B2B\"]', '[\"עיצוב UX/UI\", \"JIRA\", \"SQL\"]', 70, NOW());
UNLOCK TABLES;

--
-- Seeding data for table `candidates`
--
LOCK TABLES `candidates` WRITE;
INSERT INTO `candidates` (`id`, `full_name`, `email`, `phone`, `cv_text`, `source`, `created_at`) VALUES
(1, 'ישראל ישראלי', 'israel@example.com', '050-1234567', 'קורות חיים לדוגמה עבור ישראל ישראלי, מפתח תוכנה עם נסיון רב.', 'LinkedIn', NOW()),
(2, 'משה כהן', 'moshe@example.com', '052-7654321', 'משה כהן, מנהל מוצר מנוסה.', 'אתר החברה', NOW()),
(3, 'דנה לוי', 'dana@example.com', '054-1122334', NULL, 'חבר מביא חבר', NOW());
UNLOCK TABLES;

--
-- Seeding data for table `conversations`
--
LOCK TABLES `conversations` WRITE;
INSERT INTO `conversations` (`id`, `candidate_id`, `job_id`, `status`, `started_at`, `closed_at`) VALUES
('a1b2c3d4-e5f6-7890-1234-567890abcdef', 1, 1, 'in_interview', NOW(), NULL),
('fedcba98-7654-3210-fedc-ba9876543210', 2, 2, 'new', NOW(), NULL);
UNLOCK TABLES;

--
-- Seeding data for table `messages`
--
LOCK TABLES `messages` WRITE;
INSERT INTO `messages` (`conversation_id`, `sender`, `content_text`) VALUES
('a1b2c3d4-e5f6-7890-1234-567890abcdef', 'ai', 'שלום ישראל, ברוך הבא לראיון. ספר לי קצת על הנסיון שלך.'),
('a1b2c3d4-e5f6-7890-1234-567890abcdef', 'candidate', 'בטח, אני מפתח כבר 10 שנים, התמקדתי בעיקר ב-PHP ובשנים האחרונות גם ב-React.'),
('a1b2c3d4-e5f6-7890-1234-567890abcdef', 'ai', 'מצוין, תודה. כמה שנות נסיון יש לך בפיתוח Full-Stack?'),
('fedcba98-7654-3210-fedc-ba9876543210', 'ai', 'היי משה, תודה שהצטרפת. מה משך אותך במשרה שלנו?');
UNLOCK TABLES;


SET foreign_key_checks = 1;
