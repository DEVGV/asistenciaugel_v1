import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::index
* @see app/Http/Controllers/Tramite/ExpedienteController.php:33
* @route '/expedientes'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/expedientes',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::index
* @see app/Http/Controllers/Tramite/ExpedienteController.php:33
* @route '/expedientes'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::index
* @see app/Http/Controllers/Tramite/ExpedienteController.php:33
* @route '/expedientes'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::index
* @see app/Http/Controllers/Tramite/ExpedienteController.php:33
* @route '/expedientes'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::index
* @see app/Http/Controllers/Tramite/ExpedienteController.php:33
* @route '/expedientes'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::index
* @see app/Http/Controllers/Tramite/ExpedienteController.php:33
* @route '/expedientes'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::index
* @see app/Http/Controllers/Tramite/ExpedienteController.php:33
* @route '/expedientes'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::store
* @see app/Http/Controllers/Tramite/ExpedienteController.php:61
* @route '/expedientes'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/expedientes',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::store
* @see app/Http/Controllers/Tramite/ExpedienteController.php:61
* @route '/expedientes'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::store
* @see app/Http/Controllers/Tramite/ExpedienteController.php:61
* @route '/expedientes'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::store
* @see app/Http/Controllers/Tramite/ExpedienteController.php:61
* @route '/expedientes'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::store
* @see app/Http/Controllers/Tramite/ExpedienteController.php:61
* @route '/expedientes'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::anular
* @see app/Http/Controllers/Tramite/ExpedienteController.php:72
* @route '/expedientes/{expediente}/anular'
*/
export const anular = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: anular.url(args, options),
    method: 'post',
})

anular.definition = {
    methods: ["post"],
    url: '/expedientes/{expediente}/anular',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::anular
* @see app/Http/Controllers/Tramite/ExpedienteController.php:72
* @route '/expedientes/{expediente}/anular'
*/
anular.url = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { expediente: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { expediente: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            expediente: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        expediente: typeof args.expediente === 'object'
        ? args.expediente.id
        : args.expediente,
    }

    return anular.definition.url
            .replace('{expediente}', parsedArgs.expediente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::anular
* @see app/Http/Controllers/Tramite/ExpedienteController.php:72
* @route '/expedientes/{expediente}/anular'
*/
anular.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: anular.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::anular
* @see app/Http/Controllers/Tramite/ExpedienteController.php:72
* @route '/expedientes/{expediente}/anular'
*/
const anularForm = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: anular.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Tramite\ExpedienteController::anular
* @see app/Http/Controllers/Tramite/ExpedienteController.php:72
* @route '/expedientes/{expediente}/anular'
*/
anularForm.post = (args: { expediente: number | { id: number } } | [expediente: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: anular.url(args, options),
    method: 'post',
})

anular.form = anularForm

const expedientes = {
    index: Object.assign(index, index),
    store: Object.assign(store, store),
    anular: Object.assign(anular, anular),
}

export default expedientes