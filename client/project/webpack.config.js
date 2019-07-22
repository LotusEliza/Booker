// vue.config.js
module.exports = {
    //publicPath: '',
    //publicPath: '/~user3/booker/client/',
        configureWebpack: {
          publicPath: '/~user3/booker/client/project/dist/'
        }
    // publicPath: '/~user3/booker/client/project/dist/',
    baseUrl: '/~user3/booker/client/project/dist/',
    devServer: {
        proxy: 'http://localhost/',
    }
}