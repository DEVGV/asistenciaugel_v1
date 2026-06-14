import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Tramite\PermisoController::descargar
* @see app/Http/Controllers/Tramite/PermisoController.php:69
* @route '/documentos-tram/{documentoTram}/descargar'
*/
export const descargar = (args: { documentoTram: number | { id: number } } | [documentoTram: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: descargar.url(args, options),
    method: 'get',
})

descargar.definition = {
    methods: ["get","head"],
    url: '/documentos-tram/{documentoTram}/descargar',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Tramite\PermisoController::descargar
* @see app/Http/Controllers/Tramite/PermisoController.php:69
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargar.url = (args: { documentoTram: number | { id: number } } | [documentoTram: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { documentoTram: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { documentoTram: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            documentoTram: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        documentoTram: typeof args.documentoTram === 'object'
        ? args.documentoTram.id
        : args.documentoTram,
    }

    return descargar.definition.url
            .replace('{documentoTram}', parsedArgs.documentoTram.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Tramite\PermisoController::descargar
* @see app/Http/Controllers/Tramite/PermisoController.php:69
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargar.get = (args: { documentoTram: number | { id: number } } | [documentoTram: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: descargar.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::descargar
* @see app/Http/Controllers/Tramite/PermisoController.php:69
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargar.head = (args: { documentoTram: number | { id: number } } | [documentoTram: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: descargar.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::descargar
* @see app/Http/Controllers/Tramite/PermisoController.php:69
* @route '/documentos-tram/{documentoTram}/descargar'
*/
const descargarForm = (args: { documentoTram: number | { id: number } } | [documentoTram: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: descargar.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::descargar
* @see app/Http/Controllers/Tramite/PermisoController.php:69
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargarForm.get = (args: { documentoTram: number | { id: number } } | [documentoTram: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: descargar.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Tramite\PermisoController::descargar
* @see app/Http/Controllers/Tramite/PermisoController.php:69
* @route '/documentos-tram/{documentoTram}/descargar'
*/
descargarForm.head = (args: { documentoTram: number | { id: number } } | [documentoTram: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: descargar.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

descargar.form = descargarForm

const documentosTram = {
    descargar: Object.assign(descargar, descargar),
}

export default documentosTram