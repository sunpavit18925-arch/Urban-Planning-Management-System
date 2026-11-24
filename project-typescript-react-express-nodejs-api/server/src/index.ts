import cors from 'cors';
import express from 'express';
import { existsSync } from 'node:fs';
import path from 'node:path';
import apiRouter from './routes/api';

const app = express();
const PORT = Number(process.env.PORT) || 4000;

app.use(cors());
app.use(express.json());

app.use('/api', apiRouter);
app.use('/api', (_req, res) => {
  res.status(404).json({ error: 'ไม่พบ API ที่ระบุ' });
});

const clientDirectory = path.resolve(__dirname, '../../client/dist');
app.use(express.static(clientDirectory));
app.get('*', (_req, res) => {
  if (existsSync(path.join(clientDirectory, 'index.html'))) {
    res.sendFile(path.join(clientDirectory, 'index.html'));
    return;
  }
  res.json({
    service: 'UPMS API',
    docs: '/api/health',
  });
});

app.listen(PORT, () => {
  console.log(`[UPMS API] Express TypeScript server running at http://localhost:${PORT}`);
  console.log('[UPMS API] Using in-memory Mockup Database (no real database connected)');
});
