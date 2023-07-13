#!/bin/bash
echo "Setting hostname chatappsrv..."
hostnamectl set-hostname chatappsrv
systemctl restart wicked.service

echo "Enabling firewall..."
systemctl enable firewalld.service
systemctl start firewalld.service

echo "Enabling MariaDB..."
systemctl enable mysql.service
systemctl start mysql.service
echo "MariaDB Service enabled and started..."

echo "Enabling Apache2 and PHP..."
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

echo "Database setup starting..."
echo " "

mysql --user=root <<_EOF_
  CREATE USER 'chatapp'@'localhost' IDENTIFIED BY '44arLmFoCSmqHKS';
  GRANT ALL PRIVILEGES ON *.* TO 'chatapp'@'localhost' IDENTIFIED BY '44arLmFoCSmqHKS';
  FLUSH PRIVILEGES;
_EOF_


echo "Creating database for ChatApp..."

mysql --user="chatapp" --password="44arLmFoCSmqHKS" <<_EOF_
    CREATE DATABASE chatapp /*\!40100 DEFAULT CHARACTER SET utf8 */;
    USE chatapp;
    CREATE TABLE messages (time datetime(6) DEFAULT NULL, sender varchar(50) DEFAULT NULL, message varchar(255) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    CREATE TABLE presence (presence tinyint(1) NOT NULL, language varchar(50) NOT NULL, UNIQUE (language)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    CREATE TABLE settings (entity varchar(50) NOT NULL, value varchar(255) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    INSERT INTO settings(entity, value) VALUES ('messageBoxBanner','');
    INSERT INTO settings(entity, value) VALUES ('timerBoxBanner','');
    INSERT INTO settings(entity, value) VALUES ('timerValue',10);

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
