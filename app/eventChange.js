const path = require('path');

require('dotenv').config({ path: path.resolve(__dirname, '../.env')});

const http = require('http');
const request = require('request');
const server = http.createServer();
const io = require('socket.io').listen(server);

const apiHost = 'http://' + process.env.SERVER_HOST + ':' + process.env.SERVER_PORT;

io.sockets.on('connection', function (socket) {
    console.log('Connected');
    socket.on('event', function (params) {
        console.log(params);
        request(apiHost + '/' + params.url, {
            method: params.type,
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                ...params.data,
                params: {
                    apikey: process.env.API_KEY
                }
            }),
        }, function(error, response, body) {
            console.log('Response : ' + response);
            if (error) {
                console.error('Erreur : ' + error);
            }
            console.log(body);
            io.sockets.emit('message', body);
        })
    });
});

server.listen(process.env.NODE_PORT);

