import { initializeApp } from 'firebase/app';
import { getMessaging, onMessage } from 'firebase/messaging';

initializeApp({
    apiKey: "AIzaSyA9YV_C2CEe7JUH0kfOwCfdfEjd-eLEbqI",
    authDomain: "cookbook-5fdd3.firebaseapp.com",
    projectId: "cookbook-5fdd3",
    storageBucket: "cookbook-5fdd3.appspot.com",
    messagingSenderId: "500719604396",
    appId: "1:500719604396:web:5041e6221a5667c2a9c509",
    measurementId: "G-0507867HWH"
});

const messaging = getMessaging(app);

onMessage(messaging, (payload) =>  {
    console.log("Message received.", payload);
    const title = "Hello world is awesome";
    const options = {
        body: "Your notification message .",
        icon: "/firebase-logo.png",
    };
    return self.registration.showNotification(
        title,
        options,
    );
});

alert(3)


