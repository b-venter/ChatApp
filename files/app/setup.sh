#!/bin/bash
echo "Setting hostname chatappsrv..."
hostnamectl set-hostname chatappsrv
systemctl restart wicked.service

#Add entry to /etc/issue to see IP address
echo "IP Address: \4{eth0}" >> /etc/issue

systemctl enable mysql.service
systemctl start mysql.service
echo "MySQL Service enabled and started..."

a2enmod php7
systemctl enable apache2.service
systemctl start apache2.service
echo "PHP7 module added to Apache. Apache HTTP Service enabled and started..."

firewall-cmd --zone=public --add-port=80/tcp --permanent
firewall-cmd --zone=public --add-port=9000/tcp --permanent
firewall-cmd --zone=public --add-port=9001/tcp --permanent
firewall-cmd --zone=public --add-port=9000/udp --permanent
firewall-cmd --zone=public --add-port=9001/udp --permanent
firewall-cmd --reload
echo "Firewall rules added: 80 (HTTP), 9000 and 90001 (websocket servers)..."

echo "Database secure setup starting..."
echo " "

# Author: Bert Van Vreckem <bert.vanvreckem@gmail.com>
#
# A non-interactive replacement for mysql_secure_installation
#https://bertvv.github.io/notes-to-self/2015/11/16/automating-mysql_secure_installation/


set -o errexit # abort on nonzero exitstatus
set -o nounset # abort on unbound variable

#{{{ Functions

# Predicate that returns exit status 0 if the database root password
# is set, a nonzero exit status otherwise.
is_mysql_root_password_set() {
  ! mysqladmin --user=root status > /dev/null 2>&1
}

# Predicate that returns exit status 0 if the mysql(1) command is available,
# nonzero exit status otherwise.
is_mysql_command_available() {
  which mysql > /dev/null 2>&1
}

#}}}

# Script proper

if ! is_mysql_command_available; then
  echo "The MySQL/MariaDB client mysql(1) is not installed."
  exit 1
fi

if is_mysql_root_password_set; then
  echo "Database root password already set"
  exit 0
fi

mysql --user=root <<_EOF_
  UPDATE mysql.user SET Password=PASSWORD('swhNUp5VQ7ZQgZGX') WHERE User='root';
  DELETE FROM mysql.user WHERE User='';
  DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1');
  DROP DATABASE IF EXISTS test;
  DELETE FROM mysql.db WHERE Db='test' OR Db='test\\_%';
  GRANT ALL PRIVILEGES ON *.* TO 'chatapp'@'localhost' IDENTIFIED BY '44arLmFoCSmqHKS';
  FLUSH PRIVILEGES;
_EOF_

#End of scipt from Bert Van Vreckem

echo "Creating database for ChatApp..."

mysql --user="chatapp" --password="44arLmFoCSmqHKS" <<_EOF_
    CREATE DATABASE chatapp /*\!40100 DEFAULT CHARACTER SET utf8 */;
    USE chatapp;
    CREATE TABLE messages (time datetime(6) DEFAULT NULL, sender varchar(50) DEFAULT NULL, message varchar(255) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    CREATE TABLE presence (presence varchar(50) NOT NULL,UNIQUE KEY presence (presence)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
_EOF_

echo "Database creation completed..."
echo ""

echo "Creating web files..."
mv *.php /srv/www/htdocs/
mv *.png /srv/www/htdocs/
mv *.js /srv/www/htdocs/
mv *.html /srv/www/htdocs/

echo "Adding web socket services..."
cp myphpsocket1.service /etc/systemd/system/
cp myphpsocket2.service /etc/systemd/system/

systemctl enable myphpsocket1.service
systemctl start myphpsocket1.service
systemctl enable myphpsocket2.service
systemctl start myphpsocket2.service

echo echo "Setup completed. You can connect to this server on http://$(php -r 'echo gethostbyname(gethostname());')"
