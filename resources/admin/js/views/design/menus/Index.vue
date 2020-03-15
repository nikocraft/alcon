<template>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Menus</h3>
                <div class="header-tools">
                    <router-link to="create">
                        <button class="btn btn-block btn-primary btn-header">Create</button>
                    </router-link>
                </div>
            </div>
            <div class="box-body">
                <div class="menus">
                    <!-- HEADER -->
                    <div class="row-header">
                        <div class="name-column">Name</div>
                        <div class="actions-column">Actions</div>
                    </div>
                    <div class="list">
                        <div v-for="(menu, index) in menusList" :key="menu.id" class="menu">
                            <div class="name-column">
                                <router-link :to="{ name: 'menus.edit', params: { id: menu.id }}" class="menu-name">
                                    {{ menu.name }}
                                </router-link>
                            </div>
                            <div class="actions-column" style="font-size: 30px;">
                                <button @click="$router.push({ name: 'menus.edit', params: { id: menu.id }})" class="btn btn-xs btn-primary action-btn" type="button">
                                    <i class="lo-icon lo-icon-edit"></i>
                                </button>

                                <button @click="deleteMenu(index, menu.id)" class="btn btn-xs btn-danger action-btn" type="button">
                                    <i class="lo-icon lo-icon-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row-header">
                        <div class="name-column">Name</div>
                        <div class="actions-column">Actions</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                menusList: [],
            }
        },
        computed: {
            //
        },
        methods: {
            editMenu(url) {
                window.location.href = url
            },
            deleteMenu(index, id) {
                this.$confirm('This action will delete this menu, are you sure you want to continue?', 'Warning', {
                    confirmButtonText: 'Delete',
                    showCancelButton: false,
                    confirmButtonClass: 'el-button--danger',
                    type: 'warning'
                }).then(() => {
                    axios.delete(route('api.design.menus.destroy', id))
                    .then((response) => {
                        this.menusList.splice(index, 1);
                    })
                    .catch((error) => {
                        console.log(error)
                    })
                })
            },
            addMenu(newMenu) {
                let menu = {
                    uniqueId: 'menu-' + this.menusList.length,
                    id: newMenu.id,
                    name: newMenu.name,
                    description: newMenu.description,
                    location: newMenu.location
                }

                this.menusList.push(menu)
            },
            getMenus() {
                axios.get(route('api.design.menus.index'))
                .then(({data: responseData}) => {
                    this.menusList = []

                    for(var i = 0; i < responseData.data.length; i++) {
                        this.addMenu(responseData.data[i])
                    }
                })
                .catch((error) => {
                    console.log(error)
                })
            },
        },
        mounted() {
            this.getMenus()
        }
    }
</script>

<style lang="scss" scoped>
    .menu {
        display: flex;
        flex-direction: row;
    }

    .menu:nth-child(odd) {
        background-color: rgba(0,0,0, 0.04);
    }

    .menu:nth-child(even) {
        background-color: rgba(0,0,0, 0.085);
    }

    .menu:hover {
        background-color: rgba(0,0,0, 0.15);
    }

    .name-column {
        flex: 5 !important;
        display: flex;
        align-items: center;
        padding: 1px 10px;
    }

    .actions-column {
        flex: 0.5 !important;
        display: flex;
        align-items: center;
        padding: 1px 10px;
    }

    .menu .name-column, .menu .actions-column {
        height: 100px;
    }
</style>
