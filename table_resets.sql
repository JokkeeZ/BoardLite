UPDATE `data` SET message_count = 0;
TRUNCATE threads;
TRUNCATE replies;
TRUNCATE users;
TRUNCATE boards;

ALTER TABLE `threads` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `replies` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `users` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `boards` MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

INSERT INTO `boards` (`id`, `name`, `description`, `prefix`, `tag`) VALUES
(1, 'Random', 'NSFW board with random threads.', 'b', 'NSFW'),
(2, 'Music', 'All discussions about music.', 'mu', 'SFW'),
(3, 'Feedback', 'Give us your feedback & bug reports!', 'fb', 'SFW'),
(4, 'Video games', 'PC Master Race', 'v', 'SFW');
