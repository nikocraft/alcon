
import Vue from 'vue'
import Router from 'vue-router'

import AbstractRoute from '../layouts/AbstractRoute'

import Dashboard from '../views/dashboard/Index'
import Index from '../views/Index'
import WebsiteSettings from '../views/settings/Website'
import ContentSettings from '../views/settings/Content'
import AdminSettings from '../views/settings/Admin'
import MailSettings from '../views/settings/Mail'
import AuthPageSettings from '../views/settings/Authpage'
import MembersSettings from '../views/settings/Members'
import CommentsSettings from '../views/settings/Comments'
import UrlsSettings from '../views/settings/Urls'

import ContentTypesList from '../views/contentTypes/Index'
import ContentTypeEditor from '../views/contentTypes/Editor'

import ContentList from '../views/content/Index'
import ContentEditor from '../views/content/Editor'
import TaxonomyEditor from '../views/taxonomy/Editor'

import MediaImagesEditor from '../views/media/images/Index'

import Users from '../views/users/users/Index'
import Roles from '../views/users/roles/Index'

import Comments from '../views/comments/Index'

import ThemeSettings from '../views/design/settings/Index'
import Themes from '../views/design/themes/Index'
import MenusList from '../views/design/menus/Index'
import MenusEditor from '../views/design/menus/Editor'
import WidgetsList from '../views/design/widgets/Index'
import WidgetsEditor from '../views/design/widgets/Editor'

Vue.use(Router)

let contentPropsFn = (route) => {
    return {...route.params, queryString: route.query}
}

const router = new Router({
    mode: 'history',
    base: '/admin',
    linkExactActiveClass: 'active',
    routes: [
        { 
            path: '/',
            name: 'dashboard',
            component: Dashboard
        },
        {
            path: '/dashboard',
            component: Dashboard
        },
        { path: '/content/:contentTypeSlug', name: 'content.root', component: {template: '<router-view />'}, meta: { permission: 'manage-content'},
            children: [
                {
                    path: '',
                    name: 'content.list',
                    component: ContentList,
                    props: contentPropsFn
                },
                {
                    path: 'create',
                    name: 'content.create',
                    component: ContentEditor,
                    props: true
                },
                {
                    path: 'edit/:id',
                    name: 'content.edit',
                    component: ContentEditor,
                    props: true
                },
                {
                    path: 'taxonomy/:taxonomyId',
                    name: 'taxonomy',
                    component: TaxonomyEditor,
                    props: true,
                }
            ]
        },
        { path: '/settings', component: AbstractRoute, meta: { permission: 'access-settings' },
            children: [
                {
                    path: 'website',
                    name: 'settings.website',
                    component: WebsiteSettings,
                },
                {
                    path: 'content',
                    name: 'settings.content',
                    component: ContentSettings,
                },
                {
                    path: 'admin',
                    name: 'settings.admin',
                    component: AdminSettings,
                },
                {
                    path: 'mail',
                    name: 'settings.mail',
                    component: MailSettings,
                },
                {
                    path: 'auth',
                    name: 'settings.auth',
                    component: AuthPageSettings,
                },
                {
                    path: 'members',
                    name: 'settings.members',
                    component: MembersSettings,
                },
                {
                    path: 'comments',
                    name: 'settings.comments',
                    component: CommentsSettings,
                },
                {
                    path: 'content-types',
                    component: AbstractRoute,
                    meta: { permission: 'all-permissions' },
                    children: [
                        { path: '', name: 'content-types.list', component: ContentTypesList},
                        { path: 'create', name: 'content-types.create', component: ContentTypeEditor },
                        { path: 'edit/:id', name: 'content-types.edit', component: ContentTypeEditor, props: true }
                    ]
                },
                { name: 'settings.urls', path: 'urls', component: UrlsSettings },
            ]
        },
        {
            path: '/media/images',
            name: 'media.images',
            component: MediaImagesEditor,
            meta: { permission: 'access-media' }
        },
        { path: '/acl', component: AbstractRoute, meta: { permission: 'access-acl' },
            children: [
                {
                    path: 'users',
                    name: 'acl.users',
                    component: Users,
                    meta: { permission: 'manage-users' }
                },
                {
                    path: 'roles',
                    name: 'acl.roles',
                    component: Roles,
                    meta: { permission: 'manage-roles' }
                }
            ]
        },
        {
            path: '/comments',
            name: 'comments',
            component: Comments,
            meta: { permission: 'manage-comments' }
        },
        { path: '/design', component: AbstractRoute, meta: { permission: 'access-design' },
            children: [
                {
                    name: 'design.customize',
                    path: 'customize',
                    component: ThemeSettings
                },
                {
                    name: 'design.themes',
                    path: 'themes',
                    component: Themes
                },
                {
                    path: 'menus',
                    component: AbstractRoute,
                    children: [
                        { path: '', name: 'menus.list', component: MenusList},
                        { path: 'create', name: 'menus.create', component: MenusEditor },
                        { path: 'edit/:id', name: 'menus.edit', component: MenusEditor, props: true }
                    ]
                },
                {
                    path: 'widgets',
                    component: AbstractRoute,
                    children: [
                        { path: '', name: 'widgets.list', component: WidgetsList},
                        { path: 'create', name: 'widgets.create', component: WidgetsEditor },
                        { path: 'edit/:id', name: 'widgets.edit', component: WidgetsEditor, props: true }
                    ]
                }
            ]
        }
    ]
})

router.beforeEach((to, from, next) => {
    let permission = null
    if(to.matched.some( record => (record.meta.permission)?permission = record.meta.permission:false )) {
        if( !router.app.auth.hasPermission(permission) ) {
            router.app.$notify({
                title: 'Error',
                message: 'Access denied',
                type: 'error'
            })
            next(false)
        } else {
            next()
        }
    } else {
        next()
    }
})

export default router
