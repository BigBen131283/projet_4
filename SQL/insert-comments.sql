DELETE FROM projet4.comments;

INSERT INTO `comments`(`text`, `like`, `dislike`, `users_id`, `billets_id`) 
    VALUES ('Commentaire #1',3,2,2,1);
INSERT INTO `comments`(`text`, `like`, `dislike`, `users_id`, `billets_id`) 
    VALUES ('Commentaire #1',3,2,3,22);

SELECT * FROM projet4.comments;

