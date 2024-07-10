-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: mysql-db:3306
-- Generation Time: Nov 07, 2023 at 07:52 PM
-- Server version: 8.2.0
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Create the database
DROP DATABASE IF EXISTS book_shop_db;
CREATE DATABASE IF NOT EXISTS book_shop_db;

-- Use the database
USE book_shop_db;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `book_shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
CREATE TABLE `users` (
  `id` int NOT NULL PRIMARY KEY,
  `username` varchar(50) NOT NULL UNIQUE,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_verified` int NOT NULL,
  `verif_token` varchar(255),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_valid_until` timestamp DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--
INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `email`, `password`, `is_verified`, `verif_token`, `reset_token`, `reset_valid_until`, `created_at`) VALUES (1, 'federic0', 'Federico', 'Casu', 'f.casu1@studenti.unipi.it', '$2y$10$lAoR6kqC5LKP6K6szeHe8Ogjs.GDktierrw5Zu6ubCk59qAUxDHaS', 1, NULL, NULL, NULL, '2023-11-07 19:51:03');
INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `email`, `password`, `is_verified`, `verif_token`, `reset_token`, `reset_valid_until`, `created_at`) VALUES (2, 'a', 'a', 'a', 'molto.falso@gmail.com', '$2y$10$Gq73GeIRu9439C0IxaDUXORPMPqB/tZEg9Lb9u6Ewn8aFLhZs/JCO', 1, NULL, 'pippo', '2025-11-07 19:51:03',  '2023-11-07 19:51:03');

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


--
-- Table structure for table `wrong_login`
--
CREATE TABLE `wrong_login` (
  `id` int NOT NULL,
  `user_id` int,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for table `wrong_login`
--
ALTER TABLE `wrong_login`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `wrong_login`
--
ALTER TABLE `wrong_login`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Foreign keys for table `wrong_login`
--
ALTER TABLE `wrong_login`
  ADD CONSTRAINT `FK_wrong_login_id`
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);


--
-- Table structure for table `logged_users`
--
CREATE TABLE `logged_users` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `valid_until` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for table `logged_users`
--
ALTER TABLE `logged_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `logged_users`
--
ALTER TABLE `logged_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Foreign keys for table `logged_users`
--
ALTER TABLE `logged_users`
  ADD CONSTRAINT `FK_logged_users_username`
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`);


