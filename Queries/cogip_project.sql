

DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type_id` int NOT NULL,
  `country` varchar(50) NOT NULL,
  `tva` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_companies_type_id` (`type_id`)
);


INSERT INTO `companies` (`id`, `name`, `type_id`, `country`, `tva`, `created_at`, `updated_at`) VALUES
(1, 'Thomas&Piron', 1, 'Belgique', '21%', '2023-12-05 10:58:02', '2023-12-05 11:31:45');


DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `company_id` int NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_contacts_company_id` (`company_id`)
);


INSERT INTO `contacts` (`id`, `name`, `company_id`, `email`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Bob', 1, 'Bob.dylan@sing.com', '04523695', '2023-12-05 13:49:37', '2023-12-05 13:49:37');


DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ref` varchar(50) NOT NULL,
  `id_company` int NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_invoices_id_company` (`id_company`)
); 

INSERT INTO `invoices` (`id`, `ref`, `id_company`, `created_at`, `updated_at`) VALUES
(1, 'F20220915-001', 1, '2023-12-05 11:40:50', '2023-12-05 11:40:50');

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `permissions` (`id`, `created_at`, `updated_at`) VALUES
(1, '2023-12-05 13:51:03', '2023-12-05 13:51:03'),
(2, '2023-12-05 13:53:37', '2023-12-05 13:53:37');

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2023-12-05 13:52:16', '2023-12-05 13:52:16'),
(2, 'user', '2023-12-05 13:52:16', '2023-12-05 13:52:16');


DROP TABLE IF EXISTS `roles_permission`;
CREATE TABLE IF NOT EXISTS `roles_permission` (
  `id` int NOT NULL AUTO_INCREMENT,
  `permission_id` int NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_roles_permission_permission_id` (`permission_id`),
  KEY `fk_roles_permission_role_id` (`role_id`)
);

INSERT INTO `roles_permission` (`id`, `permission_id`, `role_id`) VALUES
(1, 1, 1),
(2, 2, 2);

DROP TABLE IF EXISTS `types`;
CREATE TABLE IF NOT EXISTS `types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_types_name` (`name`)
);

INSERT INTO `types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'construction', '2023-12-05 10:56:06', '0000-00-00 00:00:00');

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `role_id` int NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_role_id` (`role_id`)
);

INSERT INTO `users` (`id`, `first_name`, `role_id`, `last_name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'John', 1, 'Doe', 'john.doe@exemple.com', 'test123', '2023-12-05 09:40:04', '0000-00-00 00:00:00');
