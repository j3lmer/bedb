'use strict';

import Vue, {Component} from 'vue';

// Export decorators / annotations / objects from commonly used Vue-specific modules so they can be used in component
// classes without the need of imports from additional modules across the entire application. If a module has to be
// interchanged with another in the future, this would be the place to do so.


export * from 'vue-class-component';
export * from 'vue-property-decorator';

/**
 * Abstract vue component.
 *
 * All vue components should inherit this base class to minimize the need of imports from multiple different modules
 * in every Vue SFC (single-file-component).
 *
 * All lifecycle hooks known to Vue are declared here for type-completion and to be overridden by your component.
 *
 * {@link https://class-component.vuejs.org/guide/class-component.html Vue class component reference documentation}
 * {@link https://vuejs.org/v2/guide/instance.html#Lifecycle-Diagram Vue lifecycle diagram}
 */
export abstract class VueComponent extends Vue
{
    /**
     * Called synchronously immediately after the instance has been initialized, before data observation and
     * event/watcher setup.
     *
     * {@link https://vuejs.org/v2/api/#beforeCreate Reference documentation}
     * {@link https://vuejs.org/v2/guide/instance.html#Lifecycle-Diagram Vue lifecycle diagram}
     */
    public beforeCreate(): void
    {
    }

    /**
     * Called synchronously after the instance is created. At this stage, the instance has finished processing the
     * options which means the following have been set up: data observation, computed properties, methods, watch/event
     * callbacks. However, the mounting phase has not been started, and the $el property will not be available yet.
     *
     * {@link https://vuejs.org/v2/api/#created Reference documentation}
     * {@link https://vuejs.org/v2/guide/instance.html#Lifecycle-Diagram Vue lifecycle diagram}
     */
    public created(): void
    {
    }

    /**
     * Called right before the mounting begins: the render function is about to be called for the first time.
     *
     * {@link https://vuejs.org/v2/api/#beforeMount Reference documentation}
     * {@link https://vuejs.org/v2/guide/instance.html#Lifecycle-Diagram Vue lifecycle diagram}
     */
    public beforeMount(): void
    {
    }

    /**
     * Called after the instance has been mounted, where el is replaced by the newly created vm.$el. If the root
     * instance is mounted to an in-document element, vm.$el will also be in-document when mounted is called.
     *
     * Note that mounted does not guarantee that all child components have also been mounted. If you want to wait until
     * the entire view has been rendered, you can use vm.$nextTick inside of mounted:
     *
     * {@link https://vuejs.org/v2/api/#mounted Reference documentation}
     * {@link https://vuejs.org/v2/guide/instance.html#Lifecycle-Diagram Vue lifecycle diagram}
     */
    public mounted(): void
    {
    }

    /**
     * Called when data changes, before the DOM is patched. This is a good place to access the existing DOM before an
     * update, e.g. to remove manually added event listeners.
     *
     * {@link https://vuejs.org/v2/api/#beforeUpdate Reference documentation}
     * {@link https://vuejs.org/v2/guide/instance.html#Lifecycle-Diagram Vue lifecycle diagram}
     */
    public beforeUpdate(): void
    {
    }

    /**
     * Called after a data change causes the virtual DOM to be re-rendered and patched.
     *
     * The component’s DOM will have been updated when this hook is called, so you can perform DOM-dependent operations
     * here. However, in most cases you should avoid changing state inside the hook. To react to state changes, it’s
     * usually better to use a computed property or watcher instead.
     *
     * Note that updated does not guarantee that all child components have also been re-rendered. If you want to wait
     * until the entire view has been re-rendered, you can use vm.$nextTick inside of the updated method.
     *
     * {@link https://vuejs.org/v2/api/#updated Reference documentation}
     * {@link https://vuejs.org/v2/guide/instance.html#Lifecycle-Diagram Vue lifecycle diagram}
     */
    public updated(): void
    {
    }

    /**
     * Called when a kept-alive component is activated.
     *
     * {@link https://vuejs.org/v2/api/#activated Reference documentation}
     * {@link https://vuejs.org/v2/guide/instance.html#Lifecycle-Diagram Vue lifecycle diagram}
     */
    public activated(): void
    {
    }

    /**
     * Called when a kept-alive component is deactivated.
     *
     * {@link https://vuejs.org/v2/api/#deactivated Reference documentation}
     * {@link https://vuejs.org/v2/guide/instance.html#Lifecycle-Diagram Vue lifecycle diagram}
     */
    public deactivated(): void
    {
    }

    /**
     * Called right before a Vue instance is destroyed. At this stage the instance is still fully functional.
     *
     * {@link https://vuejs.org/v2/api/#beforeDestroy Reference documentation}
     * {@link https://vuejs.org/v2/guide/instance.html#Lifecycle-Diagram Vue lifecycle diagram}
     */
    public beforeDestroy(): void
    {
    }

    /**
     * Called after a Vue instance has been destroyed. When this hook is called, all directives of the Vue instance have
     * been unbound, all event listeners have been removed, and all child Vue instances have also been destroyed.
     *
     * {@link https://vuejs.org/v2/api/#destroyed Reference documentation}
     * {@link https://vuejs.org/v2/guide/instance.html#Lifecycle-Diagram Vue lifecycle diagram}
     */
    public destroyed(): void
    {
    }

    /**
     * Called when an error from any descendent component is captured.
     * The hook receives three arguments:
     *  - The error;
     *  - The component instance that triggered the error;
     *  - A string containing information on where the error was captured.
     *
     * The errorCaptured hook can return false to prevent the error from propagating further. This is essentially
     * saying “this error has been handled and should be ignored.” It will prevent any additional errorCaptured hooks
     * or the global config.errorHandler from being invoked for this error.
     *
     * {@link https://vuejs.org/v2/api/#errorCaptured Reference documentation}
     * {@link https://vuejs.org/v2/guide/instance.html#Lifecycle-Diagram Vue lifecycle diagram}
     */
    public errorCaptured(err: Error, vm: Component, info: string): boolean | void
    {
    }
}
