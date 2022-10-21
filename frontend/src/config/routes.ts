import IRoute from '../interfaces/route';
import HomePage from '../pages/home';
import Favourites from "../pages/favourites";

const routes: IRoute[] = [
    {
        path: '/',
        name: 'Home Page',
        component: HomePage,
        exact: true
    },
    {
        path: '/favourites',
        name: 'Favourites',
        component: Favourites,
        exact: true
    },
]

export default routes;