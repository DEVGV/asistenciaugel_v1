import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Configuracion\ZonaController::search
* @see app/Http/Controllers/Configuracion/ZonaController.php:54
* @route '/api/zonas/search'
*/
export const search = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

search.definition = {
    methods: ["get","head"],
    url: '/api/zonas/search',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::search
* @see app/Http/Controllers/Configuracion/ZonaController.php:54
* @route '/api/zonas/search'
*/
search.url = (options?: RouteQueryOptions) => {
    return search.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::search
* @see app/Http/Controllers/Configuracion/ZonaController.php:54
* @route '/api/zonas/search'
*/
search.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::search
* @see app/Http/Controllers/Configuracion/ZonaController.php:54
* @route '/api/zonas/search'
*/
search.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: search.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::search
* @see app/Http/Controllers/Configuracion/ZonaController.php:54
* @route '/api/zonas/search'
*/
const searchForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::search
* @see app/Http/Controllers/Configuracion/ZonaController.php:54
* @route '/api/zonas/search'
*/
searchForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Configuracion\ZonaController::search
* @see app/Http/Controllers/Configuracion/ZonaController.php:54
* @route '/api/zonas/search'
*/
searchForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

search.form = searchForm

const zonas = {
    search: Object.assign(search, search),
}

export default zonas