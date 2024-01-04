import Home from '@/components/Home';
import Board from '@/components/Board';
import NewBoard from '@/components/NewBoard';
import PrivateBoard from '@/components/PrivateBoard';
import NotFoundBoard from '@/components/NotFoundBoard';
const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
    },
    {
        path: '/board/:id',
        name: 'Board',
        component: Board,
        props: true,
    },
    {
        path: '/new-board',
        name: 'NewBoard',
        component: NewBoard,
    },
    {
        path: '/private',
        name: 'Private',
        component: PrivateBoard,
    },
    {
        path: '/not-found',
        name: 'NotFound',
        component: NotFoundBoard,
    },
];

export default routes;
