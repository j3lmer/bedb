import Vue from 'vue'
import Vuetify from 'vuetify/lib'
import colors from 'vuetify/es5/util/colors'


Vue.use(Vuetify, {
    iconfont: 'md',
    theme: {

        primary: colors.green.base, // #4CAF50

        secondary: colors.red.lighten4, // #FFCDD2

        accent: colors.indigo.base, // #3F51B5

        search: colors.grey.lighten2, // #E0E0E0

        searchButton: colors.green.lighten3,  // #A5D6A7

        newsBlock: colors.grey.lighten4, // #F5F5F5

// info: colors.lighten1,

// warning: colors.darken2,

// error: colors.accent4,

// success: colors.lighten2,

    }
})



const opts = {}

export default new Vuetify(opts)
