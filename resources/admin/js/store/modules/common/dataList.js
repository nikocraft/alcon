const state = {
    apiUrl: null,
    items: null,
    filterData: {},
    paginationData: {},
    loading: false,
    ready: false
}

const getters = {
    //
}

const actions = {
    setItem ({ commit }, payload) {
        commit('SET_ITEM', payload)
    },
    fetch({ commit, state, getters }, payload) {
        let { init=false } = payload || {}

        let query = {
            ..._.omitBy(state.filterData, _.isNil),
        }

        commit('SET_LOADING', true)
        return axios.get(state.apiUrl, {params: query})
            .then(({data: responseData}) => {
                commit('SET_ITEMS', responseData)
                commit('UPDATE_PAGINATION', responseData.meta)
                return responseData
            })
            .catch((error) => {
                console.error('API error', error)
            })
            .then((data) => {
                commit('SET_LOADING', false)
                commit('SET_AS_READY')
                return data
            })
    },
    setApiUrl({ commit }, payload) {
        commit('SET_API_URL', payload)
    },
    setQueryString({ commit }, payload) {
        commit('SET_QUERY_STRING', payload)
    }
}

const mutations = {
    'SET_ITEMS'(state, payload) {
        let {data} = payload
        state.items = data
    },
    'SET_API_URL'(state, payload) {
        let {data} = payload
        state.apiUrl = data
    },
    'SET_QUERY_STRING'(state, payload) {
        let {data} = payload || {}
        state.filterData = data
    },
    'SET_LOADING'(state, start) {
        state.loading = !!start
    },
    'SET_AS_READY'(state) {
        state.ready = true
    },
    'UPDATE_PAGINATION'(state, payload) {
        state.paginationData = payload
    }
}

export default {
    namespaced: true,
    state: () => ({...state}),
    getters,
    actions,
    mutations
}
