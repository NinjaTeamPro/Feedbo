import { createBrowserRouter, RouteObject } from 'react-router-dom';
import Home from '@/pages/home';
import Board from '@/pages/board';
import NewBoard from '@/pages/new-board';
import PrivateBoard from '@/pages/private';
import NotFoundBoard from '@/pages/not-found';
import Comment from '@/pages/board/comment';

const routes: RouteObject[] = [
  {
    path: '/',
    element: <Home />,
  },
  {
    path: '/board/:id',
    element: <Board />,
    children: [
      {
        path: ':postSlug',
        element: <Comment />,
      },
    ],
  },
  {
    path: '/new-board',
    element: <NewBoard />,
  },
  {
    path: '/private',
    element: <PrivateBoard />,
  },
  {
    path: '/not-found',
    element: <NotFoundBoard />,
  },
  {
    path: '*',
    element: <NotFoundBoard />,
  },
];

export const router = createBrowserRouter(routes);

export default routes;


