import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\ParamController::types
* @see app/Http/Controllers/Api/ParamController.php:65
* @route '/api/params'
*/
export const types = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: types.url(options),
    method: 'get',
})

types.definition = {
    methods: ["get","head"],
    url: '/api/params',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ParamController::types
* @see app/Http/Controllers/Api/ParamController.php:65
* @route '/api/params'
*/
types.url = (options?: RouteQueryOptions) => {
    return types.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ParamController::types
* @see app/Http/Controllers/Api/ParamController.php:65
* @route '/api/params'
*/
types.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: types.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ParamController::types
* @see app/Http/Controllers/Api/ParamController.php:65
* @route '/api/params'
*/
types.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: types.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ParamController::types
* @see app/Http/Controllers/Api/ParamController.php:65
* @route '/api/params'
*/
const typesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: types.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ParamController::types
* @see app/Http/Controllers/Api/ParamController.php:65
* @route '/api/params'
*/
typesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: types.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ParamController::types
* @see app/Http/Controllers/Api/ParamController.php:65
* @route '/api/params'
*/
typesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: types.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

types.form = typesForm

/**
* @see \App\Http\Controllers\Api\ParamController::reverseUbigeo
* @see app/Http/Controllers/Api/ParamController.php:43
* @route '/api/params/ubigeo/{ubigeo}'
*/
export const reverseUbigeo = (args: { ubigeo: string | number } | [ubigeo: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reverseUbigeo.url(args, options),
    method: 'get',
})

reverseUbigeo.definition = {
    methods: ["get","head"],
    url: '/api/params/ubigeo/{ubigeo}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ParamController::reverseUbigeo
* @see app/Http/Controllers/Api/ParamController.php:43
* @route '/api/params/ubigeo/{ubigeo}'
*/
reverseUbigeo.url = (args: { ubigeo: string | number } | [ubigeo: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ubigeo: args }
    }

    if (Array.isArray(args)) {
        args = {
            ubigeo: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        ubigeo: args.ubigeo,
    }

    return reverseUbigeo.definition.url
            .replace('{ubigeo}', parsedArgs.ubigeo.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ParamController::reverseUbigeo
* @see app/Http/Controllers/Api/ParamController.php:43
* @route '/api/params/ubigeo/{ubigeo}'
*/
reverseUbigeo.get = (args: { ubigeo: string | number } | [ubigeo: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reverseUbigeo.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ParamController::reverseUbigeo
* @see app/Http/Controllers/Api/ParamController.php:43
* @route '/api/params/ubigeo/{ubigeo}'
*/
reverseUbigeo.head = (args: { ubigeo: string | number } | [ubigeo: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: reverseUbigeo.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ParamController::reverseUbigeo
* @see app/Http/Controllers/Api/ParamController.php:43
* @route '/api/params/ubigeo/{ubigeo}'
*/
const reverseUbigeoForm = (args: { ubigeo: string | number } | [ubigeo: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reverseUbigeo.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ParamController::reverseUbigeo
* @see app/Http/Controllers/Api/ParamController.php:43
* @route '/api/params/ubigeo/{ubigeo}'
*/
reverseUbigeoForm.get = (args: { ubigeo: string | number } | [ubigeo: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reverseUbigeo.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ParamController::reverseUbigeo
* @see app/Http/Controllers/Api/ParamController.php:43
* @route '/api/params/ubigeo/{ubigeo}'
*/
reverseUbigeoForm.head = (args: { ubigeo: string | number } | [ubigeo: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: reverseUbigeo.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

reverseUbigeo.form = reverseUbigeoForm

/**
* @see \App\Http\Controllers\Api\ParamController::index
* @see app/Http/Controllers/Api/ParamController.php:23
* @route '/api/params/{type}'
*/
export const index = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/params/{type}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\ParamController::index
* @see app/Http/Controllers/Api/ParamController.php:23
* @route '/api/params/{type}'
*/
index.url = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { type: args }
    }

    if (Array.isArray(args)) {
        args = {
            type: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        type: args.type,
    }

    return index.definition.url
            .replace('{type}', parsedArgs.type.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\ParamController::index
* @see app/Http/Controllers/Api/ParamController.php:23
* @route '/api/params/{type}'
*/
index.get = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ParamController::index
* @see app/Http/Controllers/Api/ParamController.php:23
* @route '/api/params/{type}'
*/
index.head = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\ParamController::index
* @see app/Http/Controllers/Api/ParamController.php:23
* @route '/api/params/{type}'
*/
const indexForm = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ParamController::index
* @see app/Http/Controllers/Api/ParamController.php:23
* @route '/api/params/{type}'
*/
indexForm.get = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\ParamController::index
* @see app/Http/Controllers/Api/ParamController.php:23
* @route '/api/params/{type}'
*/
indexForm.head = (args: { type: string | number } | [type: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

const ParamController = { types, reverseUbigeo, index }

export default ParamController