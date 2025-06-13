import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.axios = axios;

axios.defaults.withCredentials = true;
axios.defaults.baseURL = 'http://localhost:8000';
