import { getComponentByName, moveElementInArray } from '~/utils/helpers.js'
import router from '../../../router'

let prepareDataBeforeSave = function(data, getters) {
    if(!data) return []
    return data.map((object, key) => ({
        ...object,
        subItems: prepareDataBeforeSave( getters.items(object.uniqueId), getters ),
        order: (key+1)
    }))
}

let prepareFilterData = (contentTypes, content) => {
    let filter = new Object()
    contentTypes.forEach(element => {
        if(element == 'pages')
            filter[element] = content.pages
        else
            filter[element] = []
    });
    return filter
}

const getDefaultState = () => {
    return {
        userId: 1,
        id: null,

        data: {
            title: '',
            layout: 'horizontal',
            location: null,
            contentType: [],
            content: {
            },
            filterMode: 'all'
        },
        blockItems: {},
        rootItemsList: [],
        removedItemsList: [],

        tagItems: [],
        widgetAreas: []
    }
}

const state = getDefaultState()

const getters = {
    editorMode(state) {
        return (state.id) ? 'edit' : 'create'
    },
    rootItems(state) {
        return state.rootItemsList.map( id => state.blockItems[id] )
    },
    removedItems(state) {
        return state.removedItemsList
    },
    itemType: (state, getters) => (uniqueId) => {
        if(!uniqueId) return []
        let component = state.blockItems[uniqueId]
        if(component.templateBlockId)
            return 'template'
        else
            return 'normal'
    },
    items: (state, getters) => (parentId) => {
        if(!parentId) return []
        let parent = state.blockItems[parentId]
        let components = parent.components.map( componentId => state.blockItems[componentId] )
        return components
    },
    item: (state, getters) => (uniqueId) => {
        if(!uniqueId) return []
        let component = state.blockItems[uniqueId]
        return component
    },
    itemBlockType: (state, getters) => (uniqueId) => {
        if(!uniqueId) return []
        let component = state.blockItems[uniqueId]
        return component && component.type
    },
    itemSettings: (state, getters) => (uniqueId) => {
        if(!uniqueId) return []
        let component = state.blockItems[uniqueId]
        return component && component.settings
    },
    itemContent: (state, getters) => (uniqueId) => {
        if(!uniqueId) return []
        let component = state.blockItems[uniqueId]
        return component && component.content
    }
}

