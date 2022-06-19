SET AUTOCOMMIT = 0;
SET TRANSACTION READ WRITE;

DELETE FROM projet4.users;

-- INSERT INTO `users`(`email`, `pwd`, `pseudo`, `status`, `role`) 
--     VALUES ('tono@free.fr','1234','tono77','1','Aut');
-- INSERT INTO `users`(`email`, `pwd`, `pseudo`, `status`, `role`) 
--     VALUES ('tono@orange.fr','1234','tono_77','3','Rea');
-- INSERT INTO `users`(`email`, `pwd`, `pseudo`, `status`, `role`) 
--     VALUES ('tono@sfr.fr','1234','77tono','2','Rea');

INSERT INTO projet4.users(email, pwd, pseudo) 
    VALUES ('tono@free.fr','1234','tono77');
INSERT INTO projet4.users(email, pwd, pseudo) 
    VALUES ('tono@orange.fr','1234','tono_77');
INSERT INTO projet4.users(email, pwd, pseudo, userstatus) 
    VALUES ('tono@free.fr','1234','77tono', 'SUSPENDED');
INSERT INTO projet4.users(email, pwd, pseudo, userrole) 
    VALUES ('auteur@sfr.fr','1234','Jean_Forteroche', 'AUTHOR');

COMMIT;

SELECT * FROM projet4.users;

