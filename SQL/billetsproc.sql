use projet4;
delimiter //

-- --------------------------------------
-- Procédures
-- --------------------------------------

drop procedure if exists projet4.findLastBillet;
create procedure projet4.findLastBillet(out billet_id INT, out pub_date TIMESTAMP)
    begin
        select max(pub_date) as dernierBillet from projet4.billets;
    end//

drop procedure if exists projet4.findFirstBillet;
create procedure projet4.findFirstBillet(out billet_id INT, out pub_date TIMESTAMP)
    begin
        select min(pub_date) as premierBillet from projet4.billets;
    end//

drop procedure if exists projet4.addBillet;
create procedure projet4.addBillet(in title TEXT, in content TEXT,in user_id INT, in pub_date TIMESTAMP, in published BOOLEAN)
    begin
        INSERT INTO projet4.billets(title, content, user_id, pub_date, published) 
            VALUES (title, content, user_id, pub_date, published);       
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
        declare user_id INT;
        declare authoremail VARCHAR(128);
        declare pub_date TIMESTAMP;
        declare published BOOLEAN;

        set x = 0;
        set title = '';
        set content = '';
        set user_id = projet4.ffAuthorID();
        set pub_date = NOW();
        set published = true;
        
        loop_billets : LOOP
            SET x = x + 1;
            if x > numberOfBillets THEN
                leave loop_billets;
            end if;

            set title = CONCAT('title',x + startIndex - 1);
            set content = CONCAT('content',x + startIndex - 1);

            call projet4.addBillet(title, content, user_id, pub_date, published);
        end LOOP;
    end//

-- --------------------------------------
-- Fonctions
-- --------------------------------------

-- delimiter ;