const state = {
    data: window.Laravel['contentTypesList'] || null
}

const getters = {
    getIdBySlug: (state) => (slug) => {
        if(!slug || !state.data) return []
        let contentType = _.find(state.data, {slug})
        return contentType && contentType.id
    },
    getObj: (state) => (id) => {
        if(!id || !state.data) return []
        let contentType = _.find(state.data, {id})
        return contentType
    }
}

const actions = {
    fetch({ commit }) {
        return axios.get(route('api.content-type.list'))
            .then(({data}) => {
                commit('SET_DATA', data)
            })
            .catch((error) => {
                console.error('API error', error)
            })
    }
}

const mutations = {
    'SET_DATA'(state, payload) {
        let {data} = payload
        state.data = data
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
