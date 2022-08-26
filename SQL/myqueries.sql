------------------------------------------------------------------------------
SELECT title , published FROM `billets` ORDER BY publish_at;
UPDATE billets SET published = 1 WHERE published = 0 AND publish_at <= NOW();
SELECT title , published FROM `billets` ORDER BY publish_at;

-- mysql -u root --password=root 

------------------------------------------------------------------------------

SELECT id, title, chapter, publish_at FROM billets WHERE id = 4;
SELECT b.id, title, chapter, c.publish_at, content FROM billets b, comments c WHERE b.id = 4 AND b.id = billet_id;
SELECT b.id, title, chapter,b.publish_at 'Billet', c.publish_at 'Commentaire', content FROM billets b, comments c WHERE b.id = 6 AND b.id = billet_id;

SELECT content, publish_at, users_id FROM comments WHERE billet_id = 6;

SELECT content, publish_at, users_id, pseudo FROM comments c, users u WHERE billet_id = 6 AND users_id = u.id;

SELECT content, publish_at, users_id, pseudo FROM comments c, users u WHERE billet_id = 6 AND users_id = u.id ORDER BY c.publish_at;

SELECT content, publish_at, users_id, pseudo FROM comments c, users u WHERE billet_id = 6 AND users_id = u.id ORDER BY c.publish_at DESC;

SELECT content, c.publish_at, c.users_id, pseudo, billet_id, title FROM comments c, users u, billets b 
                WHERE c.users_id = u.id AND c.billet_id = b.id AND c.report = 20 
                ORDER BY c.publish_at DESC;

SELECT COUNT(*) FROM users WHERE STATUS = 20

INSERT INTO `billets` (`title`, `abstract`, `chapter`, `publish_at`, `published`, `users_id`, `thumbs_up`, `thumbs_down`, `chapter_picture`) VALUES
('E3', 'pour le fun', 'bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla ', '2022-08-18 18:54:00', 1, 37, 0, 0, '64c7bee3bd68682341946cb8864d1ca1.jpg');
INSERT INTO `billets` (`title`, `abstract`, `chapter`, `publish_at`, `published`, `users_id`, `thumbs_up`, `thumbs_down`, `chapter_picture`) VALUES
('E4', 'pour le fun', 'bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla ', '2022-08-18 18:54:00', 1, 37, 0, 0, '64c7bee3bd68682341946cb8864d1ca1.jpg');
INSERT INTO `billets` (`title`, `abstract`, `chapter`, `publish_at`, `published`, `users_id`, `thumbs_up`, `thumbs_down`, `chapter_picture`) VALUES
('E5', 'pour le fun', 'bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla ', '2022-08-18 18:54:00', 1, 37, 0, 0, '64c7bee3bd68682341946cb8864d1ca1.jpg');
