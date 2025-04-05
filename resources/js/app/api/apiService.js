const apiService = {

    headerContent:{
        'Content-Type': 'application/json; charset=utf-8',
    },

    mediaHeaderContent:{
        "Content-Type": "multipart/form-data",
    },

    authHeaderContent: () => ({
        'Content-Type': 'application/json; charset=utf-8',
        'Authorization': `Bearer ${localStorage.getItem('token') || ''}`
    }),

    authHeaderMediaContent: () => ({
        'Content-Type': 'multipart/form-data',
        'Authorization': `Bearer ${localStorage.getItem('token') || ''}`
    })

}

export default apiService;