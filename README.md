# myWebsocketApp

Work in Progress
Command driven Symfony6 Websocket Chat Server with VueJS Frontend Chat Client using JWT for authentication

![alt text](https://github.com/snoke/myWebsocketApp/blob/master/myWebsocketApp.png?raw=true)


## installation
```
git clone https://github.com/snoke/myWebsocketApp.git myWebsocketChat 
nano myWebsocketChat/.env
```
-- edit .env Set database url and jwt passphrase--
```
cd myWebsocketChat &&
composer up &&
php bin/console doctrine:database:create &&
php bin/console do:mi:mi &&
php bin/console lexik:jwt:generate-keypair &&
npm install &&
npm run dev 
```
## start
```
php bin/console server:start to start Websocket Server
```
## known bugs / Todo
server not configured to use ssl, which will be blocked by most browsers when client and server not on same machine. 
