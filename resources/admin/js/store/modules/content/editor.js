import { getComponentByName, moveElementInArray, stateMerge } from '~/utils/helpers.js'
import { format } from 'date-fns'
import router from '../../../router'
import { __makeTemplateObject } from 'tslib';

let isEqual = (first, second) => JSON.stringify(first) === JSON.stringify(second)

let prepareDataBeforeSave = function(data, getters) {
    if(!data) return []
    return data.map((object, key) => ({
        ...object,
        subItems: prepareDataBeforeSave( getters.items(object.uniqueId), getters ),
        order: (key+1)
    }))
}

let sortWithHierarchy = function(items = [], options = {}) {
    let {parentKey = 'parent', idKey = 'id'} = options
    let data = items,
        tree = function (data, root = null) {
          var r = [], o = {}
          data.forEach(function (a) {
            let id = a[idKey],
                parent = a[parentKey]

            o[id] = { data: a, children: o[id] && o[id].children }
            if (parent === root) {
              r.push(o[id])
            } else {
              o[parent] = o[parent] || {}
              o[parent].children = o[parent].children || []
              o[parent].children.push(o[id])
            }
          })
          return r
        }(data, undefined),
        sorted = tree.reduce(function traverse(level) {
          return function (r, a) {
            a.data.level = level
            return r.concat(a.data, (a.children || []).reduce(traverse(level + 1), []))
          }
        }(0), [])
    return sorted
}

const getDefaultState = () => {
    return {
        userId: 1,
        user: {},
        contentTypeId: null,
        id: null,

        data: {
            title: '',
            layout: 'default',
            author: {},
            featuredimage: {},
            slug: '',
            css: null,
            js: null,
            seo: {
                title: null,
                metaDescription: null,
                focusKeyword: null,
            },
            settings: {
                showTitle: false,
                showMetaData: false,
                showAuthorBio: false,
                showComments: false,
            },
            publishedAt: null,
            createdAt: null,
            updatedAt: null,
            publishAt: null,
            status: 1
        },
        terms: null,
        editorSettings: {
            wideLayout: null,
            showHeaders: false,
            showLabels: true,
            favoriteBlocks: "",
            autoSave: 0,
            showTaxonomies: true,
            showFeaturedImage: true,
            showContentDates: false,
            shortcutNotifications: true,
            editorNotifications: true,
        },
        allowedTaxonomies: null,
        defaultContentLayout: null,
        defaultContentSettings: {},

        blockItems: {},
        rootItemsList: [],
        removedItemsList: [],

        originals: {data: {}, featuredimage: {}}
    }
}

const state = getDefaultState()

const getters = {
    user(state) {
        return state.user
    },
    editorMode(state) {
        return state.id ? 'edit' : 'create'
    },
    taxonomiesObject(state) {
        let {terms, allowedTaxonomies} = state
        let preparedTerms = _(terms)
            .groupBy('taxonomy_id')
            .value()

        return _(allowedTaxonomies)
            .keyBy('id')
            .mapValues(o => {
                return o.settings['allowMultiple']
                    ? preparedTerms[o.id] || null
                    : (preparedTerms[o.id] && preparedTerms[o.id][0]) || null
            })
            .value()
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
    },
    favoriteBlocks(state) {
        let componentsString = (state.editorSettings && state.editorSettings.favoriteBlocks) || ''
        return componentsString.split(",")
    },
}

