-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 06 sep. 2022 à 16:32
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet4`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `addBillet`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `addBillet` (IN `title` TEXT, IN `content` TEXT, IN `users_id` INT, IN `publish_at` TIMESTAMP, IN `published` BOOLEAN)  begin
        INSERT INTO projet4.billets(title, content, users_id, publish_at, published) 
            VALUES (title, content, users_id, publish_at, published);       
    end$$

DROP PROCEDURE IF EXISTS `addComment`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `addComment` (IN `content` VARCHAR(255), IN `users_id` INT, IN `billet_id` INT, IN `publish_at` TIMESTAMP)  begin
        INSERT INTO projet4.comments(content, users_id, billet_id, publish_at) 
            VALUES (content, users_id, billet_id, publish_at);       
    end$$

DROP PROCEDURE IF EXISTS `addUser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `addUser` (IN `email` VARCHAR(128), IN `pwd` VARCHAR(128), IN `pseudo` VARCHAR(128), IN `isAuthor` BOOLEAN, OUT `result` VARCHAR(128), OUT `isError` TINYINT)  begin
    DECLARE EXIT HANDLER FOR 1062
        begin
            SET result = concat_ws(' ', email, 'Utilisateur déjà enregistré');
            SET isError = 1; 
        end;

    if isAuthor THEN
        INSERT INTO projet4.users(email, password, pseudo, role)
            VALUES (email, pwd, pseudo, 10);
    else
        INSERT INTO projet4.users(email, password, pseudo, role) 
            VALUES (email, pwd, pseudo, 20);
    end if;
    SET result = concat_ws(' ', email, 'Utilisateur enregistré');
    SET isError = 0;
  end$$

DROP PROCEDURE IF EXISTS `dropBilletByID`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `dropBilletByID` (IN `billet_id` INT, OUT `result` VARCHAR(128))  begin
        declare code CHAR(5) DEFAULT '00000';
        declare msg TEXT;
        declare CONTINUE HANDLER FOR SQLEXCEPTION
            begin get diagnostics condition 1
                code = returned_sqlstate, msg = message_text;
            end;
        DELETE from projet4.billets where id = billet_id;
        if code = '00000' THEN
            set result = 'done';
        else
            set result = 'pas done';
        end if;
    end$$

DROP PROCEDURE IF EXISTS `dropCommentByID`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `dropCommentByID` (IN `comment_id` INT, OUT `result` VARCHAR(128))  begin
        declare code CHAR(5) DEFAULT '00000';
        declare msg TEXT;
        declare CONTINUE HANDLER FOR SQLEXCEPTION
            begin get diagnostics condition 1
                code = returned_sqlstate, msg = message_text;
            end;
        DELETE from projet4.comments where id = billet_id;
        if code = '00000' THEN
            set result = 'done';
        else
            set result = 'pas done';
        end if;
    end$$

DROP PROCEDURE IF EXISTS `dropUserById`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `dropUserById` (IN `userId` INT, OUT `result` VARCHAR(128))  begin
    DECLARE code CHAR(5) DEFAULT '00000';
    DECLARE msg TEXT;
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
      begin
          get diagnostics condition 1
              code = returned_sqlstate, msg = message_text;
      end;
    DELETE from projet4.users where id = userId;
    if code = '00000' THEN
        set result = 'done';
    else
        set result = "pas done";
    end if;
  end$$

DROP PROCEDURE IF EXISTS `findFirstAuthorID`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `findFirstAuthorID` (OUT `authorid` INT, OUT `authoremail` VARCHAR(128))  begin
    select id, email into authorid, authoremail from projet4.users
      where role = 10 limit 1;    
  end$$

DROP PROCEDURE IF EXISTS `findFirstBillet`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `findFirstBillet` (OUT `zedate` TIMESTAMP, OUT `billid` INT)  begin
        select publish_at, id into zedate, billid from projet4.billets order by 1 asc limit 1;
    end$$

DROP PROCEDURE IF EXISTS `findFirstComment`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `findFirstComment` (OUT `zedate` TIMESTAMP, OUT `commid` INT)  begin
        select publish_at, id into zedate, commid from projet4.comments order by 1 asc limit 1;
    end$$

DROP PROCEDURE IF EXISTS `findFirstReaderID`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `findFirstReaderID` (OUT `readerid` INT)  begin
    select id into readerid from projet4.users 
      where role = 20 limit 1;
  end$$

DROP PROCEDURE IF EXISTS `findLastBillet`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `findLastBillet` (OUT `zedate` TIMESTAMP, OUT `billid` INT)  begin
        select publish_at, id into zedate, billid from projet4.billets order by 1 desc limit 1;
    end$$

DROP PROCEDURE IF EXISTS `findLastComment`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `findLastComment` (OUT `zedate` TIMESTAMP, OUT `commid` INT)  begin
        select publish_at, id into zedate, commid from projet4.comments order by 1 desc limit 3;
    end$$

DROP PROCEDURE IF EXISTS `loadBillets`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `loadBillets` (IN `numberOfBillets` INT, IN `startIndex` INT)  begin
        declare x INT;
        declare title TEXT;
        declare content TEXT;
        declare users_id INT;
        declare publish_at TIMESTAMP;
        declare published BOOLEAN;

        set x = 0;
        set title = '';
        set content = '';
        set users_id = projet4.ffAuthorID();
        set published = true;
        
        loop_billets : LOOP
            SET x = x + 1;
            if x > numberOfBillets THEN
                leave loop_billets;
            end if;

            set title = CONCAT('title',x + startIndex - 1);
            set content = CONCAT('content',x + startIndex - 1);
            set publish_at = projet4.randomdate();
            call projet4.addBillet(title, content, users_id, publish_at, published);
        end LOOP;
    end$$

DROP PROCEDURE IF EXISTS `loadComments`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `loadComments` (IN `numberOfComments` INT, IN `startIndex` INT)  begin
        declare x INT;
        declare content VARCHAR(255);
        declare users_id INT;
        declare billet_id INT;
        declare publish_at TIMESTAMP;

        set x = 0;
        
        loop_comments : LOOP
            SET x = x + 1;
            if x > numberOfComments THEN
                leave loop_comments;
            end if;

            set content = CONCAT('comment',x + startIndex - 1);
            set users_id = projet4.randomReader();
            set billet_id = projet4.randomBillet();
            set publish_at = projet4.randomdate();
            call projet4.addComment(content, users_id,billet_id, publish_at);
        end LOOP;
    end$$

DROP PROCEDURE IF EXISTS `loadusers`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `loadusers` (IN `numberOfUsers` INT, IN `startIndex` INT, IN `isAuthor` BOOLEAN)  begin
    declare x INT;
    declare pseudo VARCHAR(128);
    declare email VARCHAR(128);
    declare rootname VARCHAR(128);
    declare pwd VARCHAR(128);
    declare result VARCHAR(128);
    declare isError TINYINT;

    set x = 0;
    set pseudo = '';
    set pwd = '1234';
    set email = '';

    if isAuthor THEN
      set rootname = 'auteur';
    else
      set rootname = 'utilisateur';
    end if;

    loop_toto: LOOP
        SET x = x + 1;
        if x > numberOfUsers THEN
            leave loop_toto;
        end if;

        SET pseudo = CONCAT(rootname,x + startIndex - 1);
        SET email = CONCAT(pseudo,'@free.fr');
        
        call projet4.addUser(email, pwd, pseudo, isAuthor, result, isError);
        if isError = 1 THEN
            select result;
        end if;
    end LOOP;
end$$

--
-- Fonctions
--
DROP FUNCTION IF EXISTS `ffAuthorID`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `ffAuthorID` () RETURNS INT(11) begin
      declare authorid INT;
      select id into authorid from projet4.users
        where role = 10 limit 1;
      return authorid;    
  end$$

DROP FUNCTION IF EXISTS `findUserByPseudo`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `findUserByPseudo` (`userpseudo` VARCHAR(128)) RETURNS INT(11) begin
    declare userId INT;
    DECLARE EXIT HANDLER FOR NOT FOUND
        begin
            return 9999;
        end;
    select id into userId from projet4.users
      where pseudo = userpseudo;
    return userId;
  end$$

DROP FUNCTION IF EXISTS `randomBillet`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `randomBillet` () RETURNS INT(11) begin
    declare toto INT;
        select id into toto from projet4.billets where published=1 order by rand() limit 1;
        return toto;
    end$$

DROP FUNCTION IF EXISTS `randomdate`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `randomdate` () RETURNS TIMESTAMP begin
    declare toto timestamp;
    SELECT CURRENT_TIMESTAMP - INTERVAL FLOOR(RAND() * 30 * 24 * 60 * 60) second into toto;
    return toto;
  end$$

DROP FUNCTION IF EXISTS `randomReader`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `randomReader` () RETURNS INT(11) begin
        declare toto INT;
        select id into toto from projet4.users where role=20 order by rand() limit 1;
        return toto;
    end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `billets`
--

DROP TABLE IF EXISTS `billets`;
CREATE TABLE IF NOT EXISTS `billets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `abstract` varchar(2048) NOT NULL,
  `chapter` text NOT NULL,
  `publish_at` datetime NOT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `users_id` int(11) NOT NULL,
  `thumbs_up` int(11) NOT NULL DEFAULT '0',
  `thumbs_down` int(11) NOT NULL DEFAULT '0',
  `chapter_picture` varchar(64) NOT NULL DEFAULT 'default.jpg',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `fk_billets_to_users` (`users_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `billets`
--

