import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::detalle
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:118
* @route '/consolidado-asistencia/{asistencia}/detalle'
*/
export const detalle = (args: { asistencia: string | number } | [asistencia: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detalle.url(args, options),
    method: 'get',
})

detalle.definition = {
    methods: ["get","head"],
    url: '/consolidado-asistencia/{asistencia}/detalle',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::detalle
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:118
* @route '/consolidado-asistencia/{asistencia}/detalle'
*/
detalle.url = (args: { asistencia: string | number } | [asistencia: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { asistencia: args }
    }

    if (Array.isArray(args)) {
        args = {
            asistencia: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        asistencia: args.asistencia,
    }

    return detalle.definition.url
            .replace('{asistencia}', parsedArgs.asistencia.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::detalle
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:118
* @route '/consolidado-asistencia/{asistencia}/detalle'
*/
detalle.get = (args: { asistencia: string | number } | [asistencia: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: detalle.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::detalle
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:118
* @route '/consolidado-asistencia/{asistencia}/detalle'
*/
detalle.head = (args: { asistencia: string | number } | [asistencia: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: detalle.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::detalle
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:118
* @route '/consolidado-asistencia/{asistencia}/detalle'
*/
const detalleForm = (args: { asistencia: string | number } | [asistencia: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalle.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::detalle
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:118
* @route '/consolidado-asistencia/{asistencia}/detalle'
*/
detalleForm.get = (args: { asistencia: string | number } | [asistencia: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalle.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Asistencia\ConsolidadoAsistenciaController::detalle
* @see app/Http/Controllers/Asistencia/ConsolidadoAsistenciaController.php:118
* @route '/consolidado-asistencia/{asistencia}/detalle'
*/
detalleForm.head = (args: { asistencia: string | number } | [asistencia: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: detalle.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

detalle.form = detalleForm

const consolidadoAsistencia = {
    detalle: Object.assign(detalle, detalle),
}

export default consolidadoAsistencia