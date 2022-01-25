# myWebsocketApp
Command driven Symfony6 Websocket Chat Server with VueJS Client using JWT for authentication

![alt text](https://github.com/snoke/myWebsocketApp/blob/master/myWebsocketApp.png?raw=true)


## Live Demo
check https://websocketchat.stefan-sander.online
using following credientials
```
alice:test
bob:test
```

## installation
run following command to checkout the project
```
git clone https://github.com/snoke/myWebsocketApp.git myWebsocketChat && nano myWebsocketChat/.env
```
edit following DOTENV variables matching your system
```
SERVER_URL='http://localhost' 
WEBSOCKET_URL='ws://localhost:8080' //should match server:start command output as server and client can run on different machines
DATABASE_URL="mysql://root@127.0.0.1:3306/myDatabase?serverVersion=mariadb-10.4.11"
JWT_PASSPHRASE=supersecretpassword
```
mount web root to project dir

then run following command to set up database, jwt keypairs and assets
```
cd myWebsocketChat &&
composer up &&
php bin/console doctrine:database:create &&
php bin/console do:mi:mi &&
php bin/console lexik:jwt:generate-keypair &&
chown www-data config/jwt -R &&
npm install &&
npm run dev 
```
## start websocket server
```
php bin/console server:start
```

## TODOS
* implement user settings
* check jwt bearer in socket commands
