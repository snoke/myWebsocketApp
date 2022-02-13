
<div name="menu">

---

<div align="center">
  
  [About](#about) -
  [Description](#Description) -
   [Features](#Features) -
   [Live Demo](#LiveDemo) -
   [Downloads](#Downloads) -
   [Documentation](#Documentation) -
   [Todos](#TODOs)
  </div>
  
---

  </div>
  
  

# <div align="center" name="about">myWebsocketApp </div>
### <div align="center">Command driven Symfony6 Websocket Chat Server with VueJS Web and native Android Client</div>
<p align="center">
  <img src="https://github.com/snoke/myWebsocketApp/blob/master/myWebsocketApp.png?raw=true" />
</p>

  
  
<ul>
  <li name="Description"> <h3> <a href="#menu"> [^] </a>  Description</h3> 
Websockets are persistent connections over TCP. 
This is not only a lot faster then typical http requests where a new connection gets established for each request, also this allows live broadcasting to clients.<br />
   
Project contains  a super simple `
server:start ` - Command, a Json Api and a VueJS web and native android client (apk).<br />
JWT is used for authentification.<br />

  </li>
    <li name="Features"> <h3> <a href="#menu"> [^] </a>  Features</h3> <ul>
      <li>Browser Push Notifications</li>
      <li>Emojis</li>
      <li>Message Status (delivered/seen)</li>
      <li>Image Upload/File Transfer</li>
      <li>live is typing info</li>
      <li>block/unblock chat</li>
     <li>vuejs web and native android client</li>
    </ul>
  </li>
    <li name="LiveDemo"> <h3> <a href="#menu"> [^] </a>  LiveDemo</h3> 
    
browse to https://websocketchat.stefan-sander.online or download the [Native Android Chat Client](#Downloads). <br />
You may use following credientials: 
`
alice:test
` and
`
bob:test
`

  </li>
    <li name="Downloads"> <h3> <a href="#menu"> [^] </a>  Downloads</h3> 
      
<ul>
  <li><a href="https://github.com/snoke/myWebsocketApp/raw/master/public/downloads/android-client-latest.apk">Native Android Chat Client (APK)</a>  - Live Demo Client </li>
</ul>

 
  </li>
    <li name="Documentation"> <h3> <a href="#menu"> [^] </a>  Documentation</h3> 
            
<ul>
  <li><h4> <a href="#menu"> [^] </a>  Requirements</h4>
    <ul>
      <li>webserver (apache2)</li>
      <li>database (mysql/mariadb)</li>
      <li>php7</li>
      <li>composer</li>
      <li>npm</li>
      <li>git</li>
    </ul>
  </li>
  <li><h4> <a href="#menu"> [^] </a>  Server Installation</h4>
run following command to checkout the project
&emsp; <pre>
git clone https://github.com/snoke/myWebsocketApp.git YourWebroot && cd YourWebroot
</pre>
    
    
    
create a file
` .env.local
`
with following content matching your system &emsp; <pre>
SERVER_URL='http://localhost' 
WEBSOCKET_URL='ws://localhost:8080' 
DATABASE_URL="mysql://DbUser:DbPassword@127.0.0.1:3306/myWebsocketChat?serverVersion=mariadb-10.4.11"
</pre> install dependencies, set up database, jwt keypairs and assets
&emsp; <pre>
git npm run install
</pre>

</li>
  <li><h4> <a href="#menu"> [^] </a>  Start Websocket Server</h4>
run &emsp; <pre>
npm run server
</pre> which is an alias of <pre>
php bin/console server:start
</pre>
&emsp; <img src="https://github.com/snoke/myWebsocketApp/blob/master/server_start.png?raw=true" />
</li>
  <li><h4> <a href="#menu"> [^] </a>  Build Android Client (Android Package Kit)</h4>
  check https://capacitorjs.com/docs/getting-started/environment-setup and set up Android Studio, SDKs and Emulators first.<br />then run
&emsp; <pre>
npm run build:android
</pre> 


this script will build the android app, start an emulator with the app and put the .apk into `/public/Downloads`.<br />it will remove all files created during the process

  </li>
    <li name="TODOs"> <h3> <a href="#menu"> [^] </a>  TODOs</h3> 
    
* setup firebase and implement native android notification
* build native ios client 
* add licence 

  </li>
  </ul>
<hr />
<div align="center">
happy coding :sunglasses:
  </div>
  
