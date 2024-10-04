module.exports = {
    preset: '@vue/cli-plugin-unit-jest/presets/no-babel',
    pwa: {
        name: 'Inspeccions cma',
        themeColor: '#4DBA87',
        msTileColor: '#000000',
        appleMobileWebAppCapable: 'yes',
        appleMobileWebAppStatusBarStyle: 'black',
        workboxOptions: {
            skipWaiting: true
        }
    },
}