const actions = {
    resetState ({ commit }) {
        commit('RESET_STATE')
    },
    setId({ commit }, payload) {
        commit('SET_ID', payload)
    },
    setWidgetData({ commit }, payload) {
        commit('SET_WIDGET_DATA', payload)
    },
    addItem({ commit }, payload) {
        commit('ADD_ITEM', payload)
    },
    removeItem({ commit, state }, payload) {
        let { id, parentId } = payload

        if(!parentId) {
            let parent = _.find(state.blockItems, {components: [id]})
            parentId = parent && parent.uniqueId
        }

        let recursiveRemove = (id, parentId, level=0) => {
            let item = state.blockItems[id]

            item.components.length && _.forEach(item.components, (nestedId) => {
                recursiveRemove(nestedId, id, level+1)
            })

            _.defer(() => {
                commit('REMOVE_ITEM', {id, parentId})
            })
        }

        recursiveRemove(id, parentId)
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
    moveItemUp({ commit }, payload) {
        commit('MOVE_ITEM', {...payload, offset: -1})
    },
    moveItemDown({ commit }, payload) {
        commit('MOVE_ITEM', {...payload, offset: 1})
    },
    fetchWidgetAreas({ commit }, payload) {
        return axios.get(route('api.design.widgets.areas'))
            .then(({data: responseData}) => {
                commit('SET_AREAS_DATA', responseData.data)
            })
            .catch((error) => {
                console.error('API error', error)
            })
    },
    addTemplateItem({ commit, state }, payload) {
        let { id, parentId } = payload
        return axios.get(route('api.design.templates.show', id))
            .then(({data: responseData}) => {
                let replacedIds = {}
                _.forEach(_.sortBy(responseData.data, [( o ) => { return o.parent_id || 0},'order']), (object) => {
                    let comp = getComponentByName(object.type)
                    if (!comp) {
                        console.error('Wrong component name')
                        return false
                    }

                    let id = +(state.userId+Date.now()+(+_.uniqueId()))
                    replacedIds[object.unique_id] = id

                    commit('ADD_ITEM', {
                        type: object.type,
                        id: id,
                        content: object.content ? object.content : undefined,
                        settings: object.settings,
                        parentId: replacedIds[object.parent_id] ? replacedIds[object.parent_id] : parentId ? parentId : null
                    })
                })

            })
            .catch((error) => {
                console.error('API error', error)
            })
    },
    saveAsTemplate({ commit, getters, state }, payload) {
        let { form, id } = payload

        let data = prepareDataBeforeSave( id ? [ getters.item(id) ] : getters.rootItems, getters )
        let formData = {
            ...form,
            blocksData: data
        }

        return axios.post(route('api.design.templates.store'), formData)
            .then(({data: responseData}) => {
                // console.log('SUCCESS', responseData.data)
            })
            .catch((error) => {
                console.error('API error', error)
            })
    },
    saveAll({ commit, getters, state }) {
        let formData = {
            id: state.id,
            ...state.data,
            filterData: prepareFilterData(state.data.contentType, state.data.content),
            widgetsData: prepareDataBeforeSave(getters.rootItems, getters),
            removedItems: getters.removedItems,
        }

        return axios.post(route('api.design.widgets.store'), formData)
            .then(({data: responseData}) => {
                if(getters.editorMode == 'create') {
                    router.push({ name: 'widgets.edit', params: {id: responseData.data.id} })
                }
            })
            .catch((error) => {
                console.error('API error', error)
            })
    },
    fetch({ commit, getters, state }, payload) {
        let { id } = payload
        commit('SET_ID', id)
        commit('SET_USER_ID', Vue.prototype.auth.user.id)
        let apiUrl = route('api.design.widgets.show', id)

        axios.get(apiUrl)
            .then(({data: responseData}) => {
                let replacedIds = {}

                commit('SET_AREAS_DATA', responseData.areas)
                commit('SET_WIDGET_DATA', _(responseData.data)
                    .pick(['title', 'location', 'layout', 'filter_mode'])
                    .mapKeys((v, k) => _.camelCase(k))
                    .value()
                )

                commit('SET_WIDGET_FILTER_DATA', responseData.data)

                _.forEach(_.sortBy(responseData.data.widgets, [( o ) => { return o.parent_id || 0 }, 'order']), function(object) {
                    let comp = getComponentByName(object.type)
                    if(!comp) {
                        console.error('Wrong component name')
                        return false
                    }
                    let id = Number(object.unique_id)

                    commit('ADD_ITEM', {
                        type: object.type,
                        id: id,
                        content: object.content ? object.content : undefined,
                        settings: object.settings,
                        parentId: replacedIds[object.parent_id] ? replacedIds[object.parent_id] : object.parent_id
                    })
                })
            })
            .catch((error) => {
                console.error('API error', error)
            })
    }
}

const mutations = {
    'RESET_STATE'(state) {
        Object.assign(state, getDefaultState())
    },
    'SET_ID'(state, payload) {
        state.id = payload || null
    },
    'SET_USER_ID'(state, payload) {
        state.userId = payload
    },
    'SET_WIDGET_DATA'(state, payload) {
        state.data = {
            ...state.data,
            ...payload
        }
    },
    'SET_WIDGET_FILTER_DATA'(state, payload) {
        let { filter_data } = payload
        for (var obj in filter_data) {
            state.data.contentType.push(obj)
        }
        state.data.content = filter_data
    },
    'SET_AREAS_DATA'(state, payload) {
        state.widgetAreas = payload
    },
    'ADD_ITEM'(state, payload) {
        let { type, content, settings, parentId, id } = payload
        let uniqueId = id || +(state.userId+Date.now()+(+_.uniqueId()))

        let newItem = {
            type: type,
            uniqueId: uniqueId,
            settings: settings,
            content: content,
            components: []
        }

        Vue.set(state.blockItems, uniqueId, newItem)

        if (parentId) {
            let parent = state.blockItems[parentId]
            parent.components.push(uniqueId)
        } else {
            state.rootItemsList.push(uniqueId)
        }
    },
    'REMOVE_ITEM'(state, payload) {
        let { id, parentId } = payload
        state.removedItemsList.push(id)

        if (parentId) {
            let parent = state.blockItems[parentId]
            let index = parent.components.indexOf(id)
            if (index+1) {
                parent.components.splice(index, 1)
                Vue.delete(state.blockItems, id)
            }
        } else {
            let index = state.rootItemsList.indexOf(id)
            state.rootItemsList.splice(index, 1)
            Vue.delete(state.blockItems, id)
        }
    },
    'UPDATE_ITEM'(state, payload) {
        let { data, uniqueId } = payload

        state.blockItems[uniqueId] = {
            ...state.blockItems[uniqueId],
            ...data
        }
    },
    'UPDATE_ITEM_SETTINGS'(state, payload) {
        let { settings, uniqueId } = payload

        let component = state.blockItems[uniqueId]
        component.settings = settings
    },
    'UPDATE_ITEM_CONTENT'(state, payload) {
        let { content, uniqueId } = payload

        let component = state.blockItems[uniqueId]
        component.content = content
    },
    'UPDATE_ITEMS_LIST'(state, payload) {
        let { list, id } = payload

        if (id) {
            let item = state.blockItems[id]
            item.components = list
        } else {
            state.rootItemsList = list
        }
    },
    'MOVE_ITEM'(state, payload) {
        let { id, parentId, offset=1 } = payload

        if (!parentId) {
            let parent = _.find(state.blockItems, {components: [id]})
            parentId = parent && parent.uniqueId
        }

        if (parentId) {
            let parent = state.blockItems[parentId]
            if (parent.components.indexOf(id)+1) {
                let newList = moveElementInArray(parent.components, id, offset)
                parent.components = newList
            }
        } else {
            if (state.rootItemsList.indexOf(id)+1) {
                let newList = moveElementInArray(state.rootItemsList, id, offset)
                state.rootItemsList = newList
            }
        }
    },
    'SET_TAGS_DATA'(state, payload) {
        let { data } = payload
        state.tagItems = data
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