INSERT INTO `billets` (`id`, `title`, `abstract`, `chapter`, `publish_at`, `published`, `users_id`, `thumbs_up`, `thumbs_down`, `chapter_picture`) VALUES
(1, 'Le grand dÃ©part', '&#60;p&#62;C&#39;est toujours un moment particulier dans un nouveau voyage. Il ne devrait &#38;ecirc;tre qu&#39;un moment de joie mais parfois le doute et la peur viennent nous rappeler que nous ne sommes pas forc&#38;eacute;ment &#38;agrave; notre place...&#60;/p&#62;', '&#60;p style=&#34;text-align: justify;&#34;&#62;Premier jour du voyage. L&#39;excitation de la veille a laiss&#38;eacute;la place aux interrogations. J&#39;ai d&#38;eacute;j&#38;agrave; v&#38;eacute;cu &#38;ccedil;a. Mais pour la premi&#38;egrave;re fois je dois y faire face seul. Je ne dois pas c&#38;eacute;der &#38;agrave; la peur.&#38;nbsp;&#60;/p&#62;&#13;&#10;&#60;p style=&#34;text-align: justify;&#34;&#62;J&#39;attends sur le quai num&#38;eacute;ro 4. J&#39;aurais pr&#38;eacute;f&#38;eacute;r&#38;eacute; le quai neuf trois-quart, cela aurait &#38;eacute;t&#38;eacute; plus rassurant. Le train vient de me d&#38;eacute;poser &#38;agrave; Anchorage et j&#39;attends. Ou plut&#38;ocirc;t j&#39;h&#38;eacute;site. Si je remonte, rien de tout &#38;ccedil;a ne sera r&#38;eacute;el... mais quelle diff&#38;eacute;rence? Ce qui compte c&#39;est d&#39;&#38;eacute;crire. J&#39;avance vers la sortie. Chaque &#38;eacute;tape franchie m&#39;emp&#38;ecirc;che de renoncer. De toute fa&#38;ccedil;on, j&#39;ai pass&#38;eacute; trop de temps &#38;agrave; pr&#38;eacute;parer ce voyage pour renoncer. Et puis renoncer pour aller o&#38;ugrave;? Le premier h&#38;ocirc;tel digne de ce nom est &#38;agrave; 1600 km... Alors pas apr&#38;egrave;s pas, je m&#39;&#38;eacute;loigne de mon point de d&#38;eacute;part.&#38;nbsp;&#60;/p&#62;&#13;&#10;&#60;p style=&#34;text-align: justify;&#34;&#62;Toujours ces pens&#38;eacute;es... &#34;Je ne suis pas fait pour &#38;ccedil;a!&#34; &#34;Mon sac est lourd et trop petit...&#34; Comment elle disait l&#39;autre dans sa vid&#38;eacute;o Youtube? &#34;A shallow life isn&#39;t a good life&#34;... &#38;ccedil;a m&#39;apprendra &#38;agrave; tomber amoureux de la premi&#38;egrave;re inconnue qui parle de la nature et du rapport &#38;agrave; soi... Quel idiot franchement...&#38;nbsp;&#60;/p&#62;&#13;&#10;&#60;p style=&#34;text-align: center;&#34;&#62;&#60;strong&#62;LA RUEE VERS L&#39;OR...&#60;/strong&#62;&#60;/p&#62;&#13;&#10;&#60;p style=&#34;text-align: justify;&#34;&#62;Manifestement on ne s&#39;int&#38;eacute;resse &#38;agrave; moi. A chaque personne portant un sac &#38;agrave; dos que je croise je me demande si nous allons au m&#38;ecirc;me endroit. Cent ans plus t&#38;ocirc;t &#38;ccedil;a n&#39;aurait fait aucun doute. On se serait m&#38;ecirc;me probablement battu &#38;agrave; l&#39;arriv&#38;eacute;e pour une concession porteuse de tous nos espoirs. La route qui m&#38;egrave;ne vers la sortie de la ville semble d&#38;eacute;serte. C&#39;est une grosse ville pourtant, mais une ville du cercle polaire. On est bien loin des mondanit&#38;eacute;s auxquelles je suis habitu&#38;eacute;. Des magasins vendent de l&#39;&#38;eacute;quipement. Comme un ultime recours &#38;agrave; la civilisation leurs devantures m&#39;appellent, me poussant &#38;agrave; v&#38;eacute;rifier que j&#39;ai bien le strict n&#38;eacute;cessaire. Oui, mon sac d&#38;eacute;borde... et probablement pas &#38;agrave; cause du strict n&#38;eacute;cessaire.&#38;nbsp;&#60;/p&#62;&#13;&#10;&#60;p style=&#34;text-align: justify;&#34;&#62;La nuit dans le train a &#38;eacute;t&#38;eacute; correcte. J&#39;ai refus&#38;eacute; le train couchette pour commencer &#38;agrave; m&#39;habituer &#38;agrave; l&#39;absence de confort. Le changement de d&#38;eacute;cors est saisissant. Le d&#38;eacute;part &#38;agrave; la nuit tombante dans une zone urbanis&#38;eacute;e&#38;nbsp; a laiss&#38;eacute; place au petit matin aux montagnes verdoyantes qui plongent dans la mer. Il est tant maintenant de quitter la ville pour m&#39;aventurer en pleine nature.&#38;nbsp;&#60;/p&#62;&#13;&#10;&#60;p style=&#34;text-align: justify;&#34;&#62;Le chemin de for&#38;ecirc;t un peu vallonn&#38;eacute; longe la Matunaska River. Je suis content d&#39;&#38;ecirc;tre l&#38;agrave;. La marche a fait disparaitre toutes mes anxi&#38;eacute;t&#38;eacute;s r&#38;eacute;centes. Je ne ressens pas de diffcult&#38;eacute; particuli&#38;egrave;re. Mon sac est lourd mais j&#39;avance vite. Un autre randonneur me d&#38;eacute;passe. Un salut amical mais nous pr&#38;eacute;f&#38;eacute;rons manifestement marcher seuls. Il a un rythme plus &#38;eacute;lev&#38;eacute; que le mien mais il fait des pauses. Je le rattrape donc r&#38;eacute;guli&#38;egrave;rement. Mais nous n&#39;&#38;eacute;changeons jamais plus qu&#39;un regard. De toute fa&#38;ccedil;on je suis ici pour vivre mon exp&#38;eacute;rience, seul. Pas question de la partager avec d&#39;autres marcheurs.&#38;nbsp;&#60;/p&#62;&#13;&#10;&#60;p style=&#34;text-align: justify;&#34;&#62;Apr&#38;egrave;s deux heures de marche j&#39;arrive au premier camp. Il y a toute une organisation. Cuisine, toilettes, boutique, dortoirs, emplacements de camping... Il y a m&#38;ecirc;me un sauna. Pour que le camp puisse &#38;ecirc;tre entretenu on me demande 10$. On m&#39;explique que je peux camper plus loin mais il faut compter une autre heure de marche et l&#38;agrave;, sinc&#38;egrave;rement, non merci ! Je paye les 10$...&#38;nbsp;&#60;/p&#62;&#13;&#10;&#60;p style=&#34;text-align: justify;&#34;&#62;Bon comme &#38;agrave; mon habitude, quand il faut choisir un emplacement de camping, je choisis l&#39;un des pires. La tente est en pente, mal tendue, sous un arbre, donc il fait sombre et pas loin d&#39;une cabine dortoir ce qui me donne l&#39;impression d&#39;&#38;ecirc;tre chez les autres...&#38;nbsp;&#60;/p&#62;&#13;&#10;&#60;p style=&#34;text-align: justify;&#34;&#62;Je cherche &#38;agrave; participer &#38;agrave; la vie du camp mais le bois est d&#38;eacute;j&#38;agrave; coup&#38;eacute;, l&#39;eau fra&#38;icirc;che ramen&#38;eacute;e et le seau d&#39;eaux usag&#38;eacute;es d&#38;eacute;j&#38;agrave; vide. Tant pis, je sais me comporter en terrain conquis. Je fais chauffer mon eau et y plonge mes nouilles chinoises. Quel d&#38;eacute;lice, je sens d&#38;eacute;j&#38;agrave; l&#39;euphorie de l&#39;apr&#38;egrave;s-midi me quitter.&#38;nbsp;&#60;/p&#62;', '2022-09-03 18:15:58', 1, 1, 1, 1, '5dbd54e3edb2fa962572ca67f559ff6b.jpg'),
(8, 'Le lendemain matin', '&#60;p&#62;Premi&#38;egrave;re journ&#38;eacute;e de marche compl&#38;egrave;te. Bataille avec un moustique au petit d&#38;eacute;jeuner.&#38;nbsp;&#60;/p&#62;', '&#60;p&#62;Je quitte le camp vers 7h30 apr&#38;egrave;s une nuit assez agr&#38;eacute;able. Mon &#38;eacute;quipement me semble adapt&#38;eacute; et je n&#39;ai pas eu froid. J&#39;ai &#38;eacute;t&#38;eacute; attaqu&#38;eacute; par deux moustiques lors du petit d&#38;eacute;jeuner. &#34;Vous savez, il y a d&#38;eacute;j&#38;agrave; eu une premi&#38;egrave;re vague de froid, il ne doit plus rester beaucoup de moustiques&#34; m&#39;avait dit le vendeur. Il n&#39;emp&#38;ecirc;che, &#34;plus beaucoup&#34; c&#39;est d&#38;eacute;j&#38;agrave; trop! Et s&#39;il ne devait rester qu&#39;un seul moustique vivant dans tout l&#39;Alaska, il saurait d&#38;eacute;j&#38;agrave; que je suis sur son territoire et il serait en vol pour venir me g&#38;acirc;cher mon voyage. J&#39;ai achet&#38;eacute; un produit local... tr&#38;egrave;s efficace.&#38;nbsp;&#60;/p&#62;&#13;&#10;&#60;p&#62;A la sortie du camp m&#39;attendent 300m d&#39;ascension. Je passe le col apr&#38;egrave;s une heure trente de marche. Une nouvelle vall&#38;eacute;e s&#39;ouvre &#38;agrave; moi. Au pied de la montagne s&#39;&#38;eacute;tend un lac dans lequel se jettent des cascades. Je sais maintenant pourquoi je suis ici. Je suis seul, en pleine nature, je me sens bien.&#38;nbsp;&#60;/p&#62;&#13;&#10;&#60;p&#62;Il est 11h00 quand j&#39;arrive &#38;agrave; panneau qui indique le point de d&#38;eacute;part d&#39;un bateau qui permet de faire la travers&#38;eacute;e du lac. 5km. C&#39;est long 5km... Quand j&#39;arrive &#38;agrave; l&#39;embarcad&#38;egrave;re, je reconnais l&#39;endroit que j&#39;ai vu sur les vid&#38;eacute;os. Jusqu&#39;&#38;agrave; pr&#38;eacute;sent le chemin est parsem&#38;eacute; de pierres &#38;agrave; demi enterr&#38;eacute;es, pas un seul pas n&#39;est pos&#38;eacute; &#38;agrave; plat. C&#39;est un peu comme mon entra&#38;icirc;nement &#38;agrave; Fontainebleau... les 17kg de mon sac en plus sur le dos... Je d&#38;eacute;cide de profiter de l&#39;endroit pour manger. La gastronomie du randonneur n&#38;eacute;cessite de ne pas &#38;ecirc;tre exigent : des nouilles chinoises, quelques fruits secs et une infusion aux fruits rouges.&#38;nbsp;&#60;/p&#62;&#13;&#10;&#60;p&#62;Durant toute la matin&#38;eacute;e, et plus particuli&#38;egrave;rement sur la partie la plus rocailleuse, j&#39;ai &#38;eacute;t&#38;eacute; envahi par un sentiment, je ne saurais mettre un seul mot dessus, mais clairement je ne me sentais plus capable d&#39;atteindre mon objectif. Pourtant, maintenant que j&#39;ai le ventre plein et que je me suis un peu repos&#38;eacute;, j&#39;ai maintenant une sensation totalement oppos&#38;eacute;e, comme si rien ne pouvait m&#39;arr&#38;ecirc;ter. J&#39;avance sans effort depuis que j&#39;ai repris ma route. Seul mon sac me para&#38;icirc;t lourd. Au fond du lac j&#39;aper&#38;ccedil;ois le second camp.&#38;nbsp;&#60;/p&#62;&#13;&#10;&#60;p&#62;Je ne sais pas pourquoi mais j&#39;ai beau avancer dans la bonne direction, je n&#39;ai pas l&#39;impression que je me rapproche de l&#39;arriv&#38;eacute;e. Il faut que je cesse de vouloir finir avant d&#39;avoir commenc&#38;eacute;. Si cette aventure doit repr&#38;eacute;senter quelque chose, c&#39;est bien que chaque pas, petit &#38;agrave; petit, me rapproche de mon objectif, comme une lente construction.&#38;nbsp;&#60;/p&#62;&#13;&#10;&#60;p&#62;Ce second camp est un peu d&#38;eacute;cevant. Perch&#38;eacute; au sommet d&#39;un promontoire&#38;nbsp; j&#39;imaginais pouvoir planter ma tente en l&#39;orientant face au meilleur panorama. Mais le sol est rocailleux et je ne trouve aucun endroit suffisamment confortable. En revanche, en contrebas, de l&#39;autre c&#38;ocirc;t&#38;eacute; du pont suspendu, le terrain semble plus accueillant.&#38;nbsp;&#60;/p&#62;&#13;&#10;&#60;p&#62;J&#39;ai discut&#38;eacute; avec un allemand de Berlin. Il part lui aussi vers le nord avec son groupe mais il ne calibre pas ses &#38;eacute;tapes comme moi. Leur objectif est de 15km alors que le mien est d&#39;atteindre un camp. Alors que je commence &#38;agrave; r&#38;eacute;diger ces quelques lignes, d&#39;autres campeurs on rejoint mon emplacement. L&#39;avantage pour eux c&#39;est qu&#39;ils n&#39;auront pas de moustiques...&#38;nbsp;&#60;/p&#62;', '2022-09-06 08:40:00', 1, 1, 0, 0, 'b23d2c9a2b23952846095529dd0083f9.jpg'),
(9, 'Cette fois-ci, je suis vraiment seul', '&#60;p&#62;Je laisse derri&#38;egrave;re moi la civilisation, sans v&#38;eacute;ritable regret. Je me sens de plus en plus dans mon &#38;eacute;l&#38;eacute;ment et commence r&#38;eacute;ellement &#38;agrave; profiter de l&#39;instant pr&#38;eacute;sent...&#60;/p&#62;', '&#60;p&#62;Buck ne lisait pas les journaux et &#38;eacute;tait loin de savoir ce qui se tramait vers la fin de 1897, non seulement contre lui, mais contre tous ses cong&#38;eacute;n&#38;egrave;res. En effet, dans toute la r&#38;eacute;gion qui s&#38;rsquo;&#38;eacute;tend du d&#38;eacute;troit de Puget &#38;agrave; la baie de San-Di&#38;eacute;go on traquait les grands chiens &#38;agrave; longs poils, aussi habiles &#38;agrave; se tirer d&#38;rsquo;affaire dans l&#38;rsquo;eau que sur la terre ferme&#38;hellip;&#60;/p&#62;&#13;&#10;&#60;p&#62;Les hommes, en creusant la terre obscure, y avaient trouv&#38;eacute; un m&#38;eacute;tal jaune, enfonc&#38;eacute; dans le sol glac&#38;eacute; des r&#38;eacute;gions arctiques, et les compagnies de transport ayant r&#38;eacute;pandu la nouvelle&#38;nbsp;&#60;span id=&#34;10&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/12&#34;&#62;&#60;/span&#62;&#38;agrave; grand renfort de r&#38;eacute;clame, les gens se ruaient en foule vers le Nord. Et il leur fallait des chiens, de ces grands chiens robustes aux muscles forts pour travailler, et &#38;agrave; l&#38;rsquo;&#38;eacute;paisse fourrure pour se prot&#38;eacute;ger contre le froid.&#60;/p&#62;&#13;&#10;&#60;p&#62;Buck habitait cette belle demeure, situ&#38;eacute;e dans la vall&#38;eacute;e ensoleill&#38;eacute;e de Santa-Clara, qu&#38;rsquo;on appelle &#38;laquo;&#38;nbsp;le Domaine du juge Miller&#38;nbsp;&#38;raquo;.&#60;/p&#62;&#13;&#10;&#60;p&#62;De la route, on distingue &#38;agrave; peine l&#38;rsquo;habitation &#38;agrave; demi-cach&#38;eacute;e par les grands arbres, qui laissent entrevoir la large et fra&#38;icirc;che v&#38;eacute;randa, r&#38;eacute;gnant sur les quatre faces de la maison. Des all&#38;eacute;es soigneusement sabl&#38;eacute;es m&#38;egrave;nent au perron, sous l&#38;rsquo;ombre tremblante des hauts peupliers, parmi les vertes pelouses. Un jardin immense et fleuri entoure la villa, puis ce sont les communs imposants, &#38;eacute;curies spacieuses, o&#38;ugrave; s&#38;rsquo;agitent une douzaine de grooms et de valets bavards, cottages couverts de plantes grimpantes, pour les jardiniers et leurs&#38;nbsp;&#60;span id=&#34;11&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/13&#34;&#62;&#60;/span&#62;aides&#38;nbsp;; enfin l&#38;rsquo;interminable rang&#38;eacute;e des serres, treilles et espaliers, suivis de vergers plantureux, de gras p&#38;acirc;turages, de champs fertiles et de ruisseaux jaseurs.&#60;/p&#62;&#13;&#10;&#60;p&#62;Le monarque absolu de ce beau royaume &#38;eacute;tait, depuis quatre ans, le chien Buck, magnifique animal dont le poids et la majest&#38;eacute; tenaient du gigantesque terre-neuve Elno, son p&#38;egrave;re, tandis que sa m&#38;egrave;re Sheps, fine chienne colley de pure race &#38;eacute;cossaise, lui avait donn&#38;eacute; la beaut&#38;eacute; des formes et l&#38;rsquo;intelligence humaine de son regard. L&#38;rsquo;autorit&#38;eacute; de Buck &#38;eacute;tait indiscut&#38;eacute;e. Il r&#38;eacute;gnait sans conteste non seulement sur la tourbe insignifiante des chiens d&#38;rsquo;&#38;eacute;curie, sur le carlin japonais Toots, sur le mexicain Isabel, &#38;eacute;trange cr&#38;eacute;ature sans poil dont l&#38;rsquo;aspect pr&#38;ecirc;tait &#38;agrave; rire, mais encore sur tous les habitants du m&#38;ecirc;me lieu que lui. Majestueux et doux, il &#38;eacute;tait le compagnon ins&#38;eacute;parable du juge, qu&#38;rsquo;il suivait dans toutes ses promenades, il&#38;nbsp;&#60;span id=&#34;12&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/14&#34;&#62;&#60;/span&#62;s&#38;rsquo;allongeait d&#38;rsquo;habitude aux pieds de son ma&#38;icirc;tre, dans la biblioth&#38;egrave;que, le nez sur ses pattes de devant, clignant des yeux vers le feu, et ne marquant que par une imperceptible motion des sourcils l&#38;rsquo;int&#38;eacute;r&#38;ecirc;t qu&#38;rsquo;il prenait &#38;agrave; tout ce qui se passait autour de lui. Mais apercevait-il au dehors les fils a&#38;icirc;n&#38;eacute;s du juge, pr&#38;ecirc;ts &#38;agrave; se mettre en selle, il se levait d&#38;rsquo;un air digne et daignait les escorter&#38;nbsp;; de m&#38;ecirc;me, quand les jeunes gens prenaient leur bain matinal dans le grand r&#38;eacute;servoir ciment&#38;eacute; du jardin, Buck consid&#38;eacute;rait de son devoir d&#38;rsquo;&#38;ecirc;tre de la f&#38;ecirc;te. Il ne manquait pas non plus d&#38;rsquo;accompagner les jeunes filles dans leurs promenades &#38;agrave; pied ou en voiture&#38;nbsp;; et parfois on le voyait sur les pelouses, portant sur son dos les petits-enfants du juge, les roulant sur le gazon et faisant mine de les d&#38;eacute;vorer, de ses deux rang&#38;eacute;es de dents &#38;eacute;tincelantes. Les petits l&#38;rsquo;adoraient, tout en le craignant un peu, car Buck exer&#38;ccedil;ait sur eux une sur&#60;span id=&#34;13&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/15&#34;&#62;&#60;/span&#62;veillance s&#38;eacute;v&#38;egrave;re et ne permettait aucun &#38;eacute;cart &#38;agrave; la r&#38;egrave;gle. D&#38;rsquo;ailleurs, ils n&#38;rsquo;&#38;eacute;taient pas seuls &#38;agrave; le redouter, le sentiment de sa propre importance et le respect universel qui l&#38;rsquo;entourait investissant le bel animal d&#38;rsquo;une dignit&#38;eacute; vraiment royale.&#60;/p&#62;&#13;&#10;&#60;p&#62;Depuis quatre ans, Buck menait l&#38;rsquo;existence d&#38;rsquo;un aristocrate blas&#38;eacute;, parfaitement satisfait de soi-m&#38;ecirc;me et des autres, peut-&#38;ecirc;tre l&#38;eacute;g&#38;egrave;rement enclin &#38;agrave; l&#38;rsquo;&#38;eacute;go&#38;iuml;sme, ainsi que le sont trop souvent les grands de ce monde. Mais son activit&#38;eacute; incessante, la chasse, la p&#38;ecirc;che, le sport, et surtout sa passion h&#38;eacute;r&#38;eacute;ditaire pour l&#38;rsquo;eau fra&#38;icirc;che le gardaient de tout alourdissement et de la moindre d&#38;eacute;ch&#38;eacute;ance physique&#38;nbsp;: il &#38;eacute;tait, en v&#38;eacute;rit&#38;eacute;, le plus admirable sp&#38;eacute;cimen de sa race qu&#38;rsquo;on p&#38;ucirc;t voir. Sa vaste poitrine, ses flancs &#38;eacute;vid&#38;eacute;s sous l&#38;rsquo;&#38;eacute;paisse et soyeuse fourrure, ses pattes droites et formidables, son large front &#38;eacute;toil&#38;eacute; de blanc, son regard franc, calme et attentif, le faisaient admirer de tous.&#60;span id=&#34;14&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/16&#34;&#62;&#60;/span&#62;&#60;/p&#62;&#13;&#10;&#60;p&#62;Telle &#38;eacute;tait la situation du chien Buck, lorsque la d&#38;eacute;couverte des mines d&#38;rsquo;or du Klondyke attira vers le nord des milliers d&#38;rsquo;aventuriers. Tout manquait dans ces r&#38;eacute;gions neuves et d&#38;eacute;sol&#38;eacute;es&#38;nbsp;; et pour assurer la subsistance et la vie m&#38;ecirc;me des &#38;eacute;migrants, on dut avoir recours aux tra&#38;icirc;neaux attel&#38;eacute;s de chiens, seuls animaux de trait capables de supporter une temp&#38;eacute;rature arctique.&#60;/p&#62;&#13;&#10;&#60;p&#62;Buck semblait cr&#38;eacute;&#38;eacute; pour jouer un r&#38;ocirc;le dans les solitudes glac&#38;eacute;es de l&#38;rsquo;Alaska&#38;nbsp;; et c&#38;rsquo;est pr&#38;eacute;cis&#38;eacute;ment ce qui advint, gr&#38;acirc;ce &#38;agrave; la trahison d&#38;rsquo;un aide jardinier. Le mis&#38;eacute;rable Mano&#38;euml;l avait pour la loterie chinoise une passion effr&#38;eacute;n&#38;eacute;e&#38;nbsp;; et ses gages &#38;eacute;tant &#38;agrave; peine suffisants pour assurer l&#38;rsquo;existence de sa femme et de ses enfants, il ne recula pas devant un crime pour se procurer les moyens de satisfaire son vice.&#60;/p&#62;&#13;&#10;&#60;p&#62;Un soir, que le juge pr&#38;eacute;sidait une r&#38;eacute;union et que ses fils &#38;eacute;taient absorb&#38;eacute;s par le r&#38;egrave;glement d&#38;rsquo;un nouveau club&#38;nbsp;&#60;span id=&#34;15&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/17&#34;&#62;&#60;/span&#62;athl&#38;eacute;tique, le tra&#38;icirc;tre Mano&#38;euml;l appelle doucement Buck, qui le suit sans d&#38;eacute;fiance, convaincu qu&#38;rsquo;il s&#38;rsquo;agit d&#38;rsquo;une simple promenade &#38;agrave; la brume. Tous deux traversent sans encombre la propri&#38;eacute;t&#38;eacute;, gagnent la grande route et arrivent tranquillement &#38;agrave; la petite gare de College-Park. L&#38;agrave;, un homme inconnu place dans la main de Mano&#38;euml;l quelques pi&#38;egrave;ces d&#38;rsquo;or, tout en lui reprochant d&#38;rsquo;amener l&#38;rsquo;animal en libert&#38;eacute;. Aussit&#38;ocirc;t Mano&#38;euml;l jette au cou de Buck une corde assez forte pour l&#38;rsquo;&#38;eacute;trangler en cas de r&#38;eacute;sistance. Buck supporte cet affront avec calme et dignit&#38;eacute;&#38;nbsp;; bien que ce proc&#38;eacute;d&#38;eacute; inusit&#38;eacute; le surprenne, il a, par habitude, confiance en tous les gens de la maison, et sait que les hommes poss&#38;egrave;dent une sagesse sup&#38;eacute;rieure m&#38;ecirc;me &#38;agrave; la sienne. Toutefois, quand l&#38;rsquo;&#38;eacute;tranger fait mine de prendre la corde, Buck manifeste par un profond grondement le d&#38;eacute;plaisir qu&#38;rsquo;il &#38;eacute;prouve. Aussit&#38;ocirc;t la corde se resserre, lui meurtrissant cruellement la gorge&#38;nbsp;&#60;span id=&#34;16&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/18&#34;&#62;&#60;/span&#62;et lui coupant la respiration. Indign&#38;eacute;, Buck, se jette sur l&#38;rsquo;homme&#38;nbsp;; alors celui-ci donne un tour de poignet vigoureux&#38;nbsp;: la corde se resserre encore&#38;nbsp;; furieux, surpris, la langue pendante, la poitrine convuls&#38;eacute;e, Buck se tord impuissant, ressentant plus vivement l&#38;rsquo;outrage inattendu que l&#38;rsquo;atroce douleur physique&#38;nbsp;; ses beaux yeux se couvrent d&#38;rsquo;un nuage, deviennent vitreux&#38;hellip; et c&#38;rsquo;est &#38;agrave; demi-mort qu&#38;rsquo;il est brutalement jet&#38;eacute; dans un fourgon &#38;agrave; bagages par les deux complices.&#60;/p&#62;', '2022-09-06 08:44:00', 1, 1, 1, 1, '81949b75a5a65da7d7fad8ea0c50c618.jpg'),
(10, 'PremiÃ¨res rencontres avec la faune sauvage', '&#60;p&#62;C&#39;est comme si ces animaux ne savaient pas ce que nous &#38;eacute;tions. Elles ne semblent pas nous craindre, ni m&#38;ecirc;me savoir ce que nous pourrions leur faire...&#38;nbsp;&#60;/p&#62;', '&#60;p&#62;L&#38;rsquo;animal commen&#38;ccedil;a par aboyer avec fureur contre ces nouveaux venus. Mais s&#38;rsquo;apercevant bient&#38;ocirc;t qu&#38;rsquo;ils se riaient de sa rage impuissante, il alla se coucher dans un coin de sa cage et y demeura farouche, immobile et silencieux.&#60;/p&#62;&#13;&#10;&#60;p&#62;Le voyage fut long. Transbord&#38;eacute; d&#38;rsquo;une gare &#38;agrave; une autre, passant d&#38;rsquo;un train de marchandises &#38;agrave; un express, Buck traversa &#38;agrave; toute vapeur une grande &#38;eacute;tendue de pays. Le trajet dura quarante-huit heures.&#60;/p&#62;&#13;&#10;&#60;p&#62;De tout ce temps il n&#38;rsquo;avait ni bu ni mang&#38;eacute;. Comme il ne r&#38;eacute;pondait que par un grognement sourd aux avances des employ&#38;eacute;s du train, ceux-ci, se veng&#38;egrave;rent en le privant de nourriture. La faim ne le tourmentait pas autant que la soif cruelle qui dess&#38;eacute;chait sa gorge, enflamm&#38;eacute;e par la pression de la corde. La fureur grondait en son c&#38;oelig;ur et ajoutait&#38;nbsp;&#60;span id=&#34;21&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/23&#34;&#62;&#60;/span&#62;&#38;agrave; la fi&#38;egrave;vre ardente qui le consumait&#38;nbsp;; et la douceur de sa vie pass&#38;eacute;e rendait plus douloureuse sa condition pr&#38;eacute;sente.&#60;/p&#62;&#13;&#10;&#60;p&#62;Buck, r&#38;eacute;fl&#38;eacute;chissant en son &#38;acirc;me de chien &#38;agrave; tout ce qui lui &#38;eacute;tait arriv&#38;eacute; en ces deux jours pleins de surprises et d&#38;rsquo;horreur, sentait cro&#38;icirc;tre son indignation et sa col&#38;egrave;re, augment&#38;eacute;es par la sensation inaccoutum&#38;eacute;e de la faim qui lui tenaillait les entrailles. Malheur au premier qui passerait &#38;agrave; sa port&#38;eacute;e en ce moment&#38;nbsp;! Le juge lui-m&#38;ecirc;me aurait eu peine &#38;agrave; reconna&#38;icirc;tre en cet animal farouche le d&#38;eacute;bonnaire compagnon de ses journ&#38;eacute;es paisibles&#38;nbsp;; quant aux employ&#38;eacute;s du train, ils pouss&#38;egrave;rent un soupir de soulagement en d&#38;eacute;barquant &#38;agrave; Seattle la caisse contenant &#38;laquo;&#38;nbsp;la b&#38;ecirc;te fauve&#38;nbsp;&#38;raquo;.&#60;/p&#62;&#13;&#10;&#60;p&#62;Quatre hommes l&#38;rsquo;ayant soulev&#38;eacute;e avec pr&#38;eacute;caution la transport&#38;egrave;rent dans une cour &#38;eacute;troite et noire, entour&#38;eacute;e de hautes murailles, et dans laquelle se tenait un homme court et trapu, la pipe aux dents, le buste pris dans un maillot&#38;nbsp;&#60;span id=&#34;22&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/24&#34;&#62;&#60;/span&#62;de laine rouge aux manches roul&#38;eacute;es au-dessus du coude.&#60;/p&#62;&#13;&#10;&#60;p&#62;Devinant en cet homme un nouvel ennemi, Buck, le regard rouge, le poil h&#38;eacute;riss&#38;eacute;, les crocs visibles sous la l&#38;egrave;vre retrouss&#38;eacute;e, se rua contre les barreaux de sa cage avec un v&#38;eacute;ritable hurlement.&#60;/p&#62;&#13;&#10;&#60;p&#62;L&#38;rsquo;homme eut un mauvais sourire&#38;nbsp;: il posa sa pipe, et s&#38;rsquo;&#38;eacute;tant muni d&#38;rsquo;une hache et d&#38;rsquo;un &#38;eacute;norme gourdin, il se rapprocha d&#38;rsquo;un pas d&#38;eacute;lib&#38;eacute;r&#38;eacute;.&#60;/p&#62;&#13;&#10;&#60;p&#62;&#38;mdash; Dis donc, tu ne vas pas le sortir, je pense&#38;nbsp;? s&#38;rsquo;&#38;eacute;cria un des porteurs en reculant.&#60;/p&#62;&#13;&#10;&#60;p&#62;&#38;mdash; Tu crois &#38;ccedil;a&#38;nbsp;?&#38;hellip; Attends un peu&#38;nbsp;! fit l&#38;rsquo;homme, ins&#38;eacute;rant d&#38;rsquo;un coup sa hache entre les planches de la caisse.&#60;/p&#62;&#13;&#10;&#60;p&#62;Les assistants se h&#38;acirc;t&#38;egrave;rent de se retirer, et reparurent au bout de peu d&#38;rsquo;instants, perch&#38;eacute;s sur le mur de la cour, en bonne place pour voir ce qui allait se passer.&#60;/p&#62;&#13;&#10;&#60;p&#62;Lorsque Buck entendit r&#38;eacute;sonner les coups de hache contre les parois de sa cage, il se mit debout, et mordant les&#38;nbsp;&#60;span id=&#34;23&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/25&#34;&#62;&#60;/span&#62;barreaux, fr&#38;eacute;missant de col&#38;egrave;re et d&#38;rsquo;impatience, il attendit.&#60;/p&#62;&#13;&#10;&#60;p&#62;&#38;mdash; &#38;Agrave; nous deux, l&#38;rsquo;ami&#38;nbsp;!&#38;hellip; Tu me feras les yeux doux tout &#38;agrave; l&#38;rsquo;heure&#38;nbsp;!&#38;hellip; grommela l&#38;rsquo;homme au maillot rouge.&#60;/p&#62;&#13;&#10;&#60;p&#62;Et, d&#38;egrave;s qu&#38;rsquo;il eut pratiqu&#38;eacute; une ouverture suffisante pour livrer passage &#38;agrave; l&#38;rsquo;animal, il rejeta sa hache et se tint pr&#38;ecirc;t, son gourdin bien en main.&#60;/p&#62;&#13;&#10;&#60;p&#62;Buck &#38;eacute;tait m&#38;eacute;connaissable&#38;nbsp;; l&#38;rsquo;&#38;oelig;il sanglant, la mine hagarde et farouche, l&#38;rsquo;&#38;eacute;cume &#38;agrave; la gueule, il se rua sur l&#38;rsquo;homme, pareil &#38;agrave; une b&#38;ecirc;te enrag&#38;eacute;e&#38;hellip; Mais au moment o&#38;ugrave; ses m&#38;acirc;choires de fer allaient se refermer en &#38;eacute;tau sur sa proie, un coup savamment appliqu&#38;eacute; en plein cr&#38;acirc;ne le jeta &#38;agrave; terre. Ses dents s&#38;rsquo;entrechoquent violemment&#38;nbsp;; mais se relevant d&#38;rsquo;un bond, il s&#38;rsquo;&#38;eacute;lance, plein d&#38;rsquo;une rage aveugle&#38;nbsp;; de nouveau il est rudement abattu. Sa rage cro&#38;icirc;t. Dix fois, vingt fois, il revient &#38;agrave; la charge, mais, &#38;agrave; chaque tentative, un coup formidable appliqu&#38;eacute; de main de ma&#38;icirc;tre,&#38;nbsp;&#60;span id=&#34;24&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/26&#34;&#62;&#60;/span&#62;arr&#38;ecirc;te son &#38;eacute;lan. Enfin, &#38;eacute;tourdi, h&#38;eacute;b&#38;eacute;t&#38;eacute;, Buck demeure &#38;agrave; terre, haletant&#38;nbsp;; le sang d&#38;eacute;goutte de ses narines, de sa bouche, de ses oreilles&#38;nbsp;; son beau poil est souill&#38;eacute; d&#38;rsquo;une &#38;eacute;cume sanglante&#38;nbsp;; la malheureuse b&#38;ecirc;te sent son c&#38;oelig;ur g&#38;eacute;n&#38;eacute;reux pr&#38;ecirc;t &#38;agrave; se rompre de douleur et de rage impuissante&#38;hellip;&#60;/p&#62;&#13;&#10;&#60;p&#62;Alors l&#38;rsquo;Homme fait un pas en avant, et froidement, d&#38;eacute;lib&#38;eacute;r&#38;eacute;ment, prenant &#38;agrave; deux mains son gourdin, il ass&#38;egrave;ne sur le nez du chien un coup terrible. L&#38;rsquo;atroce souffrance r&#38;eacute;veille Buck de sa torpeur&#38;nbsp;: aucun des autres coups n&#38;rsquo;avait &#38;eacute;gal&#38;eacute; celui-ci. Avec un hurlement fou il se jette sur son ennemi. Mais sans s&#38;rsquo;&#38;eacute;mouvoir, celui-ci empoigne la gueule ouverte, et broyant dans ses doigts de fer la m&#38;acirc;choire inf&#38;eacute;rieure de l&#38;rsquo;animal, il le secoue, le balance et, finalement, l&#38;rsquo;enlevant de terre &#38;agrave; bout de bras, il lui fait d&#38;eacute;crire un cercle complet et le lance &#38;agrave; toute vol&#38;eacute;e contre terre, la t&#38;ecirc;te la premi&#38;egrave;re.&#60;span id=&#34;25&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/27&#34;&#62;&#60;/span&#62;&#60;/p&#62;&#13;&#10;&#60;p&#62;Ce coup, r&#38;eacute;serv&#38;eacute; pour la fin, lui assure la victoire. Buck demeure immobile, assomm&#38;eacute;.&#60;/p&#62;&#13;&#10;&#60;p&#62;&#38;mdash; Hein&#38;nbsp;?&#38;hellip; Crois-tu&#38;hellip; qu&#38;rsquo;il n&#38;rsquo;a pas son pareil pour mater un chien&#38;nbsp;?&#38;hellip; crient les spectateurs enthousiasm&#38;eacute;s.&#60;/p&#62;&#13;&#10;&#60;p&#62;&#38;mdash; Ma foi, dit l&#38;rsquo;un d&#38;rsquo;eux en s&#38;rsquo;en allant, j&#38;rsquo;aimerais mieux casser des cailloux tous les jours sur la route, et deux fois le dimanche, que de faire un pareil m&#38;eacute;tier&#38;hellip; Cela soul&#38;egrave;ve le c&#38;oelig;ur&#38;hellip;&#60;/p&#62;&#13;&#10;&#60;p&#62;Buck, peu &#38;agrave; peu, reprenait ses sens, mais non ses forces&#38;nbsp;; &#38;eacute;tendu &#38;agrave; l&#38;rsquo;endroit o&#38;ugrave; il &#38;eacute;tait venu s&#38;rsquo;abattre, il suivait d&#38;rsquo;un &#38;oelig;il atone tous les mouvements de l&#38;rsquo;homme au maillot rouge.&#60;/p&#62;&#13;&#10;&#60;p&#62;Celui-ci se rapprochait tranquillement.&#60;/p&#62;&#13;&#10;&#60;p&#62;&#38;mdash; Eh bien, mon gar&#38;ccedil;on&#38;nbsp;? fit-il avec une sorte de rude enjouement, comment &#38;ccedil;a va-t-il&#38;nbsp;?&#38;hellip; Un peu mieux, hein&#38;nbsp;?&#38;hellip; Para&#38;icirc;t qu&#38;rsquo;on vous appelle Buck, ajouta-t-il en consultant la pancarte appendue aux barreaux de la cage. Bien. Alors, Buck, mon vieux, voil&#38;agrave; ce que j&#38;rsquo;ai &#38;agrave;&#38;nbsp;&#60;span id=&#34;26&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/28&#34;&#62;&#60;/span&#62;vous dire&#38;nbsp;: Nous nous comprenons, je crois. Vous venez d&#38;rsquo;apprendre &#38;agrave; conna&#38;icirc;tre votre place. Moi, je saurai garder la mienne. Si vous &#38;ecirc;tes un bon chien, cela marchera. Si vous faites le m&#38;eacute;chant, voici un b&#38;acirc;ton qui vous enseignera la sagesse. Compris, pas vrai&#38;nbsp;?&#38;hellip; Entendu&#38;nbsp;!&#38;hellip;&#60;/p&#62;&#13;&#10;&#60;p&#62;Et, sans nulle crainte, il passa sa rude main sur la t&#38;ecirc;te puissante, saignant encore de ses coups. Buck sentit son poil se h&#38;eacute;risser &#38;agrave; ce contact, mais il le subit sans protester. Et quand l&#38;rsquo;Homme lui apporta une jatte d&#38;rsquo;eau fra&#38;icirc;che, il but avidement&#38;nbsp;; ensuite il accepta un morceau de viande crue que l&#38;rsquo;Homme lui donna bouch&#38;eacute;e par bouch&#38;eacute;e.&#60;/p&#62;&#13;&#10;&#60;p&#62;Buck, vaincu, venait d&#38;rsquo;apprendre une le&#38;ccedil;on qu&#38;rsquo;il n&#38;rsquo;oublierait de sa vie&#38;nbsp;: c&#38;rsquo;est qu&#38;rsquo;il ne pouvait rien contre un &#38;ecirc;tre humain arm&#38;eacute; d&#38;rsquo;une massue. Se trouvant pour la premi&#38;egrave;re fois face &#38;agrave; face avec la loi primitive, envisageant les conditions nouvelles et impitoyables de son&#38;nbsp;&#60;span id=&#34;27&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/29&#34;&#62;&#60;/span&#62;existence, il perdit la m&#38;eacute;moire de la douceur des jours &#38;eacute;coul&#38;eacute;s et se r&#38;eacute;solut &#38;agrave; souffrir l&#38;rsquo;in&#38;eacute;vitable.&#60;/p&#62;&#13;&#10;&#60;p&#62;D&#38;rsquo;autres chiens arrivaient en grand nombre, les uns dociles et joyeux, les autres furieux comme lui-m&#38;ecirc;me&#38;nbsp;; mais chacun &#38;agrave; son tour apprenait sa le&#38;ccedil;on. Et chaque fois que se renouvelait sous ses yeux la sc&#38;egrave;ne brutale de sa propre arriv&#38;eacute;e, cette le&#38;ccedil;on p&#38;eacute;n&#38;eacute;trait plus profond&#38;eacute;ment dans son c&#38;oelig;ur&#38;nbsp;: sans aucun doute possible, il fallait ob&#38;eacute;ir &#38;agrave; la loi du plus fort&#38;hellip;&#60;/p&#62;&#13;&#10;&#60;p&#62;Mais, quelque convaincu qu&#38;rsquo;il f&#38;ucirc;t de cette dure n&#38;eacute;cessit&#38;eacute;, jamais Buck n&#38;rsquo;aurait imit&#38;eacute; la bassesse de certains de ses cong&#38;eacute;n&#38;egrave;res qui, battus, venaient en rampant l&#38;eacute;cher la main du ma&#38;icirc;tre. Buck, lui, ob&#38;eacute;issait, mais sans rien perdre de sa fi&#38;egrave;re attitude, en se mesurant de l&#38;rsquo;&#38;oelig;il &#38;agrave; l&#38;rsquo;Homme abhorr&#38;eacute;&#38;hellip;&#60;/p&#62;&#13;&#10;&#60;p&#62;Souvent il venait des &#38;eacute;trangers qui, apr&#38;egrave;s avoir examin&#38;eacute; les camarades, remettaient en &#38;eacute;change des pi&#38;egrave;ces&#38;nbsp;&#60;span id=&#34;28&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/30&#34;&#62;&#60;/span&#62;d&#38;rsquo;argent, puis emmenaient un ou plusieurs chiens, qui ne reparaissaient plus. Buck ne savait ce que cela signifiait.&#60;/p&#62;&#13;&#10;&#60;p&#62;Enfin, son tour vint.&#60;/p&#62;&#13;&#10;&#60;p&#62;Un jour, parut au chenil un petit homme sec et vif, &#38;agrave; la mine fut&#38;eacute;e, crachant un anglais bizarre panach&#38;eacute; d&#38;rsquo;expressions inconnues &#38;agrave; Buck.&#60;/p&#62;&#13;&#10;&#60;p&#62;&#38;mdash; Sacrrr&#38;eacute; m&#38;acirc;tin&#38;nbsp;!&#38;hellip; cria-t-il en apercevant le superbe animal. V&#38;rsquo;la un damn&#38;eacute; failli chien&#38;nbsp;!&#38;hellip; Le diable m&#38;rsquo;emporte&#38;nbsp;!&#38;hellip; Combien&#38;nbsp;?&#60;/p&#62;&#13;&#10;&#60;p&#62;&#38;mdash; Trois cents dollars. Et encore&#38;nbsp;! C&#38;rsquo;est un vrai cadeau qu&#38;rsquo;on vous fait, r&#38;eacute;pliqua promptement le vendeur de chiens. Mais c&#38;rsquo;est l&#38;rsquo;argent du gouvernement qui danse, hein, Perrault&#38;nbsp;? Pas besoin de vous g&#38;ecirc;ner&#38;nbsp;?&#60;/p&#62;&#13;&#10;&#60;p&#62;Perrault se contenta de rire dans sa barbe. Certes, non, ce n&#38;rsquo;&#38;eacute;tait pas trop payer un animal pareil, et le gouvernement canadien ne se plaindrait pas quand il verrait les courriers arriver moiti&#38;eacute; plus vite que d&#38;rsquo;ordinaire. Per&#60;span id=&#34;29&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/31&#34;&#62;&#60;/span&#62;rault &#38;eacute;tait connaisseur. Et d&#38;egrave;s qu&#38;rsquo;il eut examin&#38;eacute; Buck, il comprit qu&#38;rsquo;il ne rencontrerait jamais son &#38;eacute;gal.&#60;/p&#62;&#13;&#10;&#60;p&#62;Buck, attentif, entendit tinter l&#38;rsquo;argent que le visiteur comptait dans la main de son dompteur. Puis Perrault siffla Buck et Curly, terre-neuve d&#38;rsquo;un excellent caract&#38;egrave;re, arriv&#38;eacute; depuis peu, et qu&#38;rsquo;il avait &#38;eacute;galement achet&#38;eacute;. Les chiens suivirent leur nouveau ma&#38;icirc;tre.&#60;/p&#62;&#13;&#10;&#60;p&#62;Perrault emmena les deux chiens sur le paquebot&#38;nbsp;&#60;em&#62;Narwhal&#60;/em&#62;, qui se mit promptement en route&#38;nbsp;; et tandis que Buck, anim&#38;eacute; et joyeux, regardait dispara&#38;icirc;tre &#38;agrave; l&#38;rsquo;horizon la ville de Seattle, il ne se doutait gu&#38;egrave;re que ses yeux contemplaient pour la derni&#38;egrave;re fois les terres ensoleill&#38;eacute;es du Sud.&#60;/p&#62;&#13;&#10;&#60;p&#62;Bient&#38;ocirc;t Perrault descendit les b&#38;ecirc;tes dans l&#38;rsquo;entrepont et les confia &#38;agrave; un g&#38;eacute;ant &#38;agrave; face basan&#38;eacute;e qui r&#38;eacute;pondait au nom de Fran&#38;ccedil;ois. Perrault &#38;eacute;tait un Franco-Canadien suffisamment bronz&#38;eacute;&#38;nbsp;; mais Fran&#38;ccedil;ois &#38;eacute;tait un m&#38;eacute;tis indien&#38;nbsp;&#60;span id=&#34;30&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/32&#34;&#62;&#60;/span&#62;franco-canadien beaucoup plus bronz&#38;eacute; encore.&#60;/p&#62;&#13;&#10;&#60;p&#62;Buck n&#38;rsquo;avait jamais rencontr&#38;eacute; d&#38;rsquo;hommes du type de ceux-ci&#38;nbsp;; il ne tarda pas &#38;agrave; ressentir pour eux une estime sinc&#38;egrave;re, bien que d&#38;eacute;nu&#38;eacute;e de toute tendresse&#38;nbsp;; car, s&#38;rsquo;ils &#38;eacute;taient durs et froids, ils se montraient strictement justes&#38;nbsp;; en outre, leur intime connaissance de la race canine rendait vain tout essai de tromperie et leur attirait le respect.&#60;/p&#62;&#13;&#10;&#60;p&#62;Buck et Curly trouv&#38;egrave;rent deux autres compagnons dans l&#38;rsquo;entrepont du&#38;nbsp;&#60;em&#62;Narwhal&#60;/em&#62;. L&#38;rsquo;un, fort m&#38;acirc;tin d&#38;rsquo;un blanc de neige, ramen&#38;eacute; du Spitzberg par le capitaine d&#38;rsquo;un baleinier, &#38;eacute;tait un chien aux dehors sympathiques, mais d&#38;rsquo;un caract&#38;egrave;re faux. D&#38;egrave;s le premier repas, il vola la part de Buck. Comme celui-ci, indign&#38;eacute;, s&#38;rsquo;&#38;eacute;lan&#38;ccedil;ait pour reprendre son bien, la longue m&#38;egrave;che du fouet de Fran&#38;ccedil;ois siffla dans les airs et venant cingler le voleur, le for&#38;ccedil;a de rendre le butin mal acquis. Buck jugea que Fran&#60;span id=&#34;31&#34; class=&#34;pagenum ws-pagenum&#34; title=&#34;Page:London - L&#39;appel de la for&#38;ecirc;t, trad Galard, 1948.djvu/33&#34;&#62;&#60;/span&#62;&#38;ccedil;ois &#38;eacute;tait un homme juste et lui accorda son estime.&#60;/p&#62;', '2022-09-06 08:51:00', 1, 1, 0, 0, '1ec50b4b948ecde122132cc6c2f13363.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) NOT NULL,
  `publish_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `report` int(11) NOT NULL DEFAULT '30',
  `users_id` int(11) NOT NULL,
  `billet_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comments_to_users` (`users_id`),
  KEY `fk_comments_to_billets` (`billet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `content`, `publish_at`, `report`, `users_id`, `billet_id`) VALUES
