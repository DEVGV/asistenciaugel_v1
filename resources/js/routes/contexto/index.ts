import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\ContextoController::seleccionar
* @see app/Http/Controllers/Auth/ContextoController.php:19
* @route '/seleccionar-contexto'
*/
export const seleccionar = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: seleccionar.url(options),
    method: 'get',
})

seleccionar.definition = {
    methods: ["get","head"],
    url: '/seleccionar-contexto',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\ContextoController::seleccionar
* @see app/Http/Controllers/Auth/ContextoController.php:19
* @route '/seleccionar-contexto'
*/
seleccionar.url = (options?: RouteQueryOptions) => {
    return seleccionar.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ContextoController::seleccionar
* @see app/Http/Controllers/Auth/ContextoController.php:19
* @route '/seleccionar-contexto'
*/
seleccionar.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: seleccionar.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\ContextoController::seleccionar
* @see app/Http/Controllers/Auth/ContextoController.php:19
* @route '/seleccionar-contexto'
*/
seleccionar.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: seleccionar.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\ContextoController::seleccionar
* @see app/Http/Controllers/Auth/ContextoController.php:19
* @route '/seleccionar-contexto'
*/
const seleccionarForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: seleccionar.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\ContextoController::seleccionar
* @see app/Http/Controllers/Auth/ContextoController.php:19
* @route '/seleccionar-contexto'
*/
seleccionarForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: seleccionar.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\ContextoController::seleccionar
* @see app/Http/Controllers/Auth/ContextoController.php:19
* @route '/seleccionar-contexto'
*/
seleccionarForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: seleccionar.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

seleccionar.form = seleccionarForm

/**
* @see \App\Http\Controllers\Auth\ContextoController::establecer
* @see app/Http/Controllers/Auth/ContextoController.php:31
* @route '/seleccionar-contexto'
*/
export const establecer = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: establecer.url(options),
    method: 'post',
})

establecer.definition = {
    methods: ["post"],
    url: '/seleccionar-contexto',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\ContextoController::establecer
* @see app/Http/Controllers/Auth/ContextoController.php:31
* @route '/seleccionar-contexto'
*/
establecer.url = (options?: RouteQueryOptions) => {
    return establecer.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ContextoController::establecer
* @see app/Http/Controllers/Auth/ContextoController.php:31
* @route '/seleccionar-contexto'
*/
establecer.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: establecer.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\ContextoController::establecer
* @see app/Http/Controllers/Auth/ContextoController.php:31
* @route '/seleccionar-contexto'
*/
const establecerForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: establecer.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\ContextoController::establecer
* @see app/Http/Controllers/Auth/ContextoController.php:31
* @route '/seleccionar-contexto'
*/
establecerForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: establecer.url(options),
    method: 'post',
})

establecer.form = establecerForm

const contexto = {
    seleccionar: Object.assign(seleccionar, seleccionar),
    establecer: Object.assign(establecer, establecer),
}

export default contexto