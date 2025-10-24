import './main.css';

import React from 'react';
import { createRoot } from 'react-dom/client';
import App from './app';
import { createBrowserRouter, RouteObject, RouterProvider } from 'react-router-dom';
import routes from './routes';

const container = document.getElementById('feedbo-content');
if (container) {
  const router = createBrowserRouter(routes as RouteObject[]);
  createRoot(container).render(
    <React.StrictMode>
      <RouterProvider router={router} />
    </React.StrictMode>,
  );
}
