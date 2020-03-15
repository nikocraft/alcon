window.axios.interceptors.response.use((response) => {
    return response;
}, error => {
    // Handle a 500 error
    if (error.response.status >= 500) {
    }

    // Handle Session Timeouts
    if (error.response.status === 401) {
        window.location.href = '/auth/login'
    }

    // Handle Forbidden
    if (error.response.status === 403) {
    }
    return Promise.reject(error);
});
