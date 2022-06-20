use projet4;
delimiter //

-- --------------------------------------
--
-- --------------------------------------

drop procedure if exists projet4.findFirstAuthorID;
create procedure projet4.findFirstAuthorID(out authorid int, out authoremail varchar(128))
  begin
    select id, email into authorid, authoremail from projet4.users
      where userrole = 'AUTHOR' limit 1;    
  end//

drop procedure if exists projet4.findFirstReaderID;
create procedure projet4.findFirstReaderID(out readerid int)
  begin
    select id into readerid from projet4.users 
      where userrole = 'READER' limit 1;
  end//

drop procedure if exists projet4.log;
create procedure projet4.log(in mess varchar(128), in payload varchar(128))
  begin
    select concat_ws(':','LOG', date_format(current_timestamp, '%M %d %Y --- %H:%i:%s'), mess, payload) '';
  end//

drop procedure if exists projet4.addUser;
delimiter //
create procedure projet4.addUser(in email VARCHAR(128), in pwd VARCHAR(128), pseudo VARCHAR(128))
  begin
    INSERT INTO projet4.users(email, pwd, pseudo) 
      VALUES (email, pwd, pseudo);
  end//

drop procedure if exists projet4.dropUserById;
delimiter //
create procedure projet4.dropUserById(in id INT)
  begin
    DELETE from projet4.users where id = id;
  end//

-- --------------------------------------
--
-- --------------------------------------

drop function if exists projet4.ffAuthorID;

create function projet4.ffAuthorID() returns INT
  begin
      declare authorid INT;
      select id into authorid from projet4.users
        where userrole = 'AUTHOR' limit 1;
      return authorid;    
  end//

drop function if exists projet4.findUserByPseudo;
delimiter //
create function projet4.findUserByPseudo(in pseudo VARCHAR(128)) returns INT
  begin
    declare userId INT;
    select id into userId from projet4.users
      where pseudo = pseudo;
    return id;
  end//


delimiter ;



call projet4.findFirstAuthorID(@A, @AA);
call projet4.log('First Author ID is', @A);
call projet4.findFirstReaderID(@B);
call projet4.log('First Reader ID is', @B);

call projet4.addUser('toto@free.fr','9876', 'Toto98');
call projet4.dropUserById(14);

-- select email 'Author email is', pseudo 'and pseudo is' from projet4.users where id = projet4.ffAuthorID();
select email '', pseudo '' from projet4.users where id = projet4.ffAuthorID();
select * from projet4.users where id = projet4.findUserByPseudo('Toto98');


