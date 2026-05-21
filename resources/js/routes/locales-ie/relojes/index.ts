import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Infraestructura\RelojController::store
* @see app/Http/Controllers/Infraestructura/RelojController.php:20
* @route '/locales-ie/{localesIe}/relojes'
*/
export const store = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/locales-ie/{localesIe}/relojes',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::store
* @see app/Http/Controllers/Infraestructura/RelojController.php:20
* @route '/locales-ie/{localesIe}/relojes'
*/
store.url = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { localesIe: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { localesIe: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            localesIe: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        localesIe: typeof args.localesIe === 'object'
        ? args.localesIe.id
        : args.localesIe,
    }

    return store.definition.url
            .replace('{localesIe}', parsedArgs.localesIe.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::store
* @see app/Http/Controllers/Infraestructura/RelojController.php:20
* @route '/locales-ie/{localesIe}/relojes'
*/
store.post = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::store
* @see app/Http/Controllers/Infraestructura/RelojController.php:20
* @route '/locales-ie/{localesIe}/relojes'
*/
const storeForm = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\RelojController::store
* @see app/Http/Controllers/Infraestructura/RelojController.php:20
* @route '/locales-ie/{localesIe}/relojes'
*/
storeForm.post = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

const relojes = {
    store: Object.assign(store, store),
}

export default relojes