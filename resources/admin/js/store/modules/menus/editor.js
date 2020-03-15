
import router from '../../../router'

const swapKeys = (items = [], keyForReplaceMap = {}) => {
    return items.map((o) => {
            return _.mapKeys(o, (value, key) => {
                return keyForReplaceMap[key] || key;
            })
        })
}

const levelAndSort = (data, startingLevel = 1) => {
    // indexes
    var indexed = {};        // the original values
    var nodeIndex = {};      // tree nodes
    var i;
    for (i = 0; i < data.length; i++) {
        var id = data[i].id;
        var node = {
            id: id,
            level: startingLevel,
            children: [],
            sorted: false
        };
        indexed[id] = data[i];
        nodeIndex[id] = node;
    }

    // populate tree
    for (i = 0; i < data.length; i++) {
        var node = nodeIndex[data[i].id];
        var pNode = node;
        var j;
        var nextId = indexed[pNode.id].parent_id;
        for (j = 0; nextId in nodeIndex; j++) {
            pNode = nodeIndex[nextId];
            if (j == 0) {
                pNode.children.push(node.id);
            }
            node.level++;
            nextId = indexed[pNode.id].parent_id;
        }
    }

    // extract nodes and sort-by-level
    var nodes = [];
    for (var key in nodeIndex) {
        nodes.push(nodeIndex[key]);
    }
    nodes.sort(function(a, b) {
        return a.level - b.level;
    });

    // refine the sort: group-by-siblings
    var retval = [];
    for (i = 0; i < nodes.length; i++) {
        var node = nodes[i];
        var parentId = indexed[node.id].parent_id;
        if (parentId in indexed) {
            var pNode = nodeIndex[parentId];
            var j;
            for (j = 0; j < pNode.children.length; j++) {
                var child = nodeIndex[pNode.children[j]];
                if (!child.sorted) {
                    indexed[child.id].level = child.level;
                    retval.push(indexed[child.id]);
                    child.sorted = true;
                }
            }
        }
        else if (!node.sorted) {
            indexed[node.id].level = node.level;
            retval.push(indexed[node.id]);
            node.sorted = true;
        }
    }
    return retval;
}



const getDefaultState = () => {
    return {
        userId: 1,
        editorMode: 'create',
        menuData: {
            id: 0,
            name: '',
            location: 'primary'
        },
        menuItems: {},
        rootItemsList: [],
        removedItemsList: [],
    }
}

const state = getDefaultState()

const getters = {
    editorMode(state) {
        return state.editorMode
    },
    menuData(state) {
        return state.menuData
    },
    rootItems(state) {
        return state.rootItemsList.map( id => state.menuItems[id] )
    },
    removedItems(state) {
        return state.removedItemsList
    },
    itemType: (state, getters) => (uniqueId) => {
        if (!uniqueId) return []
        let component = state.menuItems[uniqueId]
        if(component.templateBlockId)
            return 'template'
        else
            return 'normal'
    },
    items: (state, getters) => (parentId) => {
        // if (!parentId) return []
        if (!parentId) return getters.rootItems
        let parent = state.menuItems[parentId]
        let components = parent.components.map( componentId => state.menuItems[componentId] )
        return components
    },
    nestedItems: (state, getters) => (parentId) => {
        let parent, components, output
        if (!parentId) {
            components = getters.rootItems
        } else {
            parent = state.menuItems[parentId]
            components = parent.components.map( componentId => state.menuItems[componentId] )
        }
        output = _.map(components, (o) => {
            return {
                ..._.omit(o, ['components']),
                children: getters.nestedItems(o.uniqueId)
            }
        })

        return output
    },
    item: (state, getters) => (uniqueId) => {
        if (!uniqueId) return []
        let component = state.menuItems[uniqueId]
        return component
    },
    itemBlockType: (state, getters) => (uniqueId) => {
        if (!uniqueId) return []
        let component = state.menuItems[uniqueId]
        return component && component.type
    },
    itemSettings: (state, getters) => (uniqueId) => {
        if (!uniqueId) return []
        let component = state.menuItems[uniqueId]
        return component && component.settings
    },
    itemContent: (state, getters) => (uniqueId) => {
        if (!uniqueId) return []
        let component = state.menuItems[uniqueId]
        return component && component.content
    }
}

