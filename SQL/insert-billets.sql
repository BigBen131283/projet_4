SET AUTOCOMMIT = 0;
SET TRANSACTION READ WRITE;

DELETE FROM projet4.billets;

INSERT INTO projet4.billets(content, user_id, pub_date, published) 
    VALUES ('Texte aléaloire #1',13, 
        STR_TO_DATE('Jun 18 2022 3:37:00','%M %d %Y %H:%i:%s'), TRUE);
INSERT INTO projet4.billets(content, user_id, pub_date, published) 
    VALUES ('Texte aléaloire #2',13, 
        STR_TO_DATE('Jun 24 2022 16:28:00','%M %d %Y %H:%i:%s'), TRUE);
INSERT INTO projet4.billets(content, user_id, pub_date) 
    VALUES ('Texte aléaloire #3',13, 
        STR_TO_DATE('Jun 30 2022 12:54:00','%M %d %Y %H:%i:%s'));

COMMIT;

select billets.id, content 'Title', 
      date_format(pub_date, '%M %d %Y --- %H:%i:%s') 'Published',
      pseudo 'Pseudo',
      email 'mail',
      if(published=true, 'en ligne', 'en attente') 'publication', user_id
  from projet4.billets, projet4.users where users.id = user_id
      order by pub_date desc;