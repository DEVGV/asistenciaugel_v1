import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::store
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:18
* @route '/grados/{grado}/secciones'
*/
export const store = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/grados/{grado}/secciones',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::store
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:18
* @route '/grados/{grado}/secciones'
*/
store.url = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { grado: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { grado: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            grado: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        grado: typeof args.grado === 'object'
        ? args.grado.id
        : args.grado,
    }

    return store.definition.url
            .replace('{grado}', parsedArgs.grado.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::store
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:18
* @route '/grados/{grado}/secciones'
*/
store.post = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::store
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:18
* @route '/grados/{grado}/secciones'
*/
const storeForm = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\SeccionIEController::store
* @see app/Http/Controllers/InstitucionEducativa/SeccionIEController.php:18
* @route '/grados/{grado}/secciones'
*/
storeForm.post = (args: { grado: string | number | { id: string | number } } | [grado: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

const secciones = {
    store: Object.assign(store, store),
}

export default secciones