(1, '&#60;p&#62;J&#39;aimerais bien avoir acc&#38;egrave;s au d&#38;eacute;but du chapitre parce que l&#38;agrave; on n&#39;y comprend rien.&#60;/p&#62;', '2022-09-01 12:41:06', 30, 5, 1),
(2, '&#60;p&#62;Ca commence bien&#60;/p&#62;', '2022-09-01 12:51:45', 30, 2, 1),
(3, '&#60;p&#62;Vous &#38;ecirc;tes s&#38;ucirc;r que vous &#38;ecirc;tes auteur?&#38;nbsp;&#60;/p&#62;', '2022-09-01 12:52:33', 20, 3, 1),
(4, '&#60;p&#62;franchement j&#39;aime pas... je pr&#38;eacute;f&#38;egrave;re Twillight&#60;/p&#62;', '2022-09-01 12:53:45', 40, 4, 1),
(5, '&#60;p&#62;@Ben77, j&#39;y travaille...&#38;nbsp;&#60;/p&#62;', '2022-09-01 12:55:25', 30, 1, 1),
(7, '&#60;p&#62;Je n&#39;ai vrzimanet aim&#38;eacute;&#60;/p&#62;', '2022-09-06 09:22:23', 40, 5, 9);

-- --------------------------------------------------------

