const state = {
    id: null,
    data: null,
    loading: false,

    taxonomies: {
        entities: {},
        data: [],
        removedData: []
    },
}

const getters = {
    taxonomies (state) {
        return state.taxonomies.data.map( id => state.taxonomies.entities[id] )
    },
    removedTaxonomies (state) {
        return state.taxonomies.removedData
    },
    taxonomy: (state, getters) => (uniqueId) => {
        if (!uniqueId) return []
        let t = state.taxonomies.entities[uniqueId]
        return t
    },
    taxonomyData: (state, getters) => (uniqueId) => {
        if (!uniqueId) return []
        let t = state.taxonomies.entities[uniqueId]
        return t && t.data
    },
    taxonomySettings: (state, getters) => (uniqueId) => {
        if (!uniqueId) return []
        let t = state.taxonomies.entities[uniqueId]
        return t && t.settings
    }
}

const actions = {
    setItem ({ commit }, payload) {
        commit('SET_ITEM', payload)
    },
    fetch({ commit, state }, payload) {
        if (!state.id) {
            commit('SET_DATA', {data: null})
            return false
        }

        commit('SET_LOADING', true)
        return axios.get(route('api.settings.content-types.show', {id: state.id}))
            .then((response) => {
                commit('SET_DATA', response.data)
            })
            .catch((error) => {
                console.error('API error', error)
            })
            .then(() => commit('SET_LOADING', false))
    },
    save({ commit, getters, state }, payload) {
        let { formData } = payload
        commit('SET_LOADING', true)
        return axios.post(route('api.settings.content-types.store'), formData)
            .then((response) => {
                let { data } = response
                if (!state.id) {
                    let id = data.data && data.data.id
                    id && commit('SET_ITEM', {id})
                }
                commit('SET_DATA', response.data)
            })
            .catch((error) => {
                console.error('API error', error)
            })
            .then(() => commit('SET_LOADING', false))
    },
    setTaxonomies({ commit }, payload) {
        commit('SET_TAXONOMIES', payload)
    },
    addTaxonomy({ commit }, payload) {
        commit('ADD_TAXONOMY', payload)
    },
    removeTaxonomy({ commit }, payload) {
        commit('REMOVE_TAXONOMY', payload)
    },
    updateTaxonomy({ commit }, payload) {
        commit('UPDATE_TAXONOMY', payload)
    },
    updateTaxonomyData({ commit }, payload) {
        commit('UPDATE_TAXONOMY_DATA', payload)
    },
    updateTaxonomySettings({ commit }, payload) {
        commit('UPDATE_TAXONOMY_SETTINGS', payload)
    }
}

const mutations = {
    'SET_LOADING'(state, val) {
        state.loading = !!val
    },
    'SET_DATA'(state, payload) {
        let {data} = payload
        state.data = data
    },
    'SET_ITEM' (state, payload) {
        state.id = payload.id
    },
    'SET_TAXONOMIES' (state, payload = {}) {
        let { items } = payload
        if (!items) {
            state.taxonomies.entities = {}
            state.taxonomies.data = []
            state.taxonomies.removedData = []
        }
    },
    'ADD_TAXONOMY' (state, payload = {}) {
        let { data, settings, settingsConfig } = payload
        let uniqueId = data.id || +(Date.now()+(+_.uniqueId()))

        let newItem = {
            uniqueId,
            data,
            settingsConfig,
            settings
        }

        Vue.set(state.taxonomies.entities, uniqueId, newItem)
        state.taxonomies.data.push(uniqueId)
    },
    'REMOVE_TAXONOMY' (state, payload = {}) {
        let { uniqueId } = payload

        let t = state.taxonomies.entities[uniqueId]
        t.data && t.data.id && state.taxonomies.removedData.push(t.data.id)

        let index = state.taxonomies.data.indexOf(uniqueId)
        state.taxonomies.data.splice(index, 1)
        Vue.delete(state.taxonomies.entities, uniqueId)
    },
    'UPDATE_TAXONOMY' (state, payload = {}) {
        let { data, uniqueId } = payload
        state.taxonomies.entities[uniqueId] = {
            ...state.taxonomies.entities[uniqueId],
            ...data
        }
    },
    'UPDATE_TAXONOMY_DATA' (state, payload = {}) {
        let { data, uniqueId } = payload

        let t = state.taxonomies.entities[uniqueId]
        t.data = data
    },
    'UPDATE_TAXONOMY_SETTINGS' (state, payload = {}) {
        let { settings, uniqueId } = payload
        let t = state.taxonomies.entities[uniqueId]
        t.settings = settings
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
