## docker-compose up

### create ssl certificate file
openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout localhost.key -out localhost.crt -subj "/CN=localhost"
### make that file p12 to import in browser trusted certs after 
### go by link (https://localhost:6002/app/some-pusher-app-key?protocol=7&client=js&version=4.3.1&flash=false) and advance
openssl req -key localhost.key -new -out localhost.csr
openssl x509 -signkey localhost.key -in localhost.csr -req -days 365 -out localhost.crt
openssl pkcs12 -export -in localhost.crt -inkey localhost.key -out localhost.p12


### connect to wss from outside of containers
wscat -n -c "wss://localhost:6002/app/some-pusher-app-key?protocol=7&client=js&version=4.3.1&flash=false"
