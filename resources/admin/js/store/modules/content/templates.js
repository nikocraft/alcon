const state = {
    templates: [],
    tags: [],
    filter: {
        tags: null
    },
    fetching: false,
    filled: false,
    pagination: {}
}

const getters = {
    templates: state => state.templates,
    filled: state => state.filled,
    fetching: state => state.fetching,
}

const actions = {
    fetchTemplates ({ dispatch, commit, getters, state }, payload={}) {
        if (state.fetching || state.filled) return
        let { init=false } = payload
        init && commit(types.RESET_PAGINATION)

        let query = {}
        query = _.omitBy(state.filter, _.isNil)
        query = { ...query, ...state.pagination }

        commit('SET_FETCHING', true)

        return axios.get(route('api.design.templates.index', query))
            .then(({data: responseData}) => {
                let currentPage = responseData.meta.current_page
                let lastPage = responseData.meta.last_page

                commit('SET_FETCHING')
                commit('SET_TEMPLATES_DATA', {data: responseData.data})

                if (currentPage == lastPage)
                    commit('SET_AS_FILLED')
                else
                    commit('INCREMENT_PAGINATION')
            })
            .catch((error) => {
                console.error('API error', error)
                commit('SET_FETCHING')
                commit('SET_AS_FILLED')
            })
    },
    setTemplatesFilter ({ dispatch, commit, getters }, payload) {
        commit('SET_TEMPLATES_FILTER', payload)
        commit('RESET_PAGINATION')
        commit('RESET_TEMPLATES')
    },
    init ({ dispatch, commit, getters }, payload) {
        commit('INIT')
        commit('RESET_PAGINATION')
        commit('RESET_TEMPLATES')
    },
    fetchTags({ commit }, payload) {
        return axios.get(route('api.design.templates.tags'))
            .then(({data: responseData}) => {
                commit('SET_TAGS_DATA', {data: responseData.data})
            })
            .catch((error) => {
                console.error('API error', error)
            })
    },
}

const mutations = {
    'SET_TEMPLATES_DATA' (state, payload) {
        let { data } = payload
        state.templates = [ ...state.templates, ...data ]
    },
    'SET_TAGS_DATA' (state, payload) {
        let { data } = payload
        state.tags = data
    },
    'SET_TEMPLATES_FILTER' (state, payload) {
        state.filter = { ...state.filter, ...payload }
    },
    'RESET_TEMPLATES' (state, payload) {
        state.templates = []
        state.filled = false
    },
    'INIT' (state, payload) {
        state.filter.tags = null
    },
    'SET_AS_FILLED' (state) {
        state.filled = true
    },
    'INCREMENT_PAGINATION' (state) {
        let { pagination: {page: page=1} } = state
        state.pagination.page = page + 1
    },
    'RESET_PAGINATION' (state) {
        state.pagination.page && delete state.pagination.page
    },
    'SET_FETCHING' (state, start) {
        state.fetching = !!start
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
