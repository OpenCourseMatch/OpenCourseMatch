import chokidar from "chokidar";
import { WebSocketServer } from "ws";
import { exec } from "child_process";
import util from "node:util";

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
    usePolling: true,
    ignored: "./src/static/css/style.css"
});

const promisifiedExec = util.promisify(exec);

const compileTailwindCSS = async () => {
    try {
        const { stdout, stderr } = await promisifiedExec("npx tailwindcss --input src/static/css/base.css --output src/static/css/style.css --minify");
        if(stderr) {
            console.error(`[LiveUpdate] Error compiling TailwindCSS: ${stderr}`);
        } else {
            console.log(`[LiveUpdate] TailwindCSS compiled successfully: ${stdout}`);
        }
    } catch(error) {
        console.error(`[LiveUpdate] Error compiling TailwindCSS: ${error}`);
    }
}

watcher
    .on("ready", () => {
        console.log("[LiveUpdate] Initial scan complete, waiting for changes...");
    })
    .on("change", async (path, stats) => {
        console.log(`[LiveUpdate] Update triggered - ${path}`);
        if(path.includes("templates")) {
            await compileTailwindCSS();
        }
        webSocketServer.clients.forEach(client => {
            if(client.readyState === 1) {
                client.send(JSON.stringify({
                    type: "update"
                }));
            }
        });
    });
