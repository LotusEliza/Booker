// vue.config.js
module.exports = {
    //publicPath: '',
    //publicPath: '/~user3/booker/client/',
    baseUrl: '/~user3/booker/client/project/dist/',
    devServer: {
        proxy: 'http://localhost/',
    }
}