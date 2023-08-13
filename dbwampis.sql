-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 01, 2023 at 01:16 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbwampis`
--
CREATE DATABASE IF NOT EXISTS `dbwampis` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `dbwampis`;

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE `characters` (
  `character_id` int(8) NOT NULL,
  `character_name` varchar(100) NOT NULL,
  `character_info` varchar(1000) NOT NULL,
  `character_image` varchar(100) NOT NULL,
  `character_image_location` varchar(200) NOT NULL,
  `character_thumbnail` varchar(100) NOT NULL,
  `character_thumbnail_location` varchar(200) NOT NULL,
  `character_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `characters`
--

INSERT INTO `characters` (`character_id`, `character_name`, `character_info`, `character_image`, `character_image_location`, `character_thumbnail`, `character_thumbnail_location`, `character_order`) VALUES
(14, 'Monkey D. Luffy', 'Bounty: 3B', 'luffyG4.jpg', 'uploads/img/char/luffyG4.jpg', 'luffyG4t.jpg', 'uploads/img/char/luffyG4t.jpg', 1),
(15, 'Roronoa Zoro', 'Bounty:', 'zoro.jpg', 'uploads/img/char/zoro.jpg', 'zorot.jpg', 'uploads/img/char/zorot.jpg', 2),
(16, 'Vinsmoke Sanji', 'Bounty:', 'sanji.jpg', 'uploads/img/char/sanji.jpg', 'sanjit1.jpg', 'uploads/img/char/sanjit1.jpg', 3),
(17, 'Nami', 'Bounty:', 'nami.jpg', 'uploads/img/char/nami.jpg', 'namit.jpg', 'uploads/img/char/namit.jpg', 4),
(19, 'God Usopp', 'Bounty:', 'usopp.jpg', 'uploads/img/char/usopp.jpg', 'usoppt.jpg', 'uploads/img/char/usoppt.jpg', 5),
(20, 'Chopper', 'Bounty: ', 'chopper.jpg', 'uploads/img/char/chopper.jpg', 'choppert.jpg', 'uploads/img/char/choppert.jpg', 6),
(21, 'Nico Robin', 'Bounty:', 'robin.jpg', 'uploads/img/char/robin.jpg', 'robint.jpg', 'uploads/img/char/robint.jpg', 7),
(22, 'Franky', 'Bounty:', 'Franky.jpg', 'uploads/img/char/Franky.jpg', 'Frankyt.jpg', 'uploads/img/char/Frankyt.jpg', 8),
(23, 'Jinbe', 'Bounty:', 'jinbe.jpg', 'uploads/img/char/jinbe.jpg', 'jinbet.jpg', 'uploads/img/char/jinbet.jpg', 9),
(24, 'Brook', 'Bounty:', 'brook.jpg', 'uploads/img/char/brook.jpg', 'brookt.jpg', 'uploads/img/char/brookt.jpg', 10);

-- --------------------------------------------------------

--
-- Table structure for table `episodes`
--

