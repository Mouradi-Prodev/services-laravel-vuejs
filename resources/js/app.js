import './bootstrap';
import { createRouter, createWebHistory } from 'vue-router';
//import routes from './routes';
import UserDashboard from './components/UserDashboard.vue';
import Dashboard from './components/Dashboard.vue';
import AddService from './components/AddService.vue';
import ViewServices from './components/ViewServices.vue';
import Settings from './components/Settings.vue';
import Blogs from './components/Blogs.vue';
import ViewBlogs from './components/ViewBlogs.vue';
import UBlogView from './components/UBlogView.vue';
import { createApp } from 'vue';




const app = createApp({});


app.component('user-dash', UserDashboard);


const router = createRouter({
    history: createWebHistory(),
    routes: [
      { path: '/home',name:'home', component: Dashboard },
      { path: '/addservice', component: AddService},
      {path: '/ViewServices', component:ViewServices},
      {path: '/Settings', component:Settings},
      {path: '/addBlogs',component: Blogs},
      {path: '/ViewBlogs',component: ViewBlogs},
      {path: '/UBlogView/:id',name:'blog',component: UBlogView,props: true},
    ]
  });
  
  
  app.use(router).mount('#app')
