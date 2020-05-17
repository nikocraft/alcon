<template>
    <div id="app" class="wrapper">
        <header class="main-header">
            <nav class="navbar">
                <div class="container" style="height: 100%;">
                    <div style="display: flex; flex-direction: row; height: 100%">
                        <div style="flex: 0.1; display: flex; padding: 15px; align-items: center; font-size: 18px;"><div class="laraone-logo"><img src="/images/logo.png" class="img-responsive"></div></div>
                        <div class="admin-menu" style="flex: 1;">
                            <template v-for="menuItem in adminMenu">
                                <div v-if="menuItem.visible && (!menuItem.permission || auth.hasPermission(menuItem.permission))" :key="menuItem.id" @mouseover="showDropDown(menuItem)" @mouseout="hideDropDown" :class="{'menu-item': true, 'dropdown dropdown-toggle': menuItem.children && menuItem.children.length}">
                                    <router-link
                                        v-if="menuItem.route"
                                        :to="{
                                            name: menuItem.route,
                                            params: menuItem.params ? menuItem.params : {}
                                        }"
                                    >{{ menuItem.name }}</router-link>
                                    <a v-else>{{ menuItem.name }}</a>
                                    <div class="dropdown-menu" v-show="menuItem.children && menuItem.children.length && (showDropDownMenu && menuItem.id == currentMenuItemId)">
                                        <template v-for="childItem in menuItem.children">
                                            <div v-if="childItem.visible && (!childItem.permission || auth.hasPermission(childItem.permission))" @click="clickedItem" class="dropdown-menu-item" :key="childItem.id">
                                                <router-link
                                                    v-if="childItem.route"
                                                    :to="{
                                                        name: childItem.route,
                                                        params: menuItem.params ? menuItem.params : {}
                                                    }"
                                                >{{ childItem.name }}</router-link>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div style="flex: 0.2; display: flex; padding: 15px; align-items: center; font-size: 18px;">
                            <div class="notifications-area">
                                <div @mouseover="showUserDropDownMenu = true" @mouseout="showUserDropDownMenu = false" class="user-item menu-item dropdown dropdown-toggle">
                                    <a v-if="auth.user.firstname && auth.user.lastname" class="user-name">{{ auth.user.firstname }} {{ auth.user.lastname }}</a>
                                    <a v-else class="user-name">{{ auth.user.username }}</a>
                                    <div v-show="showUserDropDownMenu" class="dropdown-menu">
                                        <div class="dropdown-menu-item">
                                            <a @click="logout">Sign Out</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </header>
        <div class="content-wrapper">
            <div class="container container-bkg">
                <section class="content">
                    <div class="row">
                        <router-view/>
                    </div>
                </section>
            </div>
        </div>
        <footer class="main-footer">
            <div class="container">
                <div style="text-align: center;"><strong>powered by <a href="https://laraone.com">LaraOne</a></strong></div>
            </div>
        </footer>
        <media-library/>
    </div>
</template>

<script>

const adminMenu = window.Laravel.menuTree || []

export default {
    el: '#app',
    data() {
        return {
            adminMenu,
            showUserDropDownMenu: false,
            showDropDownMenu: false,
            currentMenuItemId: null,
            isFirefox: navigator.userAgent.toLowerCase().indexOf('firefox') > -1
        }
    },
    methods: {
        mouseOverUser () {
            this.showUserMenu = true
        },
        mouseOutUser () {
            this.showUserMenu = false
        },
        clickedItem () {
            this.showDropDownMenu = false
        },
        showDropDown (menuItem) {
            this.showDropDownMenu = true
            this.currentMenuItemId = menuItem.id
        },
        hideDropDown () {
            this.showDropDownMenu = false
        },
        logout () {
            axios.post(route('logout'))
                .then((response) => {
                    window.location = route('login')
                })
        }
    }
}
</script>
