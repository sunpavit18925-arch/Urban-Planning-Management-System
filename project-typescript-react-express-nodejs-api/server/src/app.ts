import cors from 'cors';
import express from 'express';
import { existsSync } from 'node:fs';
import path from 'node:path';
import apiRouter from './routes/api';
import { apiErrorHandler } from './routes/operations';
import { AuthService } from './services/auth';
import { database } from './data/database';

export function createApp() {
  const app = express();
  app.disable('x-powered-by');
  app.use(cors());
  app.use(express.json({ limit: '64kb' }));
  const auth = new AuthService(database);
  app.use('/api', auth.router);
  app.use('/api', auth.middleware);
  app.use('/api', apiRouter);
  app.use('/api', (_req, res) => { res.status(404).json({ error: 'ไม่พบ API ที่ระบุ' }); });
  const clientDirectory = path.resolve(__dirname, '../../client/dist');
  app.use(express.static(clientDirectory));
  app.get('*', (_req, res) => {
    if (existsSync(path.join(clientDirectory, 'index.html'))) res.sendFile(path.join(clientDirectory, 'index.html'));
    else res.json({ service: 'UPMS API', docs: '/api/health' });
  });
  app.use(apiErrorHandler);
  return app;
}
