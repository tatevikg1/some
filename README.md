docker-compose up


connect to wss
wscat -n -c "wss://127.0.0.1:6002/app/some-pusher-app-key?protocol=7&client=js&version=4.3.1&flash=false"
