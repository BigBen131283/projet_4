use projet4;
delimiter //

-- --------------------------------------
-- Procédures
-- --------------------------------------

drop procedure if exists projet4.findLastComment;
create procedure projet4.findLastComment(out zedate TIMESTAMP, out commid INT)
    begin
        select publish_at, id into zedate, commid from projet4.comments order by 1 desc limit 3;
    end//

drop procedure if exists projet4.findFirstComment;
create procedure projet4.findFirstComment(out zedate TIMESTAMP, out commid INT)
    begin
        select publish_at, id into zedate, commid from projet4.comments order by 1 asc limit 1;
    end//

drop procedure if exists projet4.addComment;
create procedure projet4.addComment(in content VARCHAR(255),in users_id INT, in billet_id INT, in publish_at TIMESTAMP)
    begin
        INSERT INTO projet4.comments(content, users_id, billet_id, publish_at) 
            VALUES (content, users_id, billet_id, publish_at);       
    end//

drop procedure if exists projet4.dropCommentByID;
create procedure projet4.dropCommentByID(in comment_id INT, out result VARCHAR(128))
    begin
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
    end//

drop procedure if exists projet4.loadComments;
create procedure projet4.loadComments(in numberOfComments INT, in startIndex INT)
    begin
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
    end//

-- --------------------------------------
-- Fonctions
-- --------------------------------------

drop function if exists projet4.randomReader;
create function projet4.randomReader() returns INT
    begin
        declare toto INT;
        select id into toto from projet4.users where role=20 order by rand() limit 1;
        return toto;
    end//


drop function if exists projet4.randomBillet;
create function projet4.randomBillet() returns INT
    begin
    declare toto INT;
        select id into toto from projet4.billets where published=1 order by rand() limit 1;
        return toto;
    end//

delimiter ;