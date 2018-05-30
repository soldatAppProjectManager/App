INSERT INTO `ticket_status` (`id`, `label`, `color`, `code`) VALUES
  (1, 'Ouvert', 'red', '1'),
  (2, 'Affecté', 'blue', '2'),
  (3, 'Diagnostique', 'blue', '3'),
  (4, 'Intervention', '.', '4'),
  (5, 'Remise en service', '.', '5'),
  (6, 'Clôture', '.', '6');


UPDATE `user` SET `password` = '$2y$13$lbt1s.A6tNJ.7a2WQLi24O0WayN4QzrTsvlyvQK0xFd2Gzpid4jyy' WHERE `user`.`login` = 'amajri';
