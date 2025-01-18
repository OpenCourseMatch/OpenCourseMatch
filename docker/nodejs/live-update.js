import chokidar from "chokidar";
import { WebSocketServer } from "ws";

const webSocketServer = new WebSocketServer({ port: 5173 }, () => {
    console.log("[LiveUpdate] WebSocket server is running on port 5173");
});

const watcher = chokidar.watch([
    "./framework",
    "./src/config",
    "./src/lib",
    "./src/pages",
    "./src/static",
    "./src/templates",
    "./src/translations"
], {
    persistent: true,
    usePolling: true
});

watcher
    .on("ready", () => {
        console.log("[LiveUpdate] Initial scan complete, waiting for changes...");
    })
    .on("change", (path, stats) => {
        console.log(`[LiveUpdate] Update triggered - ${path}`);
        webSocketServer.clients.forEach(client => {
            if(client.readyState === 1) {
                client.send(JSON.stringify({
                    type: "update"
                }));
            }
        });
    });
