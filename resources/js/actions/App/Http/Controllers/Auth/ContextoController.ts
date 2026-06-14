import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\ContextoController::create
* @see app/Http/Controllers/Auth/ContextoController.php:19
* @route '/seleccionar-contexto'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/seleccionar-contexto',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\ContextoController::create
* @see app/Http/Controllers/Auth/ContextoController.php:19
* @route '/seleccionar-contexto'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ContextoController::create
* @see app/Http/Controllers/Auth/ContextoController.php:19
* @route '/seleccionar-contexto'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\ContextoController::create
* @see app/Http/Controllers/Auth/ContextoController.php:19
* @route '/seleccionar-contexto'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\ContextoController::create
* @see app/Http/Controllers/Auth/ContextoController.php:19
* @route '/seleccionar-contexto'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\ContextoController::create
* @see app/Http/Controllers/Auth/ContextoController.php:19
* @route '/seleccionar-contexto'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\ContextoController::create
* @see app/Http/Controllers/Auth/ContextoController.php:19
* @route '/seleccionar-contexto'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\Auth\ContextoController::store
* @see app/Http/Controllers/Auth/ContextoController.php:31
* @route '/seleccionar-contexto'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/seleccionar-contexto',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\ContextoController::store
* @see app/Http/Controllers/Auth/ContextoController.php:31
* @route '/seleccionar-contexto'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ContextoController::store
* @see app/Http/Controllers/Auth/ContextoController.php:31
* @route '/seleccionar-contexto'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\ContextoController::store
* @see app/Http/Controllers/Auth/ContextoController.php:31
* @route '/seleccionar-contexto'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\ContextoController::store
* @see app/Http/Controllers/Auth/ContextoController.php:31
* @route '/seleccionar-contexto'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

const ContextoController = { create, store }

export default ContextoController