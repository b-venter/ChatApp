# ChatApp

JW Regional Convention application that allows air-gapped instant messaging, synchronised countdown timer for simultaneous playing of media, and optional clock display for platforms.

![image](https://github.com/b-venter/ChatApp/assets/52171108/b316a8ba-2a96-4c9e-b3db-39a1fc8a123c)


The app is based on a LAMP stack alog with websockets. Deploying the application can be approached in two ways:
1. Use the provided custom OS as a virtual machine (VM). It has all the apache, mariaDb and php packages required.
2. Use a Linux OS of your choice. All package will ned to be instaleld manually. (I provide an example using openSUSE, but as this will vary between OSes - it is just a guide to find your local OS' equivalent packages)

### Custom OS image as VM
The custom OS is available as a KVM/XEN, VMware and VirtualBox hard disk image file.
#### KVM/XEN
* Choose "import existing disk image". Select the **qcow** disk.
* Create an appliance with 1x vCPU @ 2GHz, 2GB RAM
#### VMware
* Create a new VM. During the creation wizard, delete the hard disk so that one is not automatically created.
* 1x vCPU @ 2GHz, 2GB RAM
* Once created, browse to the ESXI's datastore and upload the **vmdk** to the folder of the new VM
* Edit the VM, "Add Hard Disk" > "Existing Hard Disk" > Select the uploaded **vmdk**.
#### VirtualBox
* Use the **vmdk** or convert the **qcow**.
* Create a VM as per VMware or KVM
* Recommend network adaptor is using *Bridge*

 - Once the VM has been created, connect to it via console and complete the intial start wizard.
 - After the inital start wizard, you should see the IP address obtained via DHCP. This shows that the ethernet is working.
 - Login using `root` and the passwod you chose during the inital wizard.
 - Create a normal user account (e.g. chat) that can be used for SSH access:
   >useradd -m chat  
   >password chat
 - copy the `app` folder to the VM, then run the following commands:
   >cd app/  
   >sudo sh setup.sh
 - The script will install the services, firewall rules and MySQL database
 - **Note** that currently an extra step is needed - specifying the server's IP address for the websocket service. (You can also just edit chat_init.php before copying it across):
   >sudo vi /srv/www/htdocs/chat_init.php
 - Update `$local_ip` to = IP of your VM
 - Reboot the VM
   >sudo init 6

See **Setup** below for next steps

### Linux OS general
This provides an example using the openSUSE OS.  
>sudo zypper ref
>sudo zypper up
>sudo zypper in apache2 apache2-prefork apache2-utils libapr1 libapr-util1 libbrotlienc1 system-user-wwwrun  
>sudo zypper in php7 php7-cli apache2-mod_php7 php7-mysql php7-pdo
>sudo zypper in libaio1 libJudy1 libmariadb3 libodbc2 mariadb mariadb-client mariadb-errormessages python3-mysqlclient  
>sudo zypper in mariadb-tools perl perl-DBD-mysql perl-DBI
>sudo zypper in php7-sockets php7-json

With the necessary packages installed, you can follow similar steps as above:
 - copy the `app` folder to the VM, then run the following commands:
   >cd app/  
   >sudo sh setup.sh
 - The script will install the services, firewall rules and MySQL database
 - **Note** that currently an extra step is needed - specifying the server's IP address for the websocket service. (You can also just edit chat_init.php before copying it across):
   >sudo vi /srv/www/htdocs/chat_init.php
 - Update `$local_ip` to = IP of your machine
 - Reboot your machine

See **Setup** below for next steps

### Setup
Setup is quite straightforward:
1. Load the webpage in a browser (http://ip-of-machine)
2. In the bottom right-hand corner is a "Settings" button
3. In Settings, set Banner text, add Languages
4. Browse to Home, and access the "ChatApp" button.
5. Select a language and department, then test the interface

If you want to use a custom user handle (Program Overseer, Platform, AV, etc), use a custom URL:  
`http://192.168.1.67/chat.php?user=Afrikaans_PO`  
In this example:
* The chat app server has an IP address of *192.168.1.67*
* The custom user handle is *Afrikaans_PO*
