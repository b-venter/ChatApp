# Working with the folders in this directory and installing

The "app" directory contains the webpages relating to the fully functioning Chat Application
The setup.sh script will:
<ul>
    <li> Set the host name to "chatappsrv".</li>
    <li> Move all files to the web server directory.</li>
    <li> Initiate the mysql and apache services.</li>
    <li> Initiate the two websocket services.</li>
    <li> Set the firewall requirements.</li>
    <li> Create the application's database.</li>
</ul>
The "vm" directory contains a vmdk file of a pre-built openSUSE JeOS with all needed components.
This can easily be run in VirtualBox or VMware ESXI.
The "app" directory will need to be copied to the running virtual machine via scp, and the setup.sh script run to complete the install.


Virtual Box example, starting with the creation of the virtual machine:
<ol>
  <li> Create New...  </li>
    <ol>
    <li> Provide a unique name. </li>
    <li>Specify openSUSE (64-bit). </li>
    <li> Hard disk -> use an existing virtual hard disk folder. </li>
    <li> Select the extracted .vmdk file. </li>
    <li> Create. </li>
    </ol>
  <li> Select the <b>Settings</b> of the newly created virtual machine -> Network: recommend setting it to <i>"Bridged Mode"</i>.
  <li> Start the virtual machine and complete the first-run wizard  </li>
    <ol><li> You can skip user creation, and just provide a root password.  </li></ol>
  <li> Login an determine IP address (set via DHCP) with "ip addr".  </li>
    <ol><li> A static IP can be set via "yast" -> System -> Network settings. </li></ol>
  <li> Copy the "app" directory with scp to the virtual machine (scp -r app/ root@ip.ad.re.ss:app/) </li>
  <li> SSH to the virtual machine, and cd to the "app/" directory. </li>
  <li> Run setup.sh (e.g. "sh setup.sh") </li>
  <li> When setup confirms it has completed, open http://chatappsrv. </li>
</ol>
