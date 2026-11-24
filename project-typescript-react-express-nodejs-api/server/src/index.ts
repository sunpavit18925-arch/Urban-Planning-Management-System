import { createApp } from './app';
import { database } from './data/database';
const app = createApp();
const PORT = Number(process.env.PORT) || 4000;
const server = app.listen(PORT, () => {
  console.log(`[UPMS API] Express TypeScript server running at http://localhost:${PORT}`);
  console.log('[UPMS API] SQLite storage ready; changes persist across restarts');
});
for (const signal of ['SIGINT', 'SIGTERM'] as const) process.on(signal, () => {
  server.close(() => { database.close(); process.exit(0); });
});
