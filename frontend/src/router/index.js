import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '../views/HomeView.vue';
import UserList from '../views/UserList.vue';
import UserDetail from '../views/UserDetail.vue';
import UserForm from '../views/UserForm.vue';
import PositionList from '../views/PositionList.vue';

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/users',
      name: 'user-list',
      component: UserList,
    },
    {
      path: '/users/:id',
      name: 'user-detail',
      component: UserDetail,
      props: true,
    },
    {
      path: '/users/new',
      name: 'user-create',
      component: UserForm,
    },
    {
      path: '/positions',
      name: 'positions-list',
      component: PositionList,
    }
  ]
});

export default router;
