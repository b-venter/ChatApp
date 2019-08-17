# Working with the folders in this directory and installing

The "app" directory contains the webpages relating to the fully functioning Chat Application
The setup.sh script will:
    + Set the host name to "chatappsrv".
    + Move all files to the web server directory.
    + Initiate the mysql and apache services.
    + Initiate the two websocket services.
    + Set the firewall requirements.
    + Create the application's database.

The "vm" directory contains a vmdk file of a pre-built openSUSE JeOS with all needed components.
This can easily be run in VirtualBox or VMware ESXI.
The "app" directory will need to be copied to the running virtual machine via scp, and the setup.sh script run to complete the install.

Running in Virtual Box:
  1. Create New...  
    (A) Provide a unique name.  
    (B) Specify openSUSE (64-bit).  
    (C) Hard disk -> use an existing virtual hard disk folder.  
    (D) Select the extracted .vmdk file.  
    (E) Create  
  2. Select the <b>Settings</b> of the newly created virtual machine -> Network: recommend setting it to <u>Bridged Mode</u>.
  3. Start the virtual machine and complete the first-run wizard  
    (A) You can skip user creation, and just provide a root password.  
  4. Login an determine IP address (set via DHCP) with "ip addr".  
    (A) A static IP can be set via "yast" -> System -> Network settings.  
  5. Copy the "app" directory with scp to the virtual machine (scp -r app/ root@ip.ad.re.ss:app/)
  6. SSH to the virtual machine, and cd to the "app/" directory.
  7. Run setup.sh (e.g. "sh setup.sh")
  8. When setup confirms it has completed, open http://chatappsrv.
    
