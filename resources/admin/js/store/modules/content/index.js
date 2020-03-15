const state = {
    data: null,
    settings: {
        indexPageDisplay: 'grid',
        indexPageGridColumns: 'column-3',
        indexPageGridStyle: 'inline',
        indexPageItemsPerPage: 12,
        indexPageSortBy: 'latest',
        indexPageDisplayAuthor: true,
        indexPageDisplayCreatedUpdated: true,
        indexPageDisplayFeaturedImage: true
    }
}

const actions = {
    setContentTypeData({ commit, state, dispatch }, payload) {
        let {data = {}, settings = {}} = payload
        commit('SET_DATA', {data})
        commit('SET_SETTINGS', {data: settings})
    },
    fetchContentType({ commit, state, dispatch}, payload) {
        let {contentTypeId} = payload
        if(state.data && state.data.id == contentTypeId) return
        return axios.get(route('api.content-type.show', {contentTypeId}))
            .then(({data}) => {
                dispatch('setContentTypeData', data)
            })
            .catch((error) => {
                console.error('API error', error)
            })
    },
    saveSettings({ commit, state, getters }) {
        let settings = state.settings

        return axios.post(route('api.content.index.settings'), {settings})
            .then((response) => {
                //
            })
            .catch((error) => {
                //
            })
    },
    flushContentType({ commit }) {
        commit('FLUSH_DATA')
        commit('FLUSH_SETTINGS')
    }
}

const mutations = {
    'SET_DATA'(state, payload) {
        let {data} = payload
        state.data = data
    },
    'SET_SETTINGS'(state, payload) {
        let {data} = payload
        state.settings = data
    },
    'FLUSH_DATA'(state) {
        state.data = null
    },
    'FLUSH_SETTINGS'(state) {
        state.settings = {}
    }
}

export default {
    namespaced: true,
    state,
    actions,
    mutations
}
