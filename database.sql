SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `scam` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `scam`;

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `gender` int(11) DEFAULT 0 COMMENT '0:Nữ-1:Nam',
  `birthday` date DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `user_type` int(11) DEFAULT 0 COMMENT '0: CTV-1: Admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `account` (`id`, `username`, `password`, `name`, `gender`, `birthday`, `address`, `role`, `user_type`, `created_at`) VALUES
(1, 'admin', '4297f44b13955235245b2497399d7a93', 'Admin', 1, '2000-01-01', 'Hà Nội', 'Admin', 1, '2022-06-04 00:18:56'),
(13, NULL, NULL, 'Name A', 0, '2001-12-01', 'Bắc Ninh', 'CTV', 0, '2022-06-06 14:52:23'),
(14, NULL, NULL, 'Name B', 1, '2000-11-30', 'Hải Phòng', 'CTV', 0, '2022-06-06 14:52:06'),
(15, NULL, NULL, 'Name C', 0, '2001-01-01', 'Hà Giang', 'CTV', 0, '2022-06-06 14:51:56'),
(16, NULL, NULL, 'Name D', 1, '2001-11-30', 'Hà Nội', 'CTV', 0, '2022-06-06 14:51:41'),
(17, NULL, NULL, 'Name E', 0, '2002-11-30', 'Hà Nội', 'CTV', 0, '2022-06-06 14:52:39'),
(18, NULL, NULL, 'Name F', 0, '2003-11-30', 'Lạng Sơn', 'CTV', 0, '2022-06-06 14:52:50'),
(19, NULL, NULL, 'Name G', 0, '2000-05-03', 'Hà Nội', 'CTV', 0, '2022-06-06 14:52:59'),
(20, NULL, NULL, 'Name H', 0, '2003-01-02', 'Hà Giang', 'CTV', 0, '2022-06-06 14:53:16'),
(21, NULL, NULL, 'Name A', 0, '2002-01-24', 'Bắc Ninh', 'CTV', 0, '2022-06-06 14:53:35');

CREATE TABLE `evidence` (
  `id` int(11) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `scammer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `evidence` (`id`, `url`, `scammer_id`) VALUES
(2, 'https://cafefcdn.com/thumb_w/650/203337114487263232/2022/3/3/photo1646280815645-1646280816151764748403.jpg', 2),
(7, 'https://cafefcdn.com/thumb_w/650/203337114487263232/2022/3/3/photo1646280815645-1646280816151764748403.jpg', 5),
(8, 'https://daohieu.com/wp-content/uploads/2020/05/meo-con.jpg', 5),
(9, 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4d/Cat_November_2010-1a.jpg/300px-Cat_November_2010-1a.jpg', 6),
(10, 'https://cafefcdn.com/thumb_w/650/203337114487263232/2022/3/3/photo1646280815645-1646280816151764748403.jpg', 6),
(11, 'https://media.vneconomy.vn/w800/images/upload/2022/04/15/lua-dao.jpeg', 7),
(12, '', 7);

CREATE TABLE `scammer` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(13) DEFAULT NULL,
  `card_number` varchar(30) DEFAULT NULL,
  `bank` varchar(255) DEFAULT NULL,
  `content` varchar(1000) DEFAULT NULL,
  `name_auth` varchar(255) DEFAULT NULL,
  `phone_auth` varchar(13) DEFAULT NULL,
  `victim` int(11) DEFAULT NULL COMMENT '0:Tôi-1:Người khác',
  `approved` int(11) DEFAULT 0 COMMENT '0:Chưa-1:Đã duyệt',
  `approved_by` int(11) DEFAULT NULL COMMENT 'Người duyệt',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `scammer` (`id`, `name`, `phone`, `card_number`, `bank`, `content`, `name_auth`, `phone_auth`, `victim`, `approved`, `approved_by`, `created_at`) VALUES
(2, 'Nguyễn Văn A', '0912312312', '132123132', 'mb bank', 'lừa đảo', 'Nguyễn Văn B', '0912312312', 0, 1, 1, '2022-06-06 22:08:47'),
(5, 'Mồn lèo', '0924242424', '123123123', 'Mèo', 'Rất là láo', 'Nguyễn Thị Hải', '0912312312', 0, 1, 1, '2022-06-04 19:33:19'),
(6, 'Mồn lèo', '0964123123', '123123123', 'tp bank', 'Láo', 'Nguyễn Văn A', '0964123123', 0, 1, 1, '2022-06-05 00:49:29'),
(7, 'Nguyễn Thị B', '0934222678', '99999999992', 'mb bank', 'Test', 'Nguyễn Thị C', '0946261145', 0, 0, NULL, '2022-06-06 22:10:43');


ALTER TABLE `account`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `evidence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scammer_id` (`scammer_id`);

ALTER TABLE `scammer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `approved_by` (`approved_by`);


ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

ALTER TABLE `evidence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

ALTER TABLE `scammer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;


ALTER TABLE `evidence`
  ADD CONSTRAINT `evidence_ibfk_1` FOREIGN KEY (`scammer_id`) REFERENCES `scammer` (`id`);

ALTER TABLE `scammer`
  ADD CONSTRAINT `scammer_ibfk_1` FOREIGN KEY (`approved_by`) REFERENCES `account` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
