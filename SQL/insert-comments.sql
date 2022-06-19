SET AUTOCOMMIT = 0;
SET TRANSACTION READ WRITE;

DELETE FROM projet4.comments;

INSERT INTO projet4.comments(content, user_id, billet_id, pub_date) 
    VALUES ('Commentaire #1',10,8, 
        STR_TO_DATE('Jun 24 2022 16:28:00','%M %d %Y %H:%i:%s'));
INSERT INTO projet4.comments(content, user_id, billet_id, pub_date) 
    VALUES ('Commentaire #2',11,9, 
        STR_TO_DATE('Jul 17 2022 16:34:00','%M %d %Y %H:%i:%s'));
INSERT INTO projet4.comments(content, user_id, billet_id, pub_date) 
    VALUES ('Commentaire #3',10,9, 
        STR_TO_DATE('Jun 30 2022 17:58:00','%M %d %Y %H:%i:%s'));
INSERT INTO projet4.comments(content, user_id, billet_id, pub_date) 
    VALUES ('Commentaire #4',10,8, 
        STR_TO_DATE('Jun 30 2022 09:18:00','%M %d %Y %H:%i:%s'));
INSERT INTO projet4.comments(content, user_id, billet_id, pub_date) 
    VALUES ('Commentaire #5',12,9, 
        STR_TO_DATE('Jul 04 2022 23:59:00','%M %d %Y %H:%i:%s'));

COMMIT;

select  billets.content 'Chapter Title',
        comments.content 'Comment',
        users.pseudo 'Pseudo',
        date_format(comments.pub_date, '%M %d %Y --- %H:%i:%s') 'Date'
  from projet4.billets, projet4.comments, projet4.users where comments.billet_id = billets.id
      and comments.user_id = users.id
      order by 4 desc;

