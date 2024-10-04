import './bootstrap';
import '../css/app.css';
import 'floating-vue/dist/style.css'
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import FloatingVue from 'floating-vue'
import { usePage } from '@inertiajs/vue3';
import Sticky from 'vue-sticky-directive'
// import VueSticky from 'vue-sticky' // Es6 module
// const VueSticky = VueSticky.default // Global variable
// import Vue from 'vue';
import vSelect from 'vue-select'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';


createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(FloatingVue)
            .use(Sticky)
            .mixin({
                methods: {
                    can: function (permissions) {
                        var allPermissions = this.$page.props.auth.can;
                        var hasPermission = false;
                        permissions.forEach(function (item) {
                            if (allPermissions[item]) hasPermission = true;
                        });
                        return hasPermission;
                    },
                    lang: function () {
                        return usePage().props.language.original;
                    }
                },
            })
            .mount(el);
    },
    progress: {
        color: '#0284c7',
        includeCSS: true,
        showSpinner: true,
        spinner: {
            // Increase the size of the spinner
            scale: 1.5,
        },

    },
});
