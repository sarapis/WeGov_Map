-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 22, 2020 at 11:07 PM
-- Server version: 10.3.13-MariaDB-log
-- PHP Version: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wegov_display`
--

-- --------------------------------------------------------

--
-- Table structure for table `covid_pods`
--
DROP TABLE IF EXISTS `covid_pods`;
DROP TABLE IF EXISTS `covid_prj`;

CREATE TABLE IF NOT EXISTS `covid_pods` (
  `id` varchar(25) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `link` varchar(300) DEFAULT NULL,
  `nta` varchar(10) NOT NULL,
  `ntas` varchar(1000) NOT NULL,
  `mapped` tinyint(1) DEFAULT NULL,
  `merged` tinyint(1) DEFAULT NULL,
  `address` varchar(150) DEFAULT NULL,
  `attachments` varchar(200) DEFAULT NULL,
  `contacts` varchar(200) DEFAULT NULL,
  `phone_private` varchar(20) DEFAULT NULL,
  `email_private` varchar(80) DEFAULT NULL,
  `change_request` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`,`nta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `covid_pods`
--

INSERT INTO `covid_pods` (`id`, `name`, `description`, `email`, `link`, `nta`, `ntas`, `mapped`, `merged`, `address`, `attachments`, `contacts`, `phone_private`, `email_private`, `change_request`) VALUES
('rec5i2n9WrBVhR4Zu', 'East Village Neighbors', NULL, 'EastVillageNeighbors@gmail.com', 'https://www.facebook.com/groups/eastvillageneighbors/', 'MN22', 'Manhattan: East Village', 1, NULL, NULL, '[]', NULL, '917-994-1074', NULL, NULL),
('recbnealIXzjjk4Vy', 'Bushwick Mutual Aid - Coronavirus', 'We are coordinating a database of volunteers in and around Bushwick to help our most vulnerable neighbors. This ranges from publicizing resources to running basic errands to engaging with local aid organizations.', 'bushwickmutualaid@gmail.com', 'https://www.facebook.com/groups/691761548028851/', 'BK78', 'Brooklyn: Bushwick North, Brooklyn: Bushwick South', 1, NULL, NULL, '[]', NULL, '(917) 789-4295', NULL, NULL),
('reccMrwV6Jchf5N9n', 'Red Hook Coronavirus Community Cooperative Committee Google Form', 'Let\'s form a committee to talk about how we can best prepare for and help each other if the virus affects our building/block/neighborhood.', NULL, 'https://docs.google.com/forms/d/e/1FAIpQLSfn5N5N6Wzu-zVG5nyPNs5wOgGjHj-y_EA4VoT4J74yQ2p6ag/viewform?fbclid=IwAR0OZJK8jmNSOF8tJf2HHkq22geATl2icixr0G6EvVexchgpWmMM3x7JQ74', 'BK33', 'Brooklyn: Carroll Gardens-Columbia Street-Red Hook', 1, NULL, NULL, '[]', NULL, NULL, NULL, NULL),
('recCzERf8y6ACsu1H', 'Uptown Coronavirus Community Response Network ', 'support our neighbors in meeting their needs during this time of danger and restricted movement ', NULL, 'https://docs.google.com/forms/d/1nDafJpKloqdkZqAEp0IiPDkNyROlOMBDK2KxJpwJ10g/viewform?edit_requested=true', 'MN01', 'Manhattan: Washington Heights North, Manhattan: Washington Heights South, Manhattan: Marble Hill-Inwood', 1, NULL, NULL, '[]', NULL, NULL, NULL, NULL),
('recEDFwqxyjivy0TS', 'Crown Heights Mutual Aid Facebook Group', NULL, NULL, 'https://www.facebook.com/groups/496603171016990/', 'BK61', 'Brooklyn: Crown Heights North', 1, NULL, NULL, '[]', NULL, NULL, NULL, NULL),
('recFlBxffXUjQ7be0', 'Brooklyn Neighbors Clinton Hill/Fort Greene', 'Grocery pick up/delivery, prescription pick up/delivery, dog walking, friendly phone. Services are available to anyone.', 'bklynneighbors@gmail.com', 'https://join.slack.com/t/bklynneighbors/shared_invite/zt-cui99fzl-2DBRPVni85Q~UuIYOupOcg ', 'BK68', 'Brooklyn: Clinton Hill, Brooklyn: Fort Greene', 1, NULL, NULL, '[]', NULL, '(862) 277-0747', 'bklynneighbors@gmail.com', NULL),
('recHvExBGG7YijO71', 'Kensington, Brooklyn, Group for Mutual Aid (Coronavirus)', 'Basically, members post ad hoc needs and offers of help, and other members respond. They can then buddy up if they want, but that\'s their decision.\n\nIt does not cover all folks, of course, because it\'s just Facebook, but I try to keep it relatively open, so we can link to other resources, people can have conversations about other ways to organize, and people can be inspired and make connections but then can self-organize outside the group for the most part. \n', NULL, 'https://www.facebook.com/groups/884513918671186/about/', 'BK41', 'Brooklyn: Kensington-Ocean Parkway', 1, NULL, NULL, '[]', NULL, NULL, NULL, NULL),
('recjErZ7MlMLxy9v7', 'Carroll Gardens Mutual Aid Facebook Group', NULL, NULL, 'https://www.facebook.com/groups/208815540435346/', 'BK33', 'Brooklyn: Carroll Gardens-Columbia Street-Red Hook', 1, NULL, NULL, '[]', NULL, NULL, NULL, NULL),
('recL1hQaAkXr6I4Bu', 'Brooklyn Mutual Aid', 'We have a network of volunteers who can help with groceries, prescriptions, mail, and most all deliveries and transportation. ', 'brooklynmutualaid@gmail.com', 'https://docs.google.com/document/d/1QAWJaUbF1vCkc1sWUJnWbr0Dh9CfxWCcari7ieKHrk4/edit', 'BK90', 'Brooklyn: DUMBO-Vinegar Hill-Downtown Brooklyn-Boerum Hill, Brooklyn: Brooklyn Heights-Cobble Hill, Brooklyn: Carroll Gardens-Columbia Street-Red Hook, Brooklyn: Park Slope-Gowanus, Brooklyn: Williamsburg, Brooklyn: East Williamsburg', 1, NULL, NULL, '[]', NULL, '(925) 323-7169', NULL, NULL),
('recM8LoQIRybksXvm', 'Greenpoint Community Strong', 'Neighborhood community Slack Hub (similar to Bed-Stuy Strong) which centralizes people to a platform as a support network where persons can request the community for any needs (groceries, medicine, etc.) which can be organized and delivered safely to those affected by COVID-19. Additionally, it is a platform for updates on local businesses, community resources and important information regarding the situation as it unfolds. We welcome anyone in Greenpoint and in the surrounding areas to join the group and be part of the support network as we embark in these uncertain times. ', 'greenpointcommunity2020@gmail.com', 'bit.ly/greenpointstrong', 'BK76', 'Brooklyn: Greenpoint', 1, NULL, 'n/a', '[]', NULL, '(347) 221-7532', NULL, NULL),
('recNmXul8tTZxFu3R', 'Prospect Hts (West): Neighborhood Support & Preparedness', 'Connect neighbors who need help with neighbors who need help (groceries, other errands, etc)', NULL, 'http://bit.ly/phwcov19', 'BK64', 'Brooklyn: Prospect Heights', 1, NULL, NULL, '[]', NULL, NULL, NULL, NULL),
('recs2ws0Qasrz9vrO', 'Harlem United Against Coronavirus', 'Facebook Group and Google Spreadsheet - https://docs.google.com/spreadsheets/d/1GXLBsBGAanJM_bJA4HFvX0Cx_6wrh3C7u9STm09TCDo/edit#gid=0', NULL, 'https://www.facebook.com/groups/2437603463161630/', 'MN03', 'Manhattan: Central Harlem South, Manhattan: East Harlem South, Manhattan: East Harlem North, Manhattan: Central Harlem North-Polo Grounds', 1, NULL, NULL, '[]', NULL, NULL, NULL, NULL),
('recvoemR4bhSpyhNz', 'Ridgewood Mutual Aid Network', 'Here is our volunteer inventory form, which includes a checklist of possible assistance measures, depending on volunteer abilities: https://docs.google.com/forms/d/e/1FAIpQLScMvBdL54fMmQF8CL06tDXeNjdLNZuykK72In97L9LbBgsB0A/viewform', 'ridgewoodmutualaidnetwork@gmail.com', 'https://docs.google.com/forms/d/e/1FAIpQLScMvBdL54fMmQF8CL06tDXeNjdLNZuykK72In97L9LbBgsB0A/viewform', 'QN20', 'Queens: Ridgewood', 1, NULL, NULL, '[]', NULL, '(978) 660-9754', 'alcrowley03@gmail.com', NULL),
('recw9L6zXzGVbgo9G', 'Ditmas Park/Flatbush/Prospect Park South Coronavirus Neighborhood Help Form', 'Facebook group to connect neighbors with neighbors for solidarity and mutual aid. \nSign up if you need help or can provide it to our neighbors who might be homebound because of the virus. https://docs.google.com/forms/d/e/1FAIpQLSc-rV9zUQnic8oa4fI4qx3YJ1F_om2dK2BkRshpr0BRQvpNKg/viewform', NULL, 'https://www.facebook.com/groups/2775890899146167/', 'BK42', 'Brooklyn: Flatbush', 1, NULL, NULL, '[]', NULL, '(917) 450-0053', NULL, NULL),
('recwGJ3m0qnsB4KN7', 'Boerum Hill/Downtown BK Neighborhood Services and Support Form', 'Please complete this form if you are looking for assistance or want to provide support for neighbors. ', NULL, 'https://docs.google.com/forms/d/e/1FAIpQLSeziqcXcTEy4RfH9JDivesZ_R9wQtlrl7gV8re7q2Fqwo1HOg/viewform', 'BK38', 'Brooklyn: DUMBO-Vinegar Hill-Downtown Brooklyn-Boerum Hill', 1, NULL, NULL, '[]', NULL, NULL, NULL, NULL),
('recyDRL2kCDDYbGxl', 'Washington Heights Mutual Aid', 'We are still forming our network, but as we develop our organization and assess community need, we will update you. Currently there are two people who are organizing in their buildings, but I\'ve reached out to the dog park community that I belong to and hope that as people in my building join, they will refer others in the neighborhood to the organizing group.', 'WaHiMutualAid@gmail.com', 'Washington Heights Mutual Aid WhatsApp Group: https://chat.whatsapp.com/EWoUGGjAo2cLFdWOMMT8TW', 'MN35', 'Manhattan: Washington Heights North', 1, NULL, NULL, '[]', NULL, '(434) 260-1165', 'jessica.may.mccoy@gmail.com', NULL),
('recZbsLG0OT0Lv3FL', 'Bed Stuy Strong', 'Link is to a Slack group', 'BedStuyStrong2020@gmail.com', 'http://bit.ly/bedstuystrong', 'BK35', 'Brooklyn: Bedford, Brooklyn: Stuyvesant Heights', 1, NULL, NULL, '[]', NULL, NULL, NULL, NULL),
('reczpW2Z3SgpGJcmS', 'Astoria Mutual Aid', 'We are the Astoria Mutual Aid Network a group of neighbors working together to support our community and each other through the realities and challenges of COVID-19. \n\nWe are non-political. We are non-partisan. We are grassroots.We are members of the Astoria community who are ready to help our neighbors.\n\nWe believe that our community is stronger when we take care of each other. There is no cost for our support.', 'astoriamutualaid@gmail.com', 'www.astoriamutualaid.com', 'QN71', 'Queens: Astoria, Queens: Old Astoria', 1, NULL, NULL, '[]', NULL, '(310) 938-9493', 'maryamshari@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `covid_prj`
--

CREATE TABLE IF NOT EXISTS `covid_prj` (
  `id` varchar(25) NOT NULL,
  `name` varchar(200) NOT NULL,
  `type` varchar(80) DEFAULT NULL,
  `tags` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `email_pub` varchar(80) DEFAULT NULL,
  `email_private` varchar(80) DEFAULT NULL,
  `url` varchar(300) DEFAULT NULL,
  `images` text DEFAULT NULL,
  `nta` varchar(10) NOT NULL,
  `ntas` varchar(200) NOT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `sites` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`,`nta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `covid_prj`
--

INSERT INTO `covid_prj` (`id`, `name`, `type`, `tags`, `description`, `email_pub`, `email_private`, `url`, `images`, `nta`, `ntas`, `comment`, `sites`) VALUES
('rec4eZo0Q9uPpEZnz', 'Mouse Create: free STEM, Design & CS Courses for school groups', 'educational', NULL, 'Mouse Create is a free online learning platform where over 11,000 students a year (5th grade through high school) build, collaborate, and share creative STEM, computer science and technology design projects.\n\nOur courses, lesson plans, collaboration, digital badging and portfolio features help educators build an environment for learners to explore, deepen, and practice creative and technical identities over time.\n\nEducators integrate Mouse project curriculum into their programs and classes, choosing from topics including circuitry, game design, web literacy, coding, green technology, video making, computer science, design thinking, sewable technology and more.  \n\nMany of the projects can be facilitated remotely and/or asynchronously and require no special materials.  ', NULL, 'meredith@mouse.org', 'http://mouse.org/join', '[{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/28d40a82d853f6faff9f8bbb3f524b20\\/96cff72b\\/badge770x578_160909_121447.png\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/deb6384f0ed58736c5cd093ec3fbd859\\/56483575\"},{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/ef9eb1f91bc6c84db784914f5c252df3\\/b865b0a5\\/create_detail-2.jpeg\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/dbc0a1c688f4bcf053655768595029a5\\/605fd331\"}]', '', 'City-Wide', NULL, NULL),
('rec4k30SNWJHyj7J9', 'Share your voice on the IntegrateNYC Covid19 Student Survey', 'advocacy', NULL, 'While we are facing a public health crisis and plenty of uncertainty, we need to pull together as a community. We hope you and your loved ones are safe and healthy. \n\nI\'m sure you\'ve heard that New York City public schools will be closed until April 20th, at the earliest, and until the end of the year at the latest. \n\nWe’ve created a survey for youth to take so we can better understand what New York’s youth need in this time. We need YOUR input! \n\nSTEP 1 - Fill out the survey if you\'re an NYC student \nSTEP 2 - Share it with 1-3 other youth at least bit.ly/INYCsurvey\n\nIt is so important that we hear from you all so we can uplift your voice, ensure resources are being allocated where they are needed, and support you. \n\nPlease be sure to follow us on insta (https://www.instagram.com/integratenyc/) and twitter (https://twitter.com/integratenyc) for news, resources, and support. If you need immediate support, please email hello@integratenyc.org. ', 'jace@integratenyc.org', NULL, 'bit.ly/INYCsurvey', '[{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/0e6f710f7627dbb7e66e141c35f151a9\\/32c30ce1\\/integratenyc.png\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/e1f349b8f71d7918823a915bc346cba2\\/1b9b05c4\"}]', '', 'City-Wide', NULL, NULL),
('recesAOWls7SApJGv', 'Open Letter ICE to Release Detained Given the Risk of Covid-19', 'advocacy', NULL, 'Open Letter to ICE From Medical Professionals Urging the Release of Individuals in Immigration Detention Given the Risk of COVID-19', NULL, NULL, 'https://docs.google.com/forms/d/e/1FAIpQLScB1YLk-MHzdJ2ahcxx25NRRqEnlRTk_xA6Q6cJTfZXUQV-Dg/viewform?fbclid=IwAR1iasrAvT7Sic6Jl207PBTHYXa05z7i-4HfX7PNN5Eo--COeu25_EhaAMg', '[]', '', 'National', NULL, NULL),
('recfHBHQeRcW9cAI6', 'Support Chinatown', 'food', NULL, '#SupportChinatown #DineInChinatown\nBusinesses that are operating takeout or delivery operations in Chinatown. We want to support small-time business owners during these hard times. \n\nIf you see an error with the data email stanley@zheng.nyc or DM on twitter/instagram stanleyzheng.nyc', NULL, 'stanley@zheng.nyc', 'https://airtable.com/shrHnwJcyTgBEgy1w/tbleGbrYB4pbkRLFu?blocks=hide', '[]', 'MN27', 'Manhattan: Chinatown', NULL, NULL),
('recFT1ZRQpSfmyCpw', 'Invisible Hands: Safe, free deliveries', 'delivery/transport', NULL, 'Invisible Hands is an initiative run by full-time volunteers: Simone Policano, Liam Elkind, Mimi Aboubaker, and Healy Chait. Invisible Hands will be offering delivery of groceries, prescriptions, and other general errands for elderly, immunocompromised, or otherwise at-risk individuals. ', 'invisiblehandsdeliver@gmail.com', NULL, 'InvisibleHandsDeliver.com', '[]', '', 'City-Wide', NULL, NULL),
('recHXtyGvYDk3Pi7f', 'Tenants’ Rights Telephone Hotline: 212-979-0611', 'housing', NULL, 'Call the Tenants\' Rights Telephone Hotline: 212-979-0611 to ask questions about\n- getting repairs from negligent landlords\n- getting adequate heat in the winter months\n- dealing with the threat of eviction\n- questions about leases and lease renewals\n- legal rent increases for rent-regulated apartments', NULL, NULL, 'https://www.metcouncilonhousing.org/program/tenants-rights-hotline/', '[{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/8f87e0473ec23ba775ca9e959c959063\\/86af555b\\/METCouncilonHousing.png\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/9783c9f1e59e6bc3023dc236a24a9b52\\/7ef92270\"}]', '', 'City-Wide', NULL, NULL),
('reci1lcFfvc3ZhljN', 'Learn at Home / Online Resources from the NYC Department of Education', 'educational', NULL, '5-10 days of educational materials by grade level, complete with:\n- Suggested daily study schedules\n- Guides and materials for instructional activities\n- Recommended educational television shows\n- Links to a variety of books, magazines, and websites on a wide range of topics', NULL, NULL, 'https://www.schools.nyc.gov/learning/learn-at-home', '[]', '', 'City-Wide', NULL, NULL),
('recI7MVttkl9wBkYp', 'Mouse Open Projects: Free, DIY Creative Tech/STEM projects for students at home', 'educational', NULL, 'Mouse Open Projects allows students to keep engaging with STEM and computer science in school or at home. We selected several creative tech projects that students can work on at home without an account. Each project has a lesson plan and step by step instructions, so students can work by themselves or with an adult. Most activities require only a device with internet connection and browser-based tools or craft materials. ', NULL, 'Meredith@mouse.org', 'https://projects.mouse.org', '[{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/c54446ca14f125d2e0bf08a876efc44c\\/41ebf561\\/Screen-Shot-2020-03-14-at-2.34.29-PM.png\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/68ad525e094c91431124418fba5f925b\\/ab25d0c1\"}]', '', 'City-Wide', NULL, NULL),
('reciD8wPra4IxLKxQ', 'Food Hub NYC: Get food / Donate food', 'food', NULL, 'Hub of food related resources including list of restaurants donating food.', NULL, NULL, 'bit.ly/foodhubnyc', '[]', '', 'City-Wide', NULL, NULL),
('reciGzR3hnkaZfely', 'Coronavirus Hotline for New York State: 1-888-364-3065', 'information', NULL, 'Call the Novel Coronavirus Hotline 24/7 at 1-888-364-3065.\n\nFor Updates:\nEnglish — Text COVID to 692-692\nSpanish — Text COVIDESP to 692-692', NULL, NULL, 'https://coronavirus.health.ny.gov/home', '[{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/6a656c6404e79dbb9c1ee3b8ff48af1e\\/8afc61dd\\/NYSDepartmentofHealth.png\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/c70ab05272d91fd3d3db29e1da6ec38e\\/66d87ed5\"}]', '', 'City-Wide', NULL, NULL),
('recJIGvVvjMycqgbu', 'NYC Well: Free & Confidential Mental Health', 'mental health', NULL, 'Call 1-888-NYC-Well for Free & Confidential Mental Health + Substance Use Support or text \"WELL\" to 65173.', NULL, NULL, 'https://nycwell.cityofnewyork.us/en/', '[{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/0a84586ef6da844b4f6d139a05e241e4\\/c2c831b9\\/nyc-well-logo.png\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/bcf6b0ad270efe7f9235cde6398becb6\\/82f19120\"}]', '', 'City-Wide', NULL, NULL),
('recKa8SfzCFxb4a6D', 'Remote Learning Device Request by the NYC Department of Education', 'digital access', NULL, 'To help students stay connected during emergencies, the DOE is lending internet-enabled iPads to support remote learning for students. If you would like to request a device for a NYC student in your family, please fill out the form. The DOE will use the contact information you provide to get in touch with you to discuss when and where you can pick up a device. Priority will be given to students most in need, and all devices are granted on a temporary basis and will later need to be returned. There is a limit of one device per student.', NULL, NULL, 'https://coronavirus.schools.nyc/RemoteLearningDevices', '[{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/b71c6bc48bc5185247a05c335feea072\\/51fad8df\\/ScreenshotofGoogleChrome3-18-2012-00-48PM.png\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/dc3a5de7633f6c41c1b26466cd803d0d\\/0fa51014\"}]', '', 'City-Wide', NULL, NULL),
('reclm3TiDh0O17JXs', 'Food Bank for New York City: Search for a soup kitchen or food pantry near you', 'food', NULL, 'Search for a soup kitchen or food pantry near you and/or sign up to volunteer. ', NULL, NULL, 'https://www.foodbanknyc.org/covid-19/', '[{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/c90782c8e6af2de53fd4461960f4ca89\\/112c7066\\/food_bank_for_new_york_city.png\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/0a6b857dddb366bda2346eb832f3c8f8\\/d8cba350\"}]', '', 'City-Wide', NULL, NULL),
('recrSkPQ6QPU1u5Dr', 'Get Free Internet', 'digital access', NULL, 'List of U.S Providers Offering FREE Wi-Fi or Special Accommodations for 60 Days / Proveedores de EE. UU. Que ofrecen Wi-Fi GRATUITO o alojamiento especial durante 60 días\n', NULL, NULL, 'https://docs.google.com/document/d/1kjVFeWefjnEfUrCR2yXxOvsp6_rDOfaMivUEiaBreiA/edit', '[]', '', 'National', NULL, NULL),
('rectBgkR9p03Sburv', 'GG\'s Social Trade & Treasure Club', 'mental health,information,Coordination', NULL, 'Bushwick Social Center helping to coordinate mutual aid efforts, organize locally, and offering peer support. ', 'info@ggssocialclub.com', NULL, 'ggssocialclub.com', '[]', 'BK77', 'Brooklyn: Bushwick North', NULL, NULL),
('recVEa3Sa50XTU8xK', 'HappyNumbers.com', 'educational', NULL, 'During a school closing, Happy Numbers is here to work with you to ensure the uninterrupted education of PK-5 students. We’ll help you deliver quality math instruction and monitor progress and math growth - all remotely. To ease the challenge of transitioning to online instruction, Happy Numbers is offering free access for the rest of the school year - no strings attached.\n\nWe also partnered with Clever to streamline the rostering of the classrooms.', 'ed@happynumbers.com', NULL, 'https://happynumbers.com/', '[{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/4d28a5c115f4feda3d4eaaef11e2e305\\/0969014a\\/Logo_Dino.png\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/ed046a93b399628791b2de0b3272f6a9\\/5037aef8\"},{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/262c89d7d9f419b3fad281bb5080ea00\\/9905372c\\/Bring-Happy-Numbers-to-your-class.pdf\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/5cd41a60e0d2860436de8e23ca50f942\\/99ad89a2\"},{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/aed352a2c42433d7108f46ab687e4fd6\\/0ec3a9ec\\/Create-your-Teacher-Account.pdf\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/6e488d1154bff8c5a2371d398cc81f9d\\/2c6d659a\"},{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/8d7154f3440e6bcceba79586011f4f37\\/569b9d80\\/HN-students-account.pdf\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/040cf02950697c97cde3c2382ebd9674\\/8e8b4ce8\"}]', '', 'National', NULL, NULL),
('recvQK8Lv8XKxDLbf', 'Petition Governor Andrew Cuomo: Suspend Rent, Mortgage, & Utility Payments During the Coronavirus Crisis', 'advocacy,housing', NULL, 'Sign this petition for Governor Andrew Cuomo to suspend rent, mortgage, & utility payments during the coronavirus crisis.\n\nTo New York Governor Andrew Cuomo:\n\nCOVID-19 (also known as coronavirus) has been classified as a global pandemic. New York already has at least 732 cases statewide, including 6 deaths. State and federal officials are encouraging people who feel sick to stay home, but many workers already struggle to make rent or mortgage payments. The choice to skip work for the sake of community health could leave them and their families unsheltered.\n\nIn order to protect the health and housing security of our community, we, the undersigned, call on Governor Cuomo to act now so workers won\'t have to make that choice. Specifically, we call for a suspension of all rent, mortgage, and utility payments for 2 full months to allow people to do what they need to in order to take care of themselves, their loved ones, and the community. \n\nThe legacy of every public official currently serving will be determined in the next few months.\nIt\'s time to act now, and choose the right side of history. Choose the people.\n\nSign here: https://docs.google.com/forms/d/e/1FAIpQLScmQbI-TeRnFeAm6L7VxpzHEPhWXnuW9QsmM_w9ZqhRu4SwFA/viewform?fbclid=IwAR1HvjyiT1WcYWK-lIEGs3BjM8apKeTOGtveHqhsYK6UWI9nijfr3F-4cnU', NULL, NULL, 'https://docs.google.com/forms/d/e/1FAIpQLScmQbI-TeRnFeAm6L7VxpzHEPhWXnuW9QsmM_w9ZqhRu4SwFA/viewform?fbclid=IwAR1HvjyiT1WcYWK-lIEGs3BjM8apKeTOGtveHqhsYK6UWI9nijfr3F-4cnU', '[{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/77fa14f3c20b7e038da749403bf0af8d\\/28eaeddc\\/ScreenshotofGoogleChrome3-17-204-45-27PM.png\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/c0edacc3ae81f7563a154f1f54192ce0\\/a6d8a28f\"}]', '', 'City-Wide', NULL, NULL),
('recXbTZ4y43fWtspV', 'NYC Survey for Nightlife Workers, Freelancers and Businesses Impacted by COVID-19', 'Politician,Document', NULL, 'The NYC Office of Nightlife is gathering information on the impacts for workers, performers, contractors, and businesses from COVID-19-related business closures and event cancellations. ', NULL, NULL, 'https://docs.google.com/forms/d/e/1FAIpQLSfNOye6aCd4Z1xbfYZ1X8cpYye-v8ZW1azFMEgCXMZ_O7HB7A/viewform', '[]', '', 'City-Wide', NULL, NULL),
('recxXMhqClUUEDnn2', 'Circletime: Enrichment Classes from Home (0-8yo)', 'educational', NULL, 'Circletime aims to relieve some of the burden of parenting - specifically, keeping your kids engaged. Enrichment classes are available for kids and parents that everyone can participate in at home. Such classes as Yoga Adventure, Interactive Storytime, Playing with Colors, etc, can be watched on demand or live. \nUse code HOMEFUN8 for free access during #QuarantineLife.', 'hello@circletimefun.com', NULL, 'https://circletimefun.com', '[{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/5b24d6414ded652abf384b3bdc7c690f\\/fe281c7f\\/circletime-rec.png\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/cdb7943728e1a1293b089832e802fe83\\/03558010\"}]', '', 'City-Wide', NULL, NULL),
('reczVLPEW0Rvimiuv', 'Comida para Estudiantes y Familias', 'food', NULL, 'Comida para Estudiantes y Familias. Website in Spanish for Food Access. ', NULL, NULL, 'https://tinyurl.com/vz3tg2r', '[{\"url\":\"https:\\/\\/dl.airtable.com\\/.attachments\\/98b70e3caf72a3856a95afedb35ba152\\/188d0979\\/ScreenshotofGoogleChrome3-17-201-46-44PM.png\",\"icon\":\"https:\\/\\/dl.airtable.com\\/.attachmentThumbnails\\/27dc4ec9e073849a79ee920f791dda95\\/9e9156dc\"}]', '', 'City-Wide', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dd`
--