--
-- Structure de la table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `users_id` int(11) NOT NULL,
  `billets_id` int(11) NOT NULL,
  `like_it` tinyint(1) NOT NULL,
  KEY `fk_likes_to_users` (`users_id`),
  KEY `fk_likes_to_billets` (`billets_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `likes`
--

INSERT INTO `likes` (`users_id`, `billets_id`, `like_it`) VALUES
(2, 1, 1),
(4, 1, 0),
(5, 9, 0),
(1, 9, 1);

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `logid` int(11) NOT NULL AUTO_INCREMENT,
  `logtime` datetime DEFAULT CURRENT_TIMESTAMP,
  `logmessage` varchar(256) NOT NULL,
  PRIMARY KEY (`logid`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `logs`
--

INSERT INTO `logs` (`logid`, `logtime`, `logmessage`) VALUES
(1, '2022-07-20 19:19:23', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=01e34ab907a5a8a1&token=658bcb297b499d00705c4a96c243b1d7a2009dcacf340e85bac70ee8cfbf427d'),
(2, '2022-07-20 19:40:20', '{ Module: App\\Repository\\ResetDB } 1658338820'),
(3, '2022-07-20 19:40:20', '{ Module: App\\Repository\\ResetDB } 1658339361'),
(4, '2022-07-20 19:40:20', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(5, '2022-07-20 19:40:20', '{ Module: App\\Controllers\\UsersController } User registration confirmation failed'),
(6, '2022-07-20 19:40:20', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(7, '2022-07-20 19:46:46', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=d713153b880719e2&token=5f9d71a9cf3b46f3dd2245ac978b8fb6b7d44631e61394a21fe997c35c4d9909'),
(8, '2022-07-20 19:56:02', '{ Module: App\\Repository\\ResetDB } 1658339762'),
(9, '2022-07-20 19:56:02', '{ Module: App\\Repository\\ResetDB } 1658341005'),
(10, '2022-07-20 19:56:02', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(11, '2022-07-20 19:56:02', '{ Module: App\\Controllers\\UsersController } User registration confirmation failed'),
(12, '2022-07-20 19:56:02', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(13, '2022-07-21 20:02:41', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=931e0fc7c8b651f4&token=329f1a3ed1fe8a49ead940d034daf317129cf161627c362b3551bb1560e8ef8f'),
(14, '2022-07-21 20:03:11', '{ Module: App\\Repository\\ResetDB } 1658426591'),
(15, '2022-07-21 20:03:11', '{ Module: App\\Repository\\ResetDB } 1658428359'),
(16, '2022-07-21 20:03:11', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(17, '2022-07-21 20:03:11', '{ Module: App\\Controllers\\UsersController } User registration confirmation failed'),
(18, '2022-07-21 20:03:11', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(19, '2022-07-22 18:17:27', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=d79b005f4fb083df&token=2222a1fe6536dc70c15a3b65148e21a982c419db546176f298b89f2ce322b09b'),
(20, '2022-07-22 19:09:41', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=cc1da1d47b83cd25&token=43d41943d7d489ce31c1638f7b32760d3843e8065ba20514efdb6d2a0a47cacc'),
(21, '2022-07-22 19:09:54', '{ Module: App\\Repository\\ResetDB } 1658509794'),
(22, '2022-07-22 19:09:54', '{ Module: App\\Repository\\ResetDB } 1658511579'),
(23, '2022-07-22 19:09:54', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(24, '2022-07-22 19:09:54', '{ Module: App\\Controllers\\UsersController } User registration confirmation failed'),
(25, '2022-07-22 19:09:54', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(26, '2022-07-22 19:20:20', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=bb65c781c977249e&token=2d96b3899a9807206a593fc5ad04c0f11c7ee427b53bae5d79c790350b89e774'),
(27, '2022-07-22 19:21:43', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=35bc36f57d5acea7&token=74898455022ab1ca8866e33d1fd6eababd421d02325e9d53b780dcba1ed5d958'),
(28, '2022-07-22 19:37:42', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=7bab79dc62535473&token=fc112c2350a3ea005015e9f5a8668d80187e76a52f01b5ed9b22c4637806ca4b'),
(29, '2022-07-22 19:41:01', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=8d9b833d3de0654f&token=deea9e4224acb1bf5d327c078b1317a858456fcebb79f768de6df3847f8c79cd'),
(30, '2022-07-22 19:49:41', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=6530a8a4df1bb9ff&token=89bc3ec1671e1eb059c102c50709480b8e9093fff915439dc755585f44d734b6'),
(31, '2022-07-22 19:54:15', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=3111f60e074a5883&token=2b76a4a4ac11cf507662782e499cbd078a0776b1ce132a8d4794e2e0da0ddeaa'),
(32, '2022-07-22 19:55:12', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=42976f627f691b74&token=9af4cfd3ebc7373d2b31fbe084a3ca4b7b4cafe2f766643573cd5b4b41546dad'),
(33, '2022-07-23 17:54:24', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=6db69d1ecf34a3d4&token=f05b606a33a7f0e1409b990d6e236d3dc04955f7ba71916d7de089cb2af86aed'),
(34, '2022-07-23 17:55:12', '{ Module: App\\Repository\\ResetDB } 1658591712'),
(35, '2022-07-23 17:55:12', '{ Module: App\\Repository\\ResetDB } 1658593462'),
(36, '2022-07-23 17:55:12', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(37, '2022-07-23 17:55:12', '{ Module: App\\Controllers\\UsersController } User registration confirmation failed'),
(38, '2022-07-23 17:55:12', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(39, '2022-07-23 18:02:38', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=7205fc9158604ae8&token=2386bcabe793db7cbd3848c9cdcbe3b1c2f726c306b9ed7bee8e20858419840f'),
(40, '2022-07-23 18:02:46', '{ Module: App\\Repository\\ResetDB } 1658592166'),
(41, '2022-07-23 18:02:46', '{ Module: App\\Repository\\ResetDB } 1658593950'),
(42, '2022-07-23 18:02:46', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(43, '2022-07-23 18:02:46', '{ Module: App\\Controllers\\UsersController } User registration confirmation failed'),
(44, '2022-07-23 18:02:46', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(45, '2022-07-25 19:46:33', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=e4e1d8d6499187c0&token=74550942cf6c9d5752af98e1505cd042c4e3b4c95c15cae84d86af0340dc3c43'),
(46, '2022-07-25 19:46:54', '{ Module: App\\Repository\\ResetDB } 1658771214'),
(47, '2022-07-25 19:46:54', '{ Module: App\\Repository\\ResetDB } 1658772991'),
(48, '2022-07-25 19:46:54', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(49, '2022-07-25 19:46:54', '{ Module: App\\Controllers\\UsersController } User registration confirmation failed'),
(50, '2022-07-25 19:46:54', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(51, '2022-07-26 17:32:56', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=19b759cb42ada820&token=d7040e2478f3eb194618b310f5d7590f5a3e071a5f3d5fcd8daca2f8087c793b'),
(52, '2022-07-26 17:35:28', '{ Module: App\\Repository\\ResetDB } 1658849728'),
(53, '2022-07-26 17:35:28', '{ Module: App\\Repository\\ResetDB } 1658851374'),
(54, '2022-07-26 17:35:28', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(55, '2022-07-26 17:35:28', '{ Module: App\\Controllers\\UsersController } User registration confirmation failed'),
(56, '2022-07-26 17:35:28', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(57, '2022-07-27 18:20:02', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=7df5b293622a0509&token=fa8283df33d6023f35fa7196cac5c5e9f6231be2f1ae31b2328b1bbe64b862a7'),
(58, '2022-07-27 18:20:15', '{ Module: App\\Repository\\ResetDB } 1658938815'),
(59, '2022-07-27 18:20:15', '{ Module: App\\Repository\\ResetDB } 1658940600'),
(60, '2022-07-27 18:20:15', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(61, '2022-07-27 18:20:15', '{ Module: App\\Controllers\\UsersController } User registration confirmation failed'),
(62, '2022-07-27 18:20:15', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(63, '2022-07-31 21:12:27', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=499f5bd4954fb571&token=34a6132c2762cf8214456d66015946a8123aa35c736e48476cb5c0b80ee1364f'),
(64, '2022-07-31 21:13:01', '{ Module: App\\Repository\\ResetDB } 1659294781'),
(65, '2022-07-31 21:13:01', '{ Module: App\\Repository\\ResetDB } 1659296546'),
(66, '2022-07-31 21:13:01', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(67, '2022-07-31 21:13:01', '{ Module: App\\Controllers\\UsersController } User registration confirmation failed'),
(68, '2022-07-31 21:13:01', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(69, '2022-08-29 19:05:01', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=12347cd388576ef3&token=614153c9a0d97991dd20eccb43be8badc87456eb3b0da64512c70904e1413829'),
(70, '2022-08-29 19:19:01', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=b4b833dc2a32aaa8&token=883d304680124d554fcb8918769a784727b1f13bd657c7666edb1b7763120145'),
(71, '2022-08-29 19:19:08', '{ Module: App\\Repository\\ResetDB } 1661793548'),
(72, '2022-08-29 19:19:08', '{ Module: App\\Repository\\ResetDB } 1661795339'),
(73, '2022-08-29 19:19:08', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(74, '2022-08-29 19:19:08', '{ Module: App\\Controllers\\UsersController } User registration confirmation failed'),
(75, '2022-08-29 19:19:08', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(76, '2022-08-29 19:20:07', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/passwordresetconfirmed?selector=d8867de4a0432e2c&token=2f063027f50c8b4d35647c0bee89a114fa5bba7f6f646fc48c4df8646938c50c'),
(77, '2022-08-29 19:24:56', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/passwordresetconfirmed?selector=a0cb207f7f97c939&token=51a70d48ab908cb7ac210a9e13aa8ca1b622d4cacec48175265b77ff0ca6630d'),
(78, '2022-08-29 19:45:33', '{ Module: App\\Repository\\ResetDB } 1661795133'),
(79, '2022-08-29 19:45:33', '{ Module: App\\Repository\\ResetDB } 1661795694'),
(80, '2022-08-29 19:45:33', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(81, '2022-08-29 20:05:19', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/passwordresetconfirmed?selector=105e5567b5cc00c3&token=34cb5bf2c2e04c8349e7aa10fbf951a26b46f04f1b25afd8d384b52dbbf2ac2b'),
(82, '2022-08-29 20:05:27', '{ Module: App\\Repository\\ResetDB } 1661796327'),
(83, '2022-08-29 20:05:27', '{ Module: App\\Repository\\ResetDB } 1661798118'),
(84, '2022-08-29 20:05:27', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(85, '2022-08-29 20:06:41', '{ Module: App\\Repository\\ResetDB } resetstatus : 2'),
(86, '2022-08-29 20:07:47', '{ Module: App\\Repository\\ResetDB } resetstatus : 2'),
(87, '2022-08-29 20:07:51', '{ Module: App\\Repository\\ResetDB } resetstatus : 2'),
(88, '2022-08-30 18:50:11', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/passwordresetconfirmed?selector=07ba4a6f303f6bb9&token=fdb84dbd5d68cf93e1fde05368df5c32091925fd2d6ee7104079e90e12cbd22a'),
(89, '2022-08-30 18:51:21', '{ Module: App\\Repository\\ResetDB } 1661878281'),
(90, '2022-08-30 18:51:21', '{ Module: App\\Repository\\ResetDB } 1661880009'),
(91, '2022-08-30 18:51:21', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(92, '2022-08-30 19:10:18', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/passwordresetconfirmed?selector=ffcdcfa3620cccf7&token=6bcb88d7fb1904228410e917eb47528f2381584d54aee187df9c04d32600bbbb'),
(93, '2022-08-30 19:10:24', '{ Module: App\\Repository\\ResetDB } 1661879424'),
(94, '2022-08-30 19:10:24', '{ Module: App\\Repository\\ResetDB } 1661881216'),
(95, '2022-08-30 19:10:24', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(96, '2022-08-30 19:14:12', '{ Module: App\\Repository\\ResetDB } resetstatus : 2'),
(97, '2022-08-30 19:16:02', '{ Module: App\\Repository\\ResetDB } resetstatus : 2'),
(98, '2022-08-30 19:17:25', '{ Module: App\\Repository\\ResetDB } resetstatus : 2'),
(99, '2022-08-30 19:21:52', '{ Module: App\\Repository\\ResetDB } resetstatus : 2'),
(100, '2022-08-30 19:25:18', '{ Module: App\\Repository\\ResetDB } resetstatus : 2'),
(101, '2022-08-30 19:38:10', '{ Module: App\\Repository\\ResetDB } resetstatus : 2'),
(102, '2022-08-30 19:39:54', '{ Module: App\\Repository\\ResetDB } resetstatus : 2'),
(103, '2022-08-31 19:32:24', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=dd7134e2ccee3ef9&token=79db1cd7c2a43f5e7d71614965fa8279e1773d07d2497b5bdd2c6fda03bdc784'),
(104, '2022-08-31 19:32:43', '{ Module: App\\Repository\\ResetDB } 1661967163'),
(105, '2022-08-31 19:32:43', '{ Module: App\\Repository\\ResetDB } 1661968942'),
(106, '2022-08-31 19:32:43', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(107, '2022-08-31 19:32:43', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(108, '2022-09-01 12:25:05', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=87a32a089c16ccd3&token=272ba41851408de9cc7698a39596fbb39695e5f0765c615fd2f432db39b1a493'),
(109, '2022-09-01 12:25:24', '{ Module: App\\Repository\\ResetDB } 1662027924'),
(110, '2022-09-01 12:25:24', '{ Module: App\\Repository\\ResetDB } 1662029703'),
(111, '2022-09-01 12:25:24', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(112, '2022-09-01 12:25:24', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(113, '2022-09-01 12:28:00', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=94dc46581b5ecb42&token=d6ae093d4aff82661c007ef4960ed2b7415561a1a71775be8a5c5599cfb80a2a'),
(114, '2022-09-01 12:28:04', '{ Module: App\\Repository\\ResetDB } 1662028084'),
(115, '2022-09-01 12:28:04', '{ Module: App\\Repository\\ResetDB } 1662029878'),
(116, '2022-09-01 12:28:04', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(117, '2022-09-01 12:28:04', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(118, '2022-09-01 12:29:16', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=705d968cea484214&token=7b1379d420f9f6418fcee029ce59c1c19a92ff377b15d356013e23f0a693544f'),
(119, '2022-09-01 12:29:23', '{ Module: App\\Repository\\ResetDB } 1662028163'),
(120, '2022-09-01 12:29:23', '{ Module: App\\Repository\\ResetDB } 1662029954'),
(121, '2022-09-01 12:29:23', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(122, '2022-09-01 12:29:23', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(123, '2022-09-01 12:29:59', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=e883d6351f0bbe3c&token=817e0d18d338ee0ed4e989eb2a67166827f92b52c609b42790f35766de07e066'),
(124, '2022-09-01 12:30:06', '{ Module: App\\Repository\\ResetDB } 1662028206'),
(125, '2022-09-01 12:30:06', '{ Module: App\\Repository\\ResetDB } 1662029998'),
(126, '2022-09-01 12:30:06', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(127, '2022-09-01 12:30:06', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for '),
(128, '2022-09-06 09:24:57', '{ Module: App\\Core\\MailTrap } email URL :http://projet4/users/registerconfirmed?selector=3cb796f84a96a0c4&token=f87221ba75e91d5aea3c4149e9d0ef41ece45aec25ab8a01c1a59a512147a0dd'),
(129, '2022-09-06 09:25:12', '{ Module: App\\Repository\\ResetDB } 1662449112'),
(130, '2022-09-06 09:25:12', '{ Module: App\\Repository\\ResetDB } 1662450895'),
(131, '2022-09-06 09:25:12', '{ Module: App\\Repository\\ResetDB } resetstatus : 1'),
(132, '2022-09-06 09:25:12', '{ Module: App\\Controllers\\UsersController } Confirmation request processed for ');

-- --------------------------------------------------------

--
-- Structure de la table `resets`
--

DROP TABLE IF EXISTS `resets`;
CREATE TABLE IF NOT EXISTS `resets` (
  `resetid` int(11) NOT NULL AUTO_INCREMENT,
  `resetactiontype` varchar(64) NOT NULL,
  `pseudo` varchar(64) NOT NULL,
  `selector` text NOT NULL,
  `token` longtext NOT NULL,
  `expires` int(32) NOT NULL,
  `resetstatus` tinyint(4) NOT NULL,
  `requesttime` datetime DEFAULT CURRENT_TIMESTAMP,
  `processedtime` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`resetid`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `resets`
--

INSERT INTO `resets` (`resetid`, `resetactiontype`, `pseudo`, `selector`, `token`, `expires`, `resetstatus`, `requesttime`, `processedtime`) VALUES
(1, '[App\\Repository\\ResetDB]register', 'Tono77', '01e34ab907a5a8a1', 'e‹Ë){I\0p\\J–ÂC±×¢\0ÊÏ4…ºÇèÏ¿B}', 1658339361, 1, '2022-07-20 19:19:23', '2022-07-20 19:40:20'),
(2, '[App\\Repository\\ResetDB]register', 'M4mounette', 'd713153b880719e2', '_q©Ï;FóÝ\"E¬—‹¶·ÔF1æ”¢é—Ã\\M™	', 1658341005, 1, '2022-07-20 19:46:46', '2022-07-20 19:56:02'),
(3, '[App\\Repository\\ResetDB]register', 'paer', '931e0fc7c8b651f4', '2Ÿ\Z>ÑþŠIêÙ@Ð4Úóœñab|6+5Q»`èï', 1658428359, 1, '2022-07-21 20:02:41', '2022-07-21 20:03:11'),
(4, '[App\\Repository\\ResetDB]register', 'yTert', 'd79b005f4fb083df', '\"\"¡þe6ÜpÁZ;eŽ!©‚ÄÛTavò˜¸Ÿ,ã\"°›', 1658508446, 0, '2022-07-22 18:17:27', '2022-07-22 18:17:27'),
(5, '[App\\Repository\\ResetDB]register', 'Benj', 'cc1da1d47b83cd25', 'CÔC×Ô‰Î1Ác{2v\r8Cè[¢ïÛm*\nGÊÌ', 1658511579, 1, '2022-07-22 19:09:41', '2022-07-22 19:09:54'),
(6, '[App\\Repository\\ResetDB]register', 'Benj', 'bb65c781c977249e', '-–³‰š˜ jY?Å­Àñ~ä\'µ;®]yÇ5‰çt', 1658512218, 0, '2022-07-22 19:20:20', '2022-07-22 19:20:20'),
(7, '[App\\Repository\\ResetDB]register', 'Benj', '35bc36f57d5acea7', 't‰„U*±Êˆfã=Öêº½B2^S·€ÜºÕÙX', 1658512301, 0, '2022-07-22 19:21:43', '2022-07-22 19:21:43'),
(8, '[App\\Repository\\ResetDB]register', 'Benj', '7bab79dc62535473', 'ü,#P£ê\0Péõ¨f€~v¥/µí›\"ÄcxÊK', 1658513260, 0, '2022-07-22 19:37:42', '2022-07-22 19:37:42'),
(9, '[App\\Repository\\ResetDB]register', 'yves40', '8d9b833d3de0654f', 'ÞêžB$¬±¿]2|‹¨XEoÎ»y÷hÞmó„ŒyÍ', 1658513459, 0, '2022-07-22 19:41:01', '2022-07-22 19:41:01'),
(10, '[App\\Repository\\ResetDB]register', 'yves40', '6530a8a4df1bb9ff', '‰¼>Ág°YÁÅ	HŽ“ÿùCÇUX_D×4¶', 1658513980, 0, '2022-07-22 19:49:41', '2022-07-22 19:49:41'),
(11, '[App\\Repository\\ResetDB]register', 'yves40', '3111f60e074a5883', '+v¤¤¬ÏPvbx.Iœ½Šv±Î*G”âàÚ\rÞª', 1658514253, 0, '2022-07-22 19:54:15', '2022-07-22 19:54:15'),
(12, '[App\\Repository\\ResetDB]register', 'yves40', '42976f627f691b74', 'šôÏÓëÇ7=+1ûà„£ÊK{L¯â÷fd5sÍ[KATm­', 1658514310, 0, '2022-07-22 19:55:12', '2022-07-22 19:55:12'),
(13, '[App\\Repository\\ResetDB]register', 'Benj', '6db69d1ecf34a3d4', 'ð[`j3§ðá@›™\rn#m=ÀIU÷ºq‘m}à‰Ë*øjí', 1658593462, 1, '2022-07-23 17:54:24', '2022-07-23 17:55:12'),
(14, '[App\\Repository\\ResetDB]register', 'BBen', '7205fc9158604ae8', '#†¼«ç“Û|½8HÉÍËã±Â÷&Ã¹í{îŽ …„„', 1658593950, 1, '2022-07-23 18:02:38', '2022-07-23 18:02:46'),
(15, '[App\\Repository\\ResetDB]register', 'yves40', 'e4e1d8d6499187c0', 'tU	BÏlWR¯˜áP\\ÐBÄã´É\\ÊèM†¯@Ü<C', 1658772991, 1, '2022-07-25 19:46:33', '2022-07-25 19:46:54'),
(16, '[App\\Repository\\ResetDB]register', 'Chris', '19b759cb42ada820', '×$xóëF³õ×YZ>\Z_=_Í¬¢ø|y;', 1658851374, 1, '2022-07-26 17:32:56', '2022-07-26 17:35:28'),
(17, '[App\\Repository\\ResetDB]register', 'Jean', '7df5b293622a0509', 'ú‚ƒß3Ö?5úq–ÊÅÅéö#âñ®1²2‹¾d¸b§', 1658940600, 1, '2022-07-27 18:20:02', '2022-07-27 18:20:15'),
(18, '[App\\Repository\\ResetDB]register', 'Spik666', '499f5bd4954fb571', '4¦,\'bÏ‚EmfYF¨:£\\snHGlµÀ¸á6O', 1659296546, 1, '2022-07-31 21:12:27', '2022-07-31 21:13:01'),
(19, '[App\\Repository\\ResetDB]register', 'Chris', '12347cd388576ef3', 'aASÉ Ùy‘Ý ìËC¾‹­ÈtVë;\r¦EÇ	áA8)', 1661794499, 0, '2022-08-29 19:05:01', '2022-08-29 19:05:01'),
(20, '[App\\Repository\\ResetDB]register', 'ben45', 'b4b833dc2a32aaa8', 'ˆ=0F€MUOË‰všxG\'±ñ;ÖWÇfnÛwcE', 1661795339, 1, '2022-08-29 19:19:01', '2022-08-29 19:19:08'),
(21, '[App\\Repository\\ResetDB]register', 'ben45', 'd8867de4a0432e2c', '/0\'õ‹M5d|î‰¡ú[ºodoÄŒMødi8Å', 1661795406, 0, '2022-08-29 19:20:07', '2022-08-29 19:20:07'),
(22, '[App\\Repository\\ResetDB]reset', 'ben45', 'a0cb207f7f97c939', 'Q§\rH«Œ·¬!\nžªŒ¡¶\"ÔÊÎÄu&[wÿ¦c\r', 1661795694, 1, '2022-08-29 19:24:56', '2022-08-29 19:45:33'),
(23, '[App\\Repository\\ResetDB]reset', 'ben45', '105e5567b5cc00c3', '4Ë[òÂàLƒIçªûùQ¢kFðO%¯ØÓ„µ-»ò¬+', 1661798118, 1, '2022-08-29 20:05:19', '2022-08-29 20:05:27'),
(24, '[App\\Repository\\ResetDB]reset', 'ben45', '07ba4a6f303f6bb9', 'ý¸M½]hÏ“áýàShß\\2	%ý-nç@yéËÒ*', 1661880009, 1, '2022-08-30 18:50:11', '2022-08-30 18:51:21'),
(25, '[App\\Repository\\ResetDB]reset', 'ben45', 'ffcdcfa3620cccf7', 'kËˆ×û\"„éëGR#XMT®á‡ßœÓ&\0»»', 1661881216, 1, '2022-08-30 19:10:18', '2022-08-30 19:10:24'),
(26, '[App\\Repository\\ResetDB]register', 'Jean', 'dd7134e2ccee3ef9', 'yÛ×Â¤?^}qaIeú‚yáw=ÒI{[Ý,oÚ½Ç„', 1661968942, 1, '2022-08-31 19:32:24', '2022-08-31 19:32:43'),
(27, '[App\\Repository\\ResetDB]register', 'Spik666', '87a32a089c16ccd3', '\'+¤Q@éÌv˜£•–û³–•åðv\\a_Òô2Û9±¤“', 1662029703, 1, '2022-09-01 12:25:05', '2022-09-01 12:25:24'),
(28, '[App\\Repository\\ResetDB]register', 'chris78', '94dc46581b5ecb42', 'Ö®	=Jÿ‚f\0~ô–Ò·AUa¡§u¾Š\\U™Ï¸\n*', 1662029878, 1, '2022-09-01 12:28:00', '2022-09-01 12:28:04'),
(29, '[App\\Repository\\ResetDB]register', 'Bernadtt', '705d968cea484214', '{yÔ ùöAÎà)ÎYÁÁš’ÿ7{ÓV>#ð¦“TO', 1662029954, 1, '2022-09-01 12:29:16', '2022-09-01 12:29:23'),
(30, '[App\\Repository\\ResetDB]register', 'Ben77', 'e883d6351f0bbe3c', '~\rÓ8îÔé‰ë*gh\'ù+RÆ	´\'óWfÞàf', 1662029998, 1, '2022-09-01 12:29:59', '2022-09-01 12:30:06'),
(31, '[App\\Repository\\ResetDB]register', 'Davy', '3cb796f84a96a0c4', 'ør!ºuéZê<AIéÐïAìäZì%«ŠÁ¥šQ!G Ý', 1662450895, 1, '2022-09-06 09:24:57', '2022-09-06 09:25:12');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `pseudo` varchar(64) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '10',
  `role` int(11) NOT NULL DEFAULT '20',
  `profile_picture` varchar(64) NOT NULL DEFAULT 'defaultuserpicture.png',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UC_MAIL` (`email`),
  UNIQUE KEY `UC_PSEUDO` (`pseudo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `pseudo`, `status`, `role`, `profile_picture`) VALUES
