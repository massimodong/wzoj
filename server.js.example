const SOCKET_PORT = 9000;
const REDIS = {
    "host": "127.0.0.1",
    "port": "6379",
    "password": null,
    "family": 4
}

function handler(request, response) {
    response.writeHead(200);
    response.end('');
}

var app = require('http').createServer(handler);
var io = require('socket.io')(app);
var ioRedis = require('ioredis');
var redis = new ioRedis(REDIS);

app.listen(SOCKET_PORT, function() {
});

io.on('connection', function(socket) {
});

redis.psubscribe('*', function(err, count) {
});

redis.on('pmessage', function(subscribed, channel, data) {
    data = JSON.parse(data);
    io.emit(channel + ':' + data.event, data.data);
});
