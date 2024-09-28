export default {
    state: {
        authUser: {}
    },
    getters: {
        getAuthUser: (state) => {
            return state.authUser;
        }
    },
    mutations: {
        SET_AUTH_USER(state, user) {
            state.authUser = user;
            localStorage.authUser = JSON.stringify(user);
        }
    },
    actions: {
        initAuthUser({ commit }) {
            const cachedUser = localStorage.authUser ? JSON.parse(localStorage.authUser) : null;
            if (cachedUser)
                commit('SET_AUTH_USER', cachedUser)
        }
    },
}
