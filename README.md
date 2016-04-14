#Robot Management Cloud Server

*Please bear in mind that both the application and the documentation are still work in progress.*

The whole system consists of several parts:
* OpenVPN network
* RMS (Robot Management System) and it's extension
* Dispatcher

## OpenVPN
[Get OpenVPN](https://openvpn.net/index.php/open-source/downloads.html)

The VPN network should be set up first, you can either make your own configuration, or use the one supplied in **openvpn_config** folder, in **user_client**, **robot_client** and **server** subfolders respectively.

If you are to use your own configuration, keep in mind that client isolation is strongly advised, as well as no routing being set.
Since the robots and user clients need to communicate only with the server, adding these options would only cause security risk.

One VPN network should be sufficient, given the client isolation, but two is strongly recommended, so a user cannot create a fake robot connection,
which would not cause any security issues, but could cause conflicts in robot identification.

## RMS extension
[Get RMS](http://wiki.ros.org/rms)

RMS setup is to be done exactly as described on the RMS webpage, with the addition of copying and overwriting the files in *app* folder by the ones
supplied from this repository, after the installation is complete.

Once you complete the installation and copy the required files, you need to navigate to the installation folder and modify file located in **RMS_FOLDER/app/config/bootstrap.php**
You will find a line containing **"VPN_SERVER_IP"** and change the IP address there to the address of the VPN server, to which user clients will connect.

## Dispatcher

Once the VPN and RMS with the extension are set up, you need to configure and run **dispatcher_server.py** on the server side, and **dispatcher_client.py** on the side
of the robot.

On the client, all you need to do is to edit the address of the server, you will find at the end of the script, simply change the **"cloudServerIP"** to the IP address
of the VPN server for robots.

On the server side, you may want to again navigate to the bottom lines of the script, and modify the listening ports or addresses for server threads,
or add another network service by adding another line with the **"addTunnel"** method.

Details of both server and client configurations for Dispatcher can be found below.

*Note: this is to be changed, in order to simplify the configuration of both client and server scripts*



## Configuration
work in progress...


###TODO:
* UDP
* logging
* statisctics collecting
* extensive readme
* Travis tests