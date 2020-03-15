const state = {
    taxonomyData: null,
    terms: [],
    termsLoading: false,
    termProcessLoading: false
}

const getters = {
    terms: state => state.terms,
    termsLoading: state => state.termsLoading,
    termProcessLoading: state => state.termProcessLoading
}

const actions = {
    fetch({ commit, getters, state }, payload = {}) {
        let { contentTypeId, taxonomyId } = payload

        commit('SET_TERMS_LOADING', true)
        return axios.get(route('api.content.taxonomy.show', {contentTypeId, taxonomyId}))
            .then(({data: responseData}) => {
                commit('SET_TERMS', {data: responseData.data.terms})
                commit('SET_TAXONOMY_DATA', {data: _.omit(responseData.data, ['terms'])})
            })
            .catch((error) => {
                console.error('API error', error)
            })
            .then(() => commit('SET_TERMS_LOADING', false))
    },
    removeTerm({ commit, getters, state }, payload = {}) {
        let { term } = payload

        commit('SET_TERM_PROCESS_LOADING', true)
        return axios.delete(route('api.content.taxonomy.terms.destroy', {term: term.id}))
            .then((response) => {
                console.log('deleted')
            }, (error) => {
                console.error('API error', error)
            })
            .then(() => commit('SET_TERM_PROCESS_LOADING', false))
    },
    updateTerm({ commit, getters, state }, payload = {}) {
        let { term } = payload

        let rawTermData = {
            taxonomy_id: state.taxonomyData && state.taxonomyData.id
        }

        commit('SET_TERM_PROCESS_LOADING', true)
        return axios.post(route('api.content.taxonomy.terms.store'), {term: {...rawTermData, ...term}})
            .then((response) => {
                //
            }, (error) => {
                console.error('API error', error)
            })
            .then(() => commit('SET_TERM_PROCESS_LOADING', false))
    },
    flushData({ commit }) {
        commit('SET_TERMS', {data: null})
        commit('SET_TAXONOMY_DATA', {data: null})
    }
}

const mutations = {
    'SET_TERMS_LOADING'(state, val) {
        state.termsLoading = !!val
    },
    'SET_TERM_PROCESS_LOADING'(state, val) {
        state.termProcessLoading = !!val
    },
    'SET_TERMS'(state, payload) {
        let {data} = payload
        state.terms = data
    },
    'SET_TAXONOMY_DATA'(state, payload) {
        let {data} = payload
        state.taxonomyData = data
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}
