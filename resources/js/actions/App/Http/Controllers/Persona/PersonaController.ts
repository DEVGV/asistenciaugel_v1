import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Persona\PersonaController::search
* @see app/Http/Controllers/Persona/PersonaController.php:30
* @route '/api/personas/search'
*/
export const search = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

search.definition = {
    methods: ["get","head"],
    url: '/api/personas/search',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Persona\PersonaController::search
* @see app/Http/Controllers/Persona/PersonaController.php:30
* @route '/api/personas/search'
*/
search.url = (options?: RouteQueryOptions) => {
    return search.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Persona\PersonaController::search
* @see app/Http/Controllers/Persona/PersonaController.php:30
* @route '/api/personas/search'
*/
search.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::search
* @see app/Http/Controllers/Persona/PersonaController.php:30
* @route '/api/personas/search'
*/
search.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: search.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::search
* @see app/Http/Controllers/Persona/PersonaController.php:30
* @route '/api/personas/search'
*/
const searchForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::search
* @see app/Http/Controllers/Persona/PersonaController.php:30
* @route '/api/personas/search'
*/
searchForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Persona\PersonaController::search
* @see app/Http/Controllers/Persona/PersonaController.php:30
* @route '/api/personas/search'
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

const PersonaController = { search }

export default PersonaController