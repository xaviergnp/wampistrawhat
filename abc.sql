-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2022 at 10:24 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbknyfansite`
--

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
  `character_thumbnail_location` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `characters`
--

INSERT INTO `characters` (`character_id`, `character_name`, `character_info`, `character_image`, `character_image_location`, `character_thumbnail`, `character_thumbnail_location`) VALUES
(1, 'Tanjiro Kamado', 'A kindhearted boy who loves his family. Tanjiro joins the Demon Slayer Corps in order to turn his sister Nezuko, who has become a demon, back into a human, as well as to avenge the death of his family by hunting down the demon who killed them. He has an acute sense of smell which enables him to identify the weak spots in demons or other opponents he battles.', 'kny-char01-tanjiro-kamado.jpg', 'uploads/img/char/kny-char01-tanjiro-kamado.jpg', 'kny-char01-tanjiro-kamado1.jpg', 'uploads/img/char/kny-char01-tanjiro-kamado1.jpg'),
(3, 'Nezuko Kamado', 'Tanjiro’s younger sister. After being attacked by a demon whose blood entered her body through an open wound, she became a demon herself. Even after becoming a demon, she protects Tanjiro and other humans. Before she was transformed, she was a gentle girl who loved her family.', 'kny-char02-nezuko-kamado.jpg', 'uploads/img/char/kny-char02-nezuko-kamado.jpg', 'kny-char02-nezuko-kamado1.jpg', 'uploads/img/char/kny-char02-nezuko-kamado1.jpg'),
(7, 'Zenitsu Agatsuma', 'He joins the Demon Slayer Corps at the same time as Tanjiro. He has an acute sense of hearing, and can identify the sounds made by other people or demons. Zenitsu has zero confidence in himself and often talks self-deprecatingly. However, when he is scared to death, he falls asleep and his personality changes to a sharp-minded person.', 'kny-char03-zenitsu-agatsuma.jpg', 'uploads/img/char/kny-char03-zenitsu-agatsuma.jpg', 'kny-char03-zenitsu-agatsuma1.jpg', 'uploads/img/char/kny-char03-zenitsu-agatsuma1.jpg'),
(8, 'Inosuke Hashibira', 'He joins the Demon Slayer Corps at the same time as Tanjiro. Inosuke is a very aggressive boy, and is always seen wearing a wild boar mask. Because he grew up in the mountains, he has an acute sense of touch which enables him to locate anything that he cannot see in his immediate surroundings.', 'kny-char04-inosuke-hashibira.jpg', 'uploads/img/char/kny-char04-inosuke-hashibira.jpg', 'kny-char10-black-haired-guide1.jpg', 'uploads/img/char/kny-char10-black-haired-guide1.jpg'),
(10, 'j', 'jinfo', 'kny-char07-sabito1.jpg', 'uploads/img/char/kny-char07-sabito1.jpg', 'kny-s2-ep08-thumbnail.png', 'uploads/img/char/kny-s2-ep08-thumbnail.png'),
(12, 'a', 'cat’sbad’’bad', 'kny-char01-tanjiro-kamado.jpg', 'uploads/img/char/kny-char01-tanjiro-kamado.jpg', 'kny-char06-sakonji-urokodaki1.jpg', 'uploads/img/char/kny-char06-sakonji-urokodaki1.jpg');

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

--
-- Dumping data for table `episodes`
--

INSERT INTO `episodes` (`episode_id`, `season_id`, `episode_number`, `episode_title`, `episode_title_jap`, `episode_video_name`, `episode_video_location`, `episode_video_thumbnail_name`, `episode_video_thumbnail_location`) VALUES
(9, 54, 2, 'eaj2', 'd2', 'Kimetsu_no_Yaiba_S2_Yuukaku-hen_Episode_03.mp4', 'uploads/vid/gg\'s/Kimetsu_no_Yaiba_S2_Yuukaku-hen_Episode_03.mp4', 'kny-s2-ep02-thumbnail.png', 'uploads/vid/gg\'s/ep_thumb/kny-s2-ep02-thumbnail.png'),
(10, 51, 2, '2', '2', 'Kimetsu_no_Yaiba_S2_Yuukaku-hen_Episode_01.mp4', 'uploads/vid/kk/Kimetsu_no_Yaiba_S2_Yuukaku-hen_Episode_01.mp4', 'kny-s2-ep01-thumbnail.png', 'uploads/vid/kk/ep_thumb/kny-s2-ep01-thumbnail.png');

-- --------------------------------------------------------

--
-- Table structure for table `intro`
--

CREATE TABLE `intro` (
  `intro_id` int(8) NOT NULL,
  `intro_paragraph` varchar(5000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `intro`
--

INSERT INTO `intro` (`intro_id`, `intro_paragraph`) VALUES
(1, 'Ever since the death of his father, the burden of supporting the family has fallen upon Tanjirou Kamado\'s shoulders. Though living impoverished on a remote mountain, the Kamado family are able to enjoy a relatively peaceful and happy life. One day, Tanjirou decides to go down to the local village to make a little money selling charcoal. On his way back, night falls, forcing Tanjirou to take shelter in the house of a strange man, who warns him of the existence of flesh-eating demons that lurk in the woods at night.\r\n<br/><br/>When he finally arrives back home the next day, he is met with a horrifying sight—his whole family has been slaughtered. Worse still, the sole survivor is his sister Nezuko, who has been turned into a bloodthirsty demon. Consumed by rage and hatred, Tanjirou swears to avenge his family and stay by his only remaining sibling.\r\n<br/><br/>Alongside the mysterious group calling themselves the Demon Slayer Corps, Tanjirou will do whatever it takes to slay the demons and protect the remnants of his beloved sister\'s humanity.'),
(2, 'Ever since the death of his father, the burden of supporting the family has fallen upon Tanjirou Kamado’s shoulders. Though living impoverished on a remote mountain, the Kamado family are able to enjoy a relatively peaceful and happy life. One day, Tanjirou decides to go down to the local village to make a little money selling charcoal. On his way back, night falls, forcing Tanjirou to take shelter in the house of a strange man, who warns him of the existence of flesh-eating demons that lurk in the woods at night.\r\n<br/><br/>When he finally arrives back home the next day, he is met with a horrifying sight—his whole family has been slaughtered. Worse still, the sole survivor is his sister Nezuko, who has been turned into a bloodthirsty demon. Consumed by rage and hatred, Tanjirou swears to avenge his family and stay by his only remaining sibling.\r\n<br/><br/>Alongside the mysterious group calling themselves the Demon Slayer Corps, Tanjirou will do whatever it takes to slay the demons and protect the remnants of his beloved sister’s humanity.');

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
(51, 'kk', 'uploads/vid/kk'),
(54, 'gg\'s', 'uploads/vid/gg\'s');

-- --------------------------------------------------------

--
-- Table structure for table `story`
--

CREATE TABLE `story` (
  `story_id` int(8) NOT NULL,
  `story_paragraph_top` varchar(2000) NOT NULL,
  `story_paragraph_mid` varchar(5000) NOT NULL,
  `story_image_caption` varchar(100) NOT NULL,
  `story_image_location` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `story`
--

INSERT INTO `story` (`story_id`, `story_paragraph_top`, `story_paragraph_mid`, `story_image_caption`, `story_image_location`) VALUES
(1, 'The story takes place in Taishō-era Japan, where a secret society, known as the Demon Slayer Corps, has been waging a secret war against demons for centuries.', 'The demons are former humans who were turned into demons by Muzan Kibutsuji injecting them with his own blood, and they feed on humans and possess supernatural abilities such as super strength, powers that demons can obtain called \"Blood Demon Art \", and regeneration. Demons can only be killed if they\'re decapitated with weapons crafted from an alloy known as Sun Steel, injected with poison extracted from Wisteria flowers, or exposed to sunlight. The Demon Slayers, on the other hand, are entirely human; however, they employ special breathing techniques, known as \"Breathing Styles\", which grant them superhuman strength and increased resistance. This helps increase their chances fighting against demons.', 'Taisho District.', 'uploads/img/story/default-story-kny-building.png'),
(2, 'The story takes place in Taishō-era Japan, where a secret society, known as the Demon Slayer Corps, has been waging a secret war against demons for centuries.', 'The demons are former humans who were turned into demons by Muzan Kibutsuji injecting them with his own blood, and they feed on humans and possess supernatural abilities such as super strength, powers that demons can obtain called \"Blood Demon Art \", and regeneration. Demons can only be killed if they’re decapitated with weapons crafted from an alloy known as Sun Steel, injected with poison extracted from Wisteria flowers, or exposed to sunlight. The Demon Slayers, on the other hand, are entirely human; however, they employ special breathing techniques, known as \"Breathing Styles\", which grant them superhuman strength and increased resistance. This helps increase their chances fighting against demons.', 'Taisho District.', 'uploads/img/story/kny-building.png');

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
(3, 'samp12', 'samps1231', 'samp32131@email.com', '2022-07-09', 'Female', 'sampsamp0122', 'incorrect0132131', 'Admin'),
(5, 'smpss', 'smpsss', 'smps@email.com', '2022-07-07', 'Male', 'smps1355', 'smps13', 'Admin'),
(9, 'ken', 'taguiam', 'kentaguiam@yahoo.com', '2022-07-22', 'Male', 'ken01', 'xrm1tm2', 'Admin'),
(10, 'sssssssssssssssss', 'sssssssssssssssssss', 'ssss@email.com', '2022-07-08', 'Male', 'sampsamp04', 'sampsamp04', 'Admin'),
(11, 'smp', 'smps', 'smps@email.com', '2022-07-06', 'Male', 'smpsmp01', 'smpsmp01', 'User'),
(13, 'kkkkk', 'wwww', 'awdawd@yafkalskdaksd.com', '2022-07-04', 'Male', 'k', 'k', 'User');

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
-- Indexes for table `intro`
--
ALTER TABLE `intro`
  ADD PRIMARY KEY (`intro_id`);

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
  MODIFY `character_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `episodes`
--
ALTER TABLE `episodes`
  MODIFY `episode_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `intro`
--
ALTER TABLE `intro`
  MODIFY `intro_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `plot`
--
ALTER TABLE `plot`
  MODIFY `plot_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `season`
--
ALTER TABLE `season`
  MODIFY `season_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `story`
--
ALTER TABLE `story`
  MODIFY `story_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
