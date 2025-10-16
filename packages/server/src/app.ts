import express, { Request, Response } from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import axios from 'axios';

// Load environment variables
dotenv.config({ path: './.env' });

const app = express();
const port = 5544;

// Middleware
app.use(cors()); // Enable CORS for all routes
app.use(express.json()); // Parse JSON request bodies

interface PingResponse {
  message: string;
  status: 'unimplemented';
}

app.get('/', (req: Request, res: Response<PingResponse>) => {
  const homeResponse: PingResponse = {
    message: 'This endpoint is not yet implemented',
    status: 'unimplemented',
  }
  res
    .setHeader('Content-Type', 'application/json')
    .send(homeResponse);
});

// API Routes
app.get('/api/search', async (req: Request, res: Response) => {
  try {
    const { q, page } = req.query;
    
    // Validate search query
    if (!q || typeof q !== 'string') {
      return res.status(400).json({ 
        error: 'Search query parameter "q" is required' 
      });
    }

    // Call Guardian API using axios
    const guardianResponse = await axios.get(`${process.env.GUARDIAN_API_URL}/search`, {
      params: {
        q: q,
        page: page || 1,  // Default to page 1 if not provided
        'page-size': 20
      },
      headers: {
        'api-key': process.env.GUARDIAN_API_KEY || 'test'
      }
    });

    // Return the Guardian API response
    res.json(guardianResponse.data);
  } catch (error) {
    console.error('Search error:', error);
    res.status(500).json({ error: 'Internal server error' });
  }
});

app.listen(port, () => {
  console.log(`Server running at http://localhost:${port}`);
});