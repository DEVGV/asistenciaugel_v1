import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Api\MobileController::index
* @see app/Http/Controllers/Api/MobileController.php:318
* @route '/api/mobile/horario'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/mobile/horario',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Api\MobileController::index
* @see app/Http/Controllers/Api/MobileController.php:318
* @route '/api/mobile/horario'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Api\MobileController::index
* @see app/Http/Controllers/Api/MobileController.php:318
* @route '/api/mobile/horario'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::index
* @see app/Http/Controllers/Api/MobileController.php:318
* @route '/api/mobile/horario'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Api\MobileController::index
* @see app/Http/Controllers/Api/MobileController.php:318
* @route '/api/mobile/horario'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::index
* @see app/Http/Controllers/Api/MobileController.php:318
* @route '/api/mobile/horario'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Api\MobileController::index
* @see app/Http/Controllers/Api/MobileController.php:318
* @route '/api/mobile/horario'
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

const horario = {
    index: Object.assign(index, index),
}

export default horario