CREATE TABLE IF NOT EXISTS `dd` (
  `id` varchar(25) NOT NULL,
  `date` datetime DEFAULT NULL,
  `plateNum` varchar(10) DEFAULT NULL,
  `plateState` varchar(10) DEFAULT NULL,
  `capID` varchar(40) DEFAULT NULL,
  `type` varchar(40) DEFAULT NULL,
  `lat` decimal(12,9) DEFAULT NULL,
  `lng` decimal(12,9) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `note` varchar(64) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `img1` varchar(200) DEFAULT NULL,
  `img2` varchar(200) DEFAULT NULL,
  `img3` varchar(200) DEFAULT NULL,
  `img4` varchar(200) DEFAULT NULL,
  `tweetLink` varchar(200) DEFAULT NULL,
  `commentLink` varchar(200) DEFAULT NULL,
  `cd` int(11) DEFAULT NULL,
  `pp` int(11) DEFAULT NULL,
  `dsny` int(11) DEFAULT NULL,
  `fb` int(11) DEFAULT NULL,
  `sd` int(11) DEFAULT NULL,
  `hc` int(11) DEFAULT NULL,
  `cc` int(11) DEFAULT NULL,
  `nycongress` int(11) DEFAULT NULL,
  `sa` int(11) DEFAULT NULL,
  `ss` int(11) DEFAULT NULL,
  `nta` varchar(150) DEFAULT NULL,
  `zipcode` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `date` (`date`),
  KEY `plateNum` (`plateNum`),
  KEY `capID` (`capID`),
  KEY `type` (`type`),
  KEY `cd` (`cd`),
  KEY `pp` (`pp`),
  KEY `dsny` (`dsny`),
  KEY `fb` (`fb`),
  KEY `sd` (`sd`),
  KEY `hc` (`hc`),
  KEY `cc` (`cc`),
  KEY `nycongress` (`nycongress`),
  KEY `sa` (`sa`),
  KEY `ss` (`ss`),
  KEY `zipcode` (`zipcode`),
  KEY `nta` (`nta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dd`
--

INSERT INTO `dd` (`id`, `date`, `plateNum`, `plateState`, `capID`, `type`, `lat`, `lng`, `address`, `note`, `message`, `img1`, `img2`, `img3`, `img4`, `tweetLink`, `commentLink`, `cd`, `pp`, `dsny`, `fb`, `sd`, `hc`, `cc`, `nycongress`, `sa`, `ss`, `nta`, `zipcode`) VALUES
('rec2JU410poEil7xO', '2020-02-22 19:21:41', NULL, NULL, NULL, NULL, '40.674445607', '-73.951127241', NULL, 'This is private message.', 'This is public let’s make it pop.', 'https://formio-upload-aws.s3.amazonaws.com/5a8e6a2e-e04e-4bad-a44c-2fb9c960527a', 'https://formio-upload-aws.s3.amazonaws.com/404f2598-742b-43c4-81a5-2e89c57ecf8d', 'https://formio-upload-aws.s3.amazonaws.com/466e6aa4-e3ca-496a-a398-b5711909647a', 'https://formio-upload-aws.s3.amazonaws.com/3211561a-b565-4e34-a4a1-f04402d0f036', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('rec99an6YzF2miur4', '2020-03-09 16:26:42', NULL, NULL, NULL, 'Placard Abuse', '40.674445607', '-73.951127241', '134 Spring St, New York, NY 10012, USA', '', 'This is a test via forimio', 'https://formio-upload-aws.s3.amazonaws.com/5a8e6a2e-e04e-4bad-a44c-2fb9c960527a', 'https://formio-upload-aws.s3.amazonaws.com/404f2598-742b-43c4-81a5-2e89c57ecf8d', 'https://formio-upload-aws.s3.amazonaws.com/466e6aa4-e3ca-496a-a398-b5711909647a', 'https://formio-upload-aws.s3.amazonaws.com/3211561a-b565-4e34-a4a1-f04402d0f036', NULL, NULL, 102, 1, 102, 2, 2, 15, 1, 10, 66, 26, 'SoHo-TriBeCa-Civic Center-Little Italy', 10012),
('recAZ7ivZXi2typah', '2020-02-21 02:15:57', NULL, NULL, '1XXXd-00', 'Capital Project', '40.761011253', '-73.985001466', '219 West 49th Street, New York, NY 10019', '', 'This is a great thing yay.', 'https://imaging.broadway.com/images/custom/w1200/84331-16.jpg', 'https://imaging.broadway.com/images/custom/w1200/96229-13.jpg', 'https://imaging.broadway.com/images/custom/w1200/84330-12.jpg', NULL, 'https://www.broadway.com/shows/chicago/', NULL, 105, 18, 105, 9, 2, 15, 3, 12, 75, 27, 'Midtown-Midtown South', 10019),
('recczj3QkbbTiQdem', '2020-03-07 15:33:12', 'bbb11', NULL, NULL, 'Placard Abuse', '40.760369036', '-73.985413119', '219 West 48th Street, New York, NY 10036', '', 'Welcome to Hadestown, where a song can change your fate. This acclaimed new musical by celebrated singer-songwriter Anaïs Mitchell and innovative director Rachel Chavkin (Natasha, Pierre & The Great Comet of 1812) is a love story for today… and always.\n\nHadestown intertwines two mythic tales—that of young dreamers Orpheus and Eurydice, and that of King Hades and his wife Persephone—as it invites you on a hell-raising journey to the underworld and back. Mitchell’s beguiling melodies and Chavkin’s poetic imagination pit industry against nature, doubt against faith, and fear against love. Performed by a vibrant ensemble of actors, dancers and singers, Hadestown is a haunting and hopeful theatrical experience that grabs you and never lets go.', 'https://imaging.broadway.com/images/custom/w1200/107800-15.jpg', 'https://imaging.broadway.com/images/custom/w1200/107797-25.jpg', NULL, NULL, 'https://www.broadway.com/shows/hadestown/', NULL, 105, 18, 105, 9, 2, 15, 3, 12, 75, 27, 'Midtown-Midtown South', 10036),
('recEMf3z4Uvhdhj28', '2020-03-14 05:47:16', NULL, NULL, NULL, NULL, '40.728623819', '-73.978999155', NULL, '', '', 'https://formio-upload-aws.s3.amazonaws.com/a8227838-4fe7-48ff-8954-30759798d8eb', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('recgWFBzbOG5OzBc9', '2020-03-09 05:30:19', NULL, NULL, NULL, NULL, '40.727620707', '-73.981728971', NULL, '', 'This is a test. Many like it.', 'https://formio-upload-aws.s3.amazonaws.com/5a8e6a2e-e04e-4bad-a44c-2fb9c960527a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('recHrLT3kDd0XKifK', '2020-03-09 05:56:49', NULL, NULL, NULL, 'Placard Abuse', '40.727605508', '-73.981762743', NULL, '', 'This is a test ya you', 'https://formio-upload-aws.s3.amazonaws.com/3a68bf2f-e87a-4aeb-ae46-93965db98025', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('rechu7kFOSG9oGQFm', '2020-03-07 15:33:14', 'fff444', NULL, NULL, 'Placard Abuse', '40.761565548', '-73.983943674', '1634 Broadway, New York, NY 10019', '', 'Directed by Alex Timbers (Moulin Rouge!), Beetlejuice tells the story of Lydia Deetz, a strange and unusual teenager whose whole life changes when she meets a recently deceased couple and a demon with a thing for stripes. And under its uproarious surface (six feet under, to be exact), it’s a remarkably touching show about family, love, and making the most of every Day-O!', 'https://imaging.broadway.com/images/custom/w1200/108113-16.jpg', 'https://imaging.broadway.com/images/custom/w1200/112434-18.jpg', 'https://imaging.broadway.com/images/custom/w1200/108116-22.jpg', 'https://imaging.broadway.com/images/custom/w1200/108119-18.jpg', 'https://www.broadway.com/shows/beetlejuice/', NULL, 105, 18, 105, 9, 2, 15, 4, 12, 75, 27, 'Midtown-Midtown South', 10019),
('recOvXqX9FM7jN6eg', '2020-03-07 22:46:19', NULL, NULL, '1TTT-zziz', 'Capital Project', '40.758038786', '-73.985716835', '200 West 45th Street New York, NY 10036', 'A lively stage adaptation o', 'When an unthinkable tragedy, orchestrated by Simba’s wicked uncle, Scar, takes his father’s life, Simba flees the Pride Lands, leaving his loss and the life he knew behind.', 'https://imaging.broadway.com/images/custom/w1200/103890-23.jpg', 'https://imaging.broadway.com/images/custom/w1200/103888-28.jpg', 'https://imaging.broadway.com/images/custom/w1200/103889-21.jpg', 'https://imaging.broadway.com/images/custom/w1200/103892-17.jpg', 'https://www.broadway.com/shows/the-lion-king/', NULL, 105, 14, 105, 9, 2, 15, 3, 12, 75, 27, 'Midtown-Midtown South', 10036),
('recoXHm4ZLqZXZgBQ', '2020-02-20 23:24:00', 'bbb11', NULL, NULL, 'Placard Abuse', '40.723874700', '-74.000456800', '249 West 45th Street, New York, NY 10036', '', 'Oops.. Again', NULL, NULL, NULL, NULL, NULL, NULL, 105, 18, 105, 9, 2, 15, 3, 12, 75, 27, 'Midtown-Midtown South', 10036),
('recrGUjxPLQEW9G3e', '2020-03-09 16:24:16', NULL, NULL, NULL, 'Placard Abuse', '40.732922132', '-73.991185085', '813 Broadway, New York, NY, USA', '', 'Another fun test', 'https://formio-upload-aws.s3.amazonaws.com/6a0975fc-f9fb-41e6-8699-f9562ebff163', NULL, NULL, NULL, NULL, NULL, 102, 6, 102, 6, 2, 15, 2, 12, 66, 28, 'West Village', 10003),
('recRwKYIS4lf6Q8MH', '2020-03-09 05:30:04', NULL, NULL, NULL, NULL, '40.727620707', '-73.981728971', NULL, '', 'This is a test. Many like it.', 'https://formio-upload-aws.s3.amazonaws.com/d934603a-bc00-4e48-b221-86a1e765a7b5', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('recsmt8zIWnYNyT8w', '2020-03-09 05:04:52', 'fff444', NULL, NULL, 'Placard Abuse', '40.761565548', '-73.983943674', '1634 Broadway, New York, NY 10019', '', 'When an unthinkable tragedy, orchestrated by Simba’s wicked uncle, Scar, takes his father’s life, Simba flees the Pride Lands.', 'https://imaging.broadway.com/images/custom/w1200/108113-16.jpg', 'https://imaging.broadway.com/images/custom/w1200/112434-18.jpg', 'https://imaging.broadway.com/images/custom/w1200/108116-22.jpg', 'https://imaging.broadway.com/images/custom/w1200/108119-18.jpg', NULL, NULL, 105, 18, 105, 9, 2, 15, 4, 12, 75, 27, 'Midtown-Midtown South', 10019),
('recVOgTw5jeUz8zbQ', '2020-03-09 05:44:53', NULL, NULL, NULL, 'Placard Abuse', '40.674445607', '-73.951127241', 'add', '', 'This is a test via forimio', 'https://formio-upload-aws.s3.amazonaws.com/5a8e6a2e-e04e-4bad-a44c-2fb9c960527a', 'https://formio-upload-aws.s3.amazonaws.com/404f2598-742b-43c4-81a5-2e89c57ecf8d', 'https://formio-upload-aws.s3.amazonaws.com/466e6aa4-e3ca-496a-a398-b5711909647a', 'https://formio-upload-aws.s3.amazonaws.com/3211561a-b565-4e34-a4a1-f04402d0f036', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('recvT8L7pGouISOen', '2020-03-09 05:45:40', NULL, NULL, NULL, 'Placard Abuse', '40.674445607', '-73.951127241', '134 Spring St, New York, NY 10012, USA', '', 'This is a test via forimio', 'https://formio-upload-aws.s3.amazonaws.com/5a8e6a2e-e04e-4bad-a44c-2fb9c960527a', 'https://formio-upload-aws.s3.amazonaws.com/404f2598-742b-43c4-81a5-2e89c57ecf8d', 'https://formio-upload-aws.s3.amazonaws.com/466e6aa4-e3ca-496a-a398-b5711909647a', 'https://formio-upload-aws.s3.amazonaws.com/3211561a-b565-4e34-a4a1-f04402d0f036', NULL, NULL, 102, 1, 102, 2, 2, 15, 1, 10, 66, 26, 'SoHo-TriBeCa-Civic Center-Little Italy', 10012),
('recxIzK4qapvTJE8F', '2020-03-09 05:01:54', 'fff444', NULL, NULL, 'Placard Abuse', '40.761565548', '-73.983943674', '1634 Broadway, New York, NY 10019', '', 'When an unthinkable tragedy, orchestrated by Simba’s wicked uncle, Scar, takes his father’s life, Simba flees the Pride Lands.', 'https://imaging.broadway.com/images/custom/w1200/108113-16.jpg', 'https://imaging.broadway.com/images/custom/w1200/112434-18.jpg', 'https://imaging.broadway.com/images/custom/w1200/108116-22.jpg', 'https://imaging.broadway.com/images/custom/w1200/108119-18.jpg', NULL, NULL, 105, 18, 105, 9, 2, 15, 4, 12, 75, 27, 'Midtown-Midtown South', 10019),
('recXOGPjAIjdd0PEv', '2020-02-20 23:24:00', 'yyyyy', NULL, NULL, 'Placard Abuse', '40.723746750', '-74.000248934', '123 Spring St, NY, NY 10012', 'Hi', 'This is for everyone!', 'https://dl.airtable.com/.attachments/c6c667882df5b7bd29f4ca047c8ca6da/9a950478/ERPI9vRWAAAv4Mr.jpg', 'https://dl.airtable.com/.attachments/a6b870aa4d71459fa18da1389bdd0ac2/375e1ed4/ERPI9D1XsAAMtWe.jpg', 'https://dl.airtable.com/.attachments/a6b870aa4d71459fa18da1389bdd0ac2/375e1ed4/ERPI9D1XsAAMtWe.jpg', 'https://dl.airtable.com/.attachments/a6b870aa4d71459fa18da1389bdd0ac2/375e1ed4/ERPI9D1XsAAMtWe.jpg', NULL, NULL, 102, 1, 102, 2, 2, 15, 1, 10, 66, 26, 'SoHo-TriBeCa-Civic Center-Little Italy', 10012),
('recxWzy3Pjo0D1JA4', '2020-03-07 15:30:54', 'aaaaa', NULL, NULL, 'Placard Abuse', '40.758014306', '-73.987640753', '246 West 44th Street New York, NY 10036', '', 'Frozen is the timeless tale of two sisters, pulled apart by a mysterious secret. As one young woman struggles to find her voice and harness her powers within, the other embarks on an epic adventure to bring her family together once and for all. Both are searching for love. They just don\'t know where to find it.', 'https://imaging.broadway.com/images/poster-178275/w230/222222/116481-3.jpg', 'https://imaging.broadway.com/images/custom/w1200/116482-18.jpg', NULL, NULL, 'https://www.broadway.com/shows/frozen/', NULL, 105, 14, 105, 7, 2, 15, 3, 12, 75, 27, 'Midtown-Midtown South', 10036);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
