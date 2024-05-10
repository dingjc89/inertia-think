import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';


createInertiaApp({
    title: 'inertia-think',
    resolve: (name) => {
        return resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        )
    },
    setup({ el, app, props, plugin }) {
        console.log(el)
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ElementPlus)
            .mount(el);
    },
});
