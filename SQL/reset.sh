#
#
#
clear
echo 'Create tables'
mysql -u root --password=root --database=projet4 < create-table.sql
echo 'Add user procedures and functions'
mysql -u root --password=root --database=projet4 < usersproc.sql
echo 'Add billets procedures and functions'
mysql -u root --password=root --database=projet4 < billetsproc.sql
echo 'Add comments procedures and functions'
mysql -u root --password=root --database=projet4 < commentsproc.sql
echo 'Add users and billets'
mysql -u root --password=root --database=projet4 < dataload.sql
echo
echo
echo "Now proceed to some tests"
mysql -u root --password=root --database=projet4 < tests.sql
echo "Done"