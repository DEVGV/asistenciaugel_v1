import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Tramite\PermisoController::altas
* @see app/Http/Controllers/Tramite/PermisoController.php:38
* @route '/instituciones/{institucione}/permisos-altas'
*/
export const altas = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: altas.url(args, options),
    method: 'get',
})

altas.definition = {
    methods: ["get","head"],
    url: '/instituciones/{institucione}/permisos-altas',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\PermisoController::altas
* @see app/Http/Controllers/Tramite/PermisoController.php:38
* @route '/instituciones/{institucione}/permisos-altas'
*/
altas.url = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions) => {
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

    return altas.definition.url
            .replace('{institucione}', parsedArgs.institucione.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\PermisoController::altas
* @see app/Http/Controllers/Tramite/PermisoController.php:38
* @route '/instituciones/{institucione}/permisos-altas'
*/
altas.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: altas.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::altas
* @see app/Http/Controllers/Tramite/PermisoController.php:38
* @route '/instituciones/{institucione}/permisos-altas'
*/
altas.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: altas.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::altas
* @see app/Http/Controllers/Tramite/PermisoController.php:38
* @route '/instituciones/{institucione}/permisos-altas'
*/
const altasForm = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: altas.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::altas
* @see app/Http/Controllers/Tramite/PermisoController.php:38
* @route '/instituciones/{institucione}/permisos-altas'
*/
altasForm.get = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: altas.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::altas
* @see app/Http/Controllers/Tramite/PermisoController.php:38
* @route '/instituciones/{institucione}/permisos-altas'
*/
altasForm.head = (args: { institucione: string | number | { id: string | number } } | [institucione: string | number | { id: string | number } ] | string | number | { id: string | number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: altas.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

altas.form = altasForm

const permisos = {
    altas: Object.assign(altas, altas),
}

export default permisos