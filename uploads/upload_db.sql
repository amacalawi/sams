INSERT INTO `newsams`.`modules` (`id`, `name`, `slug`, `description`, `created_by`, `updated_by`, `removed_by`, `created_at`, `updated_at`, `removed_at`, `modules_start`) VALUES ('0', 'All Schedules', 'schedules', 'All Schedules', NULL, NULL, NULL, '2016-07-17 00:10:16', '0000-00-00 00:00:00', NULL, '0'), ('0', 'Preset Messages', 'schedules/preset-messages', 'Preset Messages', NULL, NULL, NULL, '2016-07-23 15:37:31', '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `newsams`.`modules` (`id`, `name`, `slug`, `description`, `created_by`, `updated_by`, `removed_by`, `created_at`, `updated_at`, `removed_at`, `modules_start`) VALUES (NULL, 'Schoolyears', 'schoolyears', 'Schoolyears', NULL, NULL, NULL, '2016-07-23 15:37:31', '0000-00-00 00:00:00', NULL, '0');

INSERT INTO `newsams`.`modules` (`id`, `name`, `slug`, `description`, `created_by`, `updated_by`, `removed_by`, `created_at`, `updated_at`, `removed_at`, `modules_start`) VALUES ('0', 'Daily Time Record', 'monitor/dtr', 'Daily Time Record', NULL, NULL, NULL, '2016-07-17 00:10:16', '0000-00-00 00:00:00', NULL, '0'), ('0', 'Splash Page', 'monitor/splash', 'Splash Page', NULL, NULL, NULL, '2016-07-23 15:37:31', '0000-00-00 00:00:00', NULL, '0'), ('0', 'Announcement', 'monitor/announcement', 'Announcement', NULL, NULL, NULL, '2016-07-17 00:10:16', '0000-00-00 00:00:00', NULL, '0'), ('0', 'Gates', 'monitor/gates', 'Gates', NULL, NULL, NULL, '2016-07-23 15:37:31', '0000-00-00 00:00:00', NULL, '0'), ('0', 'Devices', 'monitor/devices', 'Devices', NULL, NULL, NULL, '2016-07-23 15:37:31', '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `newsams`.`modules` (`id`, `name`, `slug`, `description`, `created_by`, `updated_by`, `removed_by`, `created_at`, `updated_at`, `removed_at`, `modules_start`) VALUES ('0', 'All Users', 'users', 'All Users', NULL, NULL, NULL, '2016-07-17 00:10:16', '0000-00-00 00:00:00', NULL, '0'), ('0', 'Export Users', 'users/export', 'Export Users', NULL, NULL, NULL, '2016-07-23 15:37:31', '0000-00-00 00:00:00', NULL, '0'), ('0', 'Import Users', 'users/import', 'Import Users', NULL, NULL, NULL, '2016-07-23 15:37:31', '0000-00-00 00:00:00', NULL, '0');
INSERT INTO `newsams`.`modules` (`id`, `name`, `slug`, `description`, `created_by`, `updated_by`, `removed_by`, `created_at`, `updated_at`, `removed_at`, `modules_start`) VALUES (NULL, 'Modules', 'modules', 'Modules', NULL, NULL, NULL, '2016-07-23 15:37:31', '0000-00-00 00:00:00', NULL, '0');

UPDATE `newsams`.`privileges_levels` SET `modules` = '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76', `removed_at` = NULL WHERE `privileges_levels`.`id` = 1;