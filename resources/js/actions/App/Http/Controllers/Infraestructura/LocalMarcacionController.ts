import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Infraestructura\LocalMarcacionController::store
* @see app/Http/Controllers/Infraestructura/LocalMarcacionController.php:18
* @route '/locales-ie/{localesIe}/marcaciones-local'
*/
export const store = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/locales-ie/{localesIe}/marcaciones-local',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Infraestructura\LocalMarcacionController::store
* @see app/Http/Controllers/Infraestructura/LocalMarcacionController.php:18
* @route '/locales-ie/{localesIe}/marcaciones-local'
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
* @see \App\Http\Controllers\Infraestructura\LocalMarcacionController::store
* @see app/Http/Controllers/Infraestructura/LocalMarcacionController.php:18
* @route '/locales-ie/{localesIe}/marcaciones-local'
*/
store.post = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalMarcacionController::store
* @see app/Http/Controllers/Infraestructura/LocalMarcacionController.php:18
* @route '/locales-ie/{localesIe}/marcaciones-local'
*/
const storeForm = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalMarcacionController::store
* @see app/Http/Controllers/Infraestructura/LocalMarcacionController.php:18
* @route '/locales-ie/{localesIe}/marcaciones-local'
*/
storeForm.post = (args: { localesIe: number | { id: number } } | [localesIe: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(args, options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Infraestructura\LocalMarcacionController::destroy
* @see app/Http/Controllers/Infraestructura/LocalMarcacionController.php:26
* @route '/marcaciones-local/{marcacionesLocal}'
*/
export const destroy = (args: { marcacionesLocal: number | { id: number } } | [marcacionesLocal: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/marcaciones-local/{marcacionesLocal}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Infraestructura\LocalMarcacionController::destroy
* @see app/Http/Controllers/Infraestructura/LocalMarcacionController.php:26
* @route '/marcaciones-local/{marcacionesLocal}'
*/
destroy.url = (args: { marcacionesLocal: number | { id: number } } | [marcacionesLocal: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { marcacionesLocal: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { marcacionesLocal: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            marcacionesLocal: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        marcacionesLocal: typeof args.marcacionesLocal === 'object'
        ? args.marcacionesLocal.id
        : args.marcacionesLocal,
    }

    return destroy.definition.url
            .replace('{marcacionesLocal}', parsedArgs.marcacionesLocal.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Infraestructura\LocalMarcacionController::destroy
* @see app/Http/Controllers/Infraestructura/LocalMarcacionController.php:26
* @route '/marcaciones-local/{marcacionesLocal}'
*/
destroy.delete = (args: { marcacionesLocal: number | { id: number } } | [marcacionesLocal: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalMarcacionController::destroy
* @see app/Http/Controllers/Infraestructura/LocalMarcacionController.php:26
* @route '/marcaciones-local/{marcacionesLocal}'
*/
const destroyForm = (args: { marcacionesLocal: number | { id: number } } | [marcacionesLocal: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\LocalMarcacionController::destroy
* @see app/Http/Controllers/Infraestructura/LocalMarcacionController.php:26
* @route '/marcaciones-local/{marcacionesLocal}'
*/
destroyForm.delete = (args: { marcacionesLocal: number | { id: number } } | [marcacionesLocal: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const LocalMarcacionController = { store, destroy }

export default LocalMarcacionController