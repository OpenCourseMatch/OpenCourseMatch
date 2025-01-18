const webSocketConnection = new WebSocket("ws://localhost:5173");

webSocketConnection.onmessage = (event) => {
    const message = JSON.parse(event.data);
    if(message.type === "update") {
        location.reload();
    }
}