const actions = {
    resetState ({ commit }) {
        commit('RESET_STATE')
    },
    setRootData: ({ commit }, payload) => commit('SET_ROOT_DATA', payload),
    setContentType: ({ commit }, payload) => commit('SET_CONTENT_TYPE', payload),
    setContentId: ({ commit }, payload) => commit('SET_CONTENT_ID', payload),
    setContentData({ commit }, payload) {
        commit('SET_CONTENT_DATA', payload)
    },
    setFeaturedImage({ commit }, payload) {
        commit('SET_FEATURED_IMAGE', payload)
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

        return new Promise((resolve) => {
            _.defer(() => {
                resolve(true)
            })
        })
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
    addTemplateItem({ commit, state }, payload) {
        let { id, parentId } = payload
        return axios.get(route('api.design.templates.show', id))
            .then(({data: responseData}) => {
                let replacedIds = {}
                _.forEach(_.sortBy(responseData.data, [( o ) => { return o.parent_id || 0},'order']), (object) => {
                    let comp = getComponentByName(object.type)
                    if(!comp) {
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
    saveEditorSettings({ commit, dispatch, getters, state }, payload = {}) {
        let { contentTypeId } = state
        let { data } = payload
        let settings = {
            ...state.editorSettings,
            ...data
        }

        return axios.post(route('api.content.editor.settings'), { settings })
            .then(({data: responseData}) => {
                commit('SET_CONTENT_DATA', {
                    path: 'editorSettings',
                    data: responseData.data
                })
            })
            .catch((error) => {
                console.log(error)
            })
    },
    storeContent({ commit, getters, state }) {
        let formData = {
                title: state.data.title,
                layout: state.data.layout,
                seo: state.data.seo,
                js: state.data.js,
                css: state.data.css,
                status: state.data.status,
                settings: state.data.settings,
                // publishAt: state.data.publishAt,
                taxonomiesData: getters.taxonomiesObject,
                blocksData: prepareDataBeforeSave(getters.rootItems, getters),
                removedItems: getters.removedItems
            },
            { contentTypeId, id } = state

        return axios({ method: 'post', url: route('api.content.store', {contentTypeId}), data: formData })
            .then(({data: responseData}) => {
                let {data} = responseData
                id = data.id

                // let payload = {
                //     contentId: response.data.content.id,
                //     publishedAt: format(new Date(`${response.data.content.published_at} UTC`), 'yyyy-MM-dd HH:mm'),
                //     createdAt: format(new Date(`${response.data.content.created_at} UTC`), 'yyyy-MM-dd HH:mm'),
                //     updatedAt: format(new Date(`${response.data.content.updated_at} UTC`), 'yyyy-MM-dd HH:mm'),
                // }
                // commit('SET_CONTENT_DATA', payload)
                router.push({ name: 'content.edit', params: {contentTypeId, id} })
            })
            .catch((error) => {
                console.error('API error', error)
            })
    },
    updateContent({ commit, getters, state }) {
        let formData = {
                title: state.data.title,
                layout: state.data.layout,
                seo: state.data.seo,
                js: state.data.js,
                css: state.data.css,
                status: state.data.status,
                settings: state.data.settings,
                // publishAt: state.data.publishAt,
                taxonomiesData: getters.taxonomiesObject,
                blocksData: prepareDataBeforeSave(getters.rootItems, getters),
                removedItems: getters.removedItems
            },
            { contentTypeId, id } = state

        return axios({ method: 'patch', url: route('api.content.update', {contentTypeId, id}), data: formData })
            .then(({data: responseData}) => {
                let {data} = responseData
                id = data.id

                // let payload = {
                //     contentId: response.data.content.id,
                //     publishedAt: format(new Date(`${response.data.content.published_at} UTC`), 'yyyy-MM-dd HH:mm'),
                //     createdAt: format(new Date(`${response.data.content.created_at} UTC`), 'yyyy-MM-dd HH:mm'),
                //     updatedAt: format(new Date(`${response.data.content.updated_at} UTC`), 'yyyy-MM-dd HH:mm'),
                // }
                // commit('SET_CONTENT_DATA', payload)
                // router.push({ name: 'content.edit', params: {contentTypeId, id} })
            })
            .catch((error) => {
                console.error('API error', error)
            })
    },
    fetchContentData({ commit, dispatch, getters, state }, payload) {
        let { mode } = payload || {}
        let isDefault = mode && mode == 'default'
        let apiUrl = (state.id)
                ? route('api.content.show', {contentTypeId: state.contentTypeId, id: state.id})
                : route('api.content.editor.init-data', {contentTypeId: state.contentTypeId})

        commit('SET_CONTENT_DATA', {
            path: 'userId',
            data: Vue.prototype.auth.user.id
        })

        commit('SET_CONTENT_DATA', {
            path: 'user',
            data: Vue.prototype.auth.user
        })

        axios.get(apiUrl)
            .then(({data: responseData}) => {
                let replacedIds = {}
                if(responseData.data) {
                    commit('SET_CONTENT_DATA', {
                        path: 'data',
                        data: _(responseData.data)
                            .pick(['title', 'slug', 'author', 'css', 'js', 'settings', 'published_at', 'created_at', 'updated_at', 'status'])
                            .mapKeys((value, key) => _.camelCase(key))
                            .value()
                    })
                    commit('SET_CONTENT_LAYOUT', responseData.data.layout)
                    commit('SET_CONTENT_SETTINGS', responseData.data.settings)
                    commit('SET_CONTENT_SEO', responseData.data.seo)
                    commit('SET_FEATURED_IMAGE', responseData.data.featuredimage)
                    commit('SET_CONTENT_DATA', { path: 'terms', data: responseData.data.terms })
                } else {
                    commit('SET_CONTENT_SETTINGS', responseData.defaultContentSettings)
                }
                commit('SET_EDITOR_SETTINGS', { data: responseData.editorSettings })
                commit('SET_CONTENT_DATA', { path: 'defaultContentLayout', data: responseData.defaultContentLayout })
                commit('SET_CONTENT_DATA', { path: 'defaultContentSettings', data: responseData.defaultContentSettings })
                commit('SET_CONTENT_DATA', { path: 'allowedTaxonomies', data: responseData.contentTaxonomies })

                if(responseData.data) {
                    let blocks = _.sortBy(responseData.data.blocks, [( o ) => { return o.parent_id || 0 },'order'])
                    blocks = sortWithHierarchy(blocks, {
                        parentKey: 'parent_id',
                        idKey: 'unique_id'
                    })

                    _.forEach(blocks, function(object) {
                        let comp = getComponentByName(object.type)
                        if(!comp) {
                            console.error('Wrong component name')
                            return false
                        }

                        let id
                        let templateBlockId
                        if(isDefault) {
                            id = +(state.userId+Date.now()+(+_.uniqueId()))
                            replacedIds[object.unique_id] = id
                            templateBlockId = object.id
                        } else {
                            id = Number(object.unique_id)
                            templateBlockId = object.tblock_id
                        }

                        commit('ADD_ITEM', {
                            type: object.type,
                            id: id,
                            tId: templateBlockId,
                            content: object.content ? object.content : undefined,
                            settings: object.settings,
                            parentId: replacedIds[object.parent_id] ? replacedIds[object.parent_id] : object.parent_id
                        })
                    })
                }
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
    'SET_ROOT_DATA'(state, payload) {
        _.forEach(payload, (value, key) => {
            if(state.hasOwnProperty(key)) {
                state[key] = value
            }
        })
    },
    'SET_CONTENT_ID'(state, payload) {
        state.id = payload
    },
    'SET_CONTENT_TYPE'(state, payload) {
        state.contentTypeId = payload
    },
    'SET_CONTENT_DATA'(state, payload) {
        let {path, data} = payload
        if(isEqual(state[path], data)) return
        if(state.hasOwnProperty(path) && _.isPlainObject(data)) {
            stateMerge(state, data, path)
        } else if(state.hasOwnProperty(path) && data) {
            state[path] = data
        }
    },
    'SET_CONTENT_SEO'(state, payload) {
        if(payload || payload.length) {
            state.data.seo = {...state.data.seo, ...payload}
        }
    },
    'SET_CONTENT_SETTINGS'(state, payload) {
        state.data.settings = payload
    },
    'SET_CONTENT_LAYOUT'(state, payload) {
        state.data.layout = payload ? payload : 'default'
    },
    'SET_CONTENT_CSS'(state, payload) {
        state.data.css = payload
    },
    'SET_CONTENT_JS'(state, payload) {
        state.data.js = payload
    },
    'SET_EDITOR_SETTINGS'(state, payload) {
        let {data} = payload
        state.editorSettings = data
    },
    'SET_FEATURED_IMAGE'(state, payload) {
        state.data.featuredimage = payload
    },
    'ADD_ITEM'(state, payload) {
        let { type, content = null, settings, parentId, id, tId } = payload
        let uniqueId = id || +(state.userId+Date.now()+(+_.uniqueId()))
        let templateBlockId = tId || undefined

        let newItem = {
            type: type,
            uniqueId: uniqueId,
            templateBlockId: templateBlockId,
            settings: settings,
            content: content,
            components: []
        }

        Vue.set(state.blockItems, uniqueId, newItem)
        if(parentId) {
            let parent = state.blockItems[parentId]
            parent.components.push(uniqueId)
        } else {
            state.rootItemsList.push(uniqueId)
        }
    },
    'REMOVE_ITEM'(state, payload) {
        let { id, parentId } = payload
        state.removedItemsList.push(id)

        if(parentId) {
            let parent = state.blockItems[parentId]
            let index = parent.components.indexOf(id)
            if(index+1) {
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

        if(id) {
            let item = state.blockItems[id]
            item.components = list
        } else {
            state.rootItemsList = list
        }
    },
    'MOVE_ITEM'(state, payload) {
        let { id, parentId, offset=1 } = payload

        if(!parentId) {
            let parent = _.find(state.blockItems, {components: [id]})
            parentId = parent && parent.uniqueId
        }

        if(parentId) {
            let parent = state.blockItems[parentId]
            if(parent.components.indexOf(id)+1) {
                let newList = moveElementInArray(parent.components, id, offset)
                parent.components = newList
            }
        } else {
            if(state.rootItemsList.indexOf(id)+1) {
                let newList = moveElementInArray(state.rootItemsList, id, offset)
                state.rootItemsList = newList
            }
        }
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
