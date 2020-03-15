const state = {
    comments: null,
    commentsLoading: false,
    pagination: null,
    queryParams: {
        status: undefined,
        page: undefined
    },
    counters: {
        all: 0,
        approved: 0,
        pending: 0,
        spam: 0,
        returned: 0
    }
}

const getters = {
    currentStatus: state => state.queryParams.status
}

const actions = {
    updateQueryParams({ commit, getters, state }, payload) {
        commit('UPDATE_QUERY_PARAMS', payload)
    },
    fetchComments({ commit, getters, state }, payload) {
        let queryObj = state.queryParams
        queryObj = _.omitBy(queryObj, _.isUndefined)

        commit('SET_COMMENTS_LOADING', true)
        return axios.get(route('api.comments.index'), {
                params: {
                    ...queryObj
                }
            })
            .then(({data: responseData}) => {
                commit('SET_COMMENTS', responseData)
                commit('SET_PAGINATION', {data: responseData.meta})
                commit('SET_COUNTERS', _.omit(responseData, ['data, links, meta']))
            })
            .catch((error) => {
                console.error('API error', error)
            })
            .then(() => commit('SET_COMMENTS_LOADING', false))
    }
}

const mutations = {
    'SET_COMMENTS'(state, payload) {
        let {data} = payload
        state.comments = data
    },
    'SET_PAGINATION'(state, payload) {
        let {data} = payload
        state.pagination = data
    },
    'SET_COMMENTS_LOADING'(state, val) {
        state.commentsLoading = !!val
    },
    'SET_COUNTERS'(state, payload) {
        let {
            commentsCount: all = 0,
            approvedCount: approved = 0,
            pendingCount: pending = 0,
            spamCount: spam = 0,
            returnedCommentsCount: returned = 0
        } = payload

        state.counters = {
            all,
            approved,
            pending,
            spam,
            returned
        }
    },
    'UPDATE_QUERY_PARAMS'(state, payload) {
        state.queryParams = {
            ...state.queryParams,
            ...payload
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
