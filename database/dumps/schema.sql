-- Recruiter-AI Database Schema
-- Version: 1.0
--
-- This file contains the complete DDL for creating all tables.
-- It is designed to be idempotent and can be re-imported safely.

SET NAMES utf8mb4;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;

--
-- Table structure for table `candidates`
--
DROP TABLE IF EXISTS `candidates`;
CREATE TABLE `candidates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cv_text` mediumtext COLLATE utf8mb4_unicode_ci,
  `source` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `jobs`
--
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `required_fields_json` json NOT NULL,
  `must_have` json DEFAULT NULL,
  `nice_to_have` json DEFAULT NULL,
  `block_rules` json DEFAULT NULL,
  `heat_threshold` int(11) NOT NULL DEFAULT '70',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `users`
--
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `org_id` int(10) unsigned DEFAULT NULL COMMENT 'Reserved for multi-tenant support',
  `full_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','recruiter','viewer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'recruiter',
  `status` enum('active','disabled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `labels`
--
DROP TABLE IF EXISTS `labels`;
CREATE TABLE `labels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `org_id` int(10) unsigned DEFAULT NULL COMMENT 'Reserved for multi-tenant support',
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_label_name_org` (`name`,`org_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `conversations`
--
DROP TABLE IF EXISTS `conversations`;
CREATE TABLE `conversations` (
  `id` char(36) COLLATE utf8mb4_bin NOT NULL,
  `candidate_id` int(10) unsigned NOT NULL,
  `job_id` int(10) unsigned NOT NULL,
  `status` enum('new','in_interview','completed','accepted','rejected','more_info') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `started_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `closed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `candidate_id` (`candidate_id`),
  KEY `job_id` (`job_id`),
  KEY `status` (`status`),
  KEY `started_at` (`started_at`),
  CONSTRAINT `fk_conv_candidate_id` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_conv_job_id` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `messages`
--
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` char(36) COLLATE utf8mb4_bin NOT NULL,
  `sender` enum('candidate','ai','system') COLLATE utf8mb4_unicode_ci NOT NULL,
  `content_text` mediumtext COLLATE utf8mb4_unicode_ci,
  `content_audio_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_ms` int(11) DEFAULT NULL,
  `end_ms` int(11) DEFAULT NULL,
  `barge_in` tinyint(1) NOT NULL DEFAULT '0',
  `confidence` decimal(3,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `conversation_id` (`conversation_id`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `fk_messages_conv_id` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `interview_progress`
--
DROP TABLE IF EXISTS `interview_progress`;
CREATE TABLE `interview_progress` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` char(36) COLLATE utf8mb4_bin NOT NULL,
  `field_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `field_value` json DEFAULT NULL,
  `collected_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `conversation_id_field_key` (`conversation_id`,`field_key`),
  CONSTRAINT `fk_progress_conv_id` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `heat_scores`
--
DROP TABLE IF EXISTS `heat_scores`;
CREATE TABLE `heat_scores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` char(36) COLLATE utf8mb4_bin NOT NULL,
  `score` int(11) NOT NULL,
  `breakdown_json` json NOT NULL,
  `rubric_json` json DEFAULT NULL,
  `calculated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_heat_once` (`conversation_id`),
  CONSTRAINT `fk_heat_scores_conv_id` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `recruiter_notes`
--
DROP TABLE IF EXISTS `recruiter_notes`;
CREATE TABLE `recruiter_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` char(36) COLLATE utf8mb4_bin NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `conversation_id` (`conversation_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_notes_conv_id` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_notes_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `candidate_labels`
--
DROP TABLE IF EXISTS `candidate_labels`;
CREATE TABLE `candidate_labels` (
  `candidate_id` int(10) unsigned NOT NULL,
  `label_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`candidate_id`,`label_id`),
  KEY `label_id` (`label_id`),
  CONSTRAINT `fk_cand_label_cand_id` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cand_label_label_id` FOREIGN KEY (`label_id`) REFERENCES `labels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `audit_logs`
--
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `actor_user_id` int(10) unsigned DEFAULT NULL,
  `entity_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `before_json` json DEFAULT NULL,
  `after_json` json DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `entity_type_entity_id` (`entity_type`,`entity_id`),
  KEY `actor_user_id` (`actor_user_id`),
  KEY `created_at` (`created_at`),
  CONSTRAINT `fk_audit_actor_id` FOREIGN KEY (`actor_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


SET foreign_key_checks = 1;
