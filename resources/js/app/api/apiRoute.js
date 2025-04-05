const apiVersion = "/api/";

const apiRoute = {

    /*** Authentication ***/
    login: apiVersion + 'auth/login',
    registration: apiVersion + 'auth/registration',
    forgot: apiVersion + 'auth/forgot',
    reset: apiVersion + 'auth/reset',
    verification: apiVersion + 'auth/verification',

    /*** profile ***/
    details: apiVersion + 'profile/details',
    changeDetails: apiVersion + 'profile/change-details',
    changePassword: apiVersion + 'profile/change-password',
    logout: apiVersion + 'profile/logout',

    /*** users ***/
    users: apiVersion + 'users',

    /*** chat ***/
    chat: apiVersion + 'chat',

}

export default apiRoute;
