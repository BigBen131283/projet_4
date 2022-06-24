use projet4;
delimiter //

-- --------------------------------------
-- Procédures
-- --------------------------------------

drop procedure if exists projet4.findFirstAuthorID;
create procedure projet4.findFirstAuthorID(out authorid int, out authoremail varchar(128))
  begin
    select id, email into authorid, authoremail from projet4.users
      where role = 10 limit 1;    
  end//

drop procedure if exists projet4.findFirstReaderID;
create procedure projet4.findFirstReaderID(out readerid int)
  begin
    select id into readerid from projet4.users 
      where role = 20 limit 1;
  end//

drop procedure if exists projet4.addUser;
create procedure projet4.addUser(in email VARCHAR(128), in pwd VARCHAR(128), 
    in pseudo VARCHAR(128), in isAuthor BOOLEAN,
    out result VARCHAR(128), out isError TINYINT)
  begin
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
  end//

drop procedure if exists projet4.dropUserById;
create procedure projet4.dropUserById(in userId INT, out result VARCHAR(128))
  begin
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
  end//

drop procedure if exists projet4.loadusers;

create procedure projet4.loadusers(in numberOfUsers INT, in startIndex INT, 
    in isAuthor BOOLEAN)
begin
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
end//

-- --------------------------------------
-- Fonctions
-- --------------------------------------

drop function if exists projet4.ffAuthorID;

create function projet4.ffAuthorID() returns INT
  begin
      declare authorid INT;
      select id into authorid from projet4.users
        where role = 10 limit 1;
      return authorid;    
  end//

drop function if exists projet4.findUserByPseudo;
create function projet4.findUserByPseudo(userpseudo VARCHAR(128)) returns INT
  begin
    declare userId INT;
    DECLARE EXIT HANDLER FOR NOT FOUND
        begin
            return 9999;
        end;
    select id into userId from projet4.users
      where pseudo = userpseudo;
    return userId;
  end//


delimiter ;


