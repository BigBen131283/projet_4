use projet4;
delimiter //

-- --------------------------------------
-- Procédures
-- --------------------------------------

drop procedure if exists projet4.findLastBillet;
create procedure projet4.findLastBillet(out zedate TIMESTAMP, out billid INT)
    begin
        select publish_at, id into zedate, billid from projet4.billets order by 1 desc limit 1;
    end//

drop procedure if exists projet4.findFirstBillet;
create procedure projet4.findFirstBillet(out zedate TIMESTAMP, out billid INT)
    begin
        select publish_at, id into zedate, billid from projet4.billets order by 1 asc limit 1;
    end//

drop procedure if exists projet4.addBillet;
create procedure projet4.addBillet(in title TEXT, in content TEXT,in users_id INT, in publish_at TIMESTAMP, in published BOOLEAN)
    begin
        INSERT INTO projet4.billets(title, content, users_id, publish_at, published) 
            VALUES (title, content, users_id, publish_at, published);       
    end//

drop procedure if exists projet4.dropBilletByID;
create procedure projet4.dropBilletByID(in billet_id INT, out result VARCHAR(128))
    begin
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
    end//

drop procedure if exists projet4.loadBillets;
create procedure projet4.loadBillets(in numberOfBillets INT, in startIndex INT)
    begin
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
    end//

-- --------------------------------------
-- Fonctions
-- --------------------------------------

drop function if exists projet4.randomdate;
create function projet4.randomdate() returns timestamp
  begin
    declare toto timestamp;
    SELECT CURRENT_TIMESTAMP - INTERVAL FLOOR(RAND() * 30 * 24 * 60 * 60) second into toto;
    return toto;
  end//


delimiter ;