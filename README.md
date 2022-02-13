



<div name="menu">
  
  



---

<div align="center">  
  
      
| <a href="#about">About</a> | <a href="#Description">Description</a> | <a href="#Features">Features</a> | <a href="#LiveDemo">Live Demo</a> | <a href="#Downloads">Downloads</a> | <a href="#Documentation">Documentation</a> | <a href="#TODOs">ToDo</a> | 
|-|-|-|-|-|-|-|  
</div>
  
---

  </div>
  
  

# <div align="center" name="about"><a href="#menu"> [^] </a>myWebsocketApp (beta)</div>
### <div align="center">Command driven Symfony6 Websocket Chat Server with VueJS Web and native Android Client</div>
<p align="center">
  <img src="https://github.com/snoke/myWebsocketApp/blob/master/myWebsocketApp.png?raw=true" />
</p>

  
  
<br /><ul>
  <li name="Description"> <h3> <a href="#menu"> [^] </a>  Description</h3> 
Websockets are persistent connections over TCP. 
This is not only a lot faster then typical http requests where a new connection gets established for each request, also this allows live broadcasting to clients.<br />
   
Project contains  a super simple `
server:start ` - Command, a Json Api and a VueJS web and native android client (apk).<br /><br />
JWT is used for authentification.

  <br /></li>
    <li name="Features"> <h3> <a href="#menu"> [^] </a>  Features</h3> <ul>
      <li>Browser Push Notifications</li>
      <li>Emojis</li>
      <li>Message Status (delivered/seen)</li>
      <li>Image Upload/File Transfer</li>
      <li>live is typing info</li>
      <li>block/unblock chat</li>
     <li>vuejs web and native android client</li>
    </ul><br />
  <br /></li>
    <li name="LiveDemo"> <h3> <a href="#menu"> [^] </a>  Live Demo</h3> 
    
browse to https://websocketchat.stefan-sander.online or download the [Native Android Chat Client](#Downloads). <br />
You may use following credientials: 
`
alice:test
` and
`
bob:test
`

  <br /></li>
    <li name="Downloads"> <h3> <a href="#menu"> [^] </a>  Downloads</h3> 
      
<ul>
  <li><a href="https://github.com/snoke/myWebsocketApp/raw/master/public/downloads/android-client-latest.apk">Native Android Chat Client (APK)</a>  - Live Demo Client </li>
</ul>

 
  <br /></li>
    <li name="Documentation"> <h3> <a href="#menu"> [^] </a>  Documentation</h3> 

      
  | <a href="#Requirements">Server Requirements</a> | <a href="#ServerInstallation">Server Installation</a> | <a href="#WebsocketServer">Start Websocket Server</a> | <a href="#ClientAPK">Build Android Client (APK)</a>            | 
  |-|-|-|-| 
   <br />   
<ul>
  <li name="Requirements"><h4> <a href="#Documentation"> [^] </a> Server Requirements</h4>
    <ul>
      <li>webserver with php8 (apache2)</li>
      <li>database (mysql/mariadb)</li>
      <li>composer, npm, git</li>
    </ul>
  <br /></li>
  <li name="ServerInstallation"><h4> <a href="#Documentation"> [^] </a>  Server Installation</h4>
<p>run following command to checkout the project and start the installer:
&emsp; <pre>
git clone https://github.com/snoke/myWebsocketApp.git && cd myWebsocketApp && npm run installer
</pre></p>
    
mount your web document root to `public`
<br /><br /></li>
  <li name="WebsocketServer"><h4> <a href="#Documentation"> [^] </a>  Start Websocket Server</h4>
<p>run: &emsp;</p> <pre>
npm run server
</pre> 

   
which is an alias of `
php bin/console server:start ` 
    


&emsp; <img src="https://github.com/snoke/myWebsocketApp/blob/master/server_start.png?raw=true" />
<br /><br /></li>
  <li name="ClientAPK"><h4> <a href="#Documentation"> [^] </a>  Build Android Client (Android Package Kit)</h4>
  <p>check <a href="https://capacitorjs.com/docs/getting-started/environment-setup">Capacitor Documentation</a> and set up Android Studio, SDKs and Emulators first.<br />then run:
&emsp; </p><pre>
npm run build:android
</pre> 


this script will build the android app, start an emulator with the app and put the .apk into `/public/Downloads`.<br />it will remove all files created during the process (the unpacked app)

  <br /></li>
  </li>
  </ul>
    <li name="TODOs"> <h3> <a href="#menu"> [^] </a>  ToDo</h3> 
    
* setup firebase and implement native android notification
* build native ios client 
* add licence 

  </li>
  </ul>
<hr />
<div align="center">
  © 2022<br /><a href="https://stefan-sander.online">Stefan Sander</a>
  </div>
  
