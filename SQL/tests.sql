--
--
--
SELECT * FROM projet4.users;

call projet4.findFirstAuthorID(@A, @AA);
select concat_ws(' : ', 'Author ID [ ', @A, ' ] email', @AA) '_______________ Found Author ______________' ;
call projet4.findFirstReaderID(@B);
select concat_ws(' : ', 'Reader ID [ ', @B, ' ]' ) '_______________ Found Author ______________' ;

call projet4.findLastBillet(@x,@y);
select concat_ws(' : ', 'Last Billet ', @y) '_______________ Found Last Billet ______________'; -- penser à rajouter @x pour avoir la date si besoin
call projet4.findFirstBillet(@x,@y);
select concat_ws(' : ', 'First Billet ', @y) '_______________ Found First Billet ______________';

--
select billets.id, content 'Title', 
      date_format(publish_at, '%M %d %Y --- %H:%i:%s') 'Published',
      pseudo 'Pseudo',
      email 'mail',
      if(published=true, 'en ligne', 'en attente') 'publication', users_id
  from projet4.billets, projet4.users where users.id = users_id
      order by publish_at desc;
--
select  billets.content 'Chapter Title',
        comments.content 'Comment',
        users.pseudo 'Pseudo',
        date_format(comments.publish_at, '%M %d %Y --- %H:%i:%s') 'Date'
  from projet4.billets, projet4.comments, projet4.users where comments.billet_id = billets.id
      and comments.users_id = users.id
      order by 4 desc;
      