import Vue from 'vue';
import Router from 'vue-router';


// We could use sub-router as separated file
import nestedRouter from './modules/nested';
import errorRouter from './modules/error';

// in development-env not use lazy-loading, because lazy-loading too many pages will cause webpack hot update too slow. so only in production use lazy-loading;
// detail: https://panjiachen.github.io/vue-element-admin-site/#/lazy-loading

Vue.use(Router);

/* Layout */
import Layout from '../views/layout/Layout';
import ElementUI from "element-ui";
import Cookies from "js-cookie";
import i18n from "../lang";

/**
 * hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
 *                                if not set alwaysShow, only more than one route under the children
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noredirect           if `redirect:noredirect` will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    title: 'title'               the name show in submenu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    breadcrumb: false            if false, the item will hidden in breadcrumb(default is true)
  }
 **/
export const constantRouterMap = [
    {
        path: '/redirect',
        component: Layout,
        hidden: true,
        children: [
            {
                path: '/redirect/:path*',
                component: require('@/views/redirect/index'),
            },
        ],
    },
    {
        path: '/login',
        component: require('@/views/login/index').default,
        hidden: true,
    },
    {
        path: '/auth-redirect',
        component: require('@/views/login/AuthRedirect').default,
        hidden: true,
    },
    {
        path: '',
        component: Layout,
        redirect: 'dashboard',
        children: [
            {
                path: 'dashboard',
                component: require('@/views/dashboard/index').default,
                name: 'Dashboard',
                meta: {title: 'Dashboard', icon: 'dashboard', noCache: true},
            },
        ],
    },
    {
        path: '/menus',
        component: Layout,
        redirect: '/menus',
        name: 'Menu',
        meta: {
            title: i18n.t('my_lang.menu'),
            icon: 'example',
        },
        children: [
            {
                path: '/menu',
                component: require('@/views/menu/Thu2').default,
                name: 'Menu2',
                meta: {title: 'Thứ 2'},
            },
            {

                path: '/menu/menu3',
                component: require('@/views/menu/Thu3').default,
                name: 'Menu3',
                meta: {title: 'Thứ 3'},
            },
            {

                path: '/menu/menu4',
                component: require('@/views/menu/Thu4').default,
                name: 'Menu4',
                meta: {title: 'Thứ 4'},
            },
            {

                path: '/menu/menu5',
                component: require('@/views/menu/Thu5').default,
                name: 'Menu5',
                meta: {title: 'Thứ 5'},
            },
            {

                path: '/menu/menu6',
                component: require('@/views/menu/Thu6').default,
                name: 'Menu6',
                meta: {title: 'Thứ 6'},
            },
            {

                path: '/menu/menu7',
                component: require('@/views/menu/Thu7').default,
                name: 'Menu7',
                meta: {title: 'Thứ 7'},
            },


        ],
    },
    {
        path: '/restaurant',
        component: Layout,
        children: [
            {
                path: '',
                name: 'ListRestaurant',
                component: require('@/views/restaurant/ListRestaurant').default,
                meta: {title: 'Nhà Hàng', icon: 'form'},
            },
            {
                hidden: true,
                path: 'create',
                name: 'CreateRestaurant',
                component: require('@/views/restaurant/CreateRestaurant').default,
                meta: {title: 'Create Restaurant', icon: 'form'},
            },
        ],
    },
    {
        path: '/food',
        component: Layout,
        children: [
            {
                path: '',
                name: 'ListFood',
                component: require('@/views/Food/ListFood').default,
                meta: {title: 'Món Ăn', icon: 'form'},
            },
            {
                hidden: true,
                path: 'create',
                name: 'CreateFood',
                component: require('@/views/Food/CreateFood').default,
                meta: {title: 'Create Food', icon: 'form'},
            },
        ],
    },
    {
        path: '/order',
        component: Layout,
        children: [
            {
                path: '',
                name: 'ListOrder',
                component: require('@/views/order/ListOrder').default,
                meta: {title: 'Đơn Hàng', icon: 'form'},
            },
        ],
    },
    {
        path: '/customer',
        component: Layout,
        children: [
            {
                path: '',
                name: 'ListCustomer',
                component: require('@/views/Customer/ListCustomer').default,
                meta: {title: 'Khách Hàng', icon: 'form'},
            },
            {
                hidden: true,
                path: 'create',
                name: 'CreateCustomer',
                component: require('@/views/Customer/CreateCustomer').default,
                meta: {title: 'Create Customer', icon: 'form'},
            },
            {
                hidden: true,
                path: '/detail/:id',
                name: 'History',
                component: require('@/views/Customer/History').default,
                meta: {title: 'History'},
            },
        ],
    },
    {
        path: '/admin',
        component: Layout,
        children: [
            {
                path: '',
                name: 'ListAdmin',
                component: require('@/views/User/ListUser').default,
                meta: {title: 'Admin', icon: 'form'},
            },
            {
                hidden: true,
                path: '/create',
                name: 'CreateAdmin',
                component: require('@/views/User/CreateUser').default,
                meta: {title: 'Create Admin', icon: 'form'},
            },
        ],
    },


];

export default new Router({
    // mode: 'history', // Require service support
    scrollBehavior: () => ({y: 0}),
    routes: constantRouterMap,
});

export const asyncRouterMap = [];