CREATE TABLE `episodes` (
  `episode_id` int(8) NOT NULL,
  `season_id` int(8) NOT NULL,
  `episode_number` int(8) NOT NULL,
  `episode_title` varchar(100) NOT NULL,
  `episode_title_jap` varchar(100) NOT NULL,
  `episode_video_name` varchar(100) NOT NULL,
  `episode_video_location` varchar(200) NOT NULL,
  `episode_video_thumbnail_name` varchar(100) NOT NULL,
  `episode_video_thumbnail_location` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `plot`
--

CREATE TABLE `plot` (
  `plot_id` int(8) NOT NULL,
  `plot_paragraph_top` varchar(2000) NOT NULL,
  `plot_paragraph_mid` varchar(5000) NOT NULL,
  `plot_image_caption` varchar(100) NOT NULL,
  `plot_image_location` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `plot`
--

INSERT INTO `plot` (`plot_id`, `plot_paragraph_top`, `plot_paragraph_mid`, `plot_image_caption`, `plot_image_location`) VALUES
(1, '', 'Tanjiro Kamado is a kind-hearted and intelligent boy who lives with his family in the mountains. He became his family\'s breadwinner after his father\'s death, making trips to the nearby village to sell charcoal. Everything changed when he came home one day to discover that his family was attacked and slaughtered by a demon. Tanjiro and his sister Nezuko were the sole survivors of the incident, with Nezuko being transformed into a demon, but still surprisingly showing signs of human emotion and thought. After an encounter with Giyū Tomioka, a demon slayer, Tanjiro is recruited by Giyū and sent to his retired master Sakonji Urokodaki for training to also become a demon slayer, beginning his quest to help his sister turn into human again and avenge the death of his family.', 'Tanjiro on his way to sell charcoal.', 'uploads/img/plot/default-plot-kny-tanjiro-charcoal.png'),
(2, '', 'After two years of strenuous training, Tanjiro takes part in a formidable exam and is one of the few survivors to pass, officially making him a member of the Demon Slayer Corps. He begins his work of hunting down and slaying demons alongside Nezuko, who has been hypnotized to bring no harm to humans and who occasionally helps him in battle. One of Tanjiro\'s assignments brings him to Asakusa where he encounters Muzan Kibutsuji, the progenitor of all demons and the one who murdered his family. He also meets Tamayo, a demon who is free of Muzan\'s control. Tamayo allies with Tanjiro and begins to develop a cure for Nezuko, though it will require Tanjiro to supply her with blood from the Twelve Kizuki, the most powerful demons under Muzan\'s command.', 'Tanjiro\'s training.', 'uploads/img/plot/default-plot-kny-tanjiro-training.png'),
(3, 'Continuing his missions, Tanjiro meets Zenitsu Agatsuma and Inosuke Hashibira, fellow survivors of the exam with whom he forms an unlikely team. The group soon face off against a member of the Kizuki, but are ultimately outmatched; they are rescued by the Corps and brought back to headquarters.', 'The group soon face off against a member of the Kizuki, but are ultimately outmatched; they are rescued by the Corps and brought back to headquarters. There, the Kamado siblings partake in a council between Kagaya Ubuyashiki, the leader of the Demon Slayer Corps, and the Hashira, the Corps\' most elite members, who do not believe that Nezuko should be allowed to live. However, Kagaya nonetheless manages to convince them to accept her. With this agreement, Tanjiro begins to work alongside the Hashira, where he encounters significantly more resistance from the demons. Alongside the Hashira and Corps members, Tanjiro takes part in numerous harrowing battles against the Kizuki, which they survive by narrow margins. During the fray, it is discovered that Nezuko is invulnerable to sunlight, a discovery that makes her the prime target of Muzan, who has long sought a way to overcome the sun and thus become the ultimate being.', 'Hashira Meeting.', 'uploads/img/plot/default-plot-kny-hashira-meeting.png'),
(4, 'Kagaya forecasts Muzan\'s intentions and enacts a strict training regiment of the entire Corps to prepare for the upcoming battle. With the blood samples obtained from the Kizuki, Tamayo develops a serum to cure Nezuko, who is kept isolated far from the battle as she recovers. Muzan soon appears before Kagaya, who triggers a suicide attack to stagger him.', 'The Hashira and Tanjiro ambush Muzan, but he traps them all within Infinity Castle, an endless labyrinth which houses the Kizuki. Tamayo restrains Muzan with a poison she concocted, leaving him vulnerable for attack. To reach him, the Corps defeat the remaining Kizuki members, though they take heavy losses of their own. Muzan slays Tamayo by absorbing her but is forced above ground by the Corps. A desperate battle of attrition ensues as the remainder of the Demon Slayer Corps fight against Muzan until the morning sun can kill him. Aided by Tamayo\'s poison, the Corps succeed, though many are killed, including most of the Hashira, while Tanjiro is mortally wounded. Helpless against the sun, Muzan gives his remaining power to a dying Tanjiro and makes him a demon in a last ditch effort to have his species survive. Tanjiro begins to attack the remaining Corp members, but through the efforts of his allies and Nezuko, who has been fully restored to her human self, he is turned back into a human.', 'Infinity Castle.', 'uploads/img/plot/default-plot-kny-infinity-castle.png'),
(5, 'In the aftermath of the battle, the Corps are disbanded as the death of Muzan has effectively vanquished all other demons. Tanjiro and Nezuko return to their family home, accompanied by Zenitsu and Inosuke.', 'In a modern-day epilogue, the descendants and reincarnations of the Corps members enjoy a peaceful life free of demons.', '', ''),
(6, '', 'Tanjiro Kamado is a kind-hearted and intelligent boy who lives with his family in the mountains. He became his family’s breadwinner after his father’s death, making trips to the nearby village to sell charcoal. Everything changed when he came home one day to discover that his family was attacked and slaughtered by a demon. Tanjiro and his sister Nezuko were the sole survivors of the incident, with Nezuko being transformed into a demon, but still surprisingly showing signs of human emotion and thought. After an encounter with Giyū Tomioka, a demon slayer, Tanjiro is recruited by Giyū and sent to his retired master Sakonji Urokodaki for training to also become a demon slayer, beginning his quest to help his sister turn into human again and avenge the death of his family.', 'Tanjiro on his way to sell charcoal.', 'uploads/img/plot/kny-tanjiro-charcoal.png'),
(7, '', 'After two years of strenuous training, Tanjiro takes part in a formidable exam and is one of the few survivors to pass, officially making him a member of the Demon Slayer Corps. He begins his work of hunting down and slaying demons alongside Nezuko, who has been hypnotized to bring no harm to humans and who occasionally helps him in battle. One of Tanjiro’s assignments brings him to Asakusa where he encounters Muzan Kibutsuji, the progenitor of all demons and the one who murdered his family. He also meets Tamayo, a demon who is free of Muzan’s control. Tamayo allies with Tanjiro and begins to develop a cure for Nezuko, though it will require Tanjiro to supply her with blood from the Twelve Kizuki, the most powerful demons under Muzan’s command.', 'Tanjiro’s training.', 'uploads/img/plot/kny-tanjiro-training.png'),
(8, 'Continuing his missions, Tanjiro meets Zenitsu Agatsuma and Inosuke Hashibira, fellow survivors of the exam with whom he forms an unlikely team. The group soon face off against a member of the Kizuki, but are ultimately outmatched; they are rescued by the Corps and brought back to headquarters.', 'The group soon face off against a member of the Kizuki, but are ultimately outmatched; they are rescued by the Corps and brought back to headquarters. There, the Kamado siblings partake in a council between Kagaya Ubuyashiki, the leader of the Demon Slayer Corps, and the Hashira, the Corps’ most elite members, who do not believe that Nezuko should be allowed to live. However, Kagaya nonetheless manages to convince them to accept her. With this agreement, Tanjiro begins to work alongside the Hashira, where he encounters significantly more resistance from the demons. Alongside the Hashira and Corps members, Tanjiro takes part in numerous harrowing battles against the Kizuki, which they survive by narrow margins. During the fray, it is discovered that Nezuko is invulnerable to sunlight, a discovery that makes her the prime target of Muzan, who has long sought a way to overcome the sun and thus become the ultimate being.', 'Hashira Meeting.', 'uploads/img/plot/kny-hashira-meeting.png'),
(9, 'Kagaya forecasts Muzan’s intentions and enacts a strict training regiment of the entire Corps to prepare for the upcoming battle. With the blood samples obtained from the Kizuki, Tamayo develops a serum to cure Nezuko, who is kept isolated far from the battle as she recovers. Muzan soon appears before Kagaya, who triggers a suicide attack to stagger him.', 'The Hashira and Tanjiro ambush Muzan, but he traps them all within Infinity Castle, an endless labyrinth which houses the Kizuki. Tamayo restrains Muzan with a poison she concocted, leaving him vulnerable for attack. To reach him, the Corps defeat the remaining Kizuki members, though they take heavy losses of their own. Muzan slays Tamayo by absorbing her but is forced above ground by the Corps. A desperate battle of attrition ensues as the remainder of the Demon Slayer Corps fight against Muzan until the morning sun can kill him. Aided by Tamayo’s poison, the Corps succeed, though many are killed, including most of the Hashira, while Tanjiro is mortally wounded. Helpless against the sun, Muzan gives his remaining power to a dying Tanjiro and makes him a demon in a last ditch effort to have his species survive. Tanjiro begins to attack the remaining Corp members, but through the efforts of his allies and Nezuko, who has been fully restored to her human self, he is turned back into a human.', 'Infinity Castle.', 'uploads/img/plot/kny-infinity-castle.png'),
(10, 'In the aftermath of the battle, the Corps are disbanded as the death of Muzan has effectively vanquished all other demons. Tanjiro and Nezuko return to their family home, accompanied by Zenitsu and Inosuke.', 'In a modern-day epilogue, the descendants and reincarnations of the Corps members enjoy a peaceful life free of demons.', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `season`
--

CREATE TABLE `season` (
  `season_id` int(8) NOT NULL,
  `season_name` varchar(100) NOT NULL,
  `season_location` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `season`
--

INSERT INTO `season` (`season_id`, `season_name`, `season_location`) VALUES
(57, 'Luffy', 'uploads/vid/Luffy'),
(58, 'Zoro and Sanji', 'uploads/vid/Zoro and Sanji');

-- --------------------------------------------------------

--
-- Table structure for table `story`
--

CREATE TABLE `story` (
  `story_id` int(11) NOT NULL,
  `story_paragraph` varchar(5000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `story`
--

INSERT INTO `story` (`story_id`, `story_paragraph`) VALUES
(1, 'Ever since the death of his father, the burden of supporting the family has fallen upon Tanjirou Kamado\'s shoulders. Though living impoverished on a remote mountain, the Kamado family are able to enjoy a relatively peaceful and happy life. One day, Tanjirou decides to go down to the local village to make a little money selling charcoal. On his way back, night falls, forcing Tanjirou to take shelter in the house of a strange man, who warns him of the existence of flesh-eating demons that lurk in the woods at night.\r\n<br/><br/>When he finally arrives back home the next day, he is met with a horrifying sight—his whole family has been slaughtered. Worse still, the sole survivor is his sister Nezuko, who has been turned into a bloodthirsty demon. Consumed by rage and hatred, Tanjirou swears to avenge his family and stay by his only remaining sibling.\r\n<br/><br/>Alongside the mysterious group calling themselves the Demon Slayer Corps, Tanjirou will do whatever it takes to slay the demons and protect the remnants of his beloved sister\'s humanity.'),
(2, 'One Piece is a Japanese manga series written and illustrated by Eiichiro Oda. It has been serialized inWeekly Shōnen Jump since August 4, 1997; the individual chapters are being published in tankōbon volumes by Shueisha, with the first released on December 24, 1997, and the 73rd volume released as of March 2014. One Piece follows the adventures of Monkey D. Luffy, a young boy whose body gains the properties of rubber after unintentionally eating a Devil Fruit, and his diverse crew of pirates, named the Straw Hat Pirates. Luffy explores the ocean in search of the world’s ultimate treasure known as One Piece in order to become the next Pirate King.<br/><br/>The chapters have been adapted into an original video animation (OVA) produced by Production I.G in 1998, and an anime series produced by Toei Animation, which began broadcasting in Japan in 1999. Since then, the still ongoing series has aired over 600 episodes. Additionally, Toei has developed eleven animated feature films, two OVA’s, and five television specials. Several companies have developed various types of merchandising such as a trading card game, and a large number of video games.<br/><br/>The manga series was licensed for an English language release in North America by Viz Media, in the United Kingdom by Gollancz Manga, and in Australia and New Zealand by Madman Entertainment. The anime series has been licensed by Funimation Entertainment for an English-language release in North America, although the series was originally licensed and distributed by 4Kids Entertainment.\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `trailer`
--

CREATE TABLE `trailer` (
  `trailer_id` int(8) NOT NULL,
  `trailer_text` varchar(1000) NOT NULL,
  `trailer_video_location` varchar(200) NOT NULL,
  `trailer_video_thumbnail_location` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `trailer`
--

INSERT INTO `trailer` (`trailer_id`, `trailer_text`, `trailer_video_location`, `trailer_video_thumbnail_location`) VALUES
(1, 'Take a Sneak Peek', 'uploads/trailer/default-trailer-kny-trailer.mp4', 'uploads/trailer/default-trailer-kny-trailer-thumbnail.png'),
(2, 'Take a Sneak Peek', 'uploads/trailer/kny-trailer.mp4', 'uploads/trailer/kny-trailer-thumbnail.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(5) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email_address` varchar(50) NOT NULL,
  `birthday` date NOT NULL,
  `gender` varchar(6) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `account_type` varchar(5) NOT NULL DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email_address`, `birthday`, `gender`, `username`, `password`, `account_type`) VALUES
(5, 'web', 'prog', 'webprog@email.com', '2022-12-25', 'Male', 'webp', 'webp', 'Admin'),
(11, 'test', 'user', 'testuser@email.com', '2022-07-06', 'Male', '1', '1', 'User');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
  ADD PRIMARY KEY (`character_id`);

--
-- Indexes for table `episodes`
--
ALTER TABLE `episodes`
  ADD PRIMARY KEY (`episode_id`),
  ADD KEY `epseason` (`season_id`);

--
-- Indexes for table `plot`
--
ALTER TABLE `plot`
  ADD PRIMARY KEY (`plot_id`);

--
-- Indexes for table `season`
--
ALTER TABLE `season`
  ADD PRIMARY KEY (`season_id`);

--
-- Indexes for table `story`
--
ALTER TABLE `story`
  ADD PRIMARY KEY (`story_id`);

--
-- Indexes for table `trailer`
--
ALTER TABLE `trailer`
  ADD PRIMARY KEY (`trailer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `characters`
--
ALTER TABLE `characters`
  MODIFY `character_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `episodes`
--
ALTER TABLE `episodes`
  MODIFY `episode_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `plot`
--
ALTER TABLE `plot`
  MODIFY `plot_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `season`
--
ALTER TABLE `season`
  MODIFY `season_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `trailer`
--
ALTER TABLE `trailer`
  MODIFY `trailer_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `episodes`
--
ALTER TABLE `episodes`
  ADD CONSTRAINT `episodeSeason` FOREIGN KEY (`season_id`) REFERENCES `season` (`season_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
