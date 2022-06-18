DELETE FROM projet4.users;

INSERT INTO `users`(`email`, `pwd`, `pseudo`, `status`, `role`) 
    VALUES ('tono@free.fr','1234','tono77','1','Aut');
INSERT INTO `users`(`email`, `pwd`, `pseudo`, `status`, `role`) 
    VALUES ('tono@orange.fr','1234','tono_77','3','Rea');
INSERT INTO `users`(`email`, `pwd`, `pseudo`, `status`, `role`) 
    VALUES ('tono@sfr.fr','1234','77tono','2','Rea');

SELECT * FROM projet4.users;

