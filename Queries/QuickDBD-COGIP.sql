-- Exported from QuickDBD: https://www.quickdatabasediagrams.com/
-- Link to schema: https://app.quickdatabasediagrams.com/#/d/KFtt6C
-- NOTE! If you have used non-SQL datatypes in your design, you will have to change these here.


CREATE TABLE `types` (
    `id` int  NOT NULL ,
    `name` varchar(50)  NOT NULL ,
    `created_at` datetime  NOT NULL ,
    `updated_at` datetime  NOT NULL ,
    PRIMARY KEY (
        `id`
    )
);

CREATE TABLE `companies` (
    `id` int  NOT NULL ,
    `name` varchar(50)  NOT NULL ,
    `type_id` int  NOT NULL ,
    `country` varchar(50)  NOT NULL ,
    `tva` varchar(50)  NOT NULL ,
    `created_at` datetime  NOT NULL ,
    `updated_at` datetime  NOT NULL ,
    PRIMARY KEY (
        `id`
    )
);

CREATE TABLE `invoices` (
    `id` int  NOT NULL ,
    `ref` varchar(50)  NOT NULL ,
    `id_company` int  NOT NULL ,
    `created_at` datetime  NOT NULL ,
    `updated_at` datetime  NOT NULL ,
    PRIMARY KEY (
        `id`
    )
);

CREATE TABLE `contacts` (
    `id` int  NOT NULL ,
    `name` varchar(50)  NOT NULL ,
    `company_id` int  NOT NULL ,
    `email` varchar(50)  NOT NULL ,
    `phone` varchar(50)  NOT NULL ,
    `created_at` datetime  NOT NULL ,
    `updated_at` datetime  NOT NULL ,
    PRIMARY KEY (
        `id`
    )
);

CREATE TABLE `users` (
    `id` int  NOT NULL ,
    `first_name` varchar(50)  NOT NULL ,
    `role_id` int  NOT NULL ,
    `last_name` varchar(50)  NOT NULL ,
    `email` varchar(50)  NOT NULL ,
    `password` varchar(50)  NOT NULL ,
    `created_at` datetime  NOT NULL ,
    `updated_at` datetime  NOT NULL ,
    PRIMARY KEY (
        `id`
    )
);

CREATE TABLE `roles` (
    `id` int  NOT NULL ,
    `name` varchar(50)  NOT NULL ,
    `created_at` datetime  NOT NULL ,
    `updated_at` datetime  NOT NULL ,
    PRIMARY KEY (
        `id`
    )
);

CREATE TABLE `roles_permission` (
    `id` int  NOT NULL ,
    `permission_id` int  NOT NULL ,
    `role_id` int  NOT NULL ,
    PRIMARY KEY (
        `id`
    )
);

CREATE TABLE `permissions` (
    `id` int  NOT NULL ,
    `created_at` datetime  NOT NULL ,
    `updated_at` datetime  NOT NULL ,
    PRIMARY KEY (
        `id`
    )
);

ALTER TABLE `companies` ADD CONSTRAINT `fk_companies_type_id` FOREIGN KEY(`type_id`)
REFERENCES `types` (`id`);

ALTER TABLE `invoices` ADD CONSTRAINT `fk_invoices_id_company` FOREIGN KEY(`id_company`)
REFERENCES `companies` (`id`);

ALTER TABLE `contacts` ADD CONSTRAINT `fk_contacts_company_id` FOREIGN KEY(`company_id`)
REFERENCES `companies` (`id`);

ALTER TABLE `users` ADD CONSTRAINT `fk_users_role_id` FOREIGN KEY(`role_id`)
REFERENCES `roles` (`id`);

ALTER TABLE `roles_permission` ADD CONSTRAINT `fk_roles_permission_permission_id` FOREIGN KEY(`permission_id`)
REFERENCES `permissions` (`id`);

ALTER TABLE `roles_permission` ADD CONSTRAINT `fk_roles_permission_role_id` FOREIGN KEY(`role_id`)
REFERENCES `roles` (`id`);

CREATE INDEX `idx_types_name`
ON `types` (`name`);