const actions = {
    resetState ({ commit }) {
        commit('RESET_STATE')
    },
    setEditorMode({ commit }, payload) {
        commit('SET_EDITOR_MODE', payload)
    },
    setMenuData({ commit }, payload) {
        commit('SET_MENU_DATA', payload)
    },
    addItem({ commit }, payload) {
        commit('ADD_ITEM', payload)
    },
    addCustomItem({ commit }, payload) {
        commit('ADD_CUSTOM_ITEM', payload)
    },
    removeItem({ commit }, payload) {
        commit('REMOVE_ITEM', payload)
    },
    updateItemSettings({ commit }, payload) {
        commit('UPDATE_ITEM_SETTINGS', payload)
    },
    updateItemContent({ commit }, payload) {
        commit('UPDATE_ITEM_CONTENT', payload)
    },
    updateItemsList({ commit }, payload) {
        commit('UPDATE_ITEMS_LIST', payload)
    },
    updateFullList({ commit }, payload) {
        commit('UPDATE_FULL_LIST', payload)
    },
    saveAll({ getters, state }) {
        let prepareDataBeforeSave = function(data) {
            if(!data) return []
            return data.map((object, key) => {
                return {
                    type: object.type,
                    uniqueId: object.uniqueId,
                    content: object.content,
                    subItems: prepareDataBeforeSave( getters.items(object.uniqueId) ),
                    order: (key+1)
                }
            })
        }

        let formData = {
            ...state.menuData,
            status: 1,
            menuItems: prepareDataBeforeSave(getters.rootItems),
            removedItems: getters.removedItems
        }

        return axios.post(route('api.design.menus.store'), formData)
            .then(({data: responseData}) => {
                let {id} = responseData.data || {}
                router.push({ name: 'menus.edit', params: {id} })
            })
            .catch((error) => {
                console.error('API error', error)
            })
    },
    fetchMenu({ commit, getters, state }, payload) {
        let {id} = payload
        let apiUrl = route('api.design.menus.show', {id})

        axios.get(apiUrl)
            .then(({data: responseData}) => {
                commit('SET_MENU_DATA', _.omit(responseData.data, ['items']))
                let preparedData = levelAndSort(swapKeys(responseData.data.items, {unique_id: 'id'}), 1)
                _.forEach(_.sortBy(preparedData, ['level','order']), function(object) {
                    switch(object.type) {
                        case 'PageItem':
                            commit('ADD_ITEM', {
                                type: object.type,
                                id: Number(object.id),
                                content: {
                                    title: object.title,
                                    url: object.url
                                },
                                parentId: object.parent_id
                            })
                            break;
                        case 'CustomUrlItem':
                            commit('ADD_CUSTOM_ITEM', {
                                type: object.type,
                                id: Number(object.id),
                                content: {
                                    title: object.title,
                                    url: object.url
                                },
                                parentId: object.parent_id
                            })
                            break;
                        default:

                    }
                })
            })
            .catch((error) => {
                console.error('API error', error)
            })
    }
}

const mutations = {
    'RESET_STATE'(state, payload) {
        Object.assign(state, getDefaultState())
    },
    'SET_EDITOR_MODE'(state, payload) {
        state.editorMode = payload
    },
    'SET_MENU_DATA'(state, payload) {
        state.menuData = {
            ...state.menuData,
            ...payload
        }
    },
    'ADD_ITEM'(state, payload) {
        let { type, url, content, settingsConfig, settings, parentId, id} = payload
        let uniqueId = id || +(state.userId+Date.now()+(+_.uniqueId()))

        let newItem = {
            content: content,
            type: type,
            uniqueId: uniqueId,
            settingsConfig: settingsConfig,
            settings: settings,
            components: []
        }

        Vue.set(state.menuItems, uniqueId, newItem)
        //
        if(parentId) {
            let parent = state.menuItems[parentId]
            parent.components.push(uniqueId)
        } else {
            state.rootItemsList.push(uniqueId)
        }
    },
    'ADD_CUSTOM_ITEM'(state, payload) {
        let { type, content, settingsConfig, settings, parentId, id} = payload
        let uniqueId = id || +(state.userId+Date.now()+(+_.uniqueId()))

        let newItem = {
            content: content,
            type: type,
            uniqueId: uniqueId,
            settingsConfig: settingsConfig,
            settings: settings,
            components: []
        }

        Vue.set(state.menuItems, uniqueId, newItem)
        //
        if(parentId) {
            let parent = state.menuItems[parentId]
            parent.components.push(uniqueId)
        } else {
            state.rootItemsList.push(uniqueId)
        }
    },
    'REMOVE_ITEM'(state, payload) {
        let { id, parentId } = payload
        state.removedItemsList.push(id)

        if(!parentId) {
            let parent = _.find(state.menuItems, {components: [id]})
            parentId = parent && parent.uniqueId
        }

        let recursiveRemove = (id, parentId) => {
            let item = state.menuItems[id]
            item.components.length && _.forEach(item.components, (nestedId) => {
                recursiveRemove(nestedId, id)
            })

            if(parentId) {
                let parent = state.menuItems[parentId]
                var index = parent.components.indexOf(id)
                if(index+1) {
                    parent.components.splice(index, 1)
                    Vue.delete(state.menuItems, id)
                }
            } else {
                var index = state.rootItemsList.indexOf(id)
                state.rootItemsList.splice(index, 1)
                Vue.delete(state.menuItems, id)
            }
        }

        recursiveRemove(id, parentId)
    },
    'UPDATE_ITEM'(state, payload) {
        let { data, uniqueId } = payload

        state.menuItems[uniqueId] = {
            ...state.menuItems[uniqueId],
            ...data
        }
    },
    'UPDATE_ITEM_SETTINGS'(state, payload) {
        let { settings, uniqueId } = payload

        let component = state.menuItems[uniqueId]
        component.settings = settings
    },
    'UPDATE_ITEM_LABEL'(state, payload) {
        let { title, uniqueId } = payload

        let component = state.menuItems[uniqueId]
        component.title = title
    },
    'UPDATE_ITEM_CONTENT'(state, payload) {
        let { content, uniqueId } = payload

        let component = state.menuItems[uniqueId]
        component.content = content
    },
    'UPDATE_ITEMS_LIST'(state, payload) {
        let { list, id } = payload

        if (id) {
            let item = state.menuItems[id]
            item.components = list
        } else {
            state.rootItemsList = list
        }
    },
    'UPDATE_FULL_LIST'(state, payload) {
        let { data, id } = payload

        state.rootItemsList = _.map( _.filter(data, (o) => !o.parentId), 'id' )

        _.forEach(data, (o) => {
            let currentItem = state.menuItems[o.id]
            currentItem.components = o.children
        })
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
