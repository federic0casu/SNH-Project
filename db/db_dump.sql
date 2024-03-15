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
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_verified` int NOT NULL,
  `verif_token` varchar(255),
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `first_name`, `last_name`, `email`, `password`, `is_verified`, `verif_token`, `created_at`) VALUES (1, 'federic0', 'Federico', 'Casu', 'federicocasu@unipi.it', '$2y$10$lAoR6kqC5LKP6K6szeHe8Ogjs.GDktierrw5Zu6ubCk59qAUxDHaS', 1, NULL, '2023-11-07 19:51:03');

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `users`
  ADD UNIQUE (`username`);

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
  `isbn` varchar(10) NOT NULL,
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
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0345417623", "Timeline", "Michael Crichton", "2000", "Ballantine Books", "http://images.amazon.com/images/P/0345417623.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0345417623.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0345417623.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0684823802", "Out of the silent planet", "C.S. Lewis", "1996", "Scribner", "http://images.amazon.com/images/P/0684823802.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0684823802.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0684823802.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0375759778", "Prague : A Novel", "ARTHUR PHILLIPS", "2003", "Random House Trade Paperbacks", "http://images.amazon.com/images/P/0375759778.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0375759778.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0375759778.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0425163091", "Chocolate Jesus", "Stephan Jaramillo", "1998", "Berkley Publishing Group", "http://images.amazon.com/images/P/0425163091.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0425163091.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0425163091.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("3442446937", "Tage der Unschuld.", "Richard North Patterson", "2000", "Goldmann", "http://images.amazon.com/images/P/3442446937.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/3442446937.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/3442446937.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0375406328", "Lying Awake", "Mark Salzman", "2000", "Alfred A. Knopf", "http://images.amazon.com/images/P/0375406328.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0375406328.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0375406328.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0446310786", "To Kill a Mockingbird", "Harper Lee", "1988", "Little Brown &amp; Company", "http://images.amazon.com/images/P/0446310786.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0446310786.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0446310786.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0449005615", "Seabiscuit: An American Legend", "LAURA HILLENBRAND", "2002", "Ballantine Books", "http://images.amazon.com/images/P/0449005615.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0449005615.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0449005615.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0060168013", "Pigs in Heaven", "Barbara Kingsolver", "1993", "Harpercollins", "http://images.amazon.com/images/P/0060168013.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0060168013.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0060168013.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("038078243X", "Miss Zukas and the Raven's Dance", "Jo Dereske", "1996", "Avon", "http://images.amazon.com/images/P/038078243X.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/038078243X.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/038078243X.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("055321215X", "Pride and Prejudice", "Jane Austen", "1983", "Bantam", "http://images.amazon.com/images/P/055321215X.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/055321215X.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/055321215X.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("067176537X", "The Therapeutic Touch: How to Use Your Hands to Help or to Heal", "Dolores Krieger", "1979", "Fireside", "http://images.amazon.com/images/P/067176537X.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/067176537X.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/067176537X.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0061099686", "Downtown", "Anne Rivers Siddons", "1995", "HarperTorch", "http://images.amazon.com/images/P/0061099686.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0061099686.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0061099686.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0553582909", "Icebound", "Dean R. Koontz", "2000", "Bantam Books", "http://images.amazon.com/images/P/0553582909.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0553582909.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0553582909.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0671888587", "I'll Be Seeing You", "Mary Higgins Clark", "1994", "Pocket", "http://images.amazon.com/images/P/0671888587.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0671888587.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0671888587.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0553582747", "From the Corner of His Eye", "Dean Koontz", "2001", "Bantam Books", "http://images.amazon.com/images/P/0553582747.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0553582747.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0553582747.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0425182908", "Isle of Dogs", "Patricia Cornwell", "2002", "Berkley Publishing Group", "http://images.amazon.com/images/P/0425182908.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0425182908.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0425182908.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("042518630X", "Purity in Death", "J.D. Robb", "2002", "Berkley Publishing Group", "http://images.amazon.com/images/P/042518630X.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/042518630X.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/042518630X.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0440223571", "This Year It Will Be Different: And Other Stories", "Maeve Binchy", "1997", "Dell", "http://images.amazon.com/images/P/0440223571.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0440223571.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0440223571.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0812523873", "Proxies", "Laura J. Mixon", "1999", "Tor Books", "http://images.amazon.com/images/P/0812523873.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0812523873.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0812523873.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0842342702", "Left Behind: A Novel of the Earth's Last Days (Left Behind #1)", "Tim Lahaye", "2000", "Tyndale House Publishers", "http://images.amazon.com/images/P/0842342702.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0842342702.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0842342702.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0440225701", "The Street Lawyer", "JOHN GRISHAM", "1999", "Dell", "http://images.amazon.com/images/P/0440225701.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0440225701.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0440225701.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0060914068", "Love, Medicine and Miracles", "M.D. Bernie S. Siegel", "1988", "HarperCollins Publishers", "http://images.amazon.com/images/P/0060914068.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0060914068.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0060914068.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0156047624", "All the King's Men", "Robert Penn Warren", "1982", "Harvest Books", "http://images.amazon.com/images/P/0156047624.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0156047624.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0156047624.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0245542957", "Pacific Northwest", "Hans Johannes Hoefer", "1985", "Chambers Harrap Publishers Ltd", "http://images.amazon.com/images/P/0245542957.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0245542957.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0245542957.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0380715899", "A Soldier of the Great War", "Mark Helprin", "1992", "Avon Books", "http://images.amazon.com/images/P/0380715899.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0380715899.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0380715899.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0553280333", "Getting Well Again", "O. Carol Simonton Md", "1992", "Bantam", "http://images.amazon.com/images/P/0553280333.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0553280333.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0553280333.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0961769947", "Northwest Wines and Wineries", "Chuck Hill", "1993", "Speed Graphics", "http://images.amazon.com/images/P/0961769947.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0961769947.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0961769947.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0964778319", "An Atmosphere of Eternity: Stories of India", "David Iglehart", "2002", "Sunflower Press", "http://images.amazon.com/images/P/0964778319.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0964778319.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0964778319.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0679810307", "Shabanu: Daughter of the Wind (Border Trilogy)", "SUZANNE FISHER STAPLES", "1991", "Laurel Leaf", "http://images.amazon.com/images/P/0679810307.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0679810307.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0679810307.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0679865691", "Haveli (Laurel Leaf Books)", "SUZANNE FISHER STAPLES", "1995", "Laurel Leaf", "http://images.amazon.com/images/P/0679865691.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0679865691.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0679865691.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("2070423204", "Lieux dits", "Michel Tournier", "2002", "Gallimard", "http://images.amazon.com/images/P/2070423204.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/2070423204.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/2070423204.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0345260317", "The Dragons of Eden: Speculations on the Evolution of Human Intelligence", "Carl Sagan", "1978", "Ballantine Books", "http://images.amazon.com/images/P/0345260317.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0345260317.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0345260317.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0394743741", "The yawning heights", "Aleksandr Zinoviev", "1980", "Random House", "http://images.amazon.com/images/P/0394743741.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0394743741.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0394743741.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("042511774X", "Breathing Lessons", "Anne Tyler", "1994", "Berkley Publishing Group", "http://images.amazon.com/images/P/042511774X.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/042511774X.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/042511774X.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0804106304", "The Joy Luck Club", "Amy Tan", "1994", "Prentice Hall (K-12)", "http://images.amazon.com/images/P/0804106304.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0804106304.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0804106304.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("1853262404", "Heart of Darkness (Wordsworth Collection)", "Joseph Conrad", "1998", "NTC/Contemporary Publishing Company", "http://images.amazon.com/images/P/1853262404.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/1853262404.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/1853262404.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0312970242", "The Angel Is Near", "Deepak Chopra", "2000", "St. Martin's Press", "http://images.amazon.com/images/P/0312970242.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0312970242.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0312970242.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("1853260053", "Tess of the D'Urbervilles (Wordsworth Classics)", "Thomas Hardy", "1997", "NTC/Contemporary Publishing Company", "http://images.amazon.com/images/P/1853260053.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/1853260053.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/1853260053.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("1414035004", "The Adventures of Drew and Ellie: The Magical Dress", "Charles Noland", "2003", "1stBooks Library", "http://images.amazon.com/images/P/1414035004.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/1414035004.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/1414035004.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0060938412", "The Accidental Virgin", "Valerie Frankel", "2003", "Avon Trade", "http://images.amazon.com/images/P/0060938412.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0060938412.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0060938412.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0140067477", "The Tao of Pooh", "Benjamin Hoff", "1983", "Penguin Books", "http://images.amazon.com/images/P/0140067477.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0140067477.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0140067477.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0345465083", "Seabiscuit", "LAURA HILLENBRAND", "2003", "Ballantine Books", "http://images.amazon.com/images/P/0345465083.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0345465083.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0345465083.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0451625889", "The Prince", "Niccolo Machiavelli", "1952", "Signet Book", "http://images.amazon.com/images/P/0451625889.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0451625889.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0451625889.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("1558531025", "Life's Little Instruction Book (Life's Little Instruction Books (Paperback))", "H. Jackson Brown", "1991", "Thomas Nelson", "http://images.amazon.com/images/P/1558531025.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/1558531025.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/1558531025.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0441783589", "Starship Troopers", "Robert A. Heinlein", "1987", "Ace Books", "http://images.amazon.com/images/P/0441783589.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0441783589.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0441783589.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0394895894", "The Ruby in the Smoke (Sally Lockhart Trilogy, Book 1)", "PHILIP PULLMAN", "1988", "Laurel Leaf", "http://images.amazon.com/images/P/0394895894.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0394895894.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0394895894.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("1569871213", "Black Beauty (Illustrated Classics)", "Anna Sewell", "1995", "Landoll", "http://images.amazon.com/images/P/1569871213.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/1569871213.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/1569871213.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0966986105", "Prescription for Terror", "Sandra Levy Ceren", "1999", "Andrew Scott Publishers", "http://images.amazon.com/images/P/0966986105.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0966986105.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0966986105.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("087113375X", "Modern Manners: An Etiquette Book for Rude People", "P.J. O'Rourke", "1990", "Atlantic Monthly Press", "http://images.amazon.com/images/P/087113375X.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/087113375X.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/087113375X.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0340767936", "Turning Thirty", "Mike Gayle", "2000", "Hodder &amp; Stoughton General Division", "http://images.amazon.com/images/P/0340767936.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0340767936.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0340767936.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0743403843", "Decipher", "Stel Pavlou", "2002", "Simon &amp; Schuster (Trade Division)", "http://images.amazon.com/images/P/0743403843.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0743403843.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0743403843.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0060930365", "My First Cousin Once Removed: Money, Madness, and the Family of Robert Lowell", "Sarah Payne Stuart", "1999", "Perennial", "http://images.amazon.com/images/P/0060930365.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0060930365.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0060930365.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0060177586", "Standing Firm: A Vice-Presidential Memoir", "Dan Quayle", "1994", "Harpercollins", "http://images.amazon.com/images/P/0060177586.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0060177586.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0060177586.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0071416331", "Team Bush : Leadership Lessons from the Bush White House", "Donald F. Kettl", "2003", "McGraw-Hill", "http://images.amazon.com/images/P/0071416331.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0071416331.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0071416331.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0375509038", "The Right Man : The Surprise Presidency of George W. Bush", "DAVID FRUM", "2003", "Random House", "http://images.amazon.com/images/P/0375509038.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0375509038.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0375509038.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0553062042", "Daybreakers Louis Lamour Collection", "Louis Lamour", "1981", "Bantam Doubleday Dell", "http://images.amazon.com/images/P/0553062042.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0553062042.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0553062042.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0316769487", "The Catcher in the Rye", "J.D. Salinger", "1991", "Little, Brown", "http://images.amazon.com/images/P/0316769487.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0316769487.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0316769487.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0679429220", "Midnight in the Garden of Good and Evil: A Savannah Story", "John Berendt", "1994", "Random House", "http://images.amazon.com/images/P/0679429220.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0679429220.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0679429220.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0671867156", "Pretend You Don't See Her", "Mary Higgins Clark", "1998", "Pocket", "http://images.amazon.com/images/P/0671867156.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0671867156.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0671867156.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0312252617", "Fast Women", "Jennifer Crusie", "2001", "St. Martin's Press", "http://images.amazon.com/images/P/0312252617.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0312252617.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0312252617.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0312261594", "Female Intelligence", "Jane Heller", "2001", "St. Martin's Press", "http://images.amazon.com/images/P/0312261594.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0312261594.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0312261594.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0316748641", "Pasquale's Nose: Idle Days in an Italian Town", "Michael Rips", "2002", "Back Bay Books", "http://images.amazon.com/images/P/0316748641.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0316748641.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0316748641.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0316973742", "The Gospel of Judas: A Novel", "Simon Mawer", "2002", "Back Bay Books", "http://images.amazon.com/images/P/0316973742.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0316973742.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0316973742.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0385235941", "Prize Stories, 1987: The O'Henry Awards", "William Abrahams", "1987", "Doubleday Books", "http://images.amazon.com/images/P/0385235941.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0385235941.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0385235941.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0446677450", "Rich Dad, Poor Dad: What the Rich Teach Their Kids About Money--That the Poor and Middle Class Do Not!", "Robert T. Kiyosaki", "2000", "Warner Books", "http://images.amazon.com/images/P/0446677450.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0446677450.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0446677450.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0451166892", "The Pillars of the Earth", "Ken Follett", "1996", "Signet Book", "http://images.amazon.com/images/P/0451166892.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0451166892.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0451166892.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0553347594", "McDonald's: Behind the Arches", "John F. Love", "1995", "Bantam", "http://images.amazon.com/images/P/0553347594.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0553347594.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0553347594.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0671621009", "Creating Wealth : Retire in Ten Years Using Allen's Seven Principles of Wealth!", "Robert G. Allen", "1986", "Fireside", "http://images.amazon.com/images/P/0671621009.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0671621009.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0671621009.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0684822733", "Love, Miracles, and Animal Healing : A heartwarming look at the spiritual bond between animals and humans", "Pam Proctor", "1996", "Fireside", "http://images.amazon.com/images/P/0684822733.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0684822733.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0684822733.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0786868716", "The Five People You Meet in Heaven", "Mitch Albom", "2003", "Hyperion", "http://images.amazon.com/images/P/0786868716.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0786868716.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0786868716.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0671864769", "Relics (Star Trek: The Next Generation)", "Michael Jan Friedman", "1992", "Star Trek", "http://images.amazon.com/images/P/0671864769.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0671864769.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0671864769.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0671521519", "Bless The Beasts And Children : Bless The Beasts And Children", "Glendon Swarthout", "1995", "Pocket", "http://images.amazon.com/images/P/0671521519.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0671521519.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0671521519.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0440222303", "The Touch of Your Shadow, the Whisper of Your Name", "Neal Barrett Jr.", "1996", "Dell", "http://images.amazon.com/images/P/0440222303.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0440222303.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0440222303.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0312953453", "Blood Oath", "David Morrell", "1994", "St. Martin's Press", "http://images.amazon.com/images/P/0312953453.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0312953453.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0312953453.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0446608653", "The Alibi", "Sandra Brown", "2000", "Warner Books", "http://images.amazon.com/images/P/0446608653.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0446608653.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0446608653.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0446612545", "The Beach House", "James Patterson", "2003", "Warner Books", "http://images.amazon.com/images/P/0446612545.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0446612545.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0446612545.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0446612618", "A Kiss Remembered", "Sandra Brown", "2003", "Warner Books", "http://images.amazon.com/images/P/0446612618.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0446612618.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0446612618.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0451208080", "The Short Forever", "Stuart Woods", "2003", "Signet Book", "http://images.amazon.com/images/P/0451208080.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0451208080.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0451208080.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0553584383", "Dead Aim", "IRIS JOHANSEN", "2004", "Bantam Books", "http://images.amazon.com/images/P/0553584383.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0553584383.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0553584383.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0812575954", "The Deal", "Joe Hutsko", "2000", "Tor Books (Mm)", "http://images.amazon.com/images/P/0812575954.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0812575954.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0812575954.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0316735736", "All He Ever Wanted: A Novel", "Anita Shreve", "2004", "Back Bay Books", "http://images.amazon.com/images/P/0316735736.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0316735736.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0316735736.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0743439740", "Every Breath You Take : A True Story of Obsession, Revenge, and Murder", "Ann Rule", "2002", "Pocket", "http://images.amazon.com/images/P/0743439740.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0743439740.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0743439740.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0345372700", "If I Ever Get Back to Georgia, I'm Gonna Nail My Feet to the Ground", "LEWIS GRIZZARD", "1991", "Ballantine Books", "http://images.amazon.com/images/P/0345372700.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0345372700.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0345372700.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0380619458", "The Mosquito Coast", "Paul Theroux", "1990", "Harper Mass Market Paperbacks (Mm)", "http://images.amazon.com/images/P/0380619458.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0380619458.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0380619458.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0446325805", "If Love Were Oil, I'd Be About a Quart Low", "Lewis Grizzard", "1994", "Warner Books (Mm)", "http://images.amazon.com/images/P/0446325805.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0446325805.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0446325805.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0451406923", "Goodbye, My Little Ones: The True Story of a Murderous Mother and Five Innocent Victims", "Charles Hickey", "1996", "Onyx Books", "http://images.amazon.com/images/P/0451406923.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0451406923.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0451406923.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0671042858", "The Girl Who Loved Tom Gordon", "Stephen King", "2000", "Pocket", "http://images.amazon.com/images/P/0671042858.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0671042858.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0671042858.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0743249992", "Bringing Down the House: The Inside Story of Six M.I.T. Students Who Took Vegas for Millions", "Ben Mezrich", "2003", "Free Press", "http://images.amazon.com/images/P/0743249992.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0743249992.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0743249992.01.LZZZZZZZ.jpg");
INSERT INTO `books` (`isbn`,`book_title`,`book_author`,`year_of_publication`,`publisher`,`image_url_S`,`image_url_M`,`image_url_L`) VALUES  ("0425184226", "The Sum of All Fears", "Tom Clancy", "2002", "Berkley Publishing Group", "http://images.amazon.com/images/P/0425184226.01.THUMBZZZ.jpg", "http://images.amazon.com/images/P/0425184226.01.MZZZZZZZ.jpg", "http://images.amazon.com/images/P/0425184226.01.LZZZZZZZ.jpg");


COMMIT;