--
-- Table structure for table `anonymous_users`
--
CREATE TABLE `anonymous_users` (
  `id` bigint NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `valid_until` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for table `anonymous_user`
--
ALTER TABLE `anonymous_users`
  ADD PRIMARY KEY (`id`);


--
-- Table structure for table `log_messages`
--
CREATE TABLE `log_messages` (
    `id` INT NOT NULL,
    `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `level` INT,
    `message` TEXT,
    `context` TEXT
);

--
-- Indexes for table `log_messages`
--
ALTER TABLE `log_messages`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `log_messages`
--
ALTER TABLE `log_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;


--
-- Table structure for table `books`
--
CREATE TABLE `books` (
  `isbn` varchar(10) NOT NULL PRIMARY KEY,
  `book_title` varchar(255) NOT NULL,
  `book_author` varchar(50) NOT NULL,
  `year_of_publication` varchar(4) NOT NULL,
  `publisher` varchar(50) NOT NULL,
  `image_url_S` varchar(255) NOT NULL,
  `image_url_M` varchar(255) NOT NULL,
  `image_url_L` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Dumping data for table `books`
--
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0345417623", "Timeline", "Michael Crichton", "2000", "Ballantine Books", "https://images.amazon.com/images/P/0345417623.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0345417623.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0345417623.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0684823802", "Out of the silent planet", "C.S. Lewis", "1996", "Scribner", "https://images.amazon.com/images/P/0684823802.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0684823802.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0684823802.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0375759778", "Prague : A Novel", "ARTHUR PHILLIPS", "2003", "Random House Trade Paperbacks", "https://images.amazon.com/images/P/0375759778.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0375759778.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0375759778.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0425163091", "Chocolate Jesus", "Stephan Jaramillo", "1998", "Berkley Publishing Group", "https://images.amazon.com/images/P/0425163091.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0425163091.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0425163091.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("3442446937", "Tage der Unschuld.", "Richard North Patterson", "2000", "Goldmann", "https://images.amazon.com/images/P/3442446937.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/3442446937.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/3442446937.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0375406328", "Lying Awake", "Mark Salzman", "2000", "Alfred A. Knopf", "https://images.amazon.com/images/P/0375406328.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0375406328.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0375406328.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0446310786", "To Kill a Mockingbird", "Harper Lee", "1988", "Little Brown &amp; Company", "https://images.amazon.com/images/P/0446310786.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0446310786.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0446310786.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0449005615", "Seabiscuit: An American Legend", "LAURA HILLENBRAND", "2002", "Ballantine Books", "https://images.amazon.com/images/P/0449005615.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0449005615.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0449005615.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("038078243X", "Miss Zukas and the Raven's Dance", "Jo Dereske", "1996", "Avon", "https://images.amazon.com/images/P/038078243X.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/038078243X.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/038078243X.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("067176537X", "The Therapeutic Touch: How to Use Your Hands to Help or to Heal", "Dolores Krieger", "1979", "Fireside", "https://images.amazon.com/images/P/067176537X.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/067176537X.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/067176537X.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0061099686", "Downtown", "Anne Rivers Siddons", "1995", "HarperTorch", "https://images.amazon.com/images/P/0061099686.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0061099686.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0061099686.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0553582909", "Icebound", "Dean R. Koontz", "2000", "Bantam Books", "https://images.amazon.com/images/P/0553582909.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0553582909.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0553582909.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0671888587", "I'll Be Seeing You", "Mary Higgins Clark", "1994", "Pocket", "https://images.amazon.com/images/P/0671888587.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0671888587.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0671888587.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0553582747", "From the Corner of His Eye", "Dean Koontz", "2001", "Bantam Books", "https://images.amazon.com/images/P/0553582747.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0553582747.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0553582747.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0425182908", "Isle of Dogs", "Patricia Cornwell", "2002", "Berkley Publishing Group", "https://images.amazon.com/images/P/0425182908.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0425182908.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0425182908.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("042518630X", "Purity in Death", "J.D. Robb", "2002", "Berkley Publishing Group", "https://images.amazon.com/images/P/042518630X.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/042518630X.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/042518630X.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0440223571", "This Year It Will Be Different: And Other Stories", "Maeve Binchy", "1997", "Dell", "https://images.amazon.com/images/P/0440223571.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0440223571.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0440223571.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0812523873", "Proxies", "Laura J. Mixon", "1999", "Tor Books", "https://images.amazon.com/images/P/0812523873.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0812523873.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0812523873.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0842342702", "Left Behind: A Novel of the Earth's Last Days (Left Behind #1)", "Tim Lahaye", "2000", "Tyndale House Publishers", "https://images.amazon.com/images/P/0842342702.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0842342702.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0842342702.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0440225701", "The Street Lawyer", "JOHN GRISHAM", "1999", "Dell", "https://images.amazon.com/images/P/0440225701.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0440225701.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0440225701.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0245542957", "Pacific Northwest", "Hans Johannes Hoefer", "1985", "Chambers Harrap Publishers Ltd", "https://images.amazon.com/images/P/0245542957.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0245542957.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0245542957.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0380715899", "A Soldier of the Great War", "Mark Helprin", "1992", "Avon Books", "https://images.amazon.com/images/P/0380715899.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0380715899.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0380715899.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0553280333", "Getting Well Again", "O. Carol Simonton Md", "1992", "Bantam", "https://images.amazon.com/images/P/0553280333.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0553280333.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0553280333.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0964778319", "An Atmosphere of Eternity: Stories of India", "David Iglehart", "2002", "Sunflower Press", "https://images.amazon.com/images/P/0964778319.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0964778319.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0964778319.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0679810307", "Shabanu: Daughter of the Wind (Border Trilogy)", "SUZANNE FISHER STAPLES", "1991", "Laurel Leaf", "https://images.amazon.com/images/P/0679810307.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0679810307.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0679810307.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0679865691", "Haveli (Laurel Leaf Books)", "SUZANNE FISHER STAPLES", "1995", "Laurel Leaf", "https://images.amazon.com/images/P/0679865691.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0679865691.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0679865691.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("2070423204", "Lieux dits", "Michel Tournier", "2002", "Gallimard", "https://images.amazon.com/images/P/2070423204.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/2070423204.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/2070423204.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("042511774X", "Breathing Lessons", "Anne Tyler", "1994", "Berkley Publishing Group", "https://images.amazon.com/images/P/042511774X.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/042511774X.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/042511774X.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0804106304", "The Joy Luck Club", "Amy Tan", "1994", "Prentice Hall (K-12)", "https://images.amazon.com/images/P/0804106304.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0804106304.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0804106304.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("1853262404", "Heart of Darkness (Wordsworth Collection)", "Joseph Conrad", "1998", "NTC/Contemporary Publishing Company", "https://images.amazon.com/images/P/1853262404.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/1853262404.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/1853262404.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0312970242", "The Angel Is Near", "Deepak Chopra", "2000", "St. Martin's Press", "https://images.amazon.com/images/P/0312970242.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0312970242.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0312970242.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("1853260053", "Tess of the D'Urbervilles (Wordsworth Classics)", "Thomas Hardy", "1997", "NTC/Contemporary Publishing Company", "https://images.amazon.com/images/P/1853260053.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/1853260053.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/1853260053.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("1414035004", "The Adventures of Drew and Ellie: The Magical Dress", "Charles Noland", "2003", "1stBooks Library", "https://images.amazon.com/images/P/1414035004.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/1414035004.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/1414035004.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0060938412", "The Accidental Virgin", "Valerie Frankel", "2003", "Avon Trade", "https://images.amazon.com/images/P/0060938412.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0060938412.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0060938412.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0140067477", "The Tao of Pooh", "Benjamin Hoff", "1983", "Penguin Books", "https://images.amazon.com/images/P/0140067477.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0140067477.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0140067477.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("1558531025", "Life's Little Instruction Book", "H. Jackson Brown", "1991", "Thomas Nelson", "https://images.amazon.com/images/P/1558531025.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/1558531025.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/1558531025.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0441783589", "Starship Troopers", "Robert A. Heinlein", "1987", "Ace Books", "https://images.amazon.com/images/P/0441783589.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0441783589.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0441783589.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("1569871213", "Black Beauty (Illustrated Classics)", "Anna Sewell", "1995", "Landoll", "https://images.amazon.com/images/P/1569871213.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/1569871213.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/1569871213.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0966986105", "Prescription for Terror", "Sandra Levy Ceren", "1999", "Andrew Scott Publishers", "https://images.amazon.com/images/P/0966986105.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0966986105.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0966986105.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("087113375X", "Modern Manners: An Etiquette Book for Rude People", "P.J. O'Rourke", "1990", "Atlantic Monthly Press", "https://images.amazon.com/images/P/087113375X.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/087113375X.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/087113375X.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0340767936", "Turning Thirty", "Mike Gayle", "2000", "Hodder &amp; Stoughton General Division", "https://images.amazon.com/images/P/0340767936.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0340767936.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0340767936.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0743403843", "Decipher", "Stel Pavlou", "2002", "Simon &amp; Schuster (Trade Division)", "https://images.amazon.com/images/P/0743403843.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0743403843.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0743403843.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0060930365", "My First Cousin Once Removed: Money, Madness, and the Family of Robert Lowell", "Sarah Payne Stuart", "1999", "Perennial", "https://images.amazon.com/images/P/0060930365.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0060930365.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0060930365.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0071416331", "Team Bush : Leadership Lessons from the Bush White House", "Donald F. Kettl", "2003", "McGraw-Hill", "https://images.amazon.com/images/P/0071416331.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0071416331.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0071416331.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0375509038", "The Right Man : The Surprise Presidency of George W. Bush", "DAVID FRUM", "2003", "Random House", "https://images.amazon.com/images/P/0375509038.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0375509038.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0375509038.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0316769487", "The Catcher in the Rye", "J.D. Salinger", "1991", "Little, Brown", "https://images.amazon.com/images/P/0316769487.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0316769487.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0316769487.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0679429220", "Midnight in the Garden of Good and Evil: A Savannah Story", "John Berendt", "1994", "Random House", "https://images.amazon.com/images/P/0679429220.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0679429220.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0679429220.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0671867156", "Pretend You Don't See Her", "Mary Higgins Clark", "1998", "Pocket", "https://images.amazon.com/images/P/0671867156.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0671867156.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0671867156.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0312252617", "Fast Women", "Jennifer Crusie", "2001", "St. Martin's Press", "https://images.amazon.com/images/P/0312252617.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0312252617.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0312252617.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0312261594", "Female Intelligence", "Jane Heller", "2001", "St. Martin's Press", "https://images.amazon.com/images/P/0312261594.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0312261594.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0312261594.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0316748641", "Pasquale's Nose: Idle Days in an Italian Town", "Michael Rips", "2002", "Back Bay Books", "https://images.amazon.com/images/P/0316748641.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0316748641.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0316748641.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0316973742", "The Gospel of Judas: A Novel", "Simon Mawer", "2002", "Back Bay Books", "https://images.amazon.com/images/P/0316973742.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0316973742.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0316973742.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0446677450", "Rich Dad, Poor Dad: What the Rich Teach Their Kids About Money--That the Poor and Middle Class Do Not!", "Robert T. Kiyosaki", "2000", "Warner Books", "https://images.amazon.com/images/P/0446677450.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0446677450.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0446677450.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0451166892", "The Pillars of the Earth", "Ken Follett", "1996", "Signet Book", "https://images.amazon.com/images/P/0451166892.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0451166892.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0451166892.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0553347594", "McDonald's: Behind the Arches", "John F. Love", "1995", "Bantam", "https://images.amazon.com/images/P/0553347594.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0553347594.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0553347594.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0671621009", "Creating Wealth : Retire in Ten Years Using Allen's Seven Principles of Wealth!", "Robert G. Allen", "1986", "Fireside", "https://images.amazon.com/images/P/0671621009.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0671621009.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0671621009.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0684822733", "Love, Miracles, and Animal Healing : A heartwarming look at the spiritual bond between animals and humans", "Pam Proctor", "1996", "Fireside", "https://images.amazon.com/images/P/0684822733.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0684822733.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0684822733.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0786868716", "The Five People You Meet in Heaven", "Mitch Albom", "2003", "Hyperion", "https://images.amazon.com/images/P/0786868716.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0786868716.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0786868716.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0671864769", "Relics (Star Trek: The Next Generation)", "Michael Jan Friedman", "1992", "Star Trek", "https://images.amazon.com/images/P/0671864769.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0671864769.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0671864769.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0671521519", "Bless The Beasts And Children : Bless The Beasts And Children", "Glendon Swarthout", "1995", "Pocket", "https://images.amazon.com/images/P/0671521519.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0671521519.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0671521519.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0440222303", "The Touch of Your Shadow, the Whisper of Your Name", "Neal Barrett Jr.", "1996", "Dell", "https://images.amazon.com/images/P/0440222303.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0440222303.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0440222303.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0312953453", "Blood Oath", "David Morrell", "1994", "St. Martin's Press", "https://images.amazon.com/images/P/0312953453.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0312953453.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0312953453.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0446608653", "The Alibi", "Sandra Brown", "2000", "Warner Books", "https://images.amazon.com/images/P/0446608653.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0446608653.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0446608653.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0446612545", "The Beach House", "James Patterson", "2003", "Warner Books", "https://images.amazon.com/images/P/0446612545.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0446612545.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0446612545.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0446612618", "A Kiss Remembered", "Sandra Brown", "2003", "Warner Books", "https://images.amazon.com/images/P/0446612618.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0446612618.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0446612618.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0451208080", "The Short Forever", "Stuart Woods", "2003", "Signet Book", "https://images.amazon.com/images/P/0451208080.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0451208080.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0451208080.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0553584383", "Dead Aim", "IRIS JOHANSEN", "2004", "Bantam Books", "https://images.amazon.com/images/P/0553584383.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0553584383.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0553584383.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0812575954", "The Deal", "Joe Hutsko", "2000", "Tor Books (Mm)", "https://images.amazon.com/images/P/0812575954.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0812575954.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0812575954.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0316735736", "All He Ever Wanted: A Novel", "Anita Shreve", "2004", "Back Bay Books", "https://images.amazon.com/images/P/0316735736.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0316735736.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0316735736.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0743439740", "Every Breath You Take : A True Story of Obsession, Revenge, and Murder", "Ann Rule", "2002", "Pocket", "https://images.amazon.com/images/P/0743439740.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0743439740.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0743439740.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0345372700", "If I Ever Get Back to Georgia, I'm Gonna Nail My Feet to the Ground", "LEWIS GRIZZARD", "1991", "Ballantine Books", "https://images.amazon.com/images/P/0345372700.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0345372700.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0345372700.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0451406923", "Goodbye, My Little Ones: The True Story of a Murderous Mother and Five Innocent Victims", "Charles Hickey", "1996", "Onyx Books", "https://images.amazon.com/images/P/0451406923.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0451406923.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0451406923.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0671042858", "The Girl Who Loved Tom Gordon", "Stephen King", "2000", "Pocket", "https://images.amazon.com/images/P/0671042858.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0671042858.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0671042858.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0743249992", "Bringing Down the House: The Inside Story of Six M.I.T. Students Who Took Vegas for Millions", "Ben Mezrich", "2003", "Free Press", "https://images.amazon.com/images/P/0743249992.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0743249992.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0743249992.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0425184226", "The Sum of All Fears", "Tom Clancy", "2002", "Berkley Publishing Group", "https://images.amazon.com/images/P/0425184226.01.THUMBZZZ.jpg", "https://images.amazon.com/images/P/0425184226.01.MZZZZZZZ.jpg", "https://images.amazon.com/images/P/0425184226.01.LZZZZZZZ.jpg");


--
-- Table structure for table `shopping_carts`
--
CREATE TABLE `shopping_carts` (
  `id` int NOT NULL,
  `user_id` bigint NOT NULL,
  `isbn` varchar(10) NOT NULL,
  `book_title` varchar(255) NOT NULL,
  `book_author` varchar(50) NOT NULL,
  `price` FLOAT NOT NULL,
  `quantity` INT NOT NULL,
  `image_url_M` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `shopping_carts`
--
ALTER TABLE `shopping_carts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;


--
-- Table structure for table `addresses`
--
CREATE TABLE `addresses` (
    `address_id`  INT AUTO_INCREMENT PRIMARY KEY,
    `address`     TEXT NOT NULL,
    `city`        VARCHAR(50) NOT NULL,
    `postal_code` VARCHAR(10) NOT NULL,
    `country`     VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Table structure for table `payments`
--
CREATE TABLE `payments` (
    `payment_id`  INT AUTO_INCREMENT PRIMARY KEY,
    `card_number` VARCHAR(20) NOT NULL,
    `expiry_date` VARCHAR(5) NOT NULL,
    `cvv`         VARCHAR(3) NOT NULL,
    `first_name`  VARCHAR(50) NOT NULL,
    `last_name`   VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Table structure for table `statuses`
--
CREATE TABLE `statuses` (
    `status_id`          INT AUTO_INCREMENT PRIMARY KEY,
    `status_description` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Insert default statuses
INSERT INTO `statuses` (`status_description`) VALUES
('pending'), ('confirmed'), ('shipped');

--
-- Table structure for table `orders`
--
CREATE TABLE `orders` (
    `order_id`            INT AUTO_INCREMENT PRIMARY KEY,
    `user_id`             INT NOT NULL,
    `billing_address_id`  INT NOT NULL,
    `shipping_address_id` INT,
    `payment_id`          INT NOT NULL,
    `total_price`         DECIMAL(10, 2) NOT NULL,
    `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `status_id`           INT NOT NULL DEFAULT 0, -- 0 => 'pending', 1 => 'confirmed', 2 => 'shipped'
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    FOREIGN KEY (`billing_address_id`) REFERENCES `addresses`(`address_id`),
    FOREIGN KEY (`shipping_address_id`) REFERENCES `addresses`(`address_id`),
    FOREIGN KEY (`payment_id`) REFERENCES `payments`(`payment_id`),
    FOREIGN KEY (`status_id`) REFERENCES `statuses`(`status_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Table structure for table `order_items`
--
CREATE TABLE `order_items` (
    `item_id`  INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT NOT NULL,
    `isbn`     VARCHAR(13) NOT NULL,
    `price`    DECIMAL(10, 2) NOT NULL,
    `quantity` INT NOT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `orders`(`order_id`),
    FOREIGN KEY (`isbn`) REFERENCES `books`(`isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


COMMIT;