(1, 'jeanforteroche@auteur.fr', '$argon2i$v=19$m=65536,t=4,p=1$Q2t1TE5COWRIR3Z6aS5rYw$uHIMDL7pO+Phs1+UWKo5qadkF/pR1GZzKLmbCNLML8g', 'Jean', 20, 10, '888cb36e5883fb11692fe99cbd856746.jpeg'),
(2, 'spik666@hell.com', '$argon2i$v=19$m=65536,t=4,p=1$Z2lzaEhrU3drc1czVEQ0OA$uzYUE4hL8DeZrRodGNuQDOFiQg0BjXTY1e+imVFtNOU', 'Spik666', 20, 20, 'f5625c539d5c7eca89f2c428bc5b5a1f.jpg'),
(3, 'chris@op.com', '$argon2i$v=19$m=65536,t=4,p=1$WnhiMWpDei9VRmY3YXQwYg$F/Yod/V3oTsTxX2aGRAjcw3yKXmrhnB2rpzxkwAUWL0', 'chris78', 20, 20, 'defaultuserpicture.png'),
(4, 'bernadette.radjaratnam@bank.fr', '$argon2i$v=19$m=65536,t=4,p=1$VURvdk0vdGVDVjdFbFR5aA$nrgN6BAzyAwNtdgsEppigTCJgmFHXhYAr4bIHhNlzio', 'Bernadtt', 20, 20, 'defaultuserpicture.png'),
(5, 'Ben77@free.fr', '$argon2i$v=19$m=65536,t=4,p=1$NEI3V0ozSnBYSGlUWXBzSw$eauxwfClYo29AEbcWKaGwU8kVKbcljs+qJIyodlxQJw', 'Ben77', 20, 20, '6c1447112136d3230eca1c5f43987480.jpg'),
(6, 'Davy@opcla.com', '$argon2i$v=19$m=65536,t=4,p=1$MkRWM2ZRVFhkNmsyV2tDbw$AdUaN9pyxVbZyt+9mC7mVCppcqsYbfIfqOO5PzwV2RU', 'Davy', 20, 20, 'defaultuserpicture.png');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `billets`
--
ALTER TABLE `billets`
  ADD CONSTRAINT `fk_billets_to_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_to_billets` FOREIGN KEY (`billet_id`) REFERENCES `billets` (`id`),
  ADD CONSTRAINT `fk_comments_to_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `fk_likes_to_billets` FOREIGN KEY (`billets_id`) REFERENCES `billets` (`id`),
  ADD CONSTRAINT `fk_likes_to_users` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
