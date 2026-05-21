import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::index
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:20
* @route '/dispositivos-marca'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/dispositivos-marca',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::index
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:20
* @route '/dispositivos-marca'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::index
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:20
* @route '/dispositivos-marca'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::index
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:20
* @route '/dispositivos-marca'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::index
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:20
* @route '/dispositivos-marca'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::index
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:20
* @route '/dispositivos-marca'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::index
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:20
* @route '/dispositivos-marca'
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
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::store
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:28
* @route '/dispositivos-marca'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/dispositivos-marca',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::store
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:28
* @route '/dispositivos-marca'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::store
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:28
* @route '/dispositivos-marca'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::store
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:28
* @route '/dispositivos-marca'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::store
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:28
* @route '/dispositivos-marca'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::destroy
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:36
* @route '/dispositivos-marca/{dispositivosMarca}'
*/
export const destroy = (args: { dispositivosMarca: number | { id: number } } | [dispositivosMarca: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/dispositivos-marca/{dispositivosMarca}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::destroy
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:36
* @route '/dispositivos-marca/{dispositivosMarca}'
*/
destroy.url = (args: { dispositivosMarca: number | { id: number } } | [dispositivosMarca: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { dispositivosMarca: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { dispositivosMarca: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            dispositivosMarca: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        dispositivosMarca: typeof args.dispositivosMarca === 'object'
        ? args.dispositivosMarca.id
        : args.dispositivosMarca,
    }

    return destroy.definition.url
            .replace('{dispositivosMarca}', parsedArgs.dispositivosMarca.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::destroy
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:36
* @route '/dispositivos-marca/{dispositivosMarca}'
*/
destroy.delete = (args: { dispositivosMarca: number | { id: number } } | [dispositivosMarca: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::destroy
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:36
* @route '/dispositivos-marca/{dispositivosMarca}'
*/
const destroyForm = (args: { dispositivosMarca: number | { id: number } } | [dispositivosMarca: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Infraestructura\DispositivoMarcaController::destroy
* @see app/Http/Controllers/Infraestructura/DispositivoMarcaController.php:36
* @route '/dispositivos-marca/{dispositivosMarca}'
*/
destroyForm.delete = (args: { dispositivosMarca: number | { id: number } } | [dispositivosMarca: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const DispositivoMarcaController = { index, store, destroy }

export default DispositivoMarcaController