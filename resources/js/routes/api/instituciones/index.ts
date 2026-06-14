import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::search
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:34
* @route '/api/instituciones/search'
*/
export const search = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

search.definition = {
    methods: ["get","head"],
    url: '/api/instituciones/search',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::search
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:34
* @route '/api/instituciones/search'
*/
search.url = (options?: RouteQueryOptions) => {
    return search.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::search
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:34
* @route '/api/instituciones/search'
*/
search.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::search
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:34
* @route '/api/instituciones/search'
*/
search.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: search.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::search
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:34
* @route '/api/instituciones/search'
*/
const searchForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::search
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:34
* @route '/api/instituciones/search'
*/
searchForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: search.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::search
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:34
* @route '/api/instituciones/search'
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

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::detalles
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:71
* @route '/api/instituciones/{institucione}/detalles'
*/
export const detalles = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detalles.url(args, options),
    method: 'get',
})

detalles.definition = {
    methods: ["get","head"],
    url: '/api/instituciones/{institucione}/detalles',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::detalles
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:71
* @route '/api/instituciones/{institucione}/detalles'
*/
detalles.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { institucione: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { institucione: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            institucione: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        institucione: typeof args.institucione === 'object'
        ? args.institucione.id
        : args.institucione,
    }

    return detalles.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::detalles
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:71
* @route '/api/instituciones/{institucione}/detalles'
*/
detalles.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detalles.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::detalles
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:71
* @route '/api/instituciones/{institucione}/detalles'
*/
detalles.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: detalles.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::detalles
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:71
* @route '/api/instituciones/{institucione}/detalles'
*/
const detallesForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalles.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::detalles
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:71
* @route '/api/instituciones/{institucione}/detalles'
*/
detallesForm.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalles.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::detalles
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:71
* @route '/api/instituciones/{institucione}/detalles'
*/
detallesForm.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalles.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

detalles.form = detallesForm

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::locales
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:64
* @route '/api/instituciones/{institucione}/locales'
*/
export const locales = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: locales.url(args, options),
    method: 'get',
})

locales.definition = {
    methods: ["get","head"],
    url: '/api/instituciones/{institucione}/locales',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::locales
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:64
* @route '/api/instituciones/{institucione}/locales'
*/
locales.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { institucione: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { institucione: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            institucione: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        institucione: typeof args.institucione === 'object'
        ? args.institucione.id
        : args.institucione,
    }

    return locales.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::locales
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:64
* @route '/api/instituciones/{institucione}/locales'
*/
locales.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: locales.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::locales
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:64
* @route '/api/instituciones/{institucione}/locales'
*/
locales.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: locales.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::locales
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:64
* @route '/api/instituciones/{institucione}/locales'
*/
const localesForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: locales.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::locales
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:64
* @route '/api/instituciones/{institucione}/locales'
*/
localesForm.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: locales.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\InstitucionEducativa\InstitucionEducativaController::locales
* @see app/Http/Controllers/InstitucionEducativa/InstitucionEducativaController.php:64
* @route '/api/instituciones/{institucione}/locales'
*/
localesForm.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: locales.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

locales.form = localesForm

const instituciones = {
    search: Object.assign(search, search),
    detalles: Object.assign(detalles, detalles),
    locales: Object.assign(locales, locales),
}

export default instituciones