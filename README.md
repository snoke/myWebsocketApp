# myWebsocketApp

Work in Progress
Command driven Symfony6 Websocket Chat Server with VueJS Frontend  using JWT for authentication

![alt text](https://github.com/snoke/myWebsocketApp/blob/master/myWebsocketApp.png?raw=true)


## installation
run following command to checkout the project and set database url and jwt passphrase
```
git clone https://github.com/snoke/myWebsocketApp.git myWebsocketChat && nano myWebsocketChat/.env
```
edit following DOTENV variables matching your system
```
SERVER_URL='http://localhost' 
WEBSOCKET_URL='ws://localhost:8080' //depends on your server:start
DATABASE_URL="mysql://root@127.0.0.1:3306/myDatabase?serverVersion=mariadb-10.4.11"
JWT_PASSPHRASE=supersecretpassword
```
then run following command to set up database, jwt keypairs and assets
```
cd myWebsocketChat &&
composer up &&
php bin/console doctrine:database:create &&
php bin/console do:mi:mi &&
php bin/console lexik:jwt:generate-keypair &&
npm install &&
npm run dev 
```
## start websocket server
```
php bin/console server:start